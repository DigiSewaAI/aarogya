<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class ClinicController extends Controller
{
    /**
     * Clinic Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        if (!$clinic) {
            return redirect()->route('clinic.profile.edit')
                ->with('warning', __('messages.complete_clinic_profile_first'));
        }

        // Get doctors under this clinic
        $doctors = Doctor::where('clinic_id', $clinic->id)->with('user')->get();
        $doctorIds = $doctors->pluck('id')->toArray();

        // Stats (handle empty doctors list)
        $totalDoctors = $doctors->count();
        $totalAppointments = $doctorIds ? Appointment::whereIn('doctor_id', $doctorIds)->count() : 0;
        $todayAppointments = $doctorIds ? Appointment::whereIn('doctor_id', $doctorIds)
            ->where('appointment_date', now()->toDateString())->count() : 0;
        $pendingAppointments = $doctorIds ? Appointment::whereIn('doctor_id', $doctorIds)
            ->where('status', 'pending')->count() : 0;
        $completedAppointments = $doctorIds ? Appointment::whereIn('doctor_id', $doctorIds)
            ->where('status', 'completed')->count() : 0;

        $recentAppointments = $doctorIds ? Appointment::whereIn('doctor_id', $doctorIds)
            ->with(['doctor', 'patient'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->limit(10)
            ->get() : collect([]);

        return view('clinic.dashboard', compact(
            'clinic',
            'totalDoctors',
            'totalAppointments',
            'todayAppointments',
            'pendingAppointments',
            'completedAppointments',
            'recentAppointments'
        ));
    }

    /**
     * Clinic Doctors List
     */
    public function doctors()
    {
        $user = Auth::user();
        $clinic = $user->clinic;
        
        if (!$clinic) {
            return redirect()->route('clinic.profile.edit')
                ->with('warning', __('messages.complete_clinic_profile_first'));
        }

        $doctors = Doctor::where('clinic_id', $clinic->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get available doctors (not assigned to any clinic) for adding
        $availableDoctors = Doctor::whereNull('clinic_id')
            ->where('verification_status', 'approved')
            ->with('user')
            ->get();

        return view('clinic.doctors', compact('clinic', 'doctors', 'availableDoctors'));
    }

    /**
     * Add Doctor to Clinic
     */
    public function addDoctor(Request $request)
    {
        $user = Auth::user();
        $clinic = $user->clinic;
        
        if (!$clinic) {
            return redirect()->route('clinic.profile.edit')
                ->with('warning', __('messages.complete_clinic_profile_first'));
        }

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        
        // Check if doctor already belongs to a clinic
        if ($doctor->clinic_id) {
            return back()->with('error', __('messages.doctor_already_assigned'));
        }

        $doctor->clinic_id = $clinic->id;
        $doctor->save();

        return back()->with('success', __('messages.doctor_added_to_clinic'));
    }

    /**
     * Remove Doctor from Clinic
     */
    public function removeDoctor($id)
    {
        $user = Auth::user();
        $clinic = $user->clinic;
        
        if (!$clinic) {
            return redirect()->route('clinic.profile.edit')
                ->with('warning', __('messages.complete_clinic_profile_first'));
        }

        $doctor = Doctor::findOrFail($id);
        
        // Verify doctor belongs to this clinic
        if ($doctor->clinic_id !== $clinic->id) {
            abort(403);
        }

        $doctor->clinic_id = null;
        $doctor->save();

        return back()->with('success', __('messages.doctor_removed_from_clinic'));
    }

    /**
     * Clinic Appointments List
     */
    public function appointments(Request $request)
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        if (!$clinic) {
            return redirect()->route('clinic.profile.edit')
                ->with('warning', __('messages.complete_clinic_profile_first'));
        }

        // Get doctor IDs under this clinic
        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id')->toArray();

        // If no doctors, return empty paginator
        if (empty($doctorIds)) {
            $appointments = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1);
            $doctors = collect([]);
            return view('clinic.appointments', compact('clinic', 'appointments', 'doctors'));
        }

        $query = Appointment::whereIn('doctor_id', $doctorIds);

        // Filter by doctor (if provided)
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter by status (if provided)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->with(['doctor', 'patient'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(20);

        // Get doctors for filter dropdown
        $doctors = Doctor::where('clinic_id', $clinic->id)->with('user')->get();

        return view('clinic.appointments', compact('clinic', 'appointments', 'doctors'));
    }

    /**
     * Clinic Profile - View Only
     */
    public function profile()
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        if (!$clinic) {
            $clinic = Clinic::create([
                'user_id' => $user->id,
                'clinic_name' => $user->name,
                'verification_status' => 'pending',
                'is_active' => true,
            ]);
            $clinic = $user->clinic;
        }

        return view('clinic.profile', compact('user', 'clinic'));
    }

    /**
     * Clinic Profile - Edit Form
     */
    public function profileEdit()
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        if (!$clinic) {
            $clinic = Clinic::create([
                'user_id' => $user->id,
                'clinic_name' => $user->name,
                'verification_status' => 'pending',
                'is_active' => true,
            ]);
            $clinic = $user->clinic;
        }

        return view('clinic.profile-edit', compact('user', 'clinic'));
    }

    /**
     * Update Clinic Profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|max:2048',
        ]);

        // Update user name and email
        $user->name = $validated['clinic_name'];
        if ($request->filled('email')) {
            $user->email = $validated['email'];
        }
        if ($request->filled('phone')) {
            $user->phone = $validated['phone'];
        }
        $user->save();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($clinic->logo) {
                Storage::disk('public')->delete($clinic->logo);
            }
            $path = $request->file('logo')->store('clinic-logos', 'public');
            $clinic->logo = $path;
        }

        // Update clinic
        $clinic->clinic_name = $validated['clinic_name'];
        $clinic->address = $validated['address'];
        $clinic->description = $validated['description'];
        $clinic->save();

        return back()->with('success', __('messages.clinic_profile_updated'));
    }

    /**
     * Clinic Statistics (optional)
     */
    public function statistics()
    {
        $user = Auth::user();
        $clinic = $user->clinic;
        
        if (!$clinic) {
            return redirect()->route('clinic.profile.edit')
                ->with('warning', __('messages.complete_clinic_profile_first'));
        }

        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id')->toArray();

        // Monthly appointments for chart
        $monthlyAppointments = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Appointment::whereIn('doctor_id', $doctorIds)
                ->whereMonth('appointment_date', $month->month)
                ->whereYear('appointment_date', $month->year)
                ->count();
            $monthlyAppointments[$month->format('M Y')] = $count;
        }

        return view('clinic.statistics', compact('clinic', 'monthlyAppointments'));
    }
}