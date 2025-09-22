<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import { Link } from "@inertiajs/vue3";
import { defineProps } from "vue";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2';
import { usePage } from "@inertiajs/vue3";

const page = usePage();

defineProps({
    collection: Object,
    products: Array,
});

const addToCart = (product) => {
    console.log(product);
    router.post(route("cart.store", product), {
        onSuccess: () => {
            if (page.props.flash.success) {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    title: page.props.flash.success,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
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
</script>

<template>
    <UserLayout>
        <div class="bg-white">
            <!-- Header -->
            <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="text-3xl sm:text-4xl font-bold mb-4">{{ collection.name }}</h1>
                        <p class="text-lg sm:text-xl opacity-90 max-w-2xl mx-auto">{{ collection.description }}</p>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Results Info -->
                <div class="mb-8">
                    <p class="text-gray-600">
                        Showing <span class="font-semibold">{{ products.length }}</span> 
                        product{{ products.length !== 1 ? 's' : '' }} in {{ collection.name }}
                    </p>
                </div>

                <!-- Products Grid -->
                <div v-if="products.length > 0" class="grid grid-cols-1 gap-x-8 gap-y-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 hover:border-amber-200"
                    >
                        <!-- Product Image Container -->
                        <div class="relative aspect-square overflow-hidden bg-gray-50">
                            <img
                                v-if="product.product_images.length > 0"
                                :src="`/${product.product_images[0].image}`"
                                :alt="product.title"
                                class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                            <img
                                v-else
                                src="/product_images/No_Image_Available.jpg"
                                :alt="product.title"
                                class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-col gap-2">
                                <span v-if="product.quantity < 10" class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    Low Stock
                                </span>
                                <span v-if="product.quantity === 0" class="bg-gray-800 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    Out of Stock
                                </span>
                            </div>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 group-hover:text-amber-600 transition-colors duration-200">
                                        <Link :href="route('products.show', product.id)" class="hover:text-amber-600">
                                            {{ product.title }}
                                        </Link>
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <span v-if="product.brand">{{ product.brand.name }}</span>
                                        <span v-if="product.category"> • {{ product.category.name }}</span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Price and Stock Info -->
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl font-bold text-gray-900">
                                        {{ formatPrice(product.price) }}
                                    </span>
                                    <span v-if="product.quantity > 0" class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                        In Stock ({{ product.quantity }})
                                    </span>
                                    <span v-else class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full">
                                        Out of Stock
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-6 flex gap-3">
                                <button
                                    @click="addToCart(product)"
                                    :disabled="product.quantity === 0"
                                    class="flex-1 bg-amber-600 hover:bg-amber-700 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-base"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    Add to Cart
                                </button>
                                <Link
                                    :href="route('products.show', product.id)"
                                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-base"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    View
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Products Message -->
                <div v-else class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no products in this collection yet.</p>
                    <div class="mt-6">
                        <Link :href="route('home')" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            Continue Shopping
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
