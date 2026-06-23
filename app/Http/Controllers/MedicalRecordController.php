<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Appointment;       // ✅ ADDED
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $records = MedicalRecord::where('patient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('patient.medical-records', compact('records'));
    }

    public function create()
    {
        return view('patient.medical-records-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:lab_report,prescription,x_ray,scan_report,vaccination,other',
            'description' => 'nullable|string|max:500',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'shared_with_doctor' => 'nullable|boolean',
        ]);

        $file = $request->file('file');
        $path = $file->store('medical-records/' . Auth::id(), 'public');

        $record = MedicalRecord::create([
            'patient_id' => Auth::id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'is_public' => false,
            'shared_with_doctor' => $request->boolean('shared_with_doctor', false),
        ]);

        return redirect()->route('patient.medical-records')
            ->with('success', __('messages.record_uploaded'));
    }

    public function download($id)
    {
        $record = MedicalRecord::where('patient_id', Auth::id())->findOrFail($id);
        
        return Storage::disk('public')->download($record->file_path, $record->file_name);
    }

    public function delete($id)
    {
        $record = MedicalRecord::where('patient_id', Auth::id())->findOrFail($id);
        
        Storage::disk('public')->delete($record->file_path);
        $record->delete();

        return back()->with('success', __('messages.record_deleted'));
    }

    public function share($id)
    {
        $record = MedicalRecord::where('patient_id', Auth::id())->findOrFail($id);
        $record->shared_with_doctor = !$record->shared_with_doctor;
        $record->save();

        return back()->with('success', __('messages.record_share_updated'));
    }

    public function viewPatientRecords($patientId)
    {
        $doctor = Auth::user()->doctor;
        
        $hasPatient = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patientId)
            ->exists();
            
        if (!$hasPatient) {
            abort(403);
        }

        $records = MedicalRecord::where('patient_id', $patientId)
            ->where(function($q) {
                $q->where('is_public', true)
                  ->orWhere('shared_with_doctor', true);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.patient-records', compact('records', 'patientId'));
    }
}