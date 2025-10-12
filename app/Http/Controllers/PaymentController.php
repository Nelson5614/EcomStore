<?php

namespace App\Http\Controllers;

use App\Services\MpesaService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Initiate M-Pesa payment
     */
    public function initiateMpesaPayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|exists:orders,id',
                'phone_number' => 'required|string',
                'amount' => 'required|numeric|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if M-Pesa is configured
            if (!$this->mpesaService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'M-Pesa payment is not properly configured'
                ], 500);
            }

            $order = Order::findOrFail($request->order_id);

            $phoneNumber = $this->mpesaService->validatePhoneNumber($request->phone_number);
            if (!$phoneNumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid phone number format. Please enter a valid Lesotho phone number.'
                ], 422);
            }

            // Generate transaction ID
            $transactionId = $this->mpesaService->generateTransactionId();

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
                'payment_method' => 'mpesa',
                'amount' => $request->amount,
                'phone_number' => $phoneNumber,
                'status' => 'pending',
                'currency' => config('mpesa.currency', 'LSL'),
            ]);

            // Process M-Pesa payment
            $paymentData = [
                'amount' => $request->amount,
                'phone_number' => $phoneNumber,
                'transaction_id' => $transactionId,
                'reference' => 'ORDER' . $order->id,
                'description' => 'order' . $order->id,
            ];

            $result = $this->mpesaService->processC2BPayment($paymentData);

            if ($result['success']) {
                // Update payment status
                $payment->status = 'processing';
                $payment->response_data = $result['data'];
                $payment->save();

                return redirect()->back()->with('success', 'Payment initiated successfully. Please check your phone for M-Pesa prompt.');
            } else {
                // Update payment status to failed
                $payment->status = 'failed';
                $payment->error_message = $result['error'];
                $payment->save();

                return redirect()->back()->with('error', 'Payment initiation failed: ' . $result['error']);
            }

        } catch (\Exception $e) {
            Log::error('M-Pesa payment initiation failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle M-Pesa callback
     */
    public function handleMpesaCallback(Request $request)
    {
        try {
            Log::info('M-Pesa callback received', [
                'data' => $request->all()
            ]);

            $callbackData = $request->all();

            // Process callback data
            $processedData = $this->mpesaService->processCallback($callbackData);

            if (!$processedData['transaction_id']) {
                Log::error('M-Pesa callback missing transaction ID', [
                    'data' => $callbackData
                ]);
                return response()->json(['status' => 'error', 'message' => 'Missing transaction ID'], 400);
            }

            // Find payment by transaction ID
            $payment = Payment::where('transaction_id', $processedData['transaction_id'])->first();

            if (!$payment) {
                Log::error('M-Pesa callback payment not found', [
                    'transaction_id' => $processedData['transaction_id']
                ]);
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            // Update payment status
            $payment->status = $processedData['status'];
            $payment->callback_data = $processedData['raw_data'];
            $payment->processed_at = now();
            $payment->save();

            // Update order status if payment is successful
            if ($processedData['status'] === 'success') {
                $order = $payment->order;
                $order->status = 'paid';
                $order->paid_at = now();
                $order->save();

                // Decrease product stock
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->decrement('quantity', $item->quantity);
                }

                Log::info('Order marked as paid', [
                    'order_id' => $order->id,
                    'payment_id' => $payment->id
                ]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('M-Pesa callback processing failed', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($transactionId)
    {
        try {
            $payment = Payment::where('transaction_id', $transactionId)->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'payment' => [
                    'id' => $payment->id,
                    'transaction_id' => $payment->transaction_id,
                    'status' => $payment->status,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'created_at' => $payment->created_at,
                    'processed_at' => $payment->processed_at,
                ],
                'order' => [
                    'id' => $payment->order->id,
                    'status' => $payment->order->status,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Payment status check failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while checking payment status'
            ], 500);
        }
    }

    /**
     * Get payment methods
     */
    public function getPaymentMethods()
    {
        return response()->json([
            'success' => true,
            'payment_methods' => [
                [
                    'id' => 'mpesa',
                    'name' => 'M-Pesa',
                    'description' => 'Pay with M-Pesa mobile money',
                    'enabled' => $this->mpesaService->isConfigured(),
                    'icon' => 'mobile-alt'
                ],
                [
                    'id' => 'cod',
                    'name' => 'Cash on Delivery',
                    'description' => 'Pay when you receive your order',
                    'enabled' => true,
                    'icon' => 'money-bill-wave'
                ]
            ]
        ]);
    }

    /**
     * Initiate payment using payment ID
     */
    public function initiatePayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_id' => 'required|exists:payments,id',
                'phone_number' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $payment = Payment::findOrFail($request->payment_id);
            $order = $payment->order;

            // If a phone number override is provided, validate and persist it
            if ($request->filled('phone_number')) {
                $validatedPhone = $this->mpesaService->validatePhoneNumber($request->phone_number);
                if (!$validatedPhone) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid phone number format for M-Pesa.',
                        'errors' => ['phone_number' => ['Invalid phone number format']],
                    ], 422);
                }
                $payment->phone_number = $validatedPhone;
                $payment->save();
            }

            // Check if payment is still pending
            if ($payment->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment has already been processed'
                ], 400);
            }

            // Process M-Pesa payment
            $paymentData = [
                'amount' => $payment->amount,
                'phone_number' => $payment->phone_number,
                'transaction_id' => $payment->transaction_id,
                'reference' => 'ORDER' . $order->id,
                'description' => 'order' . $order->id,
            ];

            $result = $this->mpesaService->processC2BPayment($paymentData);

            if ($result['success']) {
                // Update payment status
                $payment->status = 'processing';
                $payment->response_data = $result['data'];
                $payment->save();

                // Extract Mpesa response details when available
                $mpesa = $result['data'] ?? null;
                $code = is_object($mpesa) && isset($mpesa->output_ResponseCode) ? $mpesa->output_ResponseCode : null;
                $desc = is_object($mpesa) && isset($mpesa->output_ResponseDesc) ? $mpesa->output_ResponseDesc : null;

                return response()->json([
                    'success' => true,
                    'message' => 'Payment initiated successfully. Please check your phone for M-Pesa prompt.',
                    'transaction_id' => $payment->transaction_id,
                    'payment_id' => $payment->id,
                    'mpesa' => $mpesa,
                    'response_code' => $code,
                    'response_desc' => $desc,
                ]);
            } else {
                // Update payment status to failed
                $payment->status = 'failed';
                $payment->error_message = $result['error'];
                $payment->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Payment initiation failed: ' . $result['error'],
                    'transaction_id' => $payment->transaction_id,
                    'response_code' => $result['response_code'] ?? null,
                    'error' => $result['error'] ?? null,
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('M-Pesa payment initiation failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check payment status by payment ID
     */
    public function checkPaymentStatusById($paymentId)
    {
        try {
            $payment = Payment::findOrFail($paymentId);

            return response()->json([
                'success' => true,
                'status' => $payment->status,
                'payment' => [
                    'id' => $payment->id,
                    'transaction_id' => $payment->transaction_id,
                    'status' => $payment->status,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'created_at' => $payment->created_at,
                    'processed_at' => $payment->processed_at,
                ],
                'order' => [
                    'id' => $payment->order->id,
                    'status' => $payment->order->status,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Payment status check failed', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while checking payment status'
            ], 500);
        }
    }

    /**
     * Cancel payment
     */
    public function cancelPayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_id' => 'required|exists:payments,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $payment = Payment::findOrFail($request->payment_id);
            $order = $payment->order;

            // Check if payment can be cancelled
            if (!in_array($payment->status, ['pending', 'processing'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment cannot be cancelled at this stage'
                ], 400);
            }

            // Update payment status
            $payment->status = 'cancelled';
            $payment->cancelled_at = now();
            $payment->save();

            // Update order status
            $order->status = 'cancelled';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment cancelled successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Payment cancellation failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while cancelling payment'
            ], 500);
        }
    }
}
