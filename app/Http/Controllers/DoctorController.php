<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentApprovedMail;
use App\Mail\AppointmentRejectedMail;
use App\Mail\AppointmentCompletedMail;


class DoctorController extends Controller
{
    // =============================================
    // PUBLIC ROUTES
    // =============================================

    /**
     * Public doctor listing page
     */
    public function index(Request $request)
    {
        $query = Doctor::where('verification_status', 'approved')
            ->with('user')
            ->where('is_active', true);

        // --- ✅ NEW: Search & Filter ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('specialization', 'LIKE', "%{$search}%")
                  ->orWhere('clinic_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($sub) use ($search) {
                      $sub->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('specialization')) {
            $query->where('specialization', $request->specialization);
        }

        if ($request->filled('min_experience')) {
            $query->where('experience', '>=', $request->min_experience);
        }

        // Sorting
        $sort = $request->sort ?? 'recent';
        switch($sort) {
            case 'experience':
                $query->orderBy('experience', 'desc');
                break;
            case 'fee_low':
                $query->orderBy('consultation_fee', 'asc');
                break;
            case 'fee_high':
                $query->orderBy('consultation_fee', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }

        $doctors = $query->paginate(12);

        // Get unique specializations for filter
        $specializations = Doctor::where('verification_status', 'approved')
            ->whereNotNull('specialization')
            ->distinct()
            ->pluck('specialization');

        return view('doctors', compact('doctors', 'specializations'));
    }

    /**
     * Public doctor profile page
     */
    public function show($id)
    {
        $doctor = Doctor::with('user', 'schedules')->findOrFail($id);
        return view('doctor-profile', compact('doctor'));
    }

    // =============================================
    // DOCTOR DASHBOARD (Protected - Role: Doctor)
    // =============================================

    /**
     * Doctor Dashboard with Analytics
     */
    public function dashboard()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.profile.edit')
                ->with('warning', __('messages.complete_profile_first'));
        }

        // --- ✅ NEW: Dashboard Analytics ---
        // Today's Appointments
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', now()->toDateString())
            ->with('patient')
            ->orderBy('appointment_time')
            ->get();

        // Pending Requests
        $pendingRequests = Appointment::where('doctor_id', $doctor->id)
            ->where('status', Appointment::STATUS_PENDING)
            ->count();

        // Total Patients (Completed appointments)
        $totalPatients = Appointment::where('doctor_id', $doctor->id)
            ->where('status', Appointment::STATUS_COMPLETED)
            ->distinct('patient_id')
            ->count('patient_id');

        // Completed Appointments
        $completedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', Appointment::STATUS_COMPLETED)
            ->count();

