<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QRCodeController extends Controller
{
    /**
     * Show QR Code page for doctor
     */
    public function index()
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.profile.edit')
                ->with('error', __('messages.complete_profile_first'));
        }

        return view('doctor.qr-code', compact('doctor'));
    }

    /**
     * Generate and download QR Code as PNG
     */
    public function download()
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.profile.edit')
                ->with('error', __('messages.complete_profile_first'));
        }

        $url = route('doctor.show', $doctor->id);
        $qrCode = QrCode::size(400)
            ->format('png')
            ->generate($url);

        // Use Str::slug instead of deprecated str_slug
        $filename = 'qr-code-' . Str::slug($doctor->name) . '.png';

        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Print QR Code (view for printing)
     */
    public function print()
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.profile.edit')
                ->with('error', __('messages.complete_profile_first'));
        }

        return view('doctor.qr-print', compact('doctor'));
    }

    /**
     * Share QR Code (social sharing page)
     */
    public function share()
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.profile.edit')
                ->with('error', __('messages.complete_profile_first'));
        }

        $shareUrl = route('doctor.show', $doctor->id);
        $qrData = $doctor->qr_code_base64; // using accessor from Doctor model

        return view('doctor.qr-share', compact('doctor', 'shareUrl', 'qrData'));
    }

    /**
     * Show QR in full screen (mobile friendly) - Public route
     */
    public function show($id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);

        // Only show if doctor is verified and active
        if ($doctor->verification_status !== 'approved' || !$doctor->is_active) {
            abort(404, 'Doctor not available.');
        }

        $qrCode = QrCode::size(400)
            ->format('png')
            ->generate(route('doctor.show', $doctor->id));

        return view('qr.show', compact('doctor', 'qrCode'));
    }
}