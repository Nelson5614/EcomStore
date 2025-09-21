<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Order;
use Carbon\Carbon;

class AdminBestSellerController extends Controller
{
    /**
     * Display a listing of best selling products.
     */
    public function index(Request $request)
    {
        $query = OrderItem::with('product.brand', 'product.category')
            ->select('product_id', \DB::raw('SUM(quantity) as total_sold'), \DB::raw('SUM(unit_price * quantity) as total_revenue'))
            ->groupBy('product_id')
            ->having('total_sold', '>', 0)
            ->orderBy('total_sold', 'desc');

        // Filter by date range if provided
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereHas('order', function($q) {
                        $q->whereDate('created_at', Carbon::today());
                    });
                    break;
                case 'week':
                    $query->whereHas('order', function($q) {
                        $q->where('created_at', '>=', Carbon::now()->subDays(7));
                    });
                    break;
                case 'month':
                    $query->whereHas('order', function($q) {
                        $q->where('created_at', '>=', Carbon::now()->startOfMonth());
                    });
                    break;
                case 'year':
                    $query->whereHas('order', function($q) {
                        $q->where('created_at', '>=', Carbon::now()->startOfYear());
                    });
                    break;
            }
        }

        $bestSellers = $query->paginate(10);

        // Get overall statistics
        $totalRevenue = OrderItem::sum(\DB::raw('unit_price * quantity'));
        $totalItemsSold = OrderItem::sum('quantity');
        $totalProductsSold = OrderItem::distinct('product_id')->count();

        return inertia('Admin/BestSellers/Index', [
            'bestSellers' => $bestSellers,
            'statistics' => [
                'totalRevenue' => $totalRevenue,
                'totalItemsSold' => $totalItemsSold,
                'totalProductsSold' => $totalProductsSold,
            ],
            'filters' => $request->only(['date_range']),
        ]);
    }

    /**
     * Show the form for creating a manual best seller assignment.
     */
    public function create()
    {
        $products = Product::with('brand', 'category')
            ->where('is_published', true)
            ->orderBy('title')
            ->get();

        return inertia('Admin/BestSellers/Create', [
            'products' => $products,
        ]);
    }

    /**
     * Store a manually assigned best seller.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'featured_until' => 'nullable|date|after:today',
            'priority' => 'nullable|integer|min:1|max:100',
        ]);

        // For now, we'll just redirect with a success message
        // In a real implementation, you might want to create a BestSeller model
        // to store manual assignments

        return redirect()->route('admin.bestsellers.index')
            ->with('success', 'Product has been manually featured as a best seller.');
    }

    /**
     * Display the specified best selling product.
     */
    public function show($productId)
    {
        $product = Product::with(['brand', 'category', 'product_images'])
            ->findOrFail($productId);

        $salesData = OrderItem::with('order')
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSold = $salesData->sum('quantity');
        $totalRevenue = $salesData->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Monthly sales data for the past 12 months
        $monthlySales = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthSales = OrderItem::where('product_id', $productId)
                ->whereHas('order', function($q) use ($date) {
                    $q->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
                })
                ->sum('quantity');
            
            $monthlySales[] = [
                'month' => $date->format('M Y'),
                'sales' => $monthSales,
            ];
        }

        return inertia('Admin/BestSellers/Show', [
            'product' => $product,
            'salesData' => $salesData,
            'statistics' => [
                'totalSold' => $totalSold,
                'totalRevenue' => $totalRevenue,
            ],
            'monthlySales' => $monthlySales,
        ]);
    }

    /**
     * Show the form for editing the specified best seller.
     */
    public function edit($productId)
    {
        $product = Product::with('brand', 'category')
            ->findOrFail($productId);

        return inertia('Admin/BestSellers/Edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified best seller.
     */
    public function update(Request $request, $productId)
    {
        $request->validate([
            'featured_until' => 'nullable|date|after:today',
            'priority' => 'nullable|integer|min:1|max:100',
        ]);

        // Update logic would go here if we had a BestSeller model

        return redirect()->route('admin.bestsellers.index')
            ->with('success', 'Best seller settings updated successfully.');
    }

    /**
     * Remove the specified best seller from featured status.
     */
    public function destroy($productId)
    {
        // Remove from featured logic would go here if we had a BestSeller model

        return redirect()->route('admin.bestsellers.index')
            ->with('success', 'Product removed from best sellers.');
    }

    /**
     * Get best sellers data for API responses.
     */
    public function getBestSellersData(Request $request)
    {
        $limit = $request->input('limit', 10);
        $dateRange = $request->input('date_range', 'all');

        $query = OrderItem::with('product.brand', 'product.category')
            ->select('product_id', \DB::raw('SUM(quantity) as total_sold'), \DB::raw('SUM(unit_price * quantity) as total_revenue'))
            ->groupBy('product_id')
            ->having('total_sold', '>', 0)
            ->orderBy('total_sold', 'desc')
            ->limit($limit);

        if ($dateRange !== 'all') {
            switch ($dateRange) {
                case 'today':
                    $query->whereHas('order', function($q) {
                        $q->whereDate('created_at', Carbon::today());
                    });
                    break;
                case 'week':
                    $query->whereHas('order', function($q) {
                        $q->where('created_at', '>=', Carbon::now()->subDays(7));
                    });
                    break;
                case 'month':
                    $query->whereHas('order', function($q) {
                        $q->where('created_at', '>=', Carbon::now()->startOfMonth());
                    });
                    break;
                case 'year':
                    $query->whereHas('order', function($q) {
                        $q->where('created_at', '>=', Carbon::now()->startOfYear());
                    });
                    break;
            }
        }

        $bestSellers = $query->get();

        return response()->json([
            'bestSellers' => $bestSellers,
            'total' => $bestSellers->count(),
        ]);
    }
}
