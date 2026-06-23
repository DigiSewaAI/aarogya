<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\DoctorApprovedMail;
use App\Mail\ClinicApprovedMail;
use App\Models\ContactMessage;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        // Get stats safely - handle missing tables/columns
        $stats = [];
        
        try {
            $stats['totalUsers'] = User::count();
        } catch (\Exception $e) {
            $stats['totalUsers'] = 0;
        }
        
        try {
            $stats['totalDoctors'] = Doctor::count();
        } catch (\Exception $e) {
            $stats['totalDoctors'] = 0;
        }
        
        try {
            $stats['totalClinics'] = Clinic::count();
        } catch (\Exception $e) {
            $stats['totalClinics'] = 0;
        }
        
        try {
            $stats['totalAppointments'] = Appointment::count();
        } catch (\Exception $e) {
            $stats['totalAppointments'] = 0;
        }
        
        try {
            $stats['pendingDoctors'] = Doctor::where('verification_status', 'pending')->count();
        } catch (\Exception $e) {
            $stats['pendingDoctors'] = 0;
        }
        
        try {
            $stats['pendingClinics'] = Clinic::where('verification_status', 'pending')->count();
        } catch (\Exception $e) {
            $stats['pendingClinics'] = 0;
        }
        
        // Today's appointments - only if table exists and column exists
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('appointments') && 
                \Illuminate\Support\Facades\Schema::hasColumn('appointments', 'appointment_date')) {
                $stats['todayAppointments'] = Appointment::where('appointment_date', now()->toDateString())->count();
            } else {
                $stats['todayAppointments'] = 0;
            }
        } catch (\Exception $e) {
            $stats['todayAppointments'] = 0;
        }

        // Recent users - safely
        try {
            $recentUsers = User::orderBy('created_at', 'desc')->limit(10)->get();
        } catch (\Exception $e) {
            $recentUsers = collect([]);
        }
        
        // Recent appointments - safely
        try {
            $recentAppointments = Appointment::with(['doctor', 'patient'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            $recentAppointments = collect([]);
        }

        // --- ✅ NEW: Unread notifications count for admin ---
        $unreadNotifications = 0;
        try {
            if (Auth::check() && class_exists(Notification::class)) {
                $unreadNotifications = Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
            }
        } catch (\Exception $e) {
            // silently fail if notifications table doesn't exist
        }

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentAppointments', 'unreadNotifications'));
    }

    /**
     * User Management
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Toggle User Active/Inactive
     */
    public function toggleUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent toggling admin
        if ($user->isAdmin()) {
            return back()->with('error', __('messages.cannot_delete_admin'));
        }

        // For doctors/clinics, also toggle their profiles
        $user->is_active = !$user->is_active;
        $user->save();

        // Toggle doctor profile if exists
        if ($user->isDoctor() && $user->doctor) {
            $user->doctor->is_active = $user->is_active;
            $user->doctor->save();
        }

        // Toggle clinic profile if exists
        if ($user->isClinic() && $user->clinic) {
            $user->clinic->is_active = $user->is_active;
            $user->clinic->save();
        }

        // --- ✅ NEW: Notify user about status change (optional) ---
        $statusMessage = $user->is_active ? 'activated' : 'deactivated';
        $this->createNotification(
            $user->id,
            'account_status_changed',
            __('messages.notification_account_status_changed', [
                'status' => $user->is_active ? __('messages.active') : __('messages.inactive'),
                'role' => ucfirst($user->role),
            ]),
            route('login')
        );

        $message = $user->is_active ? __('messages.user_activated') : __('messages.user_deactivated');
        return back()->with('success', $message);
    }

    /**
     * Delete User
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting admin
        if ($user->isAdmin()) {
            return back()->with('error', __('messages.cannot_delete_admin'));
        }

        // Delete related doctor/clinic profile
        if ($user->isDoctor() && $user->doctor) {
            $user->doctor->delete();
        }
        if ($user->isClinic() && $user->clinic) {
            $user->clinic->delete();
        }

        $user->delete();

        return back()->with('success', __('messages.user_deleted'));
    }

    /**
     * Doctor Management (List all doctors)
     */
    public function doctors(Request $request)
    {
        $query = Doctor::with('user');

        // Filter by verification status
        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nmc_registration', 'like', "%{$search}%");
            });
        }

        $doctors = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.doctors', compact('doctors'));
    }

    /**
     * Verify Doctor (Approve) - WITH EMAIL & NOTIFICATION
     */
    public function verifyDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->verification_status = 'approved';
        $doctor->save();

        // --- ✅ NEW: Send Email to Doctor ---
        try {
            if (class_exists(DoctorApprovedMail::class)) {
                Mail::to($doctor->user->email)->send(
                    new DoctorApprovedMail($doctor, app()->getLocale())
                );
            }
        } catch (\Exception $e) {
            // Log error if needed - silently fail to avoid breaking flow
        }

        // --- ✅ NEW: Create In-App Notification for Doctor ---
        $this->createNotification(
            $doctor->user_id,
            'doctor_verified',
            __('messages.notification_doctor_verified', [
                'name' => $doctor->name,
            ]),
            route('doctor.dashboard')
        );

        return back()->with('success', __('messages.doctor_verified_success'));
    }

    /**
     * Reject Doctor - WITH EMAIL & NOTIFICATION (Optional)
     */
    public function rejectDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->verification_status = 'rejected';
        $doctor->save();

        // --- ✅ NEW: Create In-App Notification for Doctor ---
        $this->createNotification(
            $doctor->user_id,
            'doctor_rejected',
            __('messages.notification_doctor_rejected', [
                'name' => $doctor->name,
            ]),
            route('doctor.profile.edit')
        );

        // Optional: Send rejection email (you can create DoctorRejectedMail later)
        // For now, we just notify in-app.

        return back()->with('success', __('messages.doctor_rejected'));
    }

    /**
     * Clinic Management (List all clinics)
     */
    public function clinics(Request $request)
    {
        $query = Clinic::with('user');

        // Filter by verification status
        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('clinic_name', 'like', "%{$search}%");
        }

        $clinics = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.clinics', compact('clinics'));
    }

    /**
     * Verify Clinic (Approve) - WITH EMAIL & NOTIFICATION
     */
    public function verifyClinic($id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->verification_status = 'approved';
        $clinic->save();

        // --- ✅ NEW: Send Email to Clinic ---
        try {
            if (class_exists(ClinicApprovedMail::class)) {
                Mail::to($clinic->user->email)->send(
                    new ClinicApprovedMail($clinic, app()->getLocale())
                );
            }
        } catch (\Exception $e) {
            // silently fail
        }

        // --- ✅ NEW: Create In-App Notification for Clinic ---
        $this->createNotification(
            $clinic->user_id,
            'clinic_verified',
            __('messages.notification_clinic_verified', [
                'name' => $clinic->clinic_name,
            ]),
            route('clinic.dashboard')
        );

        return back()->with('success', __('messages.clinic_verified_success'));
    }

    /**
     * Reject Clinic - WITH NOTIFICATION
     */
    public function rejectClinic($id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->verification_status = 'rejected';
        $clinic->save();

        // --- ✅ NEW: Create In-App Notification for Clinic ---
        $this->createNotification(
            $clinic->user_id,
            'clinic_rejected',
            __('messages.notification_clinic_rejected', [
                'name' => $clinic->clinic_name,
            ]),
            route('clinic.profile.edit')
        );

        return back()->with('success', __('messages.clinic_rejected'));
    }

    /**
     * Verifications Page (Pending approvals)
     */
    public function verifications()
    {
        $pendingDoctors = Doctor::where('verification_status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $pendingClinics = Clinic::where('verification_status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.verifications', compact('pendingDoctors', 'pendingClinics'));
    }

    // =============================================
    // ✅ NEW: Helper method to create notification
    // =============================================
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
            // Silently fail if notifications table doesn't exist
        }
    }
    /**
 * Contact Messages (Admin)
 */
