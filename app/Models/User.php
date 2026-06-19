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
     * Get all appointments where the user is the patient.
     * (Alias for patientAppointments – use this in controllers)
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

    // Note: The older 'patientAppointments' method has been removed because
    // 'appointments()' serves the same purpose with a cleaner name.
}