<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentBookedMail;
use App\Models\Notification;

class AppointmentController extends Controller
{
    /**
     * Show appointment booking form
     */
    public function create($doctorId)
    {
        $doctor = Doctor::with('user', 'schedules')->findOrFail($doctorId);
        
        // Check if doctor is verified and active
        if ($doctor->verification_status !== 'approved' || !$doctor->is_active) {
            return redirect()->route('doctors')->with('error', __('messages.doctor_unavailable'));
        }

        return view('appointment.create', compact('doctor'));
    }

    /**
     * Store appointment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'symptoms' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $doctor = Doctor::findOrFail($validated['doctor_id']);

        // Check if doctor is available on this day and time
        $schedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', now()->parse($validated['appointment_date'])->format('l'))
            ->where('start_time', '<=', $validated['appointment_time'])
            ->where('end_time', '>=', $validated['appointment_time'])
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return back()->with('error', __('messages.doctor_not_available_time'))->withInput();
        }

        // Check if slot is already booked
        $existing = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_APPROVED])
            ->first();

        if ($existing) {
            return back()->with('error', __('messages.slot_already_booked'))->withInput();
        }

        // Create appointment
        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $validated['doctor_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'symptoms' => $validated['symptoms'],
            'notes' => $validated['notes'],
            'status' => Appointment::STATUS_PENDING,
        ]);

        // --- ✅ NEW: Send Email Notifications ---
        $locale = app()->getLocale();
        
        // Email to Patient
        Mail::to($appointment->patient->email)->send(
            new AppointmentBookedMail($appointment, $locale)
        );
        
        // Email to Doctor
        Mail::to($appointment->doctor->user->email)->send(
            new AppointmentBookedMail($appointment, $locale)
        );

        // --- ✅ NEW: Create In-App Notifications ---
        // Patient notification
        $this->createNotification(
            $appointment->patient_id,
            'appointment_booked',
            __('messages.notification_appointment_booked', [
                'doctor' => $appointment->doctor->name,
                'date' => $appointment->appointment_date,
                'time' => $appointment->appointment_time,
            ]),
            route('patient.appointments')
        );

        // Doctor notification
        $this->createNotification(
            $appointment->doctor->user->id,
            'appointment_request',
            __('messages.notification_appointment_request', [
                'patient' => $appointment->patient->name,
                'date' => $appointment->appointment_date,
                'time' => $appointment->appointment_time,
            ]),
            route('doctor.appointments')
        );

        // Redirect to patient appointments list with success message
        return redirect()->route('patient.appointments')
            ->with('success', __('messages.appointment_booked'));
    }

    /**
     * Patient appointments list
     */
    public function patientAppointments()
    {
        $appointments = Appointment::where('patient_id', Auth::id())
            ->with('doctor')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('patient.appointments', compact('appointments'));
    }

    /**
     * Cancel appointment (patient)
     */
    public function cancel($id)
    {
        $appointment = Appointment::where('patient_id', Auth::id())->findOrFail($id);

        if ($appointment->status === Appointment::STATUS_COMPLETED || 
            $appointment->status === Appointment::STATUS_CANCELLED) {
            return back()->with('error', __('messages.cannot_cancel'));
        }

        $appointment->status = Appointment::STATUS_CANCELLED;
        $appointment->save();

        // --- ✅ NEW: Notification for cancellation (optional) ---
        $this->createNotification(
            $appointment->doctor->user->id,
            'appointment_cancelled',
            __('messages.notification_appointment_cancelled', [
                'patient' => $appointment->patient->name,
                'date' => $appointment->appointment_date,
                'time' => $appointment->appointment_time,
            ]),
            route('doctor.appointments')
        );

        return back()->with('success', __('messages.appointment_cancelled'));
    }

    /**
     * Get available time slots for a doctor on a specific date (AJAX)
     */
    public function getSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        $day = now()->parse($request->date)->format('l');

        $schedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $day)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return response()->json(['slots' => []]);
        }

        // Generate time slots
        $slots = [];
        $start = strtotime($schedule->start_time);
        $end = strtotime($schedule->end_time);
        $duration = ($schedule->slot_duration ?? 30) * 60; // default 30 min

        while ($start < $end) {
            $time = date('H:i', $start);
            
            // Check if slot is already booked
            $booked = Appointment::where('doctor_id', $doctor->id)
                ->where('appointment_date', $request->date)
                ->where('appointment_time', $time)
                ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_APPROVED])
                ->exists();

            if (!$booked) {
                $slots[] = $time;
            }
            
            $start += $duration;
        }

        return response()->json(['slots' => $slots]);
    }

    // =============================================
    // ✅ NEW: Helper method to create notification
    // =============================================
    private function createNotification($userId, $type, $message, $link = null)
    {
        // Check if Notification model exists, otherwise skip
        if (!class_exists(\App\Models\Notification::class)) {
            return;
        }

        try {
            Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'message' => $message,
                'link' => $link,
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            // Silently fail if notification table doesn't exist yet
        }
    }
}