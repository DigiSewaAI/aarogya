@php
    $user = auth()->user();
    $initial = strtoupper(substr($user->name, 0, 1));
    $unreadCount = 0;
    if ($user && class_exists(App\Models\Notification::class)) {
        try {
            $unreadCount = App\Models\Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();
        } catch (\Exception $e) {
            $unreadCount = 0;
        }
    }
@endphp

<div class="bg-white rounded-3xl shadow-xl p-6 sticky top-20">
    {{-- User Profile --}}
    <div class="flex items-center gap-3 pb-6 border-b border-slate-200">
        <div class="w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-xl">
            {{ $initial }}
        </div>
        <div>
            <p class="font-bold text-slate-800">{{ $user->name }}</p>
            <p class="text-xs text-slate-500">{{ __('messages.facility') }}</p> {{-- ← "Clinic" → "Facility" --}}
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="mt-6 space-y-2">
        {{-- Dashboard --}}
        <a href="{{ route('clinic.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('clinic.dashboard') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>📊</span> {{ __('messages.dashboard') }}
        </a>

        {{-- Doctors --}}
        <a href="{{ route('clinic.doctors') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('clinic.doctors*') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>👨‍⚕️</span> {{ __('messages.doctors') }}
        </a>

        {{-- Appointments --}}
        <a href="{{ route('clinic.appointments') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('clinic.appointments*') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
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

        {{-- Notifications --}}
        <a href="{{ route('clinic.notifications') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition {{ request()->routeIs('clinic.notifications*') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600' }}">
            <span>🔔</span> {{ __('messages.notifications') }}
            @if($unreadCount > 0)
                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $unreadCount }}</span>
            @endif
        </a>
    </nav>
</div>