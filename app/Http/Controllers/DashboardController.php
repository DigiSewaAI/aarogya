<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class DashboardController extends Controller
{
        public function index()
    {
        $user = auth()->user();

        // 1. Upcoming appointments (approved र आज वा भोलिका)
        $upcomingAppointments = Appointment::where('patient_id', $user->id)
            ->where('status', 'approved')
            ->where('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->limit(5)
            ->get();

        // 2. Recent appointment history (last 10)
        $appointmentHistory = Appointment::where('patient_id', $user->id)
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->limit(10)
            ->get();

        // 3. Total appointments count
        $totalAppointments = Appointment::where('patient_id', $user->id)->count();

        // 4. Pending appointments count
        $pendingAppointments = Appointment::where('patient_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Return view – निर्देशन अनुसार 'patient.dashboard' प्रयोग गरियो
        return view('patient.dashboard', compact(
            'user',
            'upcomingAppointments',
            'appointmentHistory',
            'totalAppointments',
            'pendingAppointments'
        ));
    }
}
