<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category', 'brand', 'product_images')->get();
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
        //dd($request->file('product_images'));
        $product = new Product();
        $product->title = $request->title;
        $product->slug = $request->slug;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        $product->save();

        //check if the product has an image uploaded
        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            foreach ($productImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                // Store the image in the public folder with the unique name
                $image->move('product_images', $uniqueName);
                // Create a new product image record with the product_id and unique name
                ProductImage::create([
                    'product_id' => $product->id,
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
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $product = Product::find($id);
        $product->title = $request->title;
        $product->slug = $request->slug;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;

        //check if the product has an image uploaded
        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            // Loop through each uploaded image
            foreach ($productImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();

                // Store the image in the public folder with the unique name
                $image->move('product_images', $uniqueName);

                // Create a new product image record with the product_id and unique name
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }

        $product->update();

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }

    public function deleteimage($id)
    {
        $image = ProductImage::where('id', $id)->delete();
        return redirect()->route('products.index')->with('success', 'Product image deleted successfully');
    }
}
