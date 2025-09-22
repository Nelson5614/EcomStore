<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { defineProps } from "vue";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2';

const page = usePage();

defineProps({
    products: Object,
});

const addToCart = (product) => {
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
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-3xl sm:text-4xl font-bold mb-4">All Products</h1>
                    <p class="text-lg sm:text-xl opacity-90">Browse our complete collection of premium furniture</p>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Products Grid -->
                <div v-if="products.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div
                        v-for="product in products.data"
                        :key="product.id"
                        class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 hover:border-amber-200"
                    >
                        <!-- Product Image -->
                        <div class="aspect-square overflow-hidden bg-gray-50">
                            <Link :href="route('products.show', product.id)">
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
                            </Link>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <!-- Brand and Category -->
                            <div class="flex items-center justify-between mb-2">
                                <span v-if="product.brand" class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                    {{ product.brand.name }}
                                </span>
                                <span v-if="product.category" class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                    {{ product.category.name }}
                                </span>
                            </div>

                            <!-- Product Title -->
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 group-hover:text-amber-600 transition-colors duration-200 mb-2">
                                <Link :href="route('products.show', product.id)" class="hover:text-amber-600">
                                    {{ product.title }}
                                </Link>
                            </h3>

                            <!-- Price -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xl font-bold text-gray-900">{{ formatPrice(product.price) }}</span>
                                <span 
                                    :class="[
                                        'text-xs px-2 py-1 rounded-full',
                                        product.quantity > 10 ? 'text-green-600 bg-green-100' :
                                        product.quantity > 0 ? 'text-yellow-600 bg-yellow-100' :
                                        'text-red-600 bg-red-100'
                                    ]"
                                >
                                    {{ product.quantity > 0 ? `In Stock (${product.quantity})` : 'Out of Stock' }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <button
                                    @click="addToCart(product)"
                                    :disabled="product.quantity === 0"
                                    class="flex-1 bg-amber-600 hover:bg-amber-700 text-white py-2 px-3 rounded-lg font-medium transition-colors duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center gap-1 text-sm"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    Add
                                </button>
                                <Link
                                    :href="route('products.show', product.id)"
                                    class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 py-2 px-3 rounded-lg font-medium transition-colors duration-200 text-center text-sm"
                                >
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
                    <p class="mt-1 text-sm text-gray-500">Check back later for new products.</p>
                </div>

                <!-- Pagination -->
                <div v-if="products.data.length > 0" class="mt-8 flex justify-center">
                    <nav class="flex items-center space-x-2">
                        <Link
                            v-if="products.prev_page_url"
                            :href="products.prev_page_url"
                            class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                        >
                            Previous
                        </Link>
                        
                        <span class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded-md">
                            Page {{ products.current_page }} of {{ products.last_page }}
                        </span>
                        
                        <Link
                            v-if="products.next_page_url"
                            :href="products.next_page_url"
                            class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                        >
                            Next
                        </Link>
                    </nav>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
