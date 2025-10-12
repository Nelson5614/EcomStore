<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from 'vue';
import UserLayout from "../Layouts/UserLayout.vue";

defineProps({
    order: {
        type: Object,
        required: true
    }
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-LS', {
        style: 'currency',
        currency: 'LSL',
        minimumFractionDigits: 0
    }).format(price);
};

// Lesotho VAT 15%
const VAT_RATE = 0.15;

// Safely get order items regardless of relation key casing/naming
const getItems = (order) => {
    return order?.orderItems || order?.items || order?.order_items || [];
};

const getSubtotal = (order) => {
    const items = getItems(order);
    return items.reduce((sum, item) => sum + (parseFloat(item.unit_price || 0) * (item.quantity || 0)), 0);
};

const getTax = (order) => getSubtotal(order) * VAT_RATE;
const getDeliveryCharge = (order) => parseFloat(order?.delivery_charge || 0);
const getTotal = (order) => getSubtotal(order) + getTax(order) + getDeliveryCharge(order);
</script>

<template>
    <UserLayout>
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Order #{{ order.id }}</h1>
                            <p class="mt-2 text-gray-600">Placed on {{ new Date(order.created_at).toLocaleDateString() }}</p>
                        </div>
                        <Link :href="route('orders.index')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Orders
                        </Link>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Order Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Order Status -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h2>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                          :class="{
                                              'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                              'bg-blue-100 text-blue-800': order.status === 'processing',
                                              'bg-green-100 text-green-800': order.status === 'completed',
                                              'bg-red-100 text-red-800': order.status === 'cancelled'
                                          }">
                                        {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Delivery Method</p>
                                    <p class="font-medium text-gray-900">
                                        {{ (order.delivery_method || '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) || '—' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Payment Method</p>
                                    <p class="font-medium text-gray-900">{{ (order.payment && order.payment.payment_method) ? order.payment.payment_method.toUpperCase() : '—' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Payment Status</p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          :class="{
                                              'bg-yellow-100 text-yellow-800': (order.payment?.status || 'pending') === 'pending',
                                              'bg-blue-100 text-blue-800': (order.payment?.status || '') === 'processing',
                                              'bg-green-100 text-green-800': (order.payment?.status || '') === 'success',
                                              'bg-red-100 text-red-800': (order.payment?.status || '') === 'failed' || (order.payment?.status || '') === 'cancelled'
                                          }">
                                        {{ (order.payment?.status ? order.payment.status : 'pending').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>
                            <div class="space-y-4">
                                <div v-for="item in getItems(order)" :key="item.id" class="flex items-center space-x-4 py-4 border-b border-gray-200 last:border-b-0">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100">
                                        <img v-if="item.product && item.product.product_images && item.product.product_images.length > 0"
                                             :src="`/${item.product.product_images[0].image}`"
                                             :alt="item.product.title"
                                             class="w-full h-full object-cover">
                                        <img v-else
                                             src="/product_images/No_Image_Available.jpg"
                                             :alt="item.product?.title || 'Product'"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ item.product?.title || 'Product' }}</h3>
                                        <p class="text-sm text-gray-600">Quantity: {{ item.quantity }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">{{ formatPrice(parseFloat(item.unit_price || 0) * (item.quantity || 0)) }}</p>
                                        <p class="text-sm text-gray-600">{{ formatPrice(parseFloat(item.unit_price || 0)) }} each</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Address -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Address</h2>
                            <div v-if="order.userAddress || order.user_address" class="text-gray-700">
                                <p class="text-sm">{{ (order.userAddress || order.user_address).address }}</p>
                                <p class="text-sm">{{ (order.userAddress || order.user_address).city }}, {{ (order.userAddress || order.user_address).state }} {{ (order.userAddress || order.user_address).zip }}</p>
                                <p class="text-sm">{{ (order.userAddress || order.user_address).country }}</p>
                                <p v-if="(order.userAddress || order.user_address).phone" class="text-sm mt-2">Phone: {{ (order.userAddress || order.user_address).phone }}</p>
                            </div>
                            <div v-else class="text-gray-500">
                                No address available
                            </div>
                        </div>

                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 sticky top-8">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="text-gray-900">{{ formatPrice(getSubtotal(order)) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">VAT (15%)</span>
                                    <span class="text-gray-900">{{ formatPrice(getTax(order)) }}</span>
                                </div>
                               
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between">
                                        <span class="text-base font-medium text-gray-900">Total</span>
                                        <span class="text-base font-bold text-gray-900">{{ formatPrice(getTotal(order)) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                <Link :href="route('home')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 transition-colors">
                                    Continue Shopping
                                </Link>
                                <button v-if="order.status === 'pending'" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    Cancel Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
