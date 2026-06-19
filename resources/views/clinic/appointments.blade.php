@extends('layouts.clinic')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.appointments') }}</h2>
        <span class="text-sm text-slate-500">{{ $appointments->total() }} {{ __('messages.total') }}</span>
    </div>

    {{-- Filter Section --}}
    <div class="mt-4 p-4 bg-slate-50 rounded-xl">
        <form method="GET" action="{{ route('clinic.appointments') }}" class="flex flex-wrap gap-4 items-end">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('messages.doctor') }}</label>
                <select name="doctor_id" class="border rounded-xl px-4 py-2">
                    <option value="">{{ __('messages.all_doctors') }}</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->user->name ?? $doctor->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('messages.status') }}</label>
                <select name="status" class="border rounded-xl px-4 py-2">
                    <option value="">{{ __('messages.all_status') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition">
                    {{ __('messages.filter') }}
                </button>
                <a href="{{ route('clinic.appointments') }}" class="ml-2 bg-slate-300 text-slate-700 px-6 py-2 rounded-xl hover:bg-slate-400 transition">
                    {{ __('messages.reset') }}
                </a>
            </div>
        </form>
    </div>

    {{-- Appointments List --}}
    <div class="mt-6 space-y-3">
        @forelse($appointments as $appointment)
            <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl hover:shadow transition">
                <div>
                    <p class="font-medium text-slate-800">
                        {{ $appointment->patient->name ?? __('messages.guest') }}
                    </p>
                    <p class="text-sm text-slate-500">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }} 
                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        — 
                        <span class="font-medium">{{ __('messages.doctor') }}:</span> 
                        {{ $appointment->doctor->user->name ?? $appointment->doctor->name ?? 'N/A' }}
                    </p>
                    @if($appointment->symptoms)
                        <p class="text-xs text-slate-400 mt-1">🩺 {{ $appointment->symptoms }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 text-xs rounded-full font-medium
                        @if($appointment->status == 'approved') bg-green-100 text-green-700
                        @elseif($appointment->status == 'pending') bg-yellow-100 text-yellow-700
                        @elseif($appointment->status == 'completed') bg-blue-100 text-blue-700
                        @elseif($appointment->status == 'cancelled') bg-red-100 text-red-700
                        @elseif($appointment->status == 'rejected') bg-gray-100 text-gray-700
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ ucfirst($appointment->status) }}
                    </span>
                    <a href="#" class="text-slate-400 hover:text-cyan-600 transition">
                        👁️
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center text-slate-500 py-12">
                <div class="text-6xl mb-4">📋</div>
                <p class="text-lg font-medium">{{ __('messages.no_appointments_yet') }}</p>
                <p class="text-sm text-slate-400">{{ __('messages.no_appointments_sub') }}</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($appointments->total() > 0)
        <div class="mt-6">
            {{ $appointments->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection