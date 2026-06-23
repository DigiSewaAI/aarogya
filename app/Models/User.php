<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_photo',
        'email_verified_at',
        'is_active',  // ✅ NEW: added is_active
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',  // ✅ NEW: cast to boolean
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE CHECK HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is a patient.
     */
    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    /**
     * Check if user is a doctor.
     */
    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    /**
     * Check if user is a clinic.
     */
    public function isClinic(): bool
    {
        return $this->role === 'clinic';
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the doctor profile associated with the user.
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Get the clinic profile associated with the user.
     */
    public function clinic()
    {
        return $this->hasOne(Clinic::class);
    }

    /**
     * Get the health profile associated with the user (for patients).
     */
    public function healthProfile()
    {
        return $this->hasOne(HealthProfile::class);
    }

    /**
     * Get all appointments where the user is the patient.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    /**
     * Get all appointments for the doctor (through the doctor profile).
     * This uses hasManyThrough: User -> Doctor -> Appointment
     */
    public function doctorAppointments()
    {
        return $this->hasManyThrough(
            Appointment::class,    // Final model
            Doctor::class,         // Intermediate model
            'user_id',             // Foreign key on doctors table (links to users.id)
            'doctor_id',           // Foreign key on appointments table (links to doctors.id)
            'id',                  // Local key on users table
            'id'                   // Local key on doctors table
        );
    }

    /**
     * Get all in-app notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get unread notifications count.
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    /*
    |--------------------------------------------------------------------------
    | PROFILE COMPLETION
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate profile completion percentage.
     */
    public function getProfileCompletionAttribute()
    {
        $score = 0;
        $total = 10;

        // Basic info
        if ($this->name) $score++;
        if ($this->email) $score++;
        if ($this->phone) $score++;
        if ($this->address) $score++;
        if ($this->profile_photo) $score++;

        // Role-specific checks
        if ($this->isDoctor() && $this->doctor) {
            $doctor = $this->doctor;
            if ($doctor->qualification) $score++;
            if ($doctor->specialization) $score++;
            if ($doctor->nmc_registration) $score++;
            if ($doctor->experience && $doctor->experience > 0) $score++;
            if ($doctor->consultation_fee && $doctor->consultation_fee > 0) $score++;
            if ($doctor->bio) $score++;
            if ($doctor->clinic_name) $score++;
            if ($doctor->clinic_address) $score++;
            // Check if schedule exists
            if (DoctorSchedule::where('doctor_id', $doctor->id)->exists()) $score++;
            // Adjust total for doctor (more fields)
            $total = 15;
        }

        if ($this->isPatient()) {
            $profile = $this->healthProfile;
            if ($profile) {
                if ($profile->blood_group) $score++;
                if ($profile->allergies) $score++;
                if ($profile->chronic_diseases) $score++;
                if ($profile->current_medications) $score++;
                if ($profile->height) $score++;
                if ($profile->weight) $score++;
                if ($profile->emergency_contact_name) $score++;
                if ($profile->emergency_contact_number) $score++;
                // Adjust total for patient (more fields)
                $total = 13;
            }
        }

        if ($this->isClinic() && $this->clinic) {
            $clinic = $this->clinic;
            if ($clinic->clinic_name) $score++;
            if ($clinic->address) $score++;
            if ($clinic->phone) $score++;
            if ($clinic->description) $score++;
            if ($clinic->logo) $score++;
            $total = 10;
        }

        return min(100, round(($score / $total) * 100));
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include users with a specific role.
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }
}