@extends('layouts.app')

@section('title', __('messages.clinics'))

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-slate-800">{{ __('messages.partner_clinics') }}</h2>
        <p class="text-slate-500 mt-2">{{ __('messages.partner_clinics_sub') }}</p>
    </div>

    @if(isset($clinics) && $clinics->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($clinics as $clinic)
                <div class="bg-white rounded-3xl shadow-md p-6 hover:shadow-xl transition card-hover text-center">
                    @if($clinic->logo)
                        <img src="{{ asset('storage/' . $clinic->logo) }}" class="w-20 h-20 rounded-full mx-auto object-cover border-2 border-cyan-200" alt="{{ $clinic->name }}">
                    @else
                        <div class="w-20 h-20 rounded-full bg-cyan-100 flex items-center justify-center mx-auto text-2xl font-bold text-cyan-600">{{ substr($clinic->name, 0, 1) }}</div>
                    @endif
                    <h3 class="font-bold text-lg mt-4">{{ $clinic->name }}</h3>
                    <p class="text-sm text-slate-500">{{ $clinic->address ?? __('messages.n/a') }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ __('messages.doctors_count', ['count' => $clinic->doctors()->where('verification_status','approved')->count()]) }}</p>
                    <a href="{{ route('clinic.show', $clinic->id) }}" class="inline-block mt-4 bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition text-sm">
                        {{ __('messages.view_clinic') }}
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $clinics->links() }}</div>
    @else
        <div class="text-center py-16">
            <p class="text-slate-500">{{ __('messages.no_clinics_found') }}</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 text-cyan-600 hover:underline">{{ __('messages.back_to_home') }}</a>
        </div>
    @endif
</div>
@endsection