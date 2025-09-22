<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\Product;
use App\Helpers\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $cartData = Cart::getProductsAndCartItems();
        $products = $cartData[0];
        $cartItemsData = $cartData[1];

        if (count($cartItemsData) === 0) {
            return redirect()->route('home')->with('info', 'Your cart is empty');
        }

        // Merge products with cart items data
        $cartItems = [];
        foreach ($products as $product) {
            if (isset($cartItemsData[$product->id])) {
                $cartItems[] = [
                    'id' => $cartItemsData[$product->id]['id'] ?? null,
                    'user_id' => $cartItemsData[$product->id]['user_id'] ?? null,
                    'product_id' => $product->id,
                    'quantity' => $cartItemsData[$product->id]['quantity'],
                    'price' => $cartItemsData[$product->id]['price'] ?? $product->price,
                    'product' => $product
                ];
            }
        }

        $userAddresses = [];
        if ($user) {
            $userAddresses = UserAddress::where('user_id', $user->id)->get();
        }

        return Inertia::render('User/Checkout', [
            'cartItems' => $cartItems,
            'userAddresses' => $userAddresses
        ]);
    }

    public function process(Request $request)
    {
        $user = $request->user();
        
        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'You should login or register before checkout')
                ->with('intended_url', route('checkout.index'));
        }
        
        $validated = $request->validate([
            'user_address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|string|in:cod,card',
        ]);

        $cartData = Cart::getProductsAndCartItems();
        $products = $cartData[0];
        $cartItemsData = $cartData[1];

        if (count($cartItemsData) === 0) {
            return redirect()->route('home')->with('error', 'Your cart is empty');
        }

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            foreach ($products as $product) {
                if (isset($cartItemsData[$product->id])) {
                    $quantity = $cartItemsData[$product->id]['quantity'];
                    $price = $cartItemsData[$product->id]['price'] ?? $product->price;
                    $totalAmount += $price * $quantity;
                }
            }

            // Create order
            $order = Order::create([
                'total' => $totalAmount,
                'status' => 'pending',
                'session_id' => session()->getId(),
                'user_address_id' => $validated['user_address_id'],
                'created_by' => $user ? $user->id : null,
                'updated_by' => $user ? $user->id : null,
            ]);

            // Create order items
            foreach ($products as $product) {
                if (isset($cartItemsData[$product->id])) {
                    $quantity = $cartItemsData[$product->id]['quantity'];
                    $price = $cartItemsData[$product->id]['price'] ?? $product->price;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);

                    // Update product stock
                    $product->decrement('stock', $quantity);
                }
            }

            // Clear cart
            if ($user) {
                CartItem::where('user_id', $user->id)->delete();
            } else {
                Cart::clearCookieCartItems();
            }

            DB::commit();

            return redirect()->route('checkout.success', ['order' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')->with('error', 'There was an error processing your order. Please try again.');
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order');
        $order = Order::with(['orderItems.product', 'userAddress'])
            ->findOrFail($orderId);

        return Inertia::render('User/CheckoutSuccess', [
            'order' => $order
        ]);
    }

    public function cancel()
    {
        return Inertia::render('User/CheckoutCancel');
    }
}
