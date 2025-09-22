<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { defineProps } from "vue";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2';

const page = usePage();

const props = defineProps({
    cartItems: Array,
    userAddress: Object,
});

const removeFromCart = (cartItem) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to remove this item from your cart?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('cart.destroy', cartItem.product_id), {
                onSuccess: () => {
                    Swal.fire({
                        toast: true,
                        icon: "success",
                        title: "Item removed from cart",
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                },
            });
        }
    });
};

const updateQuantity = (cartItem, newQuantity) => {
    if (newQuantity < 1) return;
    
    router.patch(route('cart.update', cartItem.product_id), {
        quantity: newQuantity
    }, {
        onSuccess: () => {
            Swal.fire({
                toast: true,
                icon: "success",
                title: "Cart updated",
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        },
    });
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-LS', {
        style: 'currency',
        currency: 'LSL',
        minimumFractionDigits: 0
    }).format(price);
};

const calculateSubtotal = () => {
    return props.cartItems.reduce((total, item) => {
        return total + (item.product.price * item.quantity);
    }, 0);
};

const calculateTotal = () => {
    return calculateSubtotal(); // Add shipping/tax if needed
};
</script>

<template>
    <UserLayout>
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-3xl sm:text-4xl font-bold mb-4">Shopping Cart</h1>
                    <p class="text-lg sm:text-xl opacity-90">Review your selected items</p>
                </div>
            </div>
        </div>

        <!-- Cart Content -->
        <div class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="props.cartItems && props.cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Cart Items ({{ props.cartItems.length }})</h2>
                            </div>
                            
                            <div class="divide-y divide-gray-200">
                                <div v-for="item in props.cartItems" :key="item.id" class="p-6">
                                    <div class="flex items-center space-x-4">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                            <Link :href="route('products.show', item.product.id)">
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
                                            </Link>
                                        </div>

                                        <!-- Product Details -->
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                <Link :href="route('products.show', item.product.id)" class="hover:text-amber-600">
                                                    {{ item.product.title }}
                                                </Link>
                                            </h3>
                                            <p v-if="item.product.brand" class="text-sm text-gray-600">{{ item.product.brand.name }}</p>
                                            <p class="text-lg font-bold text-amber-600 mt-1">{{ formatPrice(item.product.price) }}</p>
                                        </div>

                                        <!-- Quantity and Actions -->
                                        <div class="flex items-center space-x-3">
                                            <!-- Quantity Selector -->
                                            <div class="flex items-center border border-gray-300 rounded-lg">
                                                <button
                                                    @click="updateQuantity(item, item.quantity - 1)"
                                                    :disabled="item.quantity <= 1"
                                                    class="px-3 py-1 text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    -
                                                </button>
                                                <span class="px-3 py-1 text-center min-w-[3rem]">{{ item.quantity }}</span>
                                                <button
                                                    @click="updateQuantity(item, item.quantity + 1)"
                                                    :disabled="item.quantity >= item.product.quantity"
                                                    class="px-3 py-1 text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    +
                                                </button>
                                            </div>

                                            <!-- Remove Button -->
                                            <button
                                                @click="removeFromCart(item)"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Remove item"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Item Subtotal -->
                                    <div class="mt-4 text-right">
                                        <span class="text-sm text-gray-600">Subtotal: </span>
                                        <span class="text-lg font-semibold text-gray-900">{{ formatPrice(item.product.price * item.quantity) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-8">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                            </div>
                            
                            <div class="p-6 space-y-4">
                                <!-- Subtotal -->
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span>{{ formatPrice(calculateSubtotal()) }}</span>
                                </div>

                                <!-- Shipping -->
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span class="text-green-600">Free</span>
                                </div>

                                <!-- Tax -->
                                <div class="flex justify-between text-gray-600">
                                    <span>Tax</span>
                                    <span>{{ formatPrice(0) }}</span>
                                </div>

                                <!-- Total -->
                                <div class="border-t pt-4">
                                    <div class="flex justify-between text-lg font-semibold text-gray-900">
                                        <span>Total</span>
                                        <span class="text-amber-600">{{ formatPrice(calculateTotal()) }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="space-y-3 mt-6">
                                    <Link
                                        :href="route('home')"
                                        class="w-full border border-gray-300 text-gray-700 hover:bg-gray-50 py-3 px-4 rounded-lg font-medium transition-colors duration-200 text-center"
                                    >
                                        Continue Shopping
                                    </Link>
                                    
                                    <Link
                                        :href="route('checkout.index')"
                                        class="w-full bg-amber-600 hover:bg-amber-700 text-white py-3 px-4 rounded-lg font-semibold transition-colors duration-200 text-center block"
                                    >
                                        Proceed to Checkout
                                    </Link>
                                </div>

                                <!-- Shipping Address -->
                                <div v-if="props.userAddress" class="mt-6 p-4 bg-gray-50 rounded-lg">
                                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Shipping Address</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ props.userAddress.address }}<br>
                                        {{ props.userAddress.city }}, {{ props.userAddress.state }} {{ props.userAddress.zip }}<br>
                                        {{ props.userAddress.country }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty Cart -->
                <div v-else class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h3>
                    <p class="mt-2 text-sm text-gray-500">Looks like you haven't added any items to your cart yet.</p>
                    <div class="mt-6">
                        <Link
                            :href="route('products.index')"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700"
                        >
                            Start Shopping
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