        // Recent Appointments (last 10)
        $recentAppointments = Appointment::where('doctor_id', $doctor->id)
            ->with('patient')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // --- ✅ NEW: Monthly Appointments Chart ---
        $monthlyAppointments = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Appointment::where('doctor_id', $doctor->id)
                ->whereMonth('appointment_date', $month->month)
                ->whereYear('appointment_date', $month->year)
                ->count();
            $monthlyAppointments[] = [
                'month' => $month->format('M'),
                'count' => $count
            ];
        }

        // --- ✅ NEW: Unread Notifications Count ---
        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // --- ✅ NEW: Profile Completion Score ---
        $profileCompletion = $this->calculateProfileCompletion($doctor);

        return view('doctor.dashboard', compact(
            'doctor',
            'todayAppointments',
            'pendingRequests',
            'totalPatients',
            'completedAppointments',
            'recentAppointments',
            'monthlyAppointments',
            'unreadNotifications',
            'profileCompletion'
        ));
    }

    /**
     * Doctor Appointments List with Filters
     */
    public function appointments(Request $request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        
        if (!$doctor) {
            return redirect()->route('doctor.profile.edit')
                ->with('warning', __('messages.complete_profile_first'));
        }

        // --- ✅ NEW: Filtered Appointments ---
        $query = Appointment::where('doctor_id', $doctor->id)
            ->with('patient');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        // Search by patient name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        // Status counts
        $statusCounts = [
            'all' => Appointment::where('doctor_id', $doctor->id)->count(),
            'pending' => Appointment::where('doctor_id', $doctor->id)->where('status', Appointment::STATUS_PENDING)->count(),
            'approved' => Appointment::where('doctor_id', $doctor->id)->where('status', Appointment::STATUS_APPROVED)->count(),
            'completed' => Appointment::where('doctor_id', $doctor->id)->where('status', Appointment::STATUS_COMPLETED)->count(),
            'rejected' => Appointment::where('doctor_id', $doctor->id)->where('status', Appointment::STATUS_REJECTED)->count(),
            'cancelled' => Appointment::where('doctor_id', $doctor->id)->where('status', Appointment::STATUS_CANCELLED)->count(),
        ];

        return view('doctor.appointments', compact('doctor', 'appointments', 'statusCounts'));
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
     * Doctor Profile - View Only
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
     * Doctor Profile - Edit Form
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

        // Get unique patients who have completed appointments
        $patients = Appointment::where('doctor_id', $doctor->id)
            ->where('status', Appointment::STATUS_COMPLETED)
            ->with('patient')
            ->distinct('patient_id')
            ->get()
            ->pluck('patient')
            ->filter();

        return view('doctor.patients', compact('doctor', 'patients'));
    }

    // =============================================
    // ✅ UPDATED: APPOINTMENT ACTIONS WITH EMAIL & NOTIFICATIONS
    // =============================================

    /**
     * Approve Appointment
     */
    public function approve($id)
    {
        $appointment = Appointment::findOrFail($id);
        
        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $appointment->status = Appointment::STATUS_APPROVED;
        $appointment->save();

        // --- ✅ NEW: Send Email to Patient ---
        Mail::to($appointment->patient->email)->send(
            new AppointmentApprovedMail($appointment, app()->getLocale())
        );

        // --- ✅ NEW: Create In-App Notification ---
        $this->createNotification(
            $appointment->patient_id,
            'appointment_approved',
            __('messages.notification_appointment_approved', [
                'doctor' => $appointment->doctor->name,
                'date' => $appointment->appointment_date,
                'time' => $appointment->appointment_time,
            ]),
            route('patient.appointments')
        );

        return back()->with('success', __('messages.appointment_approved'));
    }

    /**
     * Reject Appointment
     */
    public function reject($id, Request $request)
    {
        $appointment = Appointment::findOrFail($id);
        
        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $appointment->status = Appointment::STATUS_REJECTED;
        if ($request->filled('rejection_reason')) {
            $appointment->notes = $request->rejection_reason;
        }
        $appointment->save();

        // --- ✅ NEW: Send Email to Patient ---
        Mail::to($appointment->patient->email)->send(
            new AppointmentRejectedMail($appointment, app()->getLocale())
        );

        // --- ✅ NEW: Create In-App Notification ---
        $this->createNotification(
            $appointment->patient_id,
            'appointment_rejected',
            __('messages.notification_appointment_rejected', [
                'doctor' => $appointment->doctor->name,
                'date' => $appointment->appointment_date,
            ]),
            route('patient.appointments')
        );

        return back()->with('success', __('messages.appointment_rejected'));
    }

    /**
     * Complete Appointment
     */
    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);
        
        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $appointment->status = Appointment::STATUS_COMPLETED;
        $appointment->save();

        // --- ✅ NEW: Send Email to Patient ---
        Mail::to($appointment->patient->email)->send(
            new AppointmentCompletedMail($appointment, app()->getLocale())
        );

        // --- ✅ NEW: Create In-App Notification ---
        $this->createNotification(
            $appointment->patient_id,
            'appointment_completed',
            __('messages.notification_appointment_completed', [
                'doctor' => $appointment->doctor->name,
                'date' => $appointment->appointment_date,
            ]),
            route('patient.appointments')
        );

        return back()->with('success', __('messages.appointment_completed'));
    }

    /**
     * Cancel Appointment (Doctor initiated)
     */
    public function cancelAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        
        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        if ($appointment->status === Appointment::STATUS_COMPLETED) {
            return back()->with('error', __('messages.cannot_cancel_completed'));
        }

        $appointment->status = Appointment::STATUS_CANCELLED;
        $appointment->save();

        // Notification to patient
        $this->createNotification(
            $appointment->patient_id,
            'appointment_cancelled',
            __('messages.notification_appointment_cancelled', [
                'doctor' => $appointment->doctor->name,
                'date' => $appointment->appointment_date,
            ]),
            route('patient.appointments')
        );

        return back()->with('success', __('messages.appointment_cancelled'));
    }

        /**
     * Update Appointment Status via Dropdown (Single Action)
     * Handles: approve, reject, complete, cancelled
     */
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        
        // Ensure this appointment belongs to the logged-in doctor
        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,completed,cancelled'
        ]);

        $newStatus = $validated['status'];

        // If cancelling a completed appointment, prevent it
        if ($appointment->status === Appointment::STATUS_COMPLETED && $newStatus === 'cancelled') {
            return back()->with('error', __('messages.cannot_cancel_completed'));
        }

        // Update status
        $appointment->status = $newStatus;
        $appointment->save();

        // --- Optional: Send notification/email based on status ---
        switch ($newStatus) {
            case 'approved':
                // Mail::to($appointment->patient->email)->send(new AppointmentApprovedMail($appointment, app()->getLocale()));
                $this->createNotification(
                    $appointment->patient_id,
                    'appointment_approved',
                    __('messages.notification_appointment_approved', [
                        'doctor' => $appointment->doctor->name,
                        'date' => $appointment->appointment_date,
                        'time' => $appointment->appointment_time,
                    ]),
                    route('patient.appointments')
                );
                break;

            case 'rejected':
                // Mail::to($appointment->patient->email)->send(new AppointmentRejectedMail($appointment, app()->getLocale()));
                $this->createNotification(
                    $appointment->patient_id,
                    'appointment_rejected',
                    __('messages.notification_appointment_rejected', [
                        'doctor' => $appointment->doctor->name,
                        'date' => $appointment->appointment_date,
                    ]),
                    route('patient.appointments')
                );
                break;

            case 'completed':
                // Mail::to($appointment->patient->email)->send(new AppointmentCompletedMail($appointment, app()->getLocale()));
                $this->createNotification(
                    $appointment->patient_id,
                    'appointment_completed',
                    __('messages.notification_appointment_completed', [
                        'doctor' => $appointment->doctor->name,
                        'date' => $appointment->appointment_date,
                    ]),
                    route('patient.appointments')
                );
                break;

            case 'cancelled':
                $this->createNotification(
                    $appointment->patient_id,
                    'appointment_cancelled',
                    __('messages.notification_appointment_cancelled', [
                        'doctor' => $appointment->doctor->name,
                        'date' => $appointment->appointment_date,
                    ]),
                    route('patient.appointments')
                );
                break;
        }

        return back()->with('success', __('messages.appointment_status_updated'));
    }
    
    // =============================================
    // ✅ NEW: Helper Methods
    // =============================================

    /**
     * Create In-App Notification
     */
    private function createNotification($userId, $type, $message, $link = null)
    {
        if (!class_exists(Notification::class)) {
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
            // Silently fail if table doesn't exist
        }
    }

    /**
     * Calculate Profile Completion Score
     */
    private function calculateProfileCompletion($doctor)
    {
        $score = 0;
        $total = 12;

        if ($doctor->name) $score++;
        if ($doctor->qualification) $score++;
        if ($doctor->specialization) $score++;
        if ($doctor->nmc_registration) $score++;
        if ($doctor->experience && $doctor->experience > 0) $score++;
        if ($doctor->consultation_fee && $doctor->consultation_fee > 0) $score++;
        if ($doctor->bio) $score++;
        if ($doctor->profile_photo) $score++;
        if ($doctor->clinic_name) $score++;
        if ($doctor->clinic_address) $score++;
        if ($doctor->phone) $score++;
        if ($doctor->address) $score++;

        // Check if schedule exists
        $hasSchedule = DoctorSchedule::where('doctor_id', $doctor->id)->exists();
        if ($hasSchedule) $score++;

        return min(100, round(($score / $total) * 100));
    }

    // =============================================
    // QR CODE GENERATION
    // =============================================

    /**
     * Generate QR Code for Doctor
     */
    public function generateQR()
    {
        $doctor = Auth::user()->doctor;
        return view('doctor.qr', compact('doctor'));
    }

    /**
     * Get Doctor Availability Status
     */
    public function getAvailability($id)
    {
        $doctor = Doctor::findOrFail($id);
        
        $status = [
            'is_available' => false,
            'status' => 'offline',
            'label' => __('messages.offline'),
            'today_slots' => [],
        ];

        $today = now()->toDateString();
        $day = now()->format('l');

        // Check schedule for today
        $schedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $day)
            ->where('is_active', true)
            ->first();

        if ($schedule) {
            $status['is_available'] = true;
            $status['status'] = 'available';
            $status['label'] = __('messages.available_today');
            
            // Generate available slots for today
            $start = strtotime($schedule->start_time);
            $end = strtotime($schedule->end_time);
            $duration = ($schedule->slot_duration ?? 30) * 60;

            while ($start < $end) {
                $time = date('H:i', $start);
                
                $booked = Appointment::where('doctor_id', $doctor->id)
                    ->where('appointment_date', $today)
                    ->where('appointment_time', $time)
                    ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_APPROVED])
                    ->exists();

                if (!$booked) {
                    $status['today_slots'][] = $time;
                }
                
                $start += $duration;
            }

            if (empty($status['today_slots'])) {
                $status['status'] = 'fully_booked';
                $status['label'] = __('messages.fully_booked');
            }
        }

        return response()->json($status);
    }
}