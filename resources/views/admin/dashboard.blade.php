@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.admin_dashboard') }}</h2>
    <p class="text-slate-500">{{ __('messages.dashboard_welcome', ['name' => Auth::user()->name]) }}</p>

    <div class="grid md:grid-cols-4 gap-4 mt-6">
        <div class="bg-cyan-50 p-4 rounded-2xl text-center">
            <div class="text-3xl font-bold text-cyan-600">{{ $stats['totalUsers'] ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.users') }}</p>
        </div>
        <div class="bg-blue-50 p-4 rounded-2xl text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['totalDoctors'] ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.doctors') }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-2xl text-center">
            <div class="text-3xl font-bold text-green-600">{{ $stats['totalClinics'] ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.clinics') }}</p>
        </div>
        <div class="bg-amber-50 p-4 rounded-2xl text-center">
            <div class="text-3xl font-bold text-amber-600">{{ $stats['totalAppointments'] ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.appointments') }}</p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4 mt-6">
        <div class="bg-yellow-50 p-4 rounded-2xl text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pendingDoctors'] ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.pending_doctors') }}</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-2xl text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pendingClinics'] ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.pending_clinics') }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-2xl text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['todayAppointments'] ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.todays_appointments') }}</p>
        </div>
    </div>
</div>
@endsection