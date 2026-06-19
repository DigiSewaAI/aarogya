<?php

use App\Http\Controllers\SymptomCheckerController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\QRCodeController;
use Illuminate\Support\Facades\Auth;

// ==============================================
// PUBLIC ROUTES (No auth required)
// ==============================================
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/symptom-checker', [SymptomCheckerController::class, 'index'])->name('symptom.checker');
Route::post('/symptom-checker', [SymptomCheckerController::class, 'analyze'])->name('symptom.analyze');
Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors');
Route::get('/services', [ServiceController::class, 'index'])->name('services');

// ✅ Public QR Code view
Route::get('/qr/{id}', [QRCodeController::class, 'show'])->name('qr.show');

// Language Switcher
Route::post('/switch-language', [LanguageController::class, 'switch'])->name('language.switch');

// ==============================================
// GUEST ROUTES
// ==============================================
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// ==============================================
// AUTHENTICATED ROUTES
// ==============================================
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ==========================================
    // PATIENT DASHBOARD
    // ==========================================
    Route::middleware('role:patient')->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/appointments', [AppointmentController::class, 'patientAppointments'])->name('appointments');
        Route::post('/appointments/cancel/{id}', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    });

    // ==========================================
    // DOCTOR DASHBOARD
    // ==========================================
    Route::middleware('role:doctor')->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
        Route::get('/appointments', [DoctorController::class, 'appointments'])->name('appointments');
        Route::get('/schedule', [DoctorController::class, 'schedule'])->name('schedule');
        Route::get('/profile', [DoctorController::class, 'profile'])->name('profile');
        Route::get('/patients', [DoctorController::class, 'patients'])->name('patients');
        Route::get('/profile/edit', [DoctorController::class, 'profileEdit'])->name('profile.edit');
        Route::post('/profile/update', [DoctorController::class, 'updateProfile'])->name('profile.update');
        Route::post('/schedule/update', [DoctorController::class, 'scheduleStore'])->name('schedule.update');

        Route::get('/qr-code', [QRCodeController::class, 'index'])->name('qr.index');
        Route::get('/qr-code/download', [QRCodeController::class, 'download'])->name('qr.download');
        Route::get('/qr-code/print', [QRCodeController::class, 'print'])->name('qr.print');
        Route::get('/qr-code/share', [QRCodeController::class, 'share'])->name('qr.share');
    });

    // ==========================================
    // CLINIC DASHBOARD
    // ==========================================
    Route::middleware('role:clinic')->prefix('clinic')->name('clinic.')->group(function () {
        Route::get('/dashboard', [ClinicController::class, 'dashboard'])->name('dashboard');
        Route::get('/doctors', [ClinicController::class, 'doctors'])->name('doctors');
        Route::get('/appointments', [ClinicController::class, 'appointments'])->name('appointments');
        Route::get('/profile', [ClinicController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [ClinicController::class, 'profileEdit'])->name('profile.edit');
        Route::post('/profile/update', [ClinicController::class, 'updateProfile'])->name('profile.update');
        Route::post('/doctors/add', [ClinicController::class, 'addDoctor'])->name('doctors.add');
        Route::delete('/doctors/{id}/remove', [ClinicController::class, 'removeDoctor'])->name('doctors.remove');
    });

    // ==========================================
    // ADMIN DASHBOARD
    // ==========================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/doctors', [AdminController::class, 'doctors'])->name('doctors');
        Route::get('/clinics', [AdminController::class, 'clinics'])->name('clinics');
        Route::get('/verifications', [AdminController::class, 'verifications'])->name('verifications');

        Route::post('/verify-doctor/{id}', [AdminController::class, 'verifyDoctor'])->name('verify.doctor');
        Route::post('/reject-doctor/{id}', [AdminController::class, 'rejectDoctor'])->name('reject.doctor');
        Route::post('/verify-clinic/{id}', [AdminController::class, 'verifyClinic'])->name('verify.clinic');
        Route::post('/reject-clinic/{id}', [AdminController::class, 'rejectClinic'])->name('reject.clinic');

        Route::post('/toggle-user/{id}', [AdminController::class, 'toggleUser'])->name('toggle.user');
        Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('delete.user');
    });

    // ==========================================
    // APPOINTMENT ROUTES
    // ==========================================
    Route::get('/appointment/create/{doctorId}', [AppointmentController::class, 'create'])->name('appointment.create');
    Route::post('/appointment/store', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::get('/appointment/slots', [AppointmentController::class, 'getSlots'])->name('appointment.slots');

}); // ✅ auth group ends here

// ==============================================
// ⭐ PUBLIC DOCTOR PROFILE ROUTE (MUST BE AT THE BOTTOM)
// ==============================================
Route::get('/doctor/{id}', [DoctorController::class, 'show'])->name('doctor.show');

// ==============================================
// DEFAULT DASHBOARD REDIRECT
// ==============================================
Route::middleware('auth')->get('/dashboard', function () {
    $user = Auth::user();
    return match($user->role) {
        'doctor' => redirect()->route('doctor.dashboard'),
        'clinic' => redirect()->route('clinic.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        default => redirect()->route('patient.dashboard'),
    };
})->name('dashboard');

// ==============================================
// DEFAULT FALLBACK
// ==============================================
Route::fallback(function () {
    return redirect('/');
});