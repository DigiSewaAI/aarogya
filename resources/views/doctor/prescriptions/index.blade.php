@extends('layouts.doctor')

@section('title', __('messages.prescriptions'))

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    {{-- हेडर: अब 'Create' बटन छैन --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.prescriptions') }}</h2>
        {{-- 
            यहाँ 'create' बटन राख्नु आवश्यक छैन किनभने 
            यसलाई Appointment को सूचीबाट मात्र खोल्नुपर्छ।
        --}}
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow p-4 mb-6">
        <form method="GET" action="{{ route('doctor.prescriptions') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="patient" value="{{ request('patient') }}" 
                       placeholder="{{ __('messages.search_patient') }}"
                       class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            </div>
            <div class="w-40">
                <select name="status" class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                    <option value="">{{ __('messages.all_status') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>{{ __('messages.expired') }}</option>
                </select>
            </div>
            <button type="submit" class="bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition">
                {{ __('messages.filter') }}
            </button>
            <a href="{{ route('doctor.prescriptions') }}" class="bg-slate-200 text-slate-700 px-6 py-2 rounded-xl hover:bg-slate-300 transition">
                {{ __('messages.reset') }}
            </a>
        </form>
    </div>

    {{-- Prescriptions List --}}
    @if($prescriptions->count() > 0)
        <div class="grid gap-4">
            @foreach($prescriptions as $prescription)
                <div class="bg-white rounded-2xl shadow p-6 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">💊</span>
                            <div>
                                <p class="font-semibold text-slate-800">
                                    {{ $prescription->patient->name }}
                                </p>
                                <p class="text-sm text-slate-500">
                                    {{ $prescription->diagnosis }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3 mt-2 text-sm text-slate-500">
                            <span>📅 {{ $prescription->created_at->format('M d, Y') }}</span>
                            <span>💊 {{ $prescription->items->count() }} {{ __('messages.medicines') }}</span>
                            <span class="px-2 py-1 rounded-full text-xs {{ $prescription->status_badge }}">
                                {{ ucfirst($prescription->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <a href="{{ route('doctor.prescriptions.show', $prescription->id) }}" 
                           class="text-cyan-600 hover:text-cyan-800 text-sm">👁️ {{ __('messages.view') }}</a>
                        <a href="{{ route('doctor.prescriptions.download', $prescription->id) }}" 
                           class="text-green-600 hover:text-green-800 text-sm">⬇️ {{ __('messages.download') }}</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $prescriptions->links() }}</div>
    @else
        <div class="bg-white rounded-2xl shadow p-12 text-center">
            <div class="text-6xl mb-4">💊</div>
            <p class="text-slate-500">{{ __('messages.no_prescriptions') }}</p>
            {{-- 
                यहाँ पनि 'create' बटन नराखी, Appointment पेजमा जान लिङ्क दिइएको छ।
                किनभने नयाँ Prescription सधैं कुनै न कुनै Appointment बाट सुरु गर्नुपर्छ।
            --}}
            <a href="{{ route('doctor.appointments') }}" 
               class="inline-block mt-4 bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition">
                📋 {{ __('messages.go_to_appointments') }}
            </a>
        </div>
    @endif
</div>
@endsection