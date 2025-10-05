<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('id', 'asc')->get();
        return inertia('Admin/Brands/Index', [
            'brands' => $brands
        ]);
    }

    public function create()
    {
        return inertia('Admin/Brands/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'description' => 'nullable|string',
        ]);

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function show(string $id)
    {
        $brand = Brand::findOrFail($id);
        return inertia('Admin/Brands/Show', [
            'brand' => $brand
        ]);
    }

    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);
        return inertia('Admin/Brands/Edit', [
            'brand' => $brand
        ]);
    }

    public function update(Request $request, string $id)
    {
        $brand = Brand::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);
        
        if ($brand->products()->exists()) {
            return redirect()->route('admin.brands.index')->with('error', 'Cannot delete brand. It has associated products.');
        }
        
        $brand->delete();
        
        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }
}
