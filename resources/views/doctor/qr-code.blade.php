@extends('layouts.doctor')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.qr_code') }}</h2>
        <span class="text-sm text-slate-500">Dr. {{ $doctor->name }}</span>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl mt-4">{{ session('success') }}</div>
    @endif

    <div class="mt-8 text-center">
        <!-- QR Code Display -->
        <div class="inline-block bg-white p-6 rounded-3xl shadow-lg border border-slate-200">
            {!! QrCode::size(300)->generate(route('doctor.show', $doctor->id)) !!}
        </div>

        <p class="mt-4 text-slate-600">
            {{ __('messages.qr_scan_message') }}
        </p>
        <p class="text-sm text-slate-400">
            {{ route('doctor.show', $doctor->id) }}
        </p>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="{{ route('doctor.qr.download') }}" 
               class="bg-cyan-600 text-white px-6 py-3 rounded-xl hover:bg-cyan-700 transition flex items-center gap-2">
                ⬇️ {{ __('messages.download_qr') }}
            </a>
            <a href="{{ route('doctor.qr.print') }}" target="_blank"
               class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition flex items-center gap-2">
                🖨️ {{ __('messages.print_qr') }}
            </a>
            <a href="{{ route('doctor.qr.share') }}" 
               class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition flex items-center gap-2">
                📤 {{ __('messages.share_qr') }}
            </a>
        </div>
    </div>

    <!-- QR Code Info -->
    <div class="mt-8 p-6 bg-slate-50 rounded-2xl">
        <h3 class="font-semibold text-slate-800">{{ __('messages.qr_info_title') }}</h3>
        <ul class="mt-2 space-y-2 text-sm text-slate-600">
            <li>✅ {{ __('messages.qr_info_1') }}</li>
            <li>✅ {{ __('messages.qr_info_2') }}</li>
            <li>✅ {{ __('messages.qr_info_3') }}</li>
        </ul>
    </div>
</div>
@endsection