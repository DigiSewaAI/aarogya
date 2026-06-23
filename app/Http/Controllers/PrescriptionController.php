<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// use Barryvdh\DomPDF\Facade\Pdf; // Optional: For PDF generation (uncomment when needed)

class PrescriptionController extends Controller
{
    // ============================
    // DOCTOR SIDE
    // ============================

    /**
     * Doctor Prescriptions List
     * Route: doctor.prescriptions → matches web.php
     */
    public function doctorIndex(Request $request)
    {
        $doctor = Auth::user()->doctor;
        
        $query = Prescription::where('doctor_id', $doctor->id)
            ->with(['patient', 'items']);

        if ($request->filled('patient')) {
            $search = $request->patient;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $prescriptions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('doctor.prescriptions.index', compact('prescriptions'));
    }

    /**
     * Create Prescription Form
     * Route: doctor.prescriptions.create
     */
    public function create($appointmentId = null)
    {
        $doctor = Auth::user()->doctor;
        
        if ($appointmentId) {
            $appointment = Appointment::where('doctor_id', $doctor->id)
                ->with('patient')
                ->findOrFail($appointmentId);
            
            // Check if prescription already exists for this appointment
            $existing = Prescription::where('appointment_id', $appointmentId)->first();
            if ($existing) {
                return redirect()->route('doctor.prescriptions.show', $existing->id);
            }
            
            return view('doctor.prescriptions.create', compact('appointment'));
        }

        // List appointments for patient selection
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->with('patient')
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('doctor.prescriptions.create', compact('appointments'));
    }

    /**
     * Store Prescription
     * Route: doctor.prescriptions.store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'patient_id' => 'required|exists:users,id',
            'diagnosis' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:500',
            'valid_until' => 'nullable|date|after:today',
            'medicines' => 'required|array|min:1',
            'medicines.*.name' => 'required|string|max:255',
            'medicines.*.dosage' => 'required|string|max:100',
            'medicines.*.frequency' => 'required|string|max:100',
            'medicines.*.duration' => 'required|string|max:100',
            'medicines.*.instructions' => 'nullable|string|max:500',
        ]);

        $doctor = Auth::user()->doctor;

        $prescription = Prescription::create([
            'appointment_id' => $validated['appointment_id'],
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $doctor->id,
            'diagnosis' => $validated['diagnosis'],
            'notes' => $validated['notes'],
            'valid_from' => now(),
            'valid_until' => $validated['valid_until'] ?? null,
            'status' => 'active',
        ]);

        foreach ($validated['medicines'] as $item) {
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medicine_name' => $item['name'],
                'dosage' => $item['dosage'],
                'frequency' => $item['frequency'],
                'duration' => $item['duration'],
                'instructions' => $item['instructions'] ?? null,
            ]);
        }

        return redirect()->route('doctor.prescriptions')
            ->with('success', __('messages.prescription_created'));
    }

    /**
     * Show Prescription (Doctor view)
     * Route: doctor.prescriptions.show
     */
    public function show($id)
    {
        $doctor = Auth::user()->doctor;
        $prescription = Prescription::where('doctor_id', $doctor->id)
            ->with(['patient', 'items'])
            ->findOrFail($id);

        return view('doctor.prescriptions.show', compact('prescription'));
    }

    /**
     * Edit Prescription Form
     * Route: doctor.prescriptions.edit
     */
    public function edit($id)
    {
        $doctor = Auth::user()->doctor;
        $prescription = Prescription::where('doctor_id', $doctor->id)
            ->with(['patient', 'items'])
            ->findOrFail($id);

        return view('doctor.prescriptions.edit', compact('prescription'));
    }

    /**
     * Update Prescription
     * Route: doctor.prescriptions.update
     */
    public function update(Request $request, $id)
    {
        $doctor = Auth::user()->doctor;
        $prescription = Prescription::where('doctor_id', $doctor->id)->findOrFail($id);

        $validated = $request->validate([
            'diagnosis' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:500',
            'valid_until' => 'nullable|date',
            'status' => 'required|in:active,completed,expired',
            'medicines' => 'required|array|min:1',
            'medicines.*.id' => 'nullable|exists:prescription_items,id',
            'medicines.*.name' => 'required|string|max:255',
            'medicines.*.dosage' => 'required|string|max:100',
            'medicines.*.frequency' => 'required|string|max:100',
            'medicines.*.duration' => 'required|string|max:100',
            'medicines.*.instructions' => 'nullable|string|max:500',
        ]);

        $prescription->update([
            'diagnosis' => $validated['diagnosis'],
            'notes' => $validated['notes'],
            'valid_until' => $validated['valid_until'],
            'status' => $validated['status'],
        ]);

        // Sync medicine items
        $existingIds = [];
        foreach ($validated['medicines'] as $item) {
            if (isset($item['id'])) {
                $itemModel = PrescriptionItem::find($item['id']);
                if ($itemModel && $itemModel->prescription_id === $prescription->id) {
                    $itemModel->update([
                        'medicine_name' => $item['name'],
                        'dosage' => $item['dosage'],
                        'frequency' => $item['frequency'],
                        'duration' => $item['duration'],
                        'instructions' => $item['instructions'] ?? null,
                    ]);
                    $existingIds[] = $itemModel->id;
                }
            } else {
                $newItem = PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_name' => $item['name'],
                    'dosage' => $item['dosage'],
                    'frequency' => $item['frequency'],
                    'duration' => $item['duration'],
                    'instructions' => $item['instructions'] ?? null,
                ]);
                $existingIds[] = $newItem->id;
            }
        }

