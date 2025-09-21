<?php

namespace App\Http\Controllers\Admin;

use App\Models\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::withCount('products')->orderBy('id', 'asc')->get();
        return inertia('Admin/Collections/Index', [
            'collections' => $collections
        ]);
    }

    public function create()
    {
        return inertia('Admin/Collections/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:collections',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Collection::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.collections.index')->with('success', 'Collection created successfully.');
    }

    public function show(string $id)
    {
        $collection = Collection::with(['products' => function($query) {
            $query->with('brand', 'category', 'product_images')->orderBy('id', 'asc');
        }])->findOrFail($id);
        
        return inertia('Admin/Collections/Show', [
            'collection' => $collection
        ]);
    }

    public function edit(string $id)
    {
        $collection = Collection::findOrFail($id);
        return inertia('Admin/Collections/Edit', [
            'collection' => $collection
        ]);
    }

    public function update(Request $request, string $id)
    {
        $collection = Collection::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:collections,name,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $collection->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.collections.index')->with('success', 'Collection updated successfully.');
    }

    public function destroy(string $id)
    {
        $collection = Collection::findOrFail($id);
        
        if ($collection->products()->exists()) {
            return redirect()->route('admin.collections.index')->with('error', 'Cannot delete collection. It has associated products.');
        }
        
        $collection->delete();
        
        return redirect()->route('admin.collections.index')->with('success', 'Collection deleted successfully.');
    }
}
