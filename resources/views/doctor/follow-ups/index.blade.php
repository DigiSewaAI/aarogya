@extends('layouts.doctor')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.follow_ups') }}</h2>
    <div class="mt-4">
        @if($followUps->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">{{ __('messages.patient') }}</th>
                        <th class="text-left py-2">{{ __('messages.follow_up_date') }}</th>
                        <th class="text-left py-2">{{ __('messages.status') }}</th>
                        <th class="text-left py-2">{{ __('messages.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($followUps as $followUp)
                    <tr class="border-b">
                        <td class="py-2">{{ $followUp->patient->name ?? 'N/A' }}</td>
                        <td class="py-2">{{ $followUp->follow_up_date }}</td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded text-xs 
                                @if($followUp->status == 'scheduled') bg-yellow-100 text-yellow-800
                                @elseif($followUp->status == 'completed') bg-green-100 text-green-800
                                @elseif($followUp->status == 'cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ __('messages.' . $followUp->status) }}
                            </span>
                        </td>
                        <td class="py-2">
                            <a href="#" class="text-cyan-600 hover:text-cyan-800">{{ __('messages.view') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $followUps->links() }}
        @else
            <p class="text-slate-500 italic">{{ __('messages.no_follow_ups') }}</p>
        @endif
    </div>
</div>
@endsection