<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\Product;
use App\Helpers\Cart;
use App\Services\MpesaService;
use App\Models\Payment;
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
        
        // Debug logging
        \Log::info('Checkout process started', [
            'user_id' => $user ? $user->id : null,
            'request_data' => $request->all(),
            'request_method' => $request->method(),
        ]);
        
        // Check if user is authenticated
        if (!$user) {
            \Log::warning('Checkout attempted by unauthenticated user');
            return redirect()->route('login')
                ->with('error', 'You should login or register before checkout')
                ->with('intended_url', route('checkout.index'));
        }
        
        try {
            $validated = $request->validate([
                'user_address_id' => 'required|exists:user_addresses,id',
                'payment_method' => 'required|string|in:mpesa',
                'phone_number' => 'required|string',
            ]);
            
            \Log::info('Validation passed', ['validated_data' => $validated]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $cartData = Cart::getProductsAndCartItems();
        $products = $cartData[0];
        $cartItemsData = $cartData[1];

        if (count($cartItemsData) === 0) {
            \Log::warning('Empty cart during checkout');
            return redirect()->route('home')->with('error', 'Your cart is empty');
        }

        \Log::info('Cart data loaded', [
            'products_count' => count($products),
            'cart_items_count' => count($cartItemsData)
        ]);

        try {
            DB::beginTransaction();
            \Log::info('Database transaction started');

            // Calculate total amount
            $totalAmount = 0;
            foreach ($products as $product) {
                if (isset($cartItemsData[$product->id])) {
                    $quantity = $cartItemsData[$product->id]['quantity'];
                    $price = $cartItemsData[$product->id]['price'] ?? $product->price;
                    $totalAmount += $price * $quantity;
                }
            }
            
            \Log::info('Total amount calculated', ['total_amount' => $totalAmount]);

            // Create order
            $order = Order::create([
                'total' => $totalAmount,
                'status' => 'pending_payment',
                'session_id' => session()->getId(),
                'user_address_id' => $validated['user_address_id'],
                'created_by' => $user ? $user->id : null,
                'updated_by' => $user ? $user->id : null,
            ]);
            
            \Log::info('Order created', ['order_id' => $order->id, 'order_data' => $order->toArray()]);

            // Create order items
            foreach ($products as $product) {
                if (isset($cartItemsData[$product->id])) {
                    $quantity = $cartItemsData[$product->id]['quantity'];
                    $price = $cartItemsData[$product->id]['price'] ?? $product->price;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $price,
                    ]);

                    // Update product stock (M-Pesa will update after payment confirmation)
                    // Stock is not decremented here - it will be updated after successful payment
                }
            }

            // Clear cart
            if ($user) {
                CartItem::where('user_id', $user->id)->delete();
            } else {
                Cart::clearCookieCartItems();
            }

            // Create payment record for M-Pesa
            $payment = Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'MPESA_' . time() . '_' . $order->id,
                'payment_method' => 'mpesa',
                'amount' => $totalAmount,
                'currency' => config('mpesa.currency', 'LSL'),
                'phone_number' => $validated['phone_number'],
                'status' => 'pending',
            ]);
            
            \Log::info('Payment created', ['payment_id' => $payment->id, 'payment_data' => $payment->toArray()]);

            DB::commit();
            \Log::info('Database transaction committed');

            // Redirect to payment processing page
            \Log::info('Redirecting to payment page', ['order_id' => $order->id]);
            return redirect()->route('checkout.payment', ['order' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout process failed', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString()
            ]);
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

    public function payment(Request $request, $orderId)
    {
        $user = $request->user();
        $order = Order::with(['orderItems.product', 'userAddress', 'payment'])
            ->where('created_by', $user->id)
            ->findOrFail($orderId);

        // Check if order is pending payment
        if ($order->status !== 'pending_payment') {
            return redirect()->route('checkout.success', ['order' => $order->id]);
        }

        return Inertia::render('User/CheckoutPayment', [
            'order' => $order,
            'payment' => $order->payment
        ]);
    }
}
