<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect based on role
            $user = Auth::user();
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => __('messages.auth_email_error'),
        ])->onlyInput('email');
    }

    protected function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
                break;
            case 'doctor':
                return redirect()->route('doctor.dashboard');
                break;
            case 'clinic':
                return redirect()->route('clinic.dashboard');
                break;
            default:
                return redirect()->route('patient.dashboard');
                break;
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}