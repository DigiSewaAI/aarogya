@extends('layouts.app')

@section('title', $clinic->name . ' - AAROGYA')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <a href="{{ route('clinics') }}" class="inline-flex items-center gap-2 text-cyan-600 hover:text-cyan-800 transition mb-6">
        ← {{ __('messages.back') }}
    </a>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-10 text-white">
            <div class="flex flex-col md:flex-row items-center gap-6">
                @if($clinic->logo)
    <img src="{{ asset('storage/' . $clinic->logo) }}" 
         class="w-28 h-28 rounded-full border-4 border-white bg-white object-cover" 
         alt="{{ $clinic->name }}">
@else
    <div class="w-28 h-28 rounded-full bg-white/20 flex items-center justify-center text-4xl font-bold">
        {{ substr($clinic->name, 0, 1) }}
    </div>
@endif
                <div>
                    <h1 class="text-3xl font-bold">{{ $clinic->name }}</h1>
                    <p class="text-cyan-100">{{ $clinic->address ?? '' }}</p>
                    <span class="inline-block mt-2 bg-green-400/30 text-white text-xs px-3 py-1 rounded-full">✓ {{ __('messages.verified') }}</span>
                </div>
            </div>
        </div>

        <div class="p-8">
            <h2 class="text-xl font-bold text-slate-800 mb-4">{{ __('messages.doctors_at_clinic') }}</h2>

            @if($clinic->doctors && $clinic->doctors->count() > 0)
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($clinic->doctors as $doc)
                        <div class="bg-slate-50 p-4 rounded-xl flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $doc->name }}</p>
                                <p class="text-sm text-slate-500">{{ $doc->specialization ?? __('messages.n/a') }}</p>
                            </div>
                            <a href="{{ route('doctor.show', $doc->id) }}" class="text-cyan-600 text-sm hover:underline">
                                {{ __('messages.view_profile') }}
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-slate-500">{{ __('messages.no_doctors_assigned') }}</p>
            @endif

            <div class="mt-8 flex gap-4">
                @auth
                    <a href="{{ route('doctors') }}" class="bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition">
                        {{ __('messages.find_doctors') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition">
                        {{ __('messages.login') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection