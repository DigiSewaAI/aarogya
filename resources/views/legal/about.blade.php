@extends('layouts.app')

@section('title', __('messages.about') . ' - AAROGYA')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    {{-- Breadcrumb --}}
    <nav class="text-sm text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-cyan-600">{{ __('messages.home') }}</a>
        <span class="mx-2">/</span>
        <span class="text-slate-700">{{ __('messages.about') }}</span>
    </nav>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-10 text-center">
            <div class="inline-flex items-center gap-3 bg-white/20 rounded-full px-4 py-2 text-white text-sm mb-4">
                ❤️ {{ __('messages.footer_tagline') }}
            </div>
            <h1 class="text-4xl font-bold text-white">{{ __('messages.about') }}</h1>
            <p class="text-cyan-100 mt-2 text-lg">Transforming Healthcare in Nepal</p>
        </div>

        <div class="p-8 space-y-8">
            {{-- Mission --}}
            <div class="text-center">
                <div class="text-5xl mb-4">🎯</div>
                <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.about_mission_title') }}</h2>
                <p class="text-slate-600 mt-2 text-lg">{{ __('messages.about_mission_desc') }}</p>
            </div>

            {{-- Vision --}}
            <div class="text-center">
                <div class="text-5xl mb-4">👁️</div>
                <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.about_vision_title') }}</h2>
                <p class="text-slate-600 mt-2 text-lg">{{ __('messages.about_vision_desc') }}</p>
            </div>

            {{-- Why AAROGYA --}}
            <div>
                <h2 class="text-2xl font-bold text-slate-800 text-center mb-6">{{ __('messages.about_why_title') }}</h2>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="bg-slate-50 p-4 rounded-xl text-center">
                        <div class="text-3xl mb-2">🤖</div>
                        <p class="font-medium">{{ __('messages.about_why_1') }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl text-center">
                        <div class="text-3xl mb-2">👨‍⚕️</div>
                        <p class="font-medium">{{ __('messages.about_why_2') }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl text-center">
                        <div class="text-3xl mb-2">🏥</div>
                        <p class="font-medium">{{ __('messages.about_why_3') }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl text-center">
                        <div class="text-3xl mb-2">📅</div>
                        <p class="font-medium">{{ __('messages.about_why_4') }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl text-center md:col-span-2">
                        <div class="text-3xl mb-2">🔒</div>
                        <p class="font-medium">{{ __('messages.about_why_5') }}</p>
                    </div>
                </div>
            </div>

            {{-- Trust Badge --}}
            <div class="bg-gradient-to-r from-cyan-50 to-blue-50 rounded-2xl p-6 text-center border border-cyan-100">
                <p class="text-slate-600">AAROGYA is built with ❤️ for Nepal's healthcare community.</p>
                <p class="text-sm text-slate-400 mt-2">© {{ date('Y') }} AAROGYA. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</div>
@endsection