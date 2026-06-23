<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorScheduleController extends Controller
{
    /**
     * Display the doctor's schedule page.
     */
    public function index()
    {
        $doctor = Auth::user()->doctor;
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)->get();

        // Group schedules by day for easy access in view
        $scheduleData = [];
        foreach ($schedules as $schedule) {
            $scheduleData[$schedule->day_of_week] = [
                'start' => $schedule->start_time,
                'end'   => $schedule->end_time,
            ];
        }

        // Ensure all days are present (even if no schedule)
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        foreach ($days as $day) {
            if (!isset($scheduleData[$day])) {
                $scheduleData[$day] = ['start' => '', 'end' => ''];
            }
        }

        return view('doctor.schedule', compact('scheduleData'));
    }

    /**
     * Store or update schedule(s) – handles both bulk and single day.
     */
    public function store(Request $request)
    {
        $doctor = Auth::user()->doctor;

        // -------------------------------
        // 1️⃣ BULK UPDATE (multiple days)
        // -------------------------------
        if ($request->has('schedule')) {
            $scheduleData = $request->input('schedule', []);
            $slotDuration = (int) $request->input('slot_duration', 30);

            // Convert all times to 24-hour format (H:i) and validate
            $converted = [];
            $errors = [];

            foreach ($scheduleData as $day => $times) {
                $start = $times['start'] ?? '';
                $end   = $times['end'] ?? '';

                // Parse and format to H:i (supports AM/PM and 24h)
                try {
                    $start24 = $start ? Carbon::parse($start)->format('H:i') : '';
                    $end24   = $end   ? Carbon::parse($end)->format('H:i') : '';
                } catch (\Exception $e) {
                    $errors[] = "Invalid time format for {$day}.";
                    continue;
                }

                // Check if both are filled
                if ($start24 && $end24) {
                    // Ensure end is after start
                    if (strtotime($end24) <= strtotime($start24)) {
                        $errors[] = "End time must be after start time for {$day}.";
                        continue;
                    }
                    $converted[$day] = ['start' => $start24, 'end' => $end24];
                } else {
                    // If either is empty, skip saving (or you can set default)
                    // For now, we skip – but you can set to null or keep existing.
                    // We'll just not update this day.
                    continue;
                }
            }

            // If there are validation errors, return with them
            if (!empty($errors)) {
                return back()->withErrors($errors)->withInput();
            }

            // Save each day
            foreach ($converted as $day => $times) {
                DoctorSchedule::updateOrCreate(
                    [
                        'doctor_id'   => $doctor->id,
                        'day_of_week' => $day,
                    ],
                    [
                        'start_time'    => $times['start'],
                        'end_time'      => $times['end'],
                        'slot_duration' => $slotDuration,
                        'is_active'     => true,
                    ]
                );
            }

            return back()->with('success', 'All schedules updated successfully!');
        }

        // -------------------------------
        // 2️⃣ SINGLE DAY UPDATE
        // -------------------------------
        $request->validate([
            'day_of_week'   => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'slot_duration' => 'nullable|integer|min:15|max:120',
        ]);

        DoctorSchedule::updateOrCreate(
            [
                'doctor_id'   => $doctor->id,
                'day_of_week' => $request->day_of_week,
            ],
            [
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time,
                'slot_duration' => $request->slot_duration ?? 30,
                'is_active'     => true,
            ]
        );

        return back()->with('success', 'Schedule saved successfully!');
    }

    /**
     * Delete a specific schedule entry.
     */
    public function destroy($id)
    {
        $schedule = DoctorSchedule::findOrFail($id);

        // Ensure the schedule belongs to the logged-in doctor
        if ($schedule->doctor_id !== Auth::user()->doctor->id) {
            abort(403, 'Unauthorized action.');
        }

        $schedule->delete();
        return back()->with('success', 'Schedule deleted successfully!');
    }
}