@extends('layouts.app')

@section('title', __('messages.contact') . ' - AAROGYA')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12">
    {{-- Breadcrumb --}}
    <nav class="text-sm text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-cyan-600">{{ __('messages.home') }}</a>
        <span class="mx-2">/</span>
        <span class="text-slate-700">{{ __('messages.contact') }}</span>
    </nav>

    <div class="grid md:grid-cols-3 gap-8">
        {{-- Contact Information --}}
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-xl p-6">
                <h2 class="text-xl font-bold text-slate-800 mb-6">{{ __('messages.contact_info_title') }}</h2>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📧</span>
                        <div>
                            <p class="text-sm text-slate-500">{{ __('messages.contact_email_label') }}</p>
                            <p class="font-medium text-slate-800">support@aarogya.com</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📞</span>
                        <div>
                            <p class="text-sm text-slate-500">{{ __('messages.contact_phone_label') }}</p>
                            <p class="font-medium text-slate-800">+977-XXXXXXXXXX</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📍</span>
                        <div>
                            <p class="text-sm text-slate-500">{{ __('messages.contact_location_label') }}</p>
                            <p class="font-medium text-slate-800">Kathmandu, Nepal</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-200">
                    <p class="text-sm text-slate-500">{{ __('messages.contact_working_hours') }}</p>
                    <p class="font-medium text-slate-800">{{ __('messages.contact_24_7_support') }}</p>
                </div>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white">{{ __('messages.contact') }}</h2>
                    <p class="text-cyan-100 text-sm">{{ __('messages.contact_response_time') }}</p>
                </div>

                <div class="p-8">
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.send') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1" for="name">
                                {{ __('messages.contact_name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                   required>
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1" for="email">
                                {{ __('messages.contact_email') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                   required>
                            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1" for="subject">
                                {{ __('messages.contact_subject') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                                   class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                   required>
                            @error('subject') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1" for="message">
                                {{ __('messages.contact_message') }} <span class="text-red-500">*</span>
                            </label>
                            <textarea name="message" id="message" rows="5"
                                      class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                      required>{{ old('message') }}</textarea>
                            @error('message') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 rounded-xl transition">
                            {{ __('messages.contact_send') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection