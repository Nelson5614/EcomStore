<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['user', 'userAddress.user', 'items.product', 'payment'])
            ->latest()
            ->paginate(10);
        
        // Debug: Log the first order's payment data
        if ($orders->count() > 0) {
            $firstOrder = $orders->first();
            \Log::info('Admin Order Debug', [
                'order_id' => $firstOrder->id,
                'has_payment' => $firstOrder->payment ? 'Yes' : 'No',
                'payment_data' => $firstOrder->payment ? $firstOrder->payment->toArray() : null,
                'phone_number' => $firstOrder->payment ? $firstOrder->payment->phone_number : 'N/A'
            ]);
        }
        
        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Orders/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_address_id' => 'required|exists:user_addresses,id',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
            'session_id' => 'required|string',
            'delivery_method' => 'required|string|in:in_store_pickup,own_uber,local_delivery',
        ]);

        $order = Order::create([
            'user_address_id' => $request->user_address_id,
            'total' => $request->total,
            'status' => $request->status,
            'session_id' => $request->session_id,
            'delivery_method' => $request->delivery_method,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'createdBy', 'userAddress.user', 'items.product']);
        
        return Inertia::render('Admin/Orders/Show', [
            'order' => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load(['user', 'createdBy', 'userAddress.user', 'items.product']);
        
        return Inertia::render('Admin/Orders/Edit', [
            'order' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'user_address_id' => 'required|exists:user_addresses,id',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
            'session_id' => 'required|string',
            'delivery_method' => 'required|string|in:in_store_pickup,own_uber,local_delivery',
        ]);

        $order->update([
            'user_address_id' => $request->user_address_id,
            'total' => $request->total,
            'status' => $request->status,
            'session_id' => $request->session_id,
            'delivery_method' => $request->delivery_method,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled',
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order status updated successfully.');
    }
}
