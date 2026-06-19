@extends('layouts.clinic')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.clinic_profile') }}</h2>
        <a href="{{ route('clinic.profile.edit') }}" class="bg-cyan-600 text-white px-4 py-2 rounded-xl hover:bg-cyan-700 transition">
            {{ __('messages.edit') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl mt-4 mb-4">{{ session('success') }}</div>
    @endif

    <div class="mt-6 grid md:grid-cols-2 gap-6">
        {{-- Logo --}}
        <div class="md:col-span-2 flex justify-center">
            @if($clinic->logo)
                <img src="{{ asset('storage/' . $clinic->logo) }}" class="w-32 h-32 rounded-full object-cover border-4 border-cyan-100" alt="Logo">
            @else
                <div class="w-32 h-32 rounded-full bg-cyan-100 flex items-center justify-center text-5xl">
                    🏥
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm text-slate-500">{{ __('messages.clinic_name') }}</label>
            <p class="text-slate-800 font-medium">{{ $clinic->clinic_name ?? '-' }}</p>
        </div>
        <div>
            <label class="block text-sm text-slate-500">{{ __('messages.phone') }}</label>
            <p class="text-slate-800 font-medium">{{ $clinic->phone ?? '-' }}</p>
        </div>
        <div>
            <label class="block text-sm text-slate-500">{{ __('messages.auth_email') }}</label>
            <p class="text-slate-800 font-medium">{{ $clinic->email ?? '-' }}</p>
        </div>
        <div>
            <label class="block text-sm text-slate-500">{{ __('messages.address') }}</label>
            <p class="text-slate-800 font-medium">{{ $clinic->address ?? '-' }}</p>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm text-slate-500">{{ __('messages.description') }}</label>
            <p class="text-slate-800 font-medium">{{ $clinic->description ?? '-' }}</p>
        </div>
        <div>
            <label class="block text-sm text-slate-500">{{ __('messages.verification_status') }}</label>
            <p class="inline-block px-3 py-1 rounded-full text-sm font-medium
                {{ $clinic->verification_status == 'approved' ? 'bg-green-100 text-green-700' : '' }}
                {{ $clinic->verification_status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                {{ $clinic->verification_status == 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                {{ ucfirst($clinic->verification_status ?? 'pending') }}
            </p>
        </div>
    </div>
</div>
@endsection