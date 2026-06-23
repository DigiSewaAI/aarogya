@extends('layouts.app')

@section('title', __('messages.clinics'))

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-slate-800">{{ __('messages.partner_clinics') }}</h2>
        <p class="text-slate-500 mt-2">{{ __('messages.partner_clinics_sub') }}</p>
    </div>

    {{-- ========== FILTER BAR (by facility type) ========== --}}
    <form method="GET" class="mb-8 flex flex-wrap items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex-1 min-w-[200px]">
            <label for="type" class="block text-sm font-medium text-slate-600 mb-1">{{ __('messages.facility_type') }}</label>
            <select name="type" id="type" class="w-full border border-slate-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                <option value="">{{ __('messages.all_types') }}</option> {{-- ← 'all_types' key प्रयोग --}}
                <option value="clinic" {{ request('type') == 'clinic' ? 'selected' : '' }}>{{ __('messages.clinic') }}</option>
                <option value="hospital" {{ request('type') == 'hospital' ? 'selected' : '' }}>{{ __('messages.hospital') }}</option>
                <option value="diagnostic" {{ request('type') == 'diagnostic' ? 'selected' : '' }}>{{ __('messages.diagnostic_center') }}</option>
                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>{{ __('messages.other') }}</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-xl transition font-medium">
                {{ __('messages.filter') }}
            </button>
            @if(request('type'))
                <a href="{{ route('clinics') }}" class="text-slate-500 hover:text-slate-700 text-sm underline">
                    {{ __('messages.reset') }}
                </a>
            @endif
        </div>
    </form>
    {{-- ============================================= --}}

    @if(isset($clinics) && $clinics->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($clinics as $clinic)
                <div class="bg-white rounded-3xl shadow-md p-6 hover:shadow-xl transition card-hover text-center">
                    @if($clinic->logo)
                        <img src="{{ asset('storage/' . $clinic->logo) }}" class="w-20 h-20 rounded-full mx-auto object-cover border-2 border-cyan-200" alt="{{ $clinic->name }}">
                    @else
                        <div class="w-20 h-20 rounded-full bg-cyan-100 flex items-center justify-center mx-auto text-2xl font-bold text-cyan-600">{{ substr($clinic->name, 0, 1) }}</div>
                    @endif
                    <h3 class="font-bold text-lg mt-4">{{ $clinic->name }}</h3>
                    <p class="text-sm text-slate-500">{{ $clinic->address ?? __('messages.n/a') }}</p>
                    <p class="text-xs text-slate-400 mt-1">
                        {{ __('messages.doctors_count', ['count' => $clinic->doctors()->where('verification_status','approved')->count()]) }}
                    </p>
                    @if($clinic->facility_type)
                        <p class="text-xs text-cyan-600 mt-1 font-medium">
                            {{ $clinic->facility_type_label }}
                        </p>
                    @endif
                    <a href="{{ route('clinic.show', $clinic->id) }}" class="inline-block mt-4 bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition text-sm">
                        {{ __('messages.view_clinic') }}
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $clinics->links() }}</div>
    @else
        <div class="text-center py-16">
            <p class="text-slate-500">{{ __('messages.no_clinics_found') }}</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 text-cyan-600 hover:underline">{{ __('messages.back_to_home') ?? 'Back to Home' }}</a>
        </div>
    @endif
</div>
@endsection