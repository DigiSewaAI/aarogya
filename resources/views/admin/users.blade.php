@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-800">{{ __('messages.users') }}</h2>
        <span class="text-sm text-slate-500">{{ $users->total() }} {{ __('messages.total') }}</span>
    </div>

    <form method="GET" class="mt-4 flex gap-4">
        <input type="text" name="search" placeholder="{{ __('messages.search') }}..." value="{{ request('search') }}" class="border rounded-xl px-4 py-2 flex-1">
        <select name="role" class="border rounded-xl px-4 py-2">
            <option value="">{{ __('messages.all_roles') }}</option>
            <option value="patient" {{ request('role') == 'patient' ? 'selected' : '' }}>{{ __('messages.patient') }}</option>
            <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>{{ __('messages.doctor') }}</option>
            <option value="clinic" {{ request('role') == 'clinic' ? 'selected' : '' }}>{{ __('messages.clinic') }}</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
        </select>
        <button type="submit" class="bg-cyan-600 text-white px-6 py-2 rounded-xl">{{ __('messages.filter') }}</button>
        <a href="{{ route('admin.users') }}" class="bg-slate-300 text-slate-700 px-6 py-2 rounded-xl">{{ __('messages.reset') }}</a>
    </form>

    <div class="mt-6 overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-3">{{ __('messages.auth_name') }}</th>
                    <th class="text-left py-3">{{ __('messages.auth_email') }}</th>
                    <th class="text-left py-3">{{ __('messages.role') }}</th>
                    <th class="text-left py-3">{{ __('messages.status') }}</th>
                    <th class="text-left py-3">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b hover:bg-slate-50">
                    <td class="py-3">{{ $user->name }}</td>
                    <td class="py-3">{{ $user->email }}</td>
                    <td class="py-3">
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($user->isAdmin()) bg-red-100 text-red-700
                            @elseif($user->isDoctor()) bg-blue-100 text-blue-700
                            @elseif($user->isClinic()) bg-green-100 text-green-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="py-3">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </td>
                    <td class="py-3 flex gap-2">
                        @if(!$user->isAdmin())
                            <form method="POST" action="{{ route('admin.toggle.user', $user->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm {{ $user->is_active ? 'text-amber-600' : 'text-green-600' }}">
                                    {{ $user->is_active ? __('messages.deactivate') : __('messages.activate') }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.delete.user', $user->id) }}" class="inline" onsubmit="return confirm('{{ __('messages.confirm_delete_user') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600">{{ __('messages.delete') }}</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">{{ __('messages.no_users_found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->appends(request()->query())->links() }}
</div>
@endsection