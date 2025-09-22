<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $orders = Order::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return inertia('User/Orders/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = Order::where('created_by', Auth::id())
            ->with(['orderItems.product', 'userAddress'])
            ->findOrFail($id);

        return inertia('User/Orders/Show', [
            'order' => $order
        ]);
    }
}
