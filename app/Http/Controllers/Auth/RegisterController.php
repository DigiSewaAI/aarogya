<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Clinic;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:patient,doctor,clinic',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'facility_type' => 'nullable|in:clinic,hospital,diagnostic,other',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        event(new Registered($user));

        // ========== HANDLE HEALTHCARE FACILITY ==========
        if ($user->role === 'clinic') {
            $facilityType = $request->input('facility_type', 'clinic');

            // Create clinic record
            $clinic = Clinic::create([
                'user_id' => $user->id,
                'clinic_name' => $validated['name'],
                'facility_type' => $facilityType,
                'verification_status' => 'pending',
                'is_active' => true,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            // ========== HOSPITAL / DIAGNOSTIC = DO NOT LOGIN ==========
            if ($facilityType !== 'clinic') {
                // Logout the user (they are not logged in yet, but just in case)
                Auth::logout();
                // Show message and redirect to login
                return redirect()->route('login')
                    ->with('info', __('messages.facility_onboarding_message', ['type' => ucfirst($facilityType)]));
            }

            // ========== CLINIC / OTHER = LOGIN ==========
            Auth::login($user);
            return redirect()->route('clinic.dashboard')
                ->with('success', __('messages.registration_success'));
        }

        // ========== PATIENT / DOCTOR = LOGIN ==========
        Auth::login($user);
        return $this->redirectBasedOnRole($user);
    }

    protected function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            'doctor' => redirect()
                ->route('doctor.profile.edit')
                ->with('success', __('messages.profile_required')),

            'clinic' => redirect()
                ->route('clinic.dashboard')
                ->with('success', __('messages.registration_success')),

            default => redirect()
                ->route('patient.dashboard')
                ->with('success', __('messages.registration_success')),
        };
    }
}