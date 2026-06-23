@extends('layouts.app')

@section('title', __('messages.health_profile'))

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-6">
            <h2 class="text-2xl font-bold text-white">{{ __('messages.health_profile') }}</h2>
            <p class="text-cyan-100">{{ __('messages.health_profile_subtitle') }}</p>
        </div>

        <div class="p-8">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('patient.health-profile.update') }}" class="space-y-6">
                @csrf

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.blood_group') }}</label>
                        <select name="blood_group" class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500">
                            <option value="">{{ __('messages.select_blood_group') }}</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $profile->blood_group) == $bg ? 'selected' : '' }}>
                                    {{ $bg }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.allergies') }}</label>
                        <input type="text" name="allergies" value="{{ old('allergies', $profile->allergies) }}" 
                               class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500" 
                               placeholder="{{ __('messages.allergies_placeholder') }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.chronic_diseases') }}</label>
                        <textarea name="chronic_diseases" rows="2" 
                                  class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500"
                                  placeholder="{{ __('messages.chronic_diseases_placeholder') }}">{{ old('chronic_diseases', $profile->chronic_diseases) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.current_medications') }}</label>
                        <textarea name="current_medications" rows="2" 
                                  class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500"
                                  placeholder="{{ __('messages.current_medications_placeholder') }}">{{ old('current_medications', $profile->current_medications) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.height_cm') }}</label>
                        <input type="number" step="0.01" name="height" value="{{ old('height', $profile->height) }}" 
                               class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500" 
                               placeholder="e.g. 170">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.weight_kg') }}</label>
                        <input type="number" step="0.01" name="weight" value="{{ old('weight', $profile->weight) }}" 
                               class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500" 
                               placeholder="e.g. 70">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.emergency_contact_name') }}</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}" 
                               class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500" 
                               placeholder="{{ __('messages.emergency_contact_name_placeholder') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.emergency_contact_number') }}</label>
                        <input type="text" name="emergency_contact_number" value="{{ old('emergency_contact_number', $profile->emergency_contact_number) }}" 
                               class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500" 
                               placeholder="{{ __('messages.emergency_contact_number_placeholder') }}">
                    </div>
                </div>

                <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 rounded-xl transition">
                    {{ __('messages.save_profile') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection