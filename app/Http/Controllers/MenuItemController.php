<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $items = MenuItem::with('category')
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->category_id, fn ($q, $c) => $q->where('category_id', $c))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('menu-items.index', compact('items', 'categories'));
    }

    public function create()
    {
        return view('menu-items.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        MenuItem::create($this->validated($request));

        return redirect()->route('menu-items.index')->with('success', 'Menu item created.');
    }

    public function edit(MenuItem $menuItem)
    {
        return view('menu-items.edit', [
            'menuItem'   => $menuItem,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $menuItem->update($this->validated($request));

        return redirect()->route('menu-items.index')->with('success', 'Menu item updated.');
    }

    public function destroy(MenuItem $menuItem)
    {
        try {
            $menuItem->delete();
        } catch (QueryException $e) {
            // Item is referenced by past orders (order_items)
            return back()->with('error', 'This item is used in existing orders. Mark it as unavailable instead of deleting it.');
        }

        return redirect()->route('menu-items.index')->with('success', 'Menu item deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price'       => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock'       => ['required', 'integer', 'min:0'],
        ]);

        $data['is_available'] = $request->boolean('is_available');

        return $data;
    }
}