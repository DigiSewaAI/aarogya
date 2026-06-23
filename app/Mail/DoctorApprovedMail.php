<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Doctor;

class DoctorApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $doctor;
    public $user;
    public $locale;

    /**
     * Create a new message instance.
     */
    public function __construct(Doctor $doctor, $locale = 'en')
    {
        $this->doctor = $doctor;
        $this->user = $doctor->user;
        $this->locale = $locale;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        app()->setLocale($this->locale);

        return $this->subject(__('messages.doctor_approved_email_subject'))
                    ->view('emails.doctor-approved')
                    ->with([
                        'doctor' => $this->doctor,
                        'user' => $this->user,
                        'login_url' => route('login'),
                        'dashboard_url' => route('doctor.dashboard'),
                    ]);
    }
}