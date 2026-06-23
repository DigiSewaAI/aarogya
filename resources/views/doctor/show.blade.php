@extends('layouts.app')

@section('title', $doctor->name . ' - AAROGYA')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    {{-- Back Button --}}
    <a href="{{ route('doctors') }}" class="inline-flex items-center gap-2 text-cyan-600 hover:text-cyan-800 transition mb-6">
        ← {{ __('messages.back') }}
    </a>

    {{-- Doctor Profile Card --}}
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        {{-- Header with background --}}
        <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-8 py-10">
            <div class="flex flex-col md:flex-row items-center gap-6">
                {{-- Profile Photo --}}
                <div class="w-28 h-28 rounded-full bg-white/20 flex items-center justify-center overflow-hidden border-4 border-white">
                    @if($doctor->profile_photo)
                        <img src="{{ asset('storage/' . $doctor->profile_photo) }}" 
                             alt="{{ $doctor->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl text-white font-bold">{{ substr($doctor->name, 0, 1) }}</span>
                    @endif
                </div>

                {{-- Doctor Info --}}
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl font-bold text-white">{{ $doctor->full_name }}</h1>
                    <p class="text-cyan-100 text-lg">{{ $doctor->specialization ?? __('messages.doctor_specialization') }}</p>
                    <div class="flex flex-wrap gap-3 mt-2 justify-center md:justify-start">
                        <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full">
                            ⭐ {{ __('messages.doctor_rating') }} (120+ reviews)
                        </span>
                        <span class="bg-green-400/30 text-white text-xs px-3 py-1 rounded-full">
                            ✅ {{ __('messages.verified') }}
                        </span>
                        <span class="bg-amber-400/30 text-white text-xs px-3 py-1 rounded-full">
                            {{ $doctor->experience ?? 0 }} {{ __('messages.doctor_experience_years') }}
                        </span>
                    </div>
                </div>

                {{-- QR Code (Right side) --}}
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center">
                    <p class="text-xs text-white/80 mb-2">{{ __('messages.scan_to_view') }}</p>
                    <img src="data:image/png;base64,{{ $doctor->qr_code_base64 }}" 
                         alt="QR Code for {{ $doctor->name }}" 
                         class="w-24 h-24 mx-auto rounded-lg">
                    <p class="text-[10px] text-white/60 mt-1">{{ __('messages.scan_with_phone') }}</p>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="p-8 grid md:grid-cols-3 gap-8">
            {{-- Left: Doctor Details --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Bio --}}
                @if($doctor->bio)
                    <div>
                        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">{{ __('messages.about_doctor') }}</h2>
                        <p class="text-slate-700">{{ $doctor->bio }}</p>
                    </div>
                @endif

                {{-- Details Grid --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <p class="text-xs text-slate-400">{{ __('messages.qualification') }}</p>
                        <p class="font-medium text-slate-800">{{ $doctor->qualification ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <p class="text-xs text-slate-400">{{ __('messages.nmc_registration') }}</p>
                        <p class="font-medium text-slate-800">{{ $doctor->nmc_registration ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <p class="text-xs text-slate-400">{{ __('messages.consultation_fee') }}</p>
                        <p class="font-medium text-slate-800">{{ __('messages.currency') }} {{ number_format($doctor->consultation_fee ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <p class="text-xs text-slate-400">{{ __('messages.experience') }}</p>
                        <p class="font-medium text-slate-800">{{ $doctor->experience ?? 0 }} {{ __('messages.doctor_experience_years') }}</p>
                    </div>
                    @if($doctor->clinic_name)
                        <div class="bg-slate-50 p-4 rounded-xl sm:col-span-2">
                            <p class="text-xs text-slate-400">{{ __('messages.clinic_name') }}</p>
                            <p class="font-medium text-slate-800">{{ $doctor->clinic_name }}</p>
                            @if($doctor->clinic_address)
                                <p class="text-sm text-slate-500">{{ $doctor->clinic_address }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Available Days --}}
                @if($doctor->schedules && $doctor->schedules->count() > 0)
                    <div>
                        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">{{ __('messages.available_days') }}</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($doctor->schedules->where('is_active', true) as $schedule)
                                <span class="bg-cyan-100 text-cyan-700 text-xs px-3 py-1 rounded-full">
                                    {{ __('messages.' . strtolower($schedule->day_of_week)) }}
                                    ({{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                                     {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }})
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right: Book Appointment & Contact --}}
            <div class="space-y-4">
                {{-- Book Button --}}
                @auth
                    <a href="{{ route('appointment.create', $doctor->id) }}" 
                       class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 rounded-xl transition block text-center">
                        📅 {{ __('messages.book_appointment') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 rounded-xl transition block text-center">
                        🔐 {{ __('messages.login_to_book') }}
                    </a>
                @endauth

                {{-- Share QR --}}
                <div class="bg-slate-50 p-4 rounded-xl text-center">
                    <p class="text-xs text-slate-500 mb-2">{{ __('messages.share_profile') }}</p>
                    <div class="flex justify-center gap-2">
                        <a href="https://wa.me/?text={{ urlencode($doctor->full_name . ' - ' . route('doctor.show', $doctor->id)) }}" 
                           target="_blank" class="px-3 py-1 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition">
                            WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('doctor.show', $doctor->id)) }}" 
                           target="_blank" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                            Facebook
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ route('doctor.show', $doctor->id) }}')" 
                                class="px-3 py-1 bg-slate-600 text-white text-sm rounded-lg hover:bg-slate-700 transition">
                            📋
                        </button>
                    </div>
                </div>

                {{-- Quick Info --}}
                <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-sm text-amber-700">
                    <p class="font-semibold">💡 {{ __('messages.quick_info') }}</p>
                    <ul class="list-disc list-inside text-xs mt-1 space-y-1">
                        <li>{{ __('messages.verified_doctor') }}</li>
                        <li>{{ __('messages.online_consultation_available') }}</li>
                        <li>{{ __('messages.book_in_advance') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- ✅ Page-specific style to ensure social icons use correct color --}}
@push('styles')
<style>
    .social-icon {
        fill: currentColor !important;
        width: 24px;
        height: 24px;
    }
</style>
@endpush