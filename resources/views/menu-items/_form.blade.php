@php
    $editing = isset($menuItem);
    $input = 'w-full bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]';
    $label = 'block text-xs font-semibold text-slate-600 uppercase mb-1';
@endphp

<form method="POST" action="{{ $editing ? route('menu-items.update', $menuItem) : route('menu-items.store') }}" class="valex-card p-6 space-y-4 max-w-2xl">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div>
        <label class="{{ $label }}">Name</label>
        <input type="text" name="name" value="{{ old('name', $menuItem->name ?? '') }}" class="{{ $input }}" required>
    </div>
    <div>
        <label class="{{ $label }}">Category</label>
        <select name="category_id" class="{{ $input }}">
            <option value="">Uncategorized</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $menuItem->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="{{ $label }}">Description</label>
        <textarea name="description" rows="3" class="{{ $input }}">{{ old('description', $menuItem->description ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="{{ $label }}">Price (₱)</label>
            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $menuItem->price ?? '0.00') }}" class="{{ $input }}" required>
        </div>
        <div>
            <label class="{{ $label }}">Stock</label>
            <input type="number" min="0" name="stock" value="{{ old('stock', $menuItem->stock ?? 100) }}" class="{{ $input }}" required>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <input type="hidden" name="is_available" value="0">
        <input type="checkbox" name="is_available" value="1" id="is_available"
               class="rounded border-slate-300" @checked(old('is_available', $menuItem->is_available ?? true))>
        <label for="is_available" class="text-sm text-slate-700">Available for ordering</label>
    </div>

    <div class="flex gap-2 pt-2">
        <button type="submit" class="px-4 py-2 bg-[#6c5ffc] hover:bg-[#5646fc] text-white text-xs font-bold rounded-lg">
            {{ $editing ? 'Update Menu Item' : 'Create Menu Item' }}
        </button>
        <a href="{{ route('menu-items.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg">Cancel</a>
    </div>
</form>