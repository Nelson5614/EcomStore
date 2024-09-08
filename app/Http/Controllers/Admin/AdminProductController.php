<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $brands = Brand::all();
        $categories = Category::all();
        return inertia('Admin/Products/Index', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $produts = new Product();
        $produts->title = $request->title;
        $produts->slug = $request->slug;
        $produts->description = $request->description;
        $produts->price = $request->price;
        $produts->quantity = $request->quantity;
        $produts->brand_id = $request->brand_id;
        $produts->category_id = $request->category_id;
        $produts->save();

        //check if the product has an image uploaded
        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            foreach ($productImages as $image) {
                //create a new product image using timestampe and a random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                //store the image in the public folder using a unique name
                $image->move('product_images', $uniqueName);
                //create a new product image record with the unique name and product id
                $produts->images()->create([
                    'product_id' => $produts->id,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
