@php
    $user = auth()->user();
    $initial = strtoupper(substr($user->name, 0, 1));
    $unreadCount = $user->unreadNotificationsCount();
@endphp

<div class="bg-white rounded-3xl shadow-xl p-6 sticky top-20">
    <div class="flex items-center gap-3 pb-6 border-b border-slate-200">
        <div class="w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-xl">
            {{ $initial }}
        </div>
        <div>
            <p class="font-bold text-slate-800">{{ $user->name }}</p>
            <p class="text-xs text-slate-500">{{ __('messages.patient') }}</p>
        </div>
    </div>

    <nav class="mt-6 space-y-2">
        {{-- Dashboard --}}
        <a href="{{ route('patient.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('patient.dashboard') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>📊</span> {{ __('messages.dashboard') }}
        </a>

        {{-- Appointments --}}
        <a href="{{ route('patient.appointments') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('patient.appointments') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>📋</span> {{ __('messages.appointments') }}
        </a>

        {{-- ✅ Health Profile --}}
        <a href="{{ route('patient.health-profile') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('patient.health-profile') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>🩺</span> {{ __('messages.health_profile') }}
        </a>

        {{-- ✅ Medical Records --}}
        <a href="{{ route('patient.medical-records') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('patient.medical-records*') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>📁</span> {{ __('messages.medical_records') }}
        </a>

        {{-- ✅ Prescriptions --}}
        <a href="{{ route('patient.prescriptions') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('patient.prescriptions') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>💊</span> {{ __('messages.prescriptions') }}
        </a>

        {{-- ✅ Follow-ups --}}
        <a href="{{ route('patient.follow-ups') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('patient.follow-ups') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>🔄</span> {{ __('messages.follow_up') }}
        </a>

        {{-- ✅ Notifications --}}
        <a href="{{ route('patient.notifications') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('patient.notifications') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>🔔</span> {{ __('messages.notifications') }}
            @if($unreadCount > 0)
                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $unreadCount }}</span>
            @endif
        </a>

        {{-- Profile (Settings) --}}
        <a href="#" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition">
            <span>👤</span> {{ __('messages.profile') }}
        </a>
    </nav>
</div>