<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'slot_duration',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
        'slot_duration' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the doctor that owns this schedule.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get all time slots for this schedule.
     */
    public function getTimeSlots()
    {
        $slots = [];
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        $duration = $this->slot_duration * 60; // convert to seconds

        while ($start < $end) {
            $slots[] = [
                'start' => date('H:i', $start),
                'end' => date('H:i', $start + $duration),
            ];
            $start += $duration;
        }

        return $slots;
    }

    /**
     * Scope a query to only include active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get day name in Nepali (for display).
     */
    public function getDayNameAttribute()
    {
        $days = [
            'Sunday' => 'आइतबार',
            'Monday' => 'सोमबार',
            'Tuesday' => 'मंगलबार',
            'Wednesday' => 'बुधबार',
            'Thursday' => 'बिहिबार',
            'Friday' => 'शुक्रबार',
            'Saturday' => 'शनिबार',
        ];
        return $days[$this->day_of_week] ?? $this->day_of_week;
    }
}