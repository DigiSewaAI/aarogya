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
use App\Http\Controllers\PageController;
use App\Http\Controllers\HealthProfileController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DoctorScheduleController;
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

// ==============================================
// PUBLIC CLINIC ROUTES
// ==============================================
Route::get('/clinics', [ClinicController::class, 'index'])->name('clinics');
Route::get('/clinic/{id}', [ClinicController::class, 'show'])->name('clinic.show');

// ==============================================
// LEGAL & INFORMATION PAGES
// ==============================================
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-of-service', [PageController::class, 'terms'])->name('terms');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/about', [PageController::class, 'about'])->name('about');

// Contact Form Submission
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');

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

        // ✅ Health Profile
        Route::get('/health-profile', [HealthProfileController::class, 'index'])->name('health-profile');
        Route::post('/health-profile', [HealthProfileController::class, 'update'])->name('health-profile.update');

        // ✅ Medical Records
        Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-records');
        Route::get('/medical-records/create', [MedicalRecordController::class, 'create'])->name('medical-records.create');
        Route::post('/medical-records', [MedicalRecordController::class, 'store'])->name('medical-records.store');
        Route::get('/medical-records/{id}/download', [MedicalRecordController::class, 'download'])->name('medical-records.download');
        Route::delete('/medical-records/{id}', [MedicalRecordController::class, 'delete'])->name('medical-records.delete');
        Route::post('/medical-records/{id}/share', [MedicalRecordController::class, 'share'])->name('medical-records.share');

        // ✅ Prescriptions (Patient view)
        Route::get('/prescriptions', [PrescriptionController::class, 'patientIndex'])->name('prescriptions');
        Route::get('/prescriptions/{id}', [PrescriptionController::class, 'patientShow'])->name('prescriptions.show');
        Route::get('/prescriptions/{id}/download', [PrescriptionController::class, 'download'])->name('prescriptions.download');

        // ✅ Follow-ups (Patient view)
        Route::get('/follow-ups', [FollowUpController::class, 'patientIndex'])->name('follow-ups');
        Route::post('/follow-ups/{id}/confirm', [FollowUpController::class, 'confirm'])->name('follow-ups.confirm');

        // ✅ Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
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
        Route::post('/schedule/update', [DoctorScheduleController::class, 'store'])->name('schedule.update');
        Route::get('/qr-code', [QRCodeController::class, 'index'])->name('qr.index');
        Route::get('/qr-code/download', [QRCodeController::class, 'download'])->name('qr.download');
        Route::get('/qr-code/print', [QRCodeController::class, 'print'])->name('qr.print');
        Route::get('/qr-code/share', [QRCodeController::class, 'share'])->name('qr.share');
        

        // ✅ Appointment Actions (Approve, Reject, Complete, Cancel)
        Route::post('/appointment/{id}/approve', [DoctorController::class, 'approve'])->name('appointment.approve');
        Route::post('/appointment/{id}/reject', [DoctorController::class, 'reject'])->name('appointment.reject');
        Route::post('/appointment/{id}/complete', [DoctorController::class, 'complete'])->name('appointment.complete');
        Route::post('/appointment/{id}/cancel', [DoctorController::class, 'cancelAppointment'])->name('appointment.cancel');
        Route::post('/appointment/{id}/action', [DoctorController::class, 'updateStatus'])->name('appointment.action');

        // ✅ Patient Health Profile (Doctor viewing patient)
        Route::get('/patient/{userId}/health-profile', [HealthProfileController::class, 'show'])->name('patient.health-profile');
        Route::get('/patient/{patientId}/records', [MedicalRecordController::class, 'viewPatientRecords'])->name('patient.records');

        // ✅ Prescriptions (Doctor)
        Route::get('/prescriptions', [PrescriptionController::class, 'doctorIndex'])->name('prescriptions');
        Route::get('/prescriptions/create/{appointmentId}', [PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
        Route::get('/prescriptions/{id}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
        Route::get('/prescriptions/{id}/download', [PrescriptionController::class, 'download'])->name('prescriptions.download');

        // ✅ Follow-ups (Doctor)
        Route::get('/follow-ups', [FollowUpController::class, 'doctorIndex'])->name('follow-ups');
        Route::get('/follow-ups/create/{appointmentId}', [FollowUpController::class, 'create'])->name('follow-ups.create');
        Route::post('/follow-ups', [FollowUpController::class, 'store'])->name('follow-ups.store');
        Route::put('/follow-ups/{id}', [FollowUpController::class, 'update'])->name('follow-ups.update');
        Route::delete('/follow-ups/{id}', [FollowUpController::class, 'delete'])->name('follow-ups.delete');

        // ✅ Doctor Availability (AJAX)
        Route::get('/availability/{id}', [DoctorController::class, 'getAvailability'])->name('availability');

        // ✅ Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
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

        // ✅ Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
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

        // ✅ Contact Messages (Admin view)
        Route::get('/contacts', [AdminController::class, 'contacts'])->name('contacts');
        Route::delete('/contacts/{id}', [AdminController::class, 'deleteContact'])->name('contacts.delete');

        // ✅ Analytics / Reports
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

        // ✅ Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    });

    // ==========================================
    // APPOINTMENT ROUTES (Shared for authenticated users)
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