<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-bold mb-4">Order Cancelled</h1>
                    <p class="text-lg sm:text-xl opacity-90">Your checkout process was cancelled</p>
                </div>
            </div>
        </div>

        <!-- Cancel Content -->
        <div class="bg-white py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-8 text-center">
                        <div class="mb-6">
                            <svg class="mx-auto h-24 w-24 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Checkout Cancelled</h2>
                        <p class="text-gray-600 mb-8">
                            Your checkout process was cancelled. No charges were made to your account. 
                            Your cart items are still saved and you can try again whenever you're ready.
                        </p>

                        <div class="space-y-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-blue-900 mb-2">What happened?</h3>
                                <p class="text-sm text-blue-700">
                                    You may have cancelled the checkout process intentionally, or there might have been an issue with the payment processing. 
                                    Don't worry, your cart items are safe and you can complete your purchase later.
                                </p>
                            </div>

                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-amber-900 mb-2">Next Steps</h3>
                                <ul class="text-sm text-amber-700 space-y-1">
                                    <li>• Your cart items have been preserved</li>
                                    <li>• You can return to your cart to review items</li>
                                    <li>• Try the checkout process again when ready</li>
                                    <li>• Contact support if you need assistance</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                            <Link
                                :href="route('cart.view')"
                                class="bg-amber-600 hover:bg-amber-700 text-white py-3 px-6 rounded-lg font-semibold transition-colors duration-200"
                            >
                                Return to Cart
                            </Link>
                            <Link
                                :href="route('home')"
                                class="border border-gray-300 text-gray-700 hover:bg-gray-50 py-3 px-6 rounded-lg font-medium transition-colors duration-200"
                            >
                                Continue Shopping
                            </Link>
                        </div>

                        <!-- Support Section -->
                        <div class="mt-12 border-t pt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Need Help?</h3>
                            <p class="text-gray-600 mb-4">
                                If you encountered any issues during checkout or have questions about your order, 
                                our support team is here to help.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="mailto:support@ecomstore.com" class="text-amber-600 hover:text-amber-700 font-medium">
                                    support@ecomstore.com
                                </a>
                                <span class="text-gray-400">|</span>
                                <a href="tel:+26612345678" class="text-amber-600 hover:text-amber-700 font-medium">
                                    +266 123 456 78
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
