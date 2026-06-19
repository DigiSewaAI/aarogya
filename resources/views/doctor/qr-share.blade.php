@extends('layouts.doctor')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.share_qr') }}</h2>
    <p class="text-slate-500 mt-1">{{ __('messages.share_qr_description') }}</p>

    <div class="mt-8 text-center">
        <div class="inline-block bg-white p-4 rounded-2xl shadow-lg border border-slate-200">
            {!! QrCode::size(200)->generate(route('doctor.show', $doctor->id)) !!}
        </div>

        <div class="mt-6">
            <p class="font-medium">{{ $doctor->name }}</p>
            <p class="text-sm text-slate-500">{{ $doctor->specialization }}</p>
        </div>

        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <!-- Share Links -->
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('doctor.show', $doctor->id)) }}" 
               target="_blank" class="bg-[#1877F2] text-white px-6 py-3 rounded-xl hover:opacity-90 transition">
                📘 Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode('Check out Dr. ' . $doctor->name . ' on AAROGYA') }}&url={{ urlencode(route('doctor.show', $doctor->id)) }}" 
               target="_blank" class="bg-[#000000] text-white px-6 py-3 rounded-xl hover:opacity-90 transition">
                🐦 Twitter
            </a>
            <a href="https://wa.me/?text={{ urlencode('Check out Dr. ' . $doctor->name . ' on AAROGYA: ' . route('doctor.show', $doctor->id)) }}" 
               target="_blank" class="bg-[#25D366] text-white px-6 py-3 rounded-xl hover:opacity-90 transition">
                💬 WhatsApp
            </a>
            <a href="mailto:?subject={{ urlencode('Check out Dr. ' . $doctor->name . ' on AAROGYA') }}&body={{ urlencode('Visit profile: ' . route('doctor.show', $doctor->id)) }}" 
               class="bg-red-600 text-white px-6 py-3 rounded-xl hover:opacity-90 transition">
                📧 Email
            </a>
        </div>
    </div>
</div>
@endsection