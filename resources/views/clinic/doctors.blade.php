@extends('layouts.clinic')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.doctors') }}</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl mt-4 mb-4">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded-xl mt-4 mb-4">{{ session('error') }}</div>
    @endif

    {{-- Add Doctor Form --}}
    @if(isset($availableDoctors) && count($availableDoctors) > 0)
        <form method="POST" action="{{ route('clinic.doctors.add') }}" class="mt-4 flex gap-4 items-end">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700">{{ __('messages.add_doctor') }}</label>
                <select name="doctor_id" class="w-full border rounded-xl px-4 py-2">
                    @foreach($availableDoctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} - {{ $doctor->specialization }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition">
                {{ __('messages.add') }}
            </button>
        </form>
    @endif

    {{-- Doctors List --}}
    <div class="mt-6 space-y-3">
        @forelse($doctors as $doctor)
            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl">
                <div>
                    <p class="font-medium text-slate-800">Dr. {{ $doctor->name }}</p>
                    <p class="text-sm text-slate-500">{{ $doctor->specialization }} - {{ $doctor->experience }} {{ __('messages.experience_years') }}</p>
                </div>
                <form method="POST" action="{{ route('clinic.doctors.remove', $doctor->id) }}" onsubmit="return confirm('{{ __('messages.confirm_remove_doctor') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 transition">
                        {{ __('messages.remove') }}
                    </button>
                </form>
            </div>
        @empty
            <div class="text-center text-slate-500 py-8">
                {{ __('messages.no_doctors_assigned') }}
            </div>
        @endforelse
    </div>

    {{ $doctors->links() }}
</div>
@endsection