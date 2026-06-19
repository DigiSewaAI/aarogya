@extends('layouts.doctor')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.patients') }}</h2>
    <div class="mt-4">
        @if($patients->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">{{ __('messages.auth_name') }}</th>
                        <th class="text-left py-2">{{ __('messages.auth_email') }}</th>
                        <th class="text-left py-2">{{ __('messages.phone') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                    <tr class="border-b">
                        <td class="py-2">{{ $patient->patient->name ?? 'N/A' }}</td>
                        <td class="py-2">{{ $patient->patient->email ?? 'N/A' }}</td>
                        <td class="py-2">{{ $patient->patient->phone ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $patients->links() }}
        @else
            <p class="text-slate-500 italic">{{ __('messages.no_patients') }}</p>
        @endif
    </div>
</div>
@endsection