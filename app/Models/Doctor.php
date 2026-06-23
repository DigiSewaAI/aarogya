<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // ✅ QR Code Facade

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'clinic_id',
        'name',
        'qualification',
        'specialization',
        'nmc_registration',
        'experience',
        'consultation_fee',
        'profile_photo',
        'bio',
        'clinic_name',
        'clinic_address',
        'verification_status',
        'is_active',
    ];

    protected $casts = [
        'experience' => 'integer',
        'consultation_fee' => 'decimal:2',
        'is_active' => 'boolean',
        'verification_status' => 'string',
    ];

    /**
     * Get the user who is this doctor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the clinic this doctor belongs to.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the schedules for this doctor.
     */
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    /**
     * Get the appointments for this doctor.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the patients (unique) for this doctor.
     */
    public function patients()
    {
        return $this->hasManyThrough(User::class, Appointment::class, 'doctor_id', 'id', 'id', 'patient_id')
            ->where('appointments.status', 'completed')
            ->distinct();
    }

    /**
     * Check if doctor is verified.
     */
    public function isVerified()
    {
        return $this->verification_status === 'approved';
    }

    /**
     * Check if doctor is pending verification.
     */
    public function isPending()
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Get the full name with title.
     */
    public function getFullNameAttribute()
    {
        return 'Dr. ' . $this->name;
    }

    /**
     * Scope a query to only include verified doctors.
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'approved');
    }

    /**
     * Scope a query to only include active doctors.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | QR CODE GENERATION METHODS (Module 8 - Appointment System)
    |--------------------------------------------------------------------------
    */

    /**
     * Generate QR Code for doctor profile (returns raw PNG image data).
     */
    public function getQrCodeAttribute()
    {
        $url = route('doctor.show', $this->id);
        return QrCode::size(300)
            ->format('png')
            ->generate($url);
    }

    /**
     * Get QR code as base64 for inline display in HTML.
     * Usage: <img src="data:image/png;base64,{{ $doctor->qr_code_base64 }}">
     */
    public function getQrCodeBase64Attribute()
    {
        $url = route('doctor.show', $this->id);
        $qrCode = QrCode::format('png')->size(300)->generate($url);
        return base64_encode($qrCode);
    }

    /**
     * Get QR code data as JSON (for sharing or API).
     * Contains doctor ID, name, specialization, and profile URL.
     */
    public function getQrCodeDataAttribute()
    {
        return json_encode([
            'id' => $this->id,
            'name' => $this->name,
            'specialization' => $this->specialization,
            'url' => route('doctor.show', $this->id),
        ]);
    }
    // Add to app/Models/Doctor.php

public function getAvailabilityStatusAttribute()
{
    $today = now()->toDateString();
    $now = now()->format('H:i');
    
    // Check if doctor has any appointment today
    $hasAppointmentToday = Appointment::where('doctor_id', $this->id)
        ->where('appointment_date', $today)
        ->whereIn('status', ['approved', 'pending'])
        ->exists();
    
    // Check if doctor has schedule for today
    $scheduleToday = DoctorSchedule::where('doctor_id', $this->id)
        ->where('day_of_week', now()->format('l'))
        ->where('is_active', true)
        ->first();
    
    if (!$scheduleToday) {
        return 'offline';
    }
    
    if ($hasAppointmentToday) {
        return 'fully_booked';
    }
    
    return 'available';
}

public function getAvailabilityBadgeAttribute()
{
    return match($this->availability_status) {
        'available' => 'bg-green-100 text-green-700',
        'fully_booked' => 'bg-orange-100 text-orange-700',
        'offline' => 'bg-gray-100 text-gray-700',
        default => 'bg-gray-100 text-gray-700',
    };
}

public function getAvailabilityLabelAttribute()
{
    return match($this->availability_status) {
        'available' => __('messages.available_today'),
        'fully_booked' => __('messages.fully_booked'),
        'offline' => __('messages.offline'),
        default => __('messages.offline'),
    };
}
}