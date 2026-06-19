@extends('layouts.app')

@section('title', __('messages.book_appointment') . ' - AAROGYA')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-6">
            <h2 class="text-2xl font-bold text-white">{{ __('messages.book_appointment') }}</h2>
            <p class="text-cyan-100">{{ __('messages.booking_with', ['name' => $doctor->full_name ?? $doctor->name]) }}</p>
        </div>

        <div class="p-8">
            {{-- Error Message --}}
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Doctor Info Summary --}}
            <div class="mb-6 grid md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl">
                <div>
                    <span class="text-sm text-slate-500">{{ __('messages.specialization') }}</span>
                    <p class="font-medium">{{ $doctor->specialization ?? __('messages.n/a') }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500">{{ __('messages.experience') }}</span>
                    <p class="font-medium">{{ $doctor->experience ?? 0 }} {{ __('messages.doctor_experience_years') }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500">{{ __('messages.consultation_fee') }}</span>
                    <p class="font-medium">{{ __('messages.currency') }} {{ number_format($doctor->consultation_fee ?? 0, 2) }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500">{{ __('messages.clinic_name') }}</span>
                    <p class="font-medium">{{ $doctor->clinic_name ?? __('messages.n/a') }}</p>
                </div>
            </div>

            {{-- Booking Form --}}
            <form method="POST" action="{{ route('appointment.store') }}" id="appointmentForm">
                @csrf
                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                <div class="space-y-4">
                    {{-- Date --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1" for="appointment_date">
                            {{ __('messages.appointment_date_label') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="appointment_date" id="appointment_date" 
                               min="{{ date('Y-m-d') }}" 
                               class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:outline-none" 
                               required>
                    </div>

                    {{-- Time --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1" for="appointment_time">
                            {{ __('messages.appointment_time_label') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="appointment_time" id="appointment_time" 
                                class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:outline-none" 
                                required>
                            <option value="">{{ __('messages.select_date') }}</option>
                        </select>
                    </div>

                    {{-- Symptoms --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1" for="symptoms">
                            {{ __('messages.symptoms_label') }}
                        </label>
                        <textarea name="symptoms" id="symptoms" rows="3" 
                                  class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:outline-none" 
                                  placeholder="{{ __('messages.symptoms_placeholder') }}"></textarea>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1" for="notes">
                            {{ __('messages.notes_label') }}
                        </label>
                        <textarea name="notes" id="notes" rows="2" 
                                  class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:outline-none" 
                                  placeholder="{{ __('messages.notes_placeholder') }}"></textarea>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" 
                            class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 rounded-xl transition duration-200">
                        {{ __('messages.confirm_booking') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('appointment_date');
        const timeSelect = document.getElementById('appointment_time');
        const doctorId = {{ $doctor->id }};
        const selectDateMsg = "{{ __('messages.select_date') }}";
        const loadingMsg = "{{ __('messages.slot_loading') }}";
        const noSlotsMsg = "{{ __('messages.no_slots') }}";
        const errorMsg = "{{ __('messages.slot_error') }}";

        dateInput.addEventListener('change', function() {
            const date = this.value;
            
            if (date) {
                timeSelect.innerHTML = `<option value="">${loadingMsg}</option>`;
                timeSelect.disabled = true;

                fetch(`{{ route('appointment.slots') }}?doctor_id=${doctorId}&date=${date}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        timeSelect.innerHTML = `<option value="">${selectDateMsg}</option>`;
                        if (data.slots && data.slots.length > 0) {
                            data.slots.forEach(slot => {
                                const option = document.createElement('option');
                                option.value = slot;
                                option.textContent = slot;
                                timeSelect.appendChild(option);
                            });
                            timeSelect.disabled = false;
                        } else {
                            timeSelect.innerHTML = `<option value="">${noSlotsMsg}</option>`;
                            timeSelect.disabled = true;
                        }
                    })
                    .catch(() => {
                        timeSelect.innerHTML = `<option value="">${errorMsg}</option>`;
                        timeSelect.disabled = true;
                    });
            } else {
                timeSelect.innerHTML = `<option value="">${selectDateMsg}</option>`;
                timeSelect.disabled = true;
            }
        });
    });
</script>
@endsection