<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Basic statistics
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalCategories = Category::count();
        $totalUsers = User::where('is_admin', false)->count();
        $totalOrders = Order::count();
        
        // Recent orders (last 7 days)
        $recentOrders = Order::with('user')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Low stock products (less than 10 items)
        $lowStockProducts = Product::with('brand', 'category')
            ->where('quantity', '<', 10)
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();
            
        // Monthly revenue for the current year
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[] = Order::whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $i)
                ->where('status', 'completed')
                ->sum('total');
        }
        
        // Order status distribution
        $orderStatusCounts = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        
        // Top selling products
        $topSellingProducts = OrderItem::with('product')
            ->select('product_id', \DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();
            
        // Best sellers data (for best sellers section)
        $bestSellingProducts = OrderItem::with('product.brand', 'product.category')
            ->select('product_id', \DB::raw('SUM(quantity) as total_sold'), \DB::raw('SUM(unit_price * quantity) as total_revenue'))
            ->groupBy('product_id')
            ->having('total_sold', '>', 0)
            ->orderBy('total_sold', 'desc')
            ->take(8)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product->id,
                    'title' => $item->product->title,
                    'price' => $item->product->price,
                    'quantity' => $item->product->quantity,
                    'brand' => $item->product->brand,
                    'category' => $item->product->category,
                    'total_sold' => $item->total_sold,
                    'total_revenue' => $item->total_revenue,
                ];
            });
            
        // New users this month
        $newUsersThisMonth = User::where('is_admin', false)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
            
        // Orders this month
        $ordersThisMonth = Order::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        
        // Revenue this month
        $revenueThisMonth = Order::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->where('status', 'completed')
            ->sum('total');

        return inertia('Admin/Dashboard', [
            'statistics' => [
                'totalProducts' => $totalProducts,
                'totalBrands' => $totalBrands,
                'totalCategories' => $totalCategories,
                'totalUsers' => $totalUsers,
                'totalOrders' => $totalOrders,
                'newUsersThisMonth' => $newUsersThisMonth,
                'ordersThisMonth' => $ordersThisMonth,
                'revenueThisMonth' => $revenueThisMonth,
            ],
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'monthlyRevenue' => $monthlyRevenue,
            'orderStatusCounts' => $orderStatusCounts,
            'topSellingProducts' => $topSellingProducts,
            'bestSellingProducts' => $bestSellingProducts,
        ]);
    }
}
