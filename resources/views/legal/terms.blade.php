@extends('layouts.app')

@section('title', __('messages.terms_of_service') . ' - AAROGYA')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    {{-- Breadcrumb --}}
    <nav class="text-sm text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-cyan-600">{{ __('messages.home') }}</a>
        <span class="mx-2">/</span>
        <span class="text-slate-700">{{ __('messages.terms_of_service') }}</span>
    </nav>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-8">
            <h1 class="text-3xl font-bold text-white">{{ __('messages.terms_of_service') }}</h1>
            <p class="text-cyan-100 mt-2 text-sm">Last Updated: {{ date('F d, Y') }}</p>
        </div>

        <div class="p-8 space-y-8">
            {{-- Acceptance --}}
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-2">{{ __('messages.terms_acceptance') }}</h2>
                <p class="text-slate-600 leading-relaxed">{{ __('messages.terms_acceptance_desc') }}</p>
            </div>

            {{-- User Responsibilities --}}
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-4">{{ __('messages.terms_responsibilities') }}</h2>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-start gap-3">
                        <span class="text-green-500">✓</span>
                        <span>{{ __('messages.terms_responsibilities_1') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-green-500">✓</span>
                        <span>{{ __('messages.terms_responsibilities_2') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-green-500">✓</span>
                        <span>{{ __('messages.terms_responsibilities_3') }}</span>
                    </li>
                </ul>
            </div>

            {{-- Doctor Responsibilities --}}
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-4">{{ __('messages.terms_doctor_title') }}</h2>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-start gap-3">
                        <span class="text-green-500">✓</span>
                        <span>{{ __('messages.terms_doctor_1') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-green-500">✓</span>
                        <span>{{ __('messages.terms_doctor_2') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-green-500">✓</span>
                        <span>{{ __('messages.terms_doctor_3') }}</span>
                    </li>
                </ul>
            </div>

            {{-- Clinic Responsibilities --}}
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-4">{{ __('messages.terms_clinic_title') }}</h2>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-start gap-3">
                        <span class="text-green-500">✓</span>
                        <span>{{ __('messages.terms_clinic_1') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-green-500">✓</span>
                        <span>{{ __('messages.terms_clinic_2') }}</span>
                    </li>
                </ul>
            </div>

            {{-- AI Disclaimer --}}
            <div class="bg-amber-50 border-2 border-amber-400 rounded-2xl p-6">
                <div class="flex items-start gap-4">
                    <span class="text-3xl">⚠️</span>
                    <div>
                        <h2 class="text-xl font-bold text-amber-800">{{ __('messages.terms_ai_title') }}</h2>
                        <p class="text-amber-700 mt-1">{{ __('messages.terms_ai_desc') }}</p>
                        <p class="text-amber-800 font-semibold mt-2 text-lg">{{ __('messages.terms_ai_alert') }}</p>
                    </div>
                </div>
            </div>

            {{-- Limitation of Liability --}}
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-2">{{ __('messages.terms_liability_title') }}</h2>
                <p class="text-slate-600 leading-relaxed">{{ __('messages.terms_liability_desc') }}</p>
            </div>

            {{-- Account Termination --}}
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-2">{{ __('messages.terms_termination_title') }}</h2>
                <p class="text-slate-600 leading-relaxed">{{ __('messages.terms_termination_desc') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection