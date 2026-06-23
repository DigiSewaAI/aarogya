<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthProfile;
use App\Models\User;              // ✅ ADDED
use App\Models\Appointment;       // ✅ ADDED
use Illuminate\Support\Facades\Auth;

class HealthProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->healthProfile ?? new HealthProfile(['user_id' => $user->id]);
        
        return view('patient.health-profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'blood_group' => 'nullable|string|max:10',
            'allergies' => 'nullable|string|max:500',
            'chronic_diseases' => 'nullable|string|max:500',
            'current_medications' => 'nullable|string|max:500',
            'height' => 'nullable|numeric|min:50|max:300',
            'weight' => 'nullable|numeric|min:10|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:20',
        ]);

        $profile = $user->healthProfile ?? new HealthProfile(['user_id' => $user->id]);
        $profile->fill($validated);
        $profile->save();

        return back()->with('success', __('messages.health_profile_updated'));
    }

    public function show($userId)
    {
        $user = User::findOrFail($userId);
        
        // Only allow if doctor is viewing their patient
        $doctor = Auth::user()->doctor;
        $hasPatient = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $user->id)
            ->exists();
            
        if (!$hasPatient && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $profile = $user->healthProfile;
        return view('doctor.patient-health-profile', compact('user', 'profile'));
    }
}