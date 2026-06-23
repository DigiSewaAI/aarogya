<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    /**
     * Show Privacy Policy page
     */
    public function privacy()
    {
        return view('legal.privacy');
    }

    /**
     * Show Terms of Service page
     */
    public function terms()
    {
        return view('legal.terms');
    }

    /**
     * Show Contact page
     */
    public function contact()
    {
        return view('legal.contact');
    }

    /**
     * Show About page
     */
    public function about()
    {
        return view('legal.about');
    }

    /**
     * Handle contact form submission
     */
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Save to database
        $contact = ContactMessage::create($validated);

        // Optional: Send email notification
        // Mail::to('support@aarogya.com')->send(new ContactMessageMail($contact));

        return back()->with('success', __('messages.contact_sent'));
    }
}