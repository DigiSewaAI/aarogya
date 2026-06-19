@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.verifications') }}</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl mt-4">{{ session('success') }}</div>
    @endif

    {{-- Pending Doctors --}}
    <div class="mt-6">
        <h3 class="text-xl font-semibold text-slate-700">{{ __('messages.pending_doctors') }} ({{ $pendingDoctors->count() }})</h3>
        @if($pendingDoctors->count() > 0)
            @foreach($pendingDoctors as $doctor)
            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl mt-2">
                <div>
                    <p class="font-medium text-slate-800">{{ $doctor->name }}</p>
                    <p class="text-sm text-slate-500">{{ $doctor->specialization ?? 'N/A' }} • {{ $doctor->nmc_registration ?? 'N/A' }}</p>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.verify.doctor', $doctor->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-xl hover:bg-green-600 transition">
                            ✅ {{ __('messages.approve') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.reject.doctor', $doctor->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600 transition">
                            ❌ {{ __('messages.reject') }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        @else
            <p class="text-slate-500 text-center py-4">{{ __('messages.no_pending_doctors') }}</p>
        @endif
    </div>

    {{-- Pending Clinics --}}
    <div class="mt-8">
        <h3 class="text-xl font-semibold text-slate-700">{{ __('messages.pending_clinics') }} ({{ $pendingClinics->count() }})</h3>
        @if($pendingClinics->count() > 0)
            @foreach($pendingClinics as $clinic)
            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl mt-2">
                <div>
                    <p class="font-medium text-slate-800">{{ $clinic->clinic_name }}</p>
                    <p class="text-sm text-slate-500">{{ $clinic->address ?? 'N/A' }} • {{ $clinic->phone ?? 'N/A' }}</p>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.verify.clinic', $clinic->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-xl hover:bg-green-600 transition">
                            ✅ {{ __('messages.approve') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.reject.clinic', $clinic->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600 transition">
                            ❌ {{ __('messages.reject') }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        @else
            <p class="text-slate-500 text-center py-4">{{ __('messages.no_pending_clinics') }}</p>
        @endif
    </div>
</div>
@endsection