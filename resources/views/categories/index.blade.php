@extends('layouts.valex')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Categories</h1>
            <p class="text-xs text-slate-500 mt-1">Group your menu items into categories.</p>
        </div>
        <div class="mt-3 sm:mt-0">
            <a href="{{ route('categories.create') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-[#6c5ffc] hover:bg-[#5646fc] text-white shadow-sm">
                <i class="bi bi-plus-lg mr-1.5"></i> Add Category
            </a>
        </div>
    </div>

    @include('partials.flash')

    <div class="valex-card p-5">
        <form method="GET" action="{{ route('categories.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div class="sm:col-span-3">
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Category name..."
                    class="w-full bg-slate-50 border border-slate-200 text-xs rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-[#6c5ffc] hover:bg-[#5646fc] text-white text-xs font-bold rounded-lg">
                    <i class="bi bi-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('categories.index') }}" class="py-2 px-4 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg">Reset</a>
            </div>
        </form>
    </div>

    <div class="valex-card p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3 px-4">Description</th>
                        <th class="py-3 px-4">Menu Items</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $category->name }}</td>
                            <td class="py-3 px-4 text-xs text-slate-500">{{ $category->description ?: '—' }}</td>
                            <td class="py-3 px-4 font-bold text-[#6c5ffc]">{{ $category->menu_items_count }}</td>
                            <td class="py-3 px-4 text-right whitespace-nowrap space-x-2">
                                <a href="{{ route('categories.edit', $category) }}" class="text-xs font-semibold text-[#6c5ffc]">Edit</a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this category? Its menu items will become uncategorized.')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-semibold text-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-slate-400 text-xs">No categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $categories->links() }}</div>
    </div>
</div>
@endsection