<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)->get();
        return view('doctor.schedule', compact('schedules'));
    }

    public function store(Request $request)
    {
        $doctor = Auth::user()->doctor;

        $request->validate([
            'day_of_week' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'slot_duration' => 'nullable|integer|min:15|max:120',
        ]);

        DoctorSchedule::updateOrCreate(
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => $request->day_of_week,
            ],
            [
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'slot_duration' => $request->slot_duration ?? 30,
                'is_active' => true,
            ]
        );

        return back()->with('success', 'Schedule saved successfully!');
    }

    public function destroy($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);
        
        if ($schedule->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $schedule->delete();
        return back()->with('success', 'Schedule deleted successfully!');
    }
}