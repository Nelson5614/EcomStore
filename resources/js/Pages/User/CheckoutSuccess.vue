<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    order: Object,
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-LS', {
        style: 'currency',
        currency: 'LSL',
        minimumFractionDigits: 0
    }).format(price);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-LS', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-bold mb-4">Order Confirmed!</h1>
                    <p class="text-lg sm:text-xl opacity-90">Thank you for your purchase</p>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="bg-white py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Order Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Order #{{ order.id }}</h2>
                                <p class="text-sm text-gray-600">Placed on {{ formatDate(order.created_at) }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ order.status.toUpperCase() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Shipping Address -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Order Address</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-900">{{ order.userAddress.address }}</p>
                                    <p class="text-sm text-gray-600">{{ order.userAddress.city }}, {{ order.userAddress.state }} {{ order.userAddress.zip }}</p>
                                    <p class="text-sm text-gray-600">{{ order.userAddress.country }}</p>
                                    <p class="text-sm text-gray-600">Phone: {{ order.userAddress.phone }}</p>
                                </div>
                            </div>

                            <!-- Delivery Method -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Delivery Method</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-900">
                                        {{ (order.delivery_method || '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) || '—' }}
                                    </p>
                                    <template v-if="order.delivery_method === 'own_uber'">
                                        <p class="text-xs text-gray-600 mt-1">
                                            Please arrange your own courier/ride to collect your order from our store.
                                        </p>
                                    </template>
                                    <template v-else-if="order.delivery_method === 'local_delivery'">
                                        <p class="text-xs text-gray-600 mt-1">
                                            We'll contact you shortly to schedule a local delivery time to your address.
                                        </p>
                                    </template>
                                    <template v-else>
                                        <p class="text-xs text-gray-600 mt-1">
                                            You can collect your order in-store at your convenience.
                                        </p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="mt-8">
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Order Items</h3>
                            <div class="border rounded-lg overflow-hidden">
                                <div class="divide-y divide-gray-200">
                                    <div v-for="item in order.orderItems" :key="item.id" class="p-4 flex items-center space-x-4">
                                        <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                            <img
                                                v-if="item.product.product_images && item.product.product_images.length > 0"
                                                :src="`/${item.product.product_images[0].image}`"
                                                :alt="item.product.title"
                                                class="w-full h-full object-cover"
                                            />
                                            <img
                                                v-else
                                                src="/product_images/No_Image_Available.jpg"
                                                :alt="item.product.title"
                                                class="w-full h-full object-cover"
                                            />
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ item.product.title }}</p>
                                            <p class="text-sm text-gray-600">Quantity: {{ item.quantity }}</p>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ formatPrice(item.unit_price * item.quantity) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="mt-8">
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Order Summary</h3>
                            <div class="bg-gray-50 rounded-lg p-6 space-y-3">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span>{{ formatPrice(order.total_amount) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span class="text-green-600">Free</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Tax</span>
                                    <span>{{ formatPrice(0) }}</span>
                                </div>
                                <div class="border-t pt-3">
                                    <div class="flex justify-between text-lg font-semibold text-gray-900">
                                        <span>Total</span>
                                        <span class="text-green-600">{{ formatPrice(order.total_amount) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="mt-8">
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Payment Information</h3>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Payment Method</p>
                                        <p class="text-sm font-medium text-gray-900">{{ order.payment_method === 'cod' ? 'Cash on Delivery' : 'Credit/Debit Card' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Payment Status</p>
                                        <p class="text-sm font-medium text-gray-900">{{ order.payment_status.toUpperCase() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 flex flex-col sm:flex-row gap-4">
                            <Link
                                :href="route('home')"
                                class="flex-1 bg-amber-600 hover:bg-amber-700 text-white py-3 px-4 rounded-lg font-semibold transition-colors duration-200 text-center"
                            >
                                Continue Shopping
                            </Link>
                            <Link
                                :href="route('profile.orders')"
                                class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 py-3 px-4 rounded-lg font-medium transition-colors duration-200 text-center"
                            >
                                View Order History
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
