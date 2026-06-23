<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Clinic;

class ClinicApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clinic;
    public $user;
    public $locale;

    /**
     * Create a new message instance.
     */
    public function __construct(Clinic $clinic, $locale = 'en')
    {
        $this->clinic = $clinic;
        $this->user = $clinic->user;
        $this->locale = $locale;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        app()->setLocale($this->locale);

        return $this->subject(__('messages.clinic_approved_email_subject'))
                    ->view('emails.clinic-approved')
                    ->with([
                        'clinic' => $this->clinic,
                        'user' => $this->user,
                        'login_url' => route('login'),
                        'dashboard_url' => route('clinic.dashboard'),
                    ]);
    }
}