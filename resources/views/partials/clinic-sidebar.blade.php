@php
    $user = auth()->user();
    $initial = strtoupper(substr($user->name, 0, 1));
@endphp

<div class="bg-white rounded-3xl shadow-xl p-6 sticky top-20">
    <div class="flex items-center gap-3 pb-6 border-b border-slate-200">
        <div class="w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-xl">
            {{ $initial }}
        </div>
        <div>
            <p class="font-bold text-slate-800">{{ $user->name }}</p>
            <p class="text-xs text-slate-500">{{ __('messages.clinic') }}</p>
        </div>
    </div>
    <nav class="mt-6 space-y-2">
        {{-- Dashboard --}}
        <a href="{{ route('clinic.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('clinic.dashboard') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>📊</span> {{ __('messages.dashboard') }}
        </a>

        {{-- Doctors --}}
        <a href="{{ route('clinic.doctors') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('clinic.doctors') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>👨‍⚕️</span> {{ __('messages.doctors') }}
        </a>

        {{-- Appointments --}}
        <a href="{{ route('clinic.appointments') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('clinic.appointments') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>📋</span> {{ __('messages.appointments') }}
        </a>

        {{-- Profile (View) --}}
        <a href="{{ route('clinic.profile') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('clinic.profile') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>👤</span> {{ __('messages.profile') }}
        </a>

        {{-- Settings (Profile Edit) --}}
        <a href="{{ route('clinic.profile.edit') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('clinic.profile.edit') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>⚙️</span> {{ __('messages.settings') }}
        </a>
    </nav>
</div>