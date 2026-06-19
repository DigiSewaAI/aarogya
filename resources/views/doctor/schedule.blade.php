@extends('layouts.doctor')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.schedule') }}</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-xl mt-4 mb-4">{{ session('success') }}</div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded-xl mt-4 mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('doctor.schedule.update') }}" class="mt-6">
        @csrf
        <div class="grid md:grid-cols-2 gap-4">
            @php
                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                $dayNames = [
                    'Sunday' => __('messages.sunday'),
                    'Monday' => __('messages.monday'),
                    'Tuesday' => __('messages.tuesday'),
                    'Wednesday' => __('messages.wednesday'),
                    'Thursday' => __('messages.thursday'),
                    'Friday' => __('messages.friday'),
                    'Saturday' => __('messages.saturday'),
                ];
            @endphp

            @foreach($days as $day)
                <div class="border rounded-xl p-4 bg-slate-50">
                    <label class="font-semibold text-slate-700">{{ $dayNames[$day] }}</label>
                    <div class="flex gap-2 mt-2">
                        <input type="time" 
                               name="schedule[{{ $day }}][start]" 
                               value="{{ old('schedule.'.$day.'.start', $scheduleData[$day]['start'] ?? '') }}" 
                               class="border rounded-lg px-3 py-2 flex-1 focus:ring-2 focus:ring-cyan-500">
                        <span class="text-slate-400 self-center">–</span>
                        <input type="time" 
                               name="schedule[{{ $day }}][end]" 
                               value="{{ old('schedule.'.$day.'.end', $scheduleData[$day]['end'] ?? '') }}" 
                               class="border rounded-lg px-3 py-2 flex-1 focus:ring-2 focus:ring-cyan-500">
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-slate-700">{{ __('messages.slot_duration') }}</label>
            <select name="slot_duration" class="border rounded-xl px-4 py-2 w-48">
                <option value="15">15 {{ __('messages.minutes') }}</option>
                <option value="30" selected>30 {{ __('messages.minutes') }}</option>
                <option value="45">45 {{ __('messages.minutes') }}</option>
                <option value="60">60 {{ __('messages.minutes') }}</option>
            </select>
        </div>

        <button type="submit" class="mt-6 bg-cyan-600 text-white px-8 py-2 rounded-xl hover:bg-cyan-700 transition">
            {{ __('messages.save') }}
        </button>
    </form>
</div>
@endsection