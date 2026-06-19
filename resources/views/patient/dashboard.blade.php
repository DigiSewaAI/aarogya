@extends('layouts.patient')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.dashboard_welcome', ['name' => Auth::user()->name]) }}</h2>
    <p class="text-slate-500">{{ __('messages.dashboard_subtitle') }}</p>

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-4 gap-4 mt-6">
        <div class="bg-cyan-50 p-4 rounded-2xl text-center">
            <div class="text-2xl font-bold text-cyan-600">{{ $totalAppointments ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.appointments') }}</p>
        </div>
        <div class="bg-amber-50 p-4 rounded-2xl text-center">
            <div class="text-2xl font-bold text-amber-600">{{ $pendingAppointments ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.pending_requests') }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-2xl text-center">
            <div class="text-2xl font-bold text-green-600">{{ $upcomingAppointments->count() ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.upcoming') }}</p>
        </div>
        <div class="bg-blue-50 p-4 rounded-2xl text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $appointmentHistory->count() ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.history') }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid md:grid-cols-3 gap-6 mt-8">
        <div class="bg-slate-50 p-6 rounded-2xl hover:shadow transition">
            <div class="text-4xl mb-3">🔍</div>
            <h3 class="font-bold">{{ __('messages.dashboard_check_symptoms') }}</h3>
            <p class="text-slate-500 mt-1">{{ __('messages.dashboard_check_symptoms_desc') }}</p>
            <a href="{{ route('symptom.checker') }}" class="inline-block mt-3 text-cyan-600 font-semibold">{{ __('messages.dashboard_check_action') }} →</a>
        </div>
        <div class="bg-slate-50 p-6 rounded-2xl hover:shadow transition">
            <div class="text-4xl mb-3">👨‍⚕️</div>
            <h3 class="font-bold">{{ __('messages.dashboard_find_doctor') }}</h3>
            <p class="text-slate-500 mt-1">{{ __('messages.dashboard_find_doctor_desc') }}</p>
            <a href="{{ route('doctors') }}" class="inline-block mt-3 text-cyan-600 font-semibold">{{ __('messages.dashboard_find_action') }} →</a>
        </div>
        <div class="bg-slate-50 p-6 rounded-2xl hover:shadow transition">
            <div class="text-4xl mb-3">📋</div>
            <h3 class="font-bold">{{ __('messages.dashboard_my_reports') }}</h3>
            <p class="text-slate-500 mt-1">{{ __('messages.dashboard_my_reports_desc') }}</p>
            <a href="#" class="inline-block mt-3 text-cyan-600 font-semibold">{{ __('messages.dashboard_reports_action') }} →</a>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="mt-8">
        <h3 class="text-xl font-semibold text-slate-800">{{ __('messages.upcoming_appointments') }}</h3>
        @if($upcomingAppointments->count() > 0)
            <div class="mt-4 space-y-3">
                @foreach($upcomingAppointments as $appointment)
                <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl">
                    <div>
                        <p class="font-medium text-slate-800">Dr. {{ $appointment->doctor->name ?? 'N/A' }}</p>
                        <p class="text-sm text-slate-500">{{ $appointment->formatted_date ?? $appointment->appointment_date }} at {{ $appointment->formatted_time ?? $appointment->appointment_time }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">{{ __('messages.approved') }}</span>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-slate-500 mt-2">{{ __('messages.no_upcoming_appointments') }}</p>
        @endif
    </div>
</div>
@endsection