public function contacts(Request $request)
{
    // Use ContactMessage model - make sure it exists
    if (!class_exists(\App\Models\ContactMessage::class)) {
        return view('admin.contacts', [
            'contacts' => collect([]),
            'message' => 'Contact messages table not found.'
        ]);
    }

    $query = \App\Models\ContactMessage::query();

    // Filter by read/unread
    if ($request->filled('status')) {
        if ($request->status === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($request->status === 'unread') {
            $query->whereNull('read_at');
        }
    }

    // Search by name, email, or subject
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('subject', 'LIKE', "%{$search}%");
        });
    }

    $contacts = $query->orderBy('created_at', 'desc')->paginate(20);

    // Mark as read when viewed
    foreach ($contacts as $contact) {
        if ($contact->read_at === null) {
            $contact->update(['read_at' => now()]);
        }
    }

    return view('admin.contacts', compact('contacts'));
}

/**
 * Delete Contact Message
 */
public function deleteContact($id)
{
    if (!class_exists(\App\Models\ContactMessage::class)) {
        return back()->with('error', 'Contact messages table not found.');
    }

    $contact = \App\Models\ContactMessage::findOrFail($id);
    $contact->delete();

    return back()->with('success', __('messages.contact_deleted'));
}

/**
 * Reports & Analytics
 */
public function reports(Request $request)
{
    // Basic stats
    $totalUsers = User::count();
    $totalDoctors = Doctor::where('verification_status', 'approved')->count();
    $totalClinics = Clinic::where('verification_status', 'approved')->count();
    $totalAppointments = Appointment::count();
    $pendingDoctors = Doctor::where('verification_status', 'pending')->count();
    $pendingClinics = Clinic::where('verification_status', 'pending')->count();

    // Monthly data for charts
    $monthlyData = [];
    for ($i = 11; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $monthlyData[] = [
            'month' => $month->format('M Y'),
            'appointments' => Appointment::whereMonth('appointment_date', $month->month)
                ->whereYear('appointment_date', $month->year)
                ->count(),
            'users' => User::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count(),
            'doctors' => Doctor::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count(),
        ];
    }

    // Top doctors by appointments
    $topDoctors = Doctor::with('user')
        ->withCount(['appointments' => function($query) {
            $query->where('status', 'completed');
        }])
        ->where('verification_status', 'approved')
        ->orderBy('appointments_count', 'desc')
        ->limit(10)
        ->get();

    // Status distribution
    $statusCounts = [
        'pending' => Appointment::where('status', 'pending')->count(),
        'approved' => Appointment::where('status', 'approved')->count(),
        'completed' => Appointment::where('status', 'completed')->count(),
        'rejected' => Appointment::where('status', 'rejected')->count(),
        'cancelled' => Appointment::where('status', 'cancelled')->count(),
    ];

    return view('admin.reports', compact(
        'totalUsers',
        'totalDoctors',
        'totalClinics',
        'totalAppointments',
        'pendingDoctors',
        'pendingClinics',
        'monthlyData',
        'topDoctors',
        'statusCounts'
    ));
}
}