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
            <p class="text-xs text-slate-500">{{ __('messages.admin') }}</p>
        </div>
    </div>
    <nav class="mt-6 space-y-2">
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>📊</span> {{ __('messages.dashboard') }}
        </a>
        <a href="{{ route('admin.users') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('admin.users') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>👥</span> {{ __('messages.users') }}
        </a>
        <a href="{{ route('admin.doctors') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('admin.doctors') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>👨‍⚕️</span> {{ __('messages.doctors') }}
        </a>
        <a href="{{ route('admin.clinics') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('admin.clinics') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>🏥</span> {{ __('messages.clinics') }}
        </a>
        <a href="{{ route('admin.verifications') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('admin.verifications') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>✅</span> {{ __('messages.verifications') }}
        </a>
    </nav>
</div>