        // Delete removed items
        PrescriptionItem::where('prescription_id', $prescription->id)
            ->whereNotIn('id', $existingIds)
            ->delete();

        return redirect()->route('doctor.prescriptions')
            ->with('success', __('messages.prescription_updated'));
    }

    /**
     * Delete Prescription
     * Route: doctor.prescriptions.delete
     */
    public function delete($id)
    {
        $doctor = Auth::user()->doctor;
        $prescription = Prescription::where('doctor_id', $doctor->id)->findOrFail($id);
        $prescription->delete();

        return back()->with('success', __('messages.prescription_deleted'));
    }

    /**
     * Download Prescription (TXT/PDF)
     * Route: doctor.prescriptions.download & patient.prescriptions.download
     */
    public function download($id)
    {
        $prescription = Prescription::with(['doctor', 'patient', 'items'])->findOrFail($id);
        
        // Check permission
        $user = Auth::user();
        if ($user->id !== $prescription->patient_id && 
            ($user->doctor && $user->doctor->id !== $prescription->doctor_id) &&
            !$user->isAdmin()) {
            abort(403);
        }

        // Generate TXT file for now (PDF can be added later with DomPDF)
        $content = "AAROGYA - PRESCRIPTION\n";
        $content .= "=========================\n\n";
        $content .= "Patient: " . $prescription->patient->name . "\n";
        $content .= "Doctor: Dr. " . $prescription->doctor->name . "\n";
        $content .= "Specialization: " . $prescription->doctor->specialization . "\n";
        $content .= "Date: " . $prescription->created_at->format('Y-m-d') . "\n\n";
        $content .= "DIAGNOSIS:\n";
        $content .= "-------------------------\n";
        $content .= $prescription->diagnosis . "\n\n";
        $content .= "MEDICINES:\n";
        $content .= "-------------------------\n";
        foreach ($prescription->items as $item) {
            $content .= "• " . $item->medicine_name . "\n";
            $content .= "  Dosage: " . $item->dosage . "\n";
            $content .= "  Frequency: " . $item->frequency . "\n";
            $content .= "  Duration: " . $item->duration . "\n";
            if ($item->instructions) {
                $content .= "  Instructions: " . $item->instructions . "\n";
            }
            $content .= "\n";
        }
        if ($prescription->notes) {
            $content .= "NOTES:\n";
            $content .= "-------------------------\n";
            $content .= $prescription->notes . "\n\n";
        }
        $content .= "=========================\n";
        $content .= "Valid From: " . $prescription->valid_from->format('Y-m-d') . "\n";
        if ($prescription->valid_until) {
            $content .= "Valid Until: " . \Carbon\Carbon::parse($prescription->valid_until)->format('Y-m-d') . "\n";
        }
        $content .= "Status: " . ucfirst($prescription->status) . "\n";
        $content .= "=========================\n";
        $content .= "This is a computer-generated prescription.\n";
        $content .= "© 2026 AAROGYA - Nepal's Trusted Healthcare Platform\n";

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="prescription-' . $prescription->id . '-' . now()->format('Ymd') . '.txt"');
    }

    // ============================
    // PATIENT SIDE
    // ============================

    /**
     * Patient Prescriptions List
     * Route: patient.prescriptions
     */
    public function patientIndex()
    {
        $prescriptions = Prescription::where('patient_id', Auth::id())
            ->with(['doctor', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('patient.prescriptions', compact('prescriptions'));
    }

    /**
     * Patient Show Prescription
     * Route: patient.prescriptions.show
     */
    public function patientShow($id)
    {
        $prescription = Prescription::where('patient_id', Auth::id())
            ->with(['doctor', 'items'])
            ->findOrFail($id);

        return view('patient.prescriptions-show', compact('prescription'));
    }
}