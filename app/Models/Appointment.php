<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // ✅ QR Code Facade

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | STATUS CONSTANTS (Best Practice)
    |--------------------------------------------------------------------------
    */
    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'symptoms',
        'notes',
        'status',
        'cancelled_at',      // यदि migration मा छ भने मात्र काम गर्छ
        'cancelled_reason',  // यदि migration मा छ भने मात्र काम गर्छ
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_date' => 'date',
        // appointment_time लाई 'string' मा cast गरियो (time field हो)
        'appointment_time' => 'string',
        'cancelled_at'     => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the patient (user) for this appointment.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the doctor for this appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS CHECK HELPERS (using constants)
    |--------------------------------------------------------------------------
    */

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeToday($query)
    {
        return $query->where('appointment_date', now()->toDateString());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
            ->where('status', self::STATUS_APPROVED);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (Formatted Output)
    |--------------------------------------------------------------------------
    */

    /**
     * Get formatted date (e.g., "Jan 15, 2025")
     */
    public function getFormattedDateAttribute()
    {
        return $this->appointment_date->format('M d, Y');
    }

    /**
     * Get formatted time (e.g., "02:30 PM")
     * appointment_time string भएकोले Carbon::parse() प्रयोग गरियो
     */
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->appointment_time)->format('h:i A');
    }

    /**
     * Get the full CSS classes for status badge (to use directly in Blade)
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING   => 'bg-yellow-100 text-yellow-800',
            self::STATUS_APPROVED  => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED  => 'bg-red-100 text-red-800',
            self::STATUS_COMPLETED => 'bg-blue-100 text-blue-800',
            self::STATUS_CANCELLED => 'bg-gray-100 text-gray-800',
            default                => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status label (human-readable)
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING   => 'Pending',
            self::STATUS_APPROVED  => 'Approved',
            self::STATUS_REJECTED  => 'Rejected',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            default                => ucfirst($this->status),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | QR CODE GENERATION (Module 8)
    |--------------------------------------------------------------------------
    */

    /**
     * Generate QR Code as base64 for appointment details.
     * Usage: <img src="data:image/png;base64,{{ $appointment->qr_code_base64 }}">
     */
    public function getQrCodeBase64Attribute()
    {
        $data = json_encode([
            'appointment_id' => $this->id,
            'doctor' => $this->doctor->name,
            'date' => $this->appointment_date->format('Y-m-d'),
            'time' => $this->appointment_time,
            'status' => $this->status,
        ]);
        $qrCode = QrCode::format('png')->size(200)->generate($data);
        return base64_encode($qrCode);
    }
}