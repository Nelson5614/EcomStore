<script setup>
import { Link } from "@inertiajs/vue3";
import UserLayout from "../Layouts/UserLayout.vue";

defineProps({
    orders: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <UserLayout>
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
                    <p class="mt-2 text-gray-600">View and track your order history</p>
                </div>

                <!-- Orders List -->
                <div v-if="orders.data.length > 0" class="space-y-6">
                    <div v-for="order in orders.data" :key="order.id" class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                        <!-- Order Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Order #{{ order.id }}</h3>
                                    <p class="text-sm text-gray-600">Placed on {{ new Date(order.created_at).toLocaleDateString() }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                          :class="{
                                              'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                              'bg-blue-100 text-blue-800': order.status === 'processing',
                                              'bg-green-100 text-green-800': order.status === 'completed',
                                              'bg-red-100 text-red-800': order.status === 'cancelled'
                                          }">
                                        {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
                                    </span>
                                    <p class="mt-1 text-lg font-bold text-gray-900">${{ parseFloat(order.total_amount).toFixed(2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items Preview -->
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div v-for="item in order.orderItems.slice(0, 3)" :key="item.id" class="flex-shrink-0">
                                        <img :src="`/product_images/${item.product.image}`" 
                                             :alt="item.product.name"
                                             class="w-12 h-12 rounded-lg object-cover">
                                    </div>
                                    <div v-if="order.orderItems.length > 3" class="text-sm text-gray-500">
                                        +{{ order.orderItems.length - 3 }} more items
                                    </div>
                                </div>
                                <Link :href="route('orders.show', order.id)" 
                                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-amber-700 bg-amber-100 hover:bg-amber-200 transition-colors">
                                    View Details
                                </Link>
                            </div>
                        </div>

                        <!-- Order Footer -->
                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Payment Method: {{ order.payment_method }}</span>
                                <span>Payment Status: {{ order.payment_status }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="orders.links.length > 3" class="flex items-center justify-center space-x-2 mt-8">
                        <template v-for="link in orders.links" :key="link.label">
                            <Link v-if="link.url" 
                                  :href="link.url" 
                                  v-html="link.label"
                                  class="px-3 py-2 text-sm rounded-md border"
                                  :class="{
                                      'bg-amber-600 text-white border-amber-600': link.active,
                                      'bg-white text-gray-700 border-gray-300 hover:bg-gray-50': !link.active
                                  }">
                            </Link>
                            <span v-else 
                                  v-html="link.label"
                                  class="px-3 py-2 text-sm text-gray-500">
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No orders yet</h3>
                    <p class="mt-1 text-gray-500">You haven't placed any orders yet.</p>
                    <div class="mt-6">
                        <Link :href="route('home')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 transition-colors">
                            Start Shopping
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
