<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FollowUp;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class FollowUpController extends Controller
{
    // ============================
    // DOCTOR SIDE
    // ============================

    /**
     * Doctor Follow-ups List
     * Route: doctor.follow-ups
     */
    public function doctorIndex()
    {
        $doctor = Auth::user()->doctor;
        
        $followUps = FollowUp::where('doctor_id', $doctor->id)
            ->with(['patient', 'appointment'])
            ->orderBy('follow_up_date', 'asc')
            ->paginate(15);

        return view('doctor.follow-ups.index', compact('followUps'));
    }

    /**
     * Create Follow-up Form (using appointment ID)
     * Route: doctor.follow-ups.create
     */
    public function create($appointmentId)
    {
        $doctor = Auth::user()->doctor;
        
        // Verify this appointment belongs to doctor
        $appointment = Appointment::where('doctor_id', $doctor->id)
            ->with('patient')
            ->findOrFail($appointmentId);

        return view('doctor.follow-ups.create', compact('appointment'));
    }

    /**
     * Store Follow-up
     * Route: doctor.follow-ups.store
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'follow_up_date' => 'required|date|after:today',
            'follow_up_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $doctor = Auth::user()->doctor;

        FollowUp::create([
            'appointment_id' => $request->appointment_id,
            'patient_id' => $request->patient_id,
            'doctor_id' => $doctor->id,
            'follow_up_date' => $request->follow_up_date,
            'follow_up_time' => $request->follow_up_time,
            'notes' => $request->notes,
            'status' => 'scheduled',
        ]);

        return redirect()->route('doctor.follow-ups')
            ->with('success', __('messages.follow_up_created'));
    }

    /**
     * Update Follow-up Status or Details
     * Route: doctor.follow-ups.update
     */
    public function update(Request $request, $id)
    {
        $doctor = Auth::user()->doctor;
        $followUp = FollowUp::where('doctor_id', $doctor->id)->findOrFail($id);

        $validated = $request->validate([
            'follow_up_date' => 'nullable|date|after:today',
            'follow_up_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500',
            'status' => 'nullable|in:scheduled,completed,cancelled',
        ]);

        $followUp->update($validated);

        return redirect()->route('doctor.follow-ups')
            ->with('success', __('messages.follow_up_updated'));
    }

    /**
     * Complete Follow-up (Quick action)
     * Route: doctor.follow-ups.complete (if you add separate route, but we can use update)
     * However, the route uses PUT for update, so this is optional.
     * Keep for convenience if you have a separate button.
     */
    public function complete($id)
    {
        $doctor = Auth::user()->doctor;
        $followUp = FollowUp::where('doctor_id', $doctor->id)->findOrFail($id);
        
        $followUp->status = 'completed';
        $followUp->save();

        return back()->with('success', __('messages.follow_up_completed'));
    }

    /**
     * Delete Follow-up
     * Route: doctor.follow-ups.delete
     */
    public function delete($id)
    {
        $doctor = Auth::user()->doctor;
        $followUp = FollowUp::where('doctor_id', $doctor->id)->findOrFail($id);
        $followUp->delete();

        return back()->with('success', __('messages.follow_up_deleted'));
    }

    // ============================
    // PATIENT SIDE
    // ============================

    /**
     * Patient Follow-ups List
     * Route: patient.follow-ups
     */
    public function patientIndex()
    {
        $followUps = FollowUp::where('patient_id', Auth::id())
            ->with(['doctor'])
            ->orderBy('follow_up_date', 'asc')
            ->paginate(15);

        return view('patient.follow-ups', compact('followUps'));
    }

    /**
     * Patient Confirm Follow-up (Optional)
     * Route: patient.follow-ups.confirm
     */
    public function confirm($id)
    {
        $followUp = FollowUp::where('patient_id', Auth::id())->findOrFail($id);
        $followUp->status = 'confirmed';
        $followUp->save();

        return back()->with('success', __('messages.follow_up_confirmed'));
    }
}