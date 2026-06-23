<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;

class AppointmentCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $doctor;
    public $patient;
    public $date;
    public $time;
    public $locale;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, $locale = 'en')
    {
        $this->appointment = $appointment;
        $this->doctor = $appointment->doctor;
        $this->patient = $appointment->patient;
        $this->date = $appointment->appointment_date;
        $this->time = $appointment->appointment_time;
        $this->locale = $locale;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        app()->setLocale($this->locale);

        return $this->subject(__('messages.appointment_completed_email_subject'))
                    ->view('emails.appointment-completed')
                    ->with([
                        'appointment' => $this->appointment,
                        'doctor' => $this->doctor,
                        'patient' => $this->patient,
                        'date' => $this->date,
                        'time' => $this->time,
                        'clinic' => $this->doctor->clinic,
                    ]);
    }
}