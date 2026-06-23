@php
    $user = auth()->user();
    $initial = strtoupper(substr($user->name, 0, 1));
    $roleName = match($user->role) {
        'doctor' => __('messages.doctor'),
        'clinic' => __('messages.clinic'),
        'admin' => __('messages.admin'),
        default => __('messages.patient'),
    };
    $unreadCount = $user->unreadNotificationsCount();
@endphp

<div class="bg-white rounded-3xl shadow-xl p-6 sticky top-20">
    {{-- User Profile Header --}}
    <div class="flex items-center gap-3 pb-6 border-b border-slate-200">
        <div class="w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-xl">
            {{ $initial }}
        </div>
        <div>
            <p class="font-bold text-slate-800">{{ $user->name }}</p>
            <p class="text-xs text-slate-500">{{ $roleName }}</p>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <nav class="mt-6 space-y-2">
        {{-- Dashboard --}}
        <a href="{{ route('doctor.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('doctor.dashboard') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>📊</span> {{ __('messages.dashboard') }}
        </a>

        {{-- Appointments --}}
        <a href="{{ route('doctor.appointments') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('doctor.appointments') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>📋</span> {{ __('messages.appointments') }}
        </a>

        {{-- Patients --}}
        <a href="{{ route('doctor.patients') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('doctor.patients') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>👨‍⚕️</span> {{ __('messages.patients') }}
        </a>

        {{-- Schedule --}}
        <a href="{{ route('doctor.schedule') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('doctor.schedule') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>📅</span> {{ __('messages.schedule') }}
        </a>

        {{-- ✅ Prescriptions (New) --}}
        <a href="{{ route('doctor.prescriptions') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('doctor.prescriptions') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>💊</span> {{ __('messages.prescriptions') }}
        </a>

        {{-- ✅ Follow-ups (New) --}}
        <a href="{{ route('doctor.follow-ups') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('doctor.follow-ups') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>🔄</span> {{ __('messages.follow_up') }}
        </a>

        {{-- ✅ QR Code --}}
        <a href="{{ route('doctor.qr.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('doctor.qr.*') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>📱</span> {{ __('messages.qr_code') }}
        </a>

        {{-- Profile (View) --}}
        <a href="{{ route('doctor.profile') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('doctor.profile') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>👤</span> {{ __('messages.profile') }}
        </a>

        {{-- Settings (Edit) --}}
        <a href="{{ route('doctor.profile.edit') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('doctor.profile.edit') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>⚙️</span> {{ __('messages.settings') }}
        </a>

        {{-- ✅ Notifications (New) --}}
        <a href="{{ route('doctor.notifications') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('doctor.notifications') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>🔔</span> {{ __('messages.notifications') }}
            @if($unreadCount > 0)
                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $unreadCount }}</span>
            @endif
        </a>
    </nav>
</div>