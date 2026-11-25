@extends('layouts.valex')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">User Management</h1>
            <p class="text-xs text-slate-500 mt-1">Create staff accounts, assign roles and activate or deactivate access.</p>
        </div>
        <div class="mt-3 sm:mt-0">
            <a href="{{ route('users.create') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-[#6c5ffc] hover:bg-[#5646fc] text-white shadow-sm">
                <i class="bi bi-plus-lg mr-1.5"></i> Add User
            </a>
        </div>
    </div>

    @include('partials.flash')

    <div class="valex-card p-5">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..."
                    class="w-full bg-slate-50 border border-slate-200 text-xs rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Role</label>
                <select name="role" class="w-full bg-slate-50 border border-slate-200 text-xs rounded-lg px-3 py-2 text-slate-700">
                    <option value="">All roles</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    <option value="staff" @selected(request('role') === 'staff')>Staff</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-[#6c5ffc] hover:bg-[#5646fc] text-white text-xs font-bold rounded-lg">
                    <i class="bi bi-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('users.index') }}" class="py-2 px-4 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg">Reset</a>
            </div>
        </form>
    </div>

    <div class="valex-card p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $user->name }}</td>
                            <td class="py-3 px-4 text-xs text-slate-500">{{ $user->email }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $user->isAdmin() ? 'bg-[#6c5ffc]/10 text-[#6c5ffc]' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap space-x-2">
                                <a href="{{ route('users.edit', $user) }}" class="text-xs font-semibold text-[#6c5ffc]">Edit</a>

                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button class="text-xs font-semibold {{ $user->is_active ? 'text-amber-600' : 'text-emerald-600' }}">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Delete this user?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs font-semibold text-red-600">Delete</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">(you)</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-slate-400 text-xs">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</div>
@endsection