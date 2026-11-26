<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('menuItems')
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        Category::create($request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]));

        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update($request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]));

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $count = $category->menuItems()->count();
        $category->delete(); // menu items keep existing, category_id becomes null

        $message = $count > 0
            ? "Category deleted. {$count} menu item(s) are now uncategorized."
            : 'Category deleted.';

        return redirect()->route('categories.index')->with('success', $message);
    }
}
