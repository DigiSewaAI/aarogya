@extends('layouts.doctor')

@section('content')
<div class="bg-white rounded-3xl shadow-xl overflow-hidden">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('messages.profile') }}</h2>
                <p class="text-cyan-100 text-sm">Your professional information</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-xs font-medium">
                    {{ $doctor->verification_status ?? 'pending' }}
                </span>
                <a href="{{ route('doctor.profile.edit') }}" 
                   class="bg-white text-cyan-600 px-4 py-2 rounded-xl hover:bg-cyan-50 transition text-sm font-medium">
                    ✏️ {{ __('messages.edit_profile') }}
                </a>
            </div>
        </div>
    </div>

    <div class="p-8">
        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Profile Photo --}}
        <div class="flex items-center gap-6 pb-6 border-b border-slate-200">
            <div>
                @if($doctor->profile_photo)
                    <img src="{{ asset('storage/' . $doctor->profile_photo) }}" 
                         alt="Profile Photo" 
                         class="w-24 h-24 rounded-full object-cover border-4 border-cyan-100">
                @else
                    <div class="w-24 h-24 rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 flex items-center justify-center text-white text-3xl font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div>
                <p class="font-medium text-slate-800 text-xl">{{ $user->name }}</p>
                <p class="text-sm text-slate-500">{{ __('messages.doctor') }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $doctor->specialization ?? 'Not specified' }}</p>
            </div>
        </div>

        {{-- Profile Information Display --}}
        <div class="grid md:grid-cols-2 gap-6 mt-6">
            {{-- Personal Information --}}
            <div class="col-span-2">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">👤</span> Personal Information
                </h3>
                <div class="grid md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl">
                    <div>
                        <p class="text-sm text-slate-500">{{ __('messages.auth_name') }}</p>
                        <p class="font-medium text-slate-800">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">{{ __('messages.specialization') }}</p>
                        <p class="font-medium text-slate-800">{{ $doctor->specialization ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">{{ __('messages.qualification') }}</p>
                        <p class="font-medium text-slate-800">{{ $doctor->qualification ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">{{ __('messages.nmc_registration') }}</p>
                        <p class="font-medium text-slate-800">{{ $doctor->nmc_registration ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Professional Information --}}
            <div class="col-span-2">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">💼</span> Professional Information
                </h3>
                <div class="grid md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl">
                    <div>
                        <p class="text-sm text-slate-500">{{ __('messages.experience') }}</p>
                        <p class="font-medium text-slate-800">{{ $doctor->experience ?? '0' }} {{ __('messages.experience_years') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">{{ __('messages.consultation_fee') }}</p>
                        <p class="font-medium text-slate-800">{{ __('messages.currency') }} {{ number_format($doctor->consultation_fee ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- Clinic Information --}}
            <div class="col-span-2">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">🏥</span> Clinic Information
                </h3>
                <div class="grid md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl">
                    <div>
                        <p class="text-sm text-slate-500">{{ __('messages.clinic_name') }}</p>
                        <p class="font-medium text-slate-800">{{ $doctor->clinic_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">{{ __('messages.clinic_address') }}</p>
                        <p class="font-medium text-slate-800">{{ $doctor->clinic_address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Bio --}}
            <div class="col-span-2">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">📝</span> About
                </h3>
                <div class="bg-slate-50 p-4 rounded-2xl">
                    <p class="text-slate-700">{{ $doctor->bio ?? 'No bio provided yet.' }}</p>
                </div>
            </div>
        </div>

        {{-- Edit Button --}}
        <div class="mt-8 pt-4 border-t border-slate-200 text-right">
            <a href="{{ route('doctor.profile.edit') }}" 
               class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold px-8 py-3 rounded-xl transition inline-flex items-center gap-2">
                ✏️ {{ __('messages.edit_profile') }}
            </a>
        </div>
    </div>
</div>
@endsection