@php
    $editing = isset($category);
    $input = 'w-full bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]';
    $label = 'block text-xs font-semibold text-slate-600 uppercase mb-1';
@endphp

<form method="POST" action="{{ $editing ? route('categories.update', $category) : route('categories.store') }}" class="valex-card p-6 space-y-4 max-w-2xl">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div>
        <label class="{{ $label }}">Name</label>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="{{ $input }}" required>
    </div>
    <div>
        <label class="{{ $label }}">Description</label>
        <textarea name="description" rows="3" class="{{ $input }}">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

    <div class="flex gap-2 pt-2">
        <button type="submit" class="px-4 py-2 bg-[#6c5ffc] hover:bg-[#5646fc] text-white text-xs font-bold rounded-lg">
            {{ $editing ? 'Update Category' : 'Create Category' }}
        </button>
        <a href="{{ route('categories.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg">Cancel</a>
    </div>
</form>