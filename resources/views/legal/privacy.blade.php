@extends('layouts.app')

@section('title', __('messages.privacy_policy') . ' - AAROGYA')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    {{-- Breadcrumb --}}
    <nav class="text-sm text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-cyan-600">{{ __('messages.home') }}</a>
        <span class="mx-2">/</span>
        <span class="text-slate-700">{{ __('messages.privacy_policy') }}</span>
    </nav>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-8">
            <h1 class="text-3xl font-bold text-white">{{ __('messages.privacy_policy') }}</h1>
            <p class="text-cyan-100 mt-2 text-sm">Last Updated: {{ date('F d, Y') }}</p>
        </div>

        <div class="p-8 space-y-8">
            {{-- Introduction --}}
            <div>
                <p class="text-slate-600 leading-relaxed">
                    {{ __('messages.privacy_intro') }}
                </p>
            </div>

            {{-- Information We Collect --}}
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-4">{{ __('messages.privacy_collect_title') }}</h2>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-start gap-3">
                        <span class="text-cyan-600 mt-1">▸</span>
                        <span>{{ __('messages.privacy_collect_patients') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-cyan-600 mt-1">▸</span>
                        <span>{{ __('messages.privacy_collect_doctors') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-cyan-600 mt-1">▸</span>
                        <span>{{ __('messages.privacy_collect_clinics') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-cyan-600 mt-1">▸</span>
                        <span>{{ __('messages.privacy_collect_system') }}</span>
                    </li>
                </ul>
            </div>

            {{-- How We Use Information --}}
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-4">{{ __('messages.privacy_use_title') }}</h2>
                <div class="grid md:grid-cols-2 gap-3">
                    <div class="bg-slate-50 p-4 rounded-xl flex items-center gap-3">
                        <span class="text-2xl">📋</span>
                        <span>{{ __('messages.privacy_use_1') }}</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl flex items-center gap-3">
                        <span class="text-2xl">🔐</span>
                        <span>{{ __('messages.privacy_use_2') }}</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl flex items-center gap-3">
                        <span class="text-2xl">📈</span>
                        <span>{{ __('messages.privacy_use_3') }}</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl flex items-center gap-3">
                        <span class="text-2xl">💬</span>
                        <span>{{ __('messages.privacy_use_4') }}</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl flex items-center gap-3 md:col-span-2">
                        <span class="text-2xl">🛡️</span>
                        <span>{{ __('messages.privacy_use_5') }}</span>
                    </div>
                </div>
            </div>

            {{-- Medical Data Protection --}}
            <div class="bg-cyan-50 border border-cyan-200 rounded-2xl p-6">
                <h2 class="text-xl font-bold text-slate-800 mb-2">{{ __('messages.privacy_medical_title') }}</h2>
                <p class="text-slate-600">{{ __('messages.privacy_medical_desc') }}</p>
            </div>

            {{-- Data Sharing --}}
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-4">{{ __('messages.privacy_sharing_title') }}</h2>
                <p class="text-slate-600 leading-relaxed">{{ __('messages.privacy_sharing_desc') }}</p>
            </div>

            {{-- User Rights --}}
            <div>
                <h2 class="text-xl font-bold text-slate-800 mb-4">{{ __('messages.privacy_rights_title') }}</h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-xl text-center">
                        <div class="text-3xl mb-2">👁️</div>
                        <p class="font-medium">{{ __('messages.privacy_rights_1') }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl text-center">
                        <div class="text-3xl mb-2">✏️</div>
                        <p class="font-medium">{{ __('messages.privacy_rights_2') }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl text-center">
                        <div class="text-3xl mb-2">🗑️</div>
                        <p class="font-medium">{{ __('messages.privacy_rights_3') }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl text-center">
                        <div class="text-3xl mb-2">📞</div>
                        <p class="font-medium">{{ __('messages.privacy_rights_4') }}</p>
                    </div>
                </div>
            </div>

            {{-- Contact --}}
            <div class="bg-slate-50 p-6 rounded-2xl">
                <p class="text-slate-600 text-center">{{ __('messages.privacy_contact') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection