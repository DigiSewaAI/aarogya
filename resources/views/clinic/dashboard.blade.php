@extends('layouts.clinic')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.clinic_dashboard') }}</h2>
    <p class="text-slate-500">{{ __('messages.dashboard_welcome', ['name' => Auth::user()->name]) }}</p>

    <div class="grid md:grid-cols-4 gap-4 mt-6">
        <div class="bg-cyan-50 p-4 rounded-2xl text-center">
            <div class="text-3xl font-bold text-cyan-600">{{ $totalDoctors ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.doctors') }}</p>
        </div>
        <div class="bg-blue-50 p-4 rounded-2xl text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $totalAppointments ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.appointments') }}</p>
        </div>
        <div class="bg-amber-50 p-4 rounded-2xl text-center">
            <div class="text-3xl font-bold text-amber-600">{{ $pendingAppointments ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.pending_requests') }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-2xl text-center">
            <div class="text-3xl font-bold text-green-600">{{ $completedAppointments ?? 0 }}</div>
            <p class="text-slate-600">{{ __('messages.completed') }}</p>
        </div>
    </div>

    @if(isset($recentAppointments) && count($recentAppointments) > 0)
        <div class="mt-8">
            <h3 class="text-lg font-bold text-slate-800">{{ __('messages.recent_appointments') }}</h3>
            <div class="mt-4 space-y-2">
                @foreach($recentAppointments as $appointment)
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                        <div>
                            <span class="font-medium">{{ $appointment->patient->name ?? 'N/A' }}</span>
                            <span class="text-sm text-slate-500 ml-2">{{ $appointment->appointment_date }} {{ $appointment->appointment_time }}</span>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($appointment->status == 'approved') bg-green-100 text-green-700
                            @elseif($appointment->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($appointment->status == 'completed') bg-blue-100 text-blue-700
                            @elseif($appointment->status == 'cancelled') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="mt-8 text-center text-slate-500">{{ __('messages.no_appointments_yet') }}</div>
    @endif
</div>
@endsection