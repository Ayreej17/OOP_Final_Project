@php
    $editing = isset($user);
    $input = 'w-full bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]';
    $label = 'block text-xs font-semibold text-slate-600 uppercase mb-1';
@endphp

<form method="POST" action="{{ $editing ? route('users.update', $user) : route('users.store') }}" class="valex-card p-6 space-y-4 max-w-2xl">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div>
        <label class="{{ $label }}">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="{{ $input }}" required>
    </div>
    <div>
        <label class="{{ $label }}">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="{{ $input }}" required>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="{{ $label }}">Password {{ $editing ? '(leave blank to keep)' : '' }}</label>
            <input type="password" name="password" class="{{ $input }}" {{ $editing ? '' : 'required' }}>
        </div>
        <div>
            <label class="{{ $label }}">Confirm Password</label>
            <input type="password" name="password_confirmation" class="{{ $input }}">
        </div>
    </div>
    <div>
        <label class="{{ $label }}">Role</label>
        @php $isSelf = $editing && $user->id === auth()->id(); @endphp
        <select name="role" class="{{ $input }}" {{ $isSelf ? 'disabled' : '' }}>
            <option value="staff" @selected(old('role', $user->role ?? 'staff') === 'staff')>Staff</option>
            <option value="admin" @selected(old('role', $user->role ?? '') === 'admin')>Admin</option>
        </select>
        @if ($isSelf)
            <input type="hidden" name="role" value="{{ $user->role }}">
            <p class="text-xs text-slate-400 mt-1">You can't change your own role.</p>
        @endif
    </div>

    <div class="flex gap-2 pt-2">
        <button type="submit" class="px-4 py-2 bg-[#6c5ffc] hover:bg-[#5646fc] text-white text-xs font-bold rounded-lg">
            {{ $editing ? 'Update User' : 'Create User' }}
        </button>
        <a href="{{ route('users.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg">Cancel</a>
    </div>
</form>