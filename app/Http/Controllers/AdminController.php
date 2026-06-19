<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    return view('admin.dashboard', compact('stats', 'recentUsers', 'recentAppointments'));
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
     * Verify Doctor (Approve)
     */
    public function verifyDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->verification_status = 'approved';
        $doctor->save();

        return back()->with('success', __('messages.doctor_verified_success'));
    }

    /**
     * Reject Doctor
     */
    public function rejectDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->verification_status = 'rejected';
        $doctor->save();

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
     * Verify Clinic (Approve)
     */
    public function verifyClinic($id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->verification_status = 'approved';
        $clinic->save();

        return back()->with('success', __('messages.clinic_verified_success'));
    }

    /**
     * Reject Clinic
     */
    public function rejectClinic($id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->verification_status = 'rejected';
        $clinic->save();

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
}