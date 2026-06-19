<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'clinic_name',
        'address',
        'phone',
        'email',
        'logo',
        'description',
        'verification_status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'verification_status' => 'string',
    ];

    // Constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get the user who owns this clinic.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the doctors associated with this clinic.
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    /**
     * Get appointments for this clinic (through doctors).
     */
    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, Doctor::class);
    }

    /**
     * Check if clinic is verified.
     */
    public function isVerified(): bool
    {
        return $this->verification_status === self::STATUS_APPROVED;
    }

    /**
     * Check if clinic is pending verification.
     */
    public function isPending(): bool
    {
        return $this->verification_status === self::STATUS_PENDING;
    }

    /**
     * Get logo URL.
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return asset('images/default-clinic.png');
    }

    /**
     * Scope for verified clinics.
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', self::STATUS_APPROVED);
    }

    /**
     * Scope for active clinics.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}