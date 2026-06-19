<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:patient,doctor,clinic',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        // Fire registered event (for email verification if enabled)
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        // Redirect based on role
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirect user based on their role.
     */
    protected function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            'doctor' => redirect()
                ->route('doctor.profile.edit')
                ->with('success', __('messages.profile_required')),

            'clinic' => redirect()
                ->route('clinic.profile.edit')
                ->with('success', __('messages.profile_required')),

            default => redirect()
                ->route('patient.dashboard')
                ->with('success', __('messages.registration_success')),
        };
    }
}