@extends('layouts.doctor')

@section('title', __('messages.prescription'))

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">{{ __('messages.prescription') }}</h2>
                <p class="text-cyan-100">#{{ $prescription->id }} • {{ $prescription->created_at->format('M d, Y') }}</p>
            </div>
            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $prescription->status_badge }}">
                {{ ucfirst($prescription->status) }}
            </span>
        </div>

        <div class="p-8 space-y-6">
            {{-- Patient Info --}}
            <div class="grid md:grid-cols-2 gap-4 bg-slate-50 rounded-xl p-4">
                <div>
                    <p class="text-sm text-slate-500">{{ __('messages.patient') }}</p>
                    <p class="font-semibold">{{ $prescription->patient->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">{{ __('messages.doctor') }}</p>
                    <p class="font-semibold">Dr. {{ $prescription->doctor->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">{{ __('messages.diagnosis') }}</p>
                    <p class="font-medium">{{ $prescription->diagnosis }}</p>
                </div>
                @if($prescription->valid_until)
                <div>
                    <p class="text-sm text-slate-500">{{ __('messages.valid_until') }}</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($prescription->valid_until)->format('M d, Y') }}</p>
                </div>
                @endif
            </div>

            {{-- Notes --}}
            @if($prescription->notes)
            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-sm text-slate-500">{{ __('messages.notes') }}</p>
                <p class="font-medium">{{ $prescription->notes }}</p>
            </div>
            @endif

            {{-- Medicines --}}
            <div>
                <h3 class="font-bold text-lg mb-3">{{ __('messages.medicines') }}</h3>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-100">
                                <th class="text-left p-3">{{ __('messages.medicine_name') }}</th>
                                <th class="text-left p-3">{{ __('messages.dosage') }}</th>
                                <th class="text-left p-3">{{ __('messages.frequency') }}</th>
                                <th class="text-left p-3">{{ __('messages.duration') }}</th>
                                <th class="text-left p-3">{{ __('messages.instructions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescription->items as $item)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="p-3 font-medium">{{ $item->medicine_name }}</td>
                                <td class="p-3">{{ $item->dosage }}</td>
                                <td class="p-3">{{ $item->frequency }}</td>
                                <td class="p-3">{{ $item->duration }}</td>
                                <td class="p-3 text-sm text-slate-500">{{ $item->instructions ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('doctor.prescriptions.download', $prescription->id) }}" 
                   class="bg-green-600 text-white px-6 py-2 rounded-xl hover:bg-green-700 transition">
                    ⬇️ {{ __('messages.download') }}
                </a>
                <a href="{{ route('doctor.prescriptions') }}" 
                   class="bg-slate-200 text-slate-700 px-6 py-2 rounded-xl hover:bg-slate-300 transition">
                    {{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection