@extends('layouts.doctor')

@section('title', __('messages.create_prescription'))

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-6">
            <h2 class="text-2xl font-bold text-white">{{ __('messages.create_prescription') }}</h2>
            <p class="text-cyan-100">{{ __('messages.create_prescription_subtitle') }}</p>
        </div>

        <div class="p-8">
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('doctor.prescriptions.store') }}" class="space-y-6" id="prescriptionForm">
                @csrf

                {{-- Patient Selection --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.patient') }} <span class="text-red-500">*</span></label>
                    <select name="patient_id" id="patient_id" class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500" required>
                        <option value="">{{ __('messages.select_patient') }}</option>
                        @foreach($appointments ?? [] as $appt)
                            <option value="{{ $appt->patient_id }}" 
                                    {{ isset($appointment) && $appointment->patient_id == $appt->patient_id ? 'selected' : '' }}>
                                {{ $appt->patient->name }} - {{ $appt->appointment_date }}
                            </option>
                        @endforeach
                        @if(isset($appointment))
                            <option value="{{ $appointment->patient_id }}" selected>
                                {{ $appointment->patient->name }} - {{ $appointment->appointment_date }}
                            </option>
                        @endif
                    </select>
                    @if(isset($appointment))
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                    @else
                        <input type="hidden" name="appointment_id" value="{{ old('appointment_id') }}">
                    @endif
                </div>

                {{-- Diagnosis --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.diagnosis') }} <span class="text-red-500">*</span></label>
                    <textarea name="diagnosis" rows="3" 
                              class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500"
                              placeholder="{{ __('messages.diagnosis_placeholder') }}" required>{{ old('diagnosis') }}</textarea>
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.notes') }}</label>
                    <textarea name="notes" rows="2" 
                              class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500"
                              placeholder="{{ __('messages.notes_placeholder') }}">{{ old('notes') }}</textarea>
                </div>

                {{-- Valid Until --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.valid_until') }}</label>
                    <input type="date" name="valid_until" value="{{ old('valid_until') }}"
                           class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500">
                </div>

                {{-- Medicines --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-slate-700">{{ __('messages.medicines') }} <span class="text-red-500">*</span></label>
                        <button type="button" onclick="addMedicine()" 
                                class="bg-cyan-600 text-white px-4 py-2 rounded-xl hover:bg-cyan-700 transition text-sm">
                            + {{ __('messages.add_medicine') }}
                        </button>
                    </div>

                    <div id="medicinesContainer" class="space-y-3">
                        <div class="medicine-item bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <div class="grid md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600">{{ __('messages.medicine_name') }} *</label>
                                    <input type="text" name="medicines[0][name]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-cyan-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600">{{ __('messages.dosage') }} *</label>
                                    <input type="text" name="medicines[0][dosage]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-cyan-500" required placeholder="e.g. 500mg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600">{{ __('messages.frequency') }} *</label>
                                    <input type="text" name="medicines[0][frequency]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-cyan-500" required placeholder="e.g. 2 times daily">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600">{{ __('messages.duration') }} *</label>
                                    <input type="text" name="medicines[0][duration]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-cyan-500" required placeholder="e.g. 7 days">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-slate-600">{{ __('messages.instructions') }}</label>
                                    <input type="text" name="medicines[0][instructions]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-cyan-500" placeholder="e.g. Take after meal">
                                </div>
                            </div>
                            <button type="button" onclick="removeMedicine(this)" class="text-red-500 text-sm mt-2 hover:underline">
                                {{ __('messages.remove') }}
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 rounded-xl transition">
                    {{ __('messages.create_prescription') }}
                </button>
            </form>
        </div>
    </div>
</div>

<script>
let medicineIndex = 1;

function addMedicine() {
    const container = document.getElementById('medicinesContainer');
    const template = `
        <div class="medicine-item bg-slate-50 rounded-xl p-4 border border-slate-200">
            <div class="grid md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-600">{{ __('messages.medicine_name') }} *</label>
                    <input type="text" name="medicines[${medicineIndex}][name]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-cyan-500" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600">{{ __('messages.dosage') }} *</label>
                    <input type="text" name="medicines[${medicineIndex}][dosage]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-cyan-500" required placeholder="e.g. 500mg">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600">{{ __('messages.frequency') }} *</label>
                    <input type="text" name="medicines[${medicineIndex}][frequency]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-cyan-500" required placeholder="e.g. 2 times daily">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600">{{ __('messages.duration') }} *</label>
                    <input type="text" name="medicines[${medicineIndex}][duration]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-cyan-500" required placeholder="e.g. 7 days">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-slate-600">{{ __('messages.instructions') }}</label>
                    <input type="text" name="medicines[${medicineIndex}][instructions]" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-cyan-500" placeholder="e.g. Take after meal">
                </div>
            </div>
            <button type="button" onclick="removeMedicine(this)" class="text-red-500 text-sm mt-2 hover:underline">
                {{ __('messages.remove') }}
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    medicineIndex++;
}

function removeMedicine(button) {
    const items = document.querySelectorAll('.medicine-item');
    if (items.length > 1) {
        button.closest('.medicine-item').remove();
    } else {
        alert('{{ __("messages.at_least_one_medicine") }}');
    }
}
</script>
@endsection