<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Application;

class UserController extends Controller
{
    public function index()
    {
        $products = Product::with('brand', 'category', 'product_images')->orderBy('id', 'asc')->take(4)->get();
        $categories = Category::all();
        $bestSellers = $this->getBestSellers();
        
        return Inertia::render('User/Index', [
            'products' => $products,
            'categories' => $categories,
            'bestSellers' => $bestSellers,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    /**
     * Get best selling products based on order items
     */
    private function getBestSellers()
    {
        return Product::with(['brand', 'category', 'product_images'])
            ->withCount(['orderItems as total_sold' => function($query) {
                $query->select(
                    \DB::raw('SUM(quantity)')
                );
            }])
            ->having('total_sold', '>', 0)
            ->orderBy('total_sold', 'desc')
            ->take(8)
            ->get();
    }

    public function show($id)
    {
        $product = Product::with('brand', 'category', 'product_images')->findOrFail($id);
        return Inertia::render('User/Show', [
            'product' => $product,
        ]);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::with('brand', 'category', 'product_images')
            ->where('category_id', $category->id)
            ->orderBy('id', 'asc')
            ->get();
        
        return Inertia::render('User/Category', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}
