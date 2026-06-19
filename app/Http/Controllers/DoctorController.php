<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    /**
     * Public doctor listing page
     */
    public function index()
    {
        $doctors = Doctor::where('verification_status', 'approved')
            ->with('user')
            ->paginate(12);
            
        return view('doctors', compact('doctors'));
    }

    /**
     * Public doctor profile page
     */
    public function show($id)
    {
        $doctor = Doctor::with('user', 'schedules')->findOrFail($id);
        return view('doctor-profile', compact('doctor'));
    }

    // ==========================================
    // DOCTOR DASHBOARD (Protected - Role: Doctor)
    // ==========================================

    /**
     * Doctor Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.profile.edit')
                ->with('warning', __('messages.complete_profile_first'));
        }

        // ✅ TEMPORARY FIX: Appointments table not created yet
        $todayAppointments = 0;
        $pendingRequests = 0;
        $totalPatients = 0;
        $completedAppointments = 0;
        $recentAppointments = collect([]);

        return view('doctor.dashboard', compact(
            'doctor',
            'todayAppointments',
            'pendingRequests',
            'totalPatients',
            'completedAppointments',
            'recentAppointments'
        ));
    }

    /**
     * Doctor Appointments List
     */
    public function appointments()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.profile.edit')
                ->with('warning', __('messages.complete_profile_first'));
        }

        $appointments = collect([]);
        return view('doctor.appointments', compact('doctor', 'appointments'));
    }

    /**
     * Doctor Schedule Management
     */
    public function schedule()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.profile.edit')
                ->with('warning', __('messages.complete_profile_first'));
        }

        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)->get();
        
        $scheduleData = [];
        foreach ($schedules as $schedule) {
            $scheduleData[$schedule->day_of_week] = [
                'start' => $schedule->start_time,
                'end' => $schedule->end_time,
            ];
        }

        return view('doctor.schedule', compact('doctor', 'schedules', 'scheduleData'));
    }

    /**
     * Store or Update Schedule (Bulk update for all days)
     */
    public function scheduleStore(Request $request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        $request->validate([
            'schedule' => 'required|array',
            'schedule.*.start' => 'nullable|date_format:H:i',
            'schedule.*.end' => 'nullable|date_format:H:i|after:schedule.*.start',
            'slot_duration' => 'nullable|integer|min:15|max:120',
        ]);

        foreach ($request->schedule as $day => $times) {
            if (empty($times['start']) || empty($times['end'])) {
                DoctorSchedule::where('doctor_id', $doctor->id)
                    ->where('day_of_week', $day)
                    ->delete();
                continue;
            }

            DoctorSchedule::updateOrCreate(
                [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                ],
                [
                    'start_time' => $times['start'],
                    'end_time' => $times['end'],
                    'slot_duration' => $request->slot_duration ?? 30,
                    'is_active' => true,
                ]
            );
        }

        return back()->with('success', __('messages.schedule_updated'));
    }

    /**
     * Delete Schedule
     */
    public function scheduleDelete($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        
        if ($schedule->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $schedule->delete();
        return back()->with('success', __('messages.schedule_deleted'));
    }

/**
 * Doctor Profile - View Only (Save Button बिना)
 */
public function profile()
{
    $user = Auth::user();
    $doctor = $user->doctor;
    
    if (!$doctor) {
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'specialization' => '',
            'phone' => '',
            'address' => '',
            'fee' => 0,
            'verification_status' => 'pending',
            'is_active' => true,
        ]);
        $doctor = $user->doctor;
    }

    return view('doctor.profile', compact('user', 'doctor'));
}

/**
 * Doctor Profile - Edit Form (Save Button सहित)
 */
public function profileEdit()
{
    $user = Auth::user();
    $doctor = $user->doctor;
    
    if (!$doctor) {
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'specialization' => '',
            'phone' => '',
            'address' => '',
            'fee' => 0,
            'verification_status' => 'pending',
            'is_active' => true,
        ]);
        $doctor = $user->doctor;
    }

    return view('doctor.profile-edit', compact('user', 'doctor'));
}

    /**
     * Update Doctor Profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            $doctor = $user->doctor;

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'qualification' => 'nullable|string|max:255',
                'specialization' => 'required|string|max:255',
                'nmc_registration' => 'required|string|unique:doctors,nmc_registration,' . ($doctor ? $doctor->id : 'NULL'),
                'experience' => 'nullable|integer|min:0',
                'consultation_fee' => 'nullable|numeric|min:0',
                'bio' => 'nullable|string|max:1000',
                'clinic_name' => 'nullable|string|max:255',
                'clinic_address' => 'nullable|string|max:500',
                'profile_photo' => 'nullable|image|max:2048',
            ]);

            $user->name = $validated['name'];
            $user->save();

            $doctor->fill($validated);
            
            if ($request->hasFile('profile_photo')) {
                if ($doctor->profile_photo) {
                    Storage::disk('public')->delete($doctor->profile_photo);
                }
                $path = $request->file('profile_photo')->store('doctor-photos', 'public');
                $doctor->profile_photo = $path;
            }

            $doctor->save();

            return redirect()->back()->with('success', __('messages.profile_updated'));
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Doctor Patients List
     */
    public function patients()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.profile.edit')
                ->with('warning', __('messages.complete_profile_first'));
        }

        $patients = collect([]);
        return view('doctor.patients', compact('doctor', 'patients'));
    }

    /**
     * Appointment Action (Approve, Reject, Complete, Cancel)
     */
    public function appointmentAction(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        
        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $appointment->status = $request->status;
        if ($request->notes) {
            $appointment->notes = $request->notes;
        }
        $appointment->save();

        return back()->with('success', __('messages.appointment_status_updated'));
    }

    /**
     * Generate QR Code for Doctor (to be implemented in Module 10)
     */
    public function generateQR()
    {
        $doctor = Auth::user()->doctor;
        return view('doctor.qr', compact('doctor'));
    }
}