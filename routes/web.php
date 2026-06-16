<?php

use App\Http\Controllers\SymptomCheckerController;
use App\Http\Controllers\DoctorController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/symptom-checker', [SymptomCheckerController::class, 'index']);
Route::get('/doctors', [DoctorController::class, 'index']);