@extends('layouts.valex')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Menu Items</h1>
            <p class="text-xs text-slate-500 mt-1">Manage dishes, prices, stock and availability.</p>
        </div>
               @if(auth()->user()->isAdmin())
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('menu-items.create') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-[#6c5ffc] hover:bg-[#5646fc] text-white shadow-sm">
                    <i class="bi bi-plus-lg mr-1.5"></i> Add Menu Item
                </a>
            </div>
        @endif
    </div>

    @include('partials.flash')

    <div class="valex-card p-5">
        <form method="GET" action="{{ route('menu-items.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Dish name..."
                    class="w-full bg-slate-50 border border-slate-200 text-xs rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Category</label>
                <select name="category_id" class="w-full bg-slate-50 border border-slate-200 text-xs rounded-lg px-3 py-2 text-slate-700">
                    <option value="">All categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-[#6c5ffc] hover:bg-[#5646fc] text-white text-xs font-bold rounded-lg">
                    <i class="bi bi-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('menu-items.index') }}" class="py-2 px-4 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg">Reset</a>
            </div>
        </form>
    </div>

    <div class="valex-card p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Price</th>
                        <th class="py-3 px-4">Stock</th>
                        <th class="py-3 px-4">Status</th>
                        @if(auth()->user()->isAdmin())
                         <th class="py-3 px-4 text-right">Actions</th>
@endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($items as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $item->name }}</td>
                            <td class="py-3 px-4 text-xs text-slate-500">{{ $item->category->name ?? 'Uncategorized' }}</td>
                            <td class="py-3 px-4 font-bold text-[#6c5ffc]">₱{{ number_format($item->price, 2) }}</td>
                            <td class="py-3 px-4 text-xs {{ $item->stock <= 10 ? 'text-red-600 font-bold' : '' }}">{{ $item->stock }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $item->is_available ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $item->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            @if(auth()->user()->isAdmin())
                            <td class="py-3 px-4 text-right whitespace-nowrap space-x-2">
                                <a href="{{ route('menu-items.edit', $item) }}" class="text-xs font-semibold text-[#6c5ffc]">Edit</a>
                                <form action="{{ route('menu-items.destroy', $item) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Delete this menu item?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-semibold text-red-600">Delete</button>
                                </form>
                            </td>
                        @endif
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-slate-400 text-xs">No menu items found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $items->links() }}</div>
    </div>
</div>
@endsection