@extends('layouts.doctor')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.appointments') }}</h2>
    <div class="mt-4">
        @if($appointments->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">{{ __('messages.patient') }}</th>
                        <th class="text-left py-2">{{ __('messages.date') }}</th>
                        <th class="text-left py-2">{{ __('messages.time') }}</th>
                        <th class="text-left py-2">{{ __('messages.status') }}</th>
                        <th class="text-left py-2">{{ __('messages.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr class="border-b">
                        <td class="py-2">{{ $appointment->patient->name ?? 'N/A' }}</td>
                        <td class="py-2">{{ $appointment->appointment_date }}</td>
                        <td class="py-2">{{ $appointment->appointment_time }}</td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded text-xs 
                                @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($appointment->status == 'approved') bg-green-100 text-green-800
                                @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800
                                @elseif($appointment->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $appointment->status }}
                            </span>
                        </td>
                        <td class="py-2">
                            {{-- Status Update Form --}}
                            <form method="POST" action="{{ route('doctor.appointment.action', $appointment->id) }}" class="inline">
                                @csrf
                                <select name="status" class="text-sm border rounded px-2 py-1">
                                    <option value="approved" {{ $appointment->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                    <option value="rejected" {{ $appointment->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                    <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Complete</option>
                                    <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancel</option>
                                </select>
                                <button type="submit" class="bg-cyan-600 text-white px-3 py-1 rounded text-sm">Update</button>
                            </form>

                            {{-- नयाँ: Prescription लेख्ने बटन --}}
                            <a href="{{ route('doctor.prescriptions.create', ['appointmentId' => $appointment->id]) }}" 
                               class="ml-2 bg-emerald-600 text-white px-3 py-1 rounded text-sm hover:bg-emerald-700 transition">
                                + Prescription
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $appointments->links() }}
        @else
            <p class="text-slate-500 italic">{{ __('messages.no_appointments') }}</p>
        @endif
    </div>
</div>
@endsection