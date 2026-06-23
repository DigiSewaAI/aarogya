<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $locale;

    public function __construct(User $user, $locale = 'en')
    {
        $this->user = $user;
        $this->locale = $locale;
    }

    public function build()
    {
        app()->setLocale($this->locale);
        
        return $this->subject(__('messages.welcome_email_subject', ['name' => $this->user->name]))
                    ->view('emails.welcome')
                    ->with([
                        'name' => $this->user->name,
                        'role' => $this->user->role,
                        'url' => route('login'),
                    ]);
    }
}