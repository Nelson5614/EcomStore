<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import { Link } from "@inertiajs/vue3";
import { defineProps } from "vue";
import {router} from "@inertiajs/vue3";


defineProps({
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
                });
            }
        },
    });
};
</script>

<template>
    <UserLayout>
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-gray-50 to-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <!-- Left Side - Text Content -->
                    <div class="text-center lg:text-left">
                        <div class="inline-flex items-center bg-amber-100 text-amber-800 px-4 py-2 rounded-full text-sm font-medium mb-6">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Premium Quality Furniture
                        </div>
                        
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                            Transform Your
                            <span class="text-amber-600">Living Space</span>
                            with Style
                        </h1>
                        
                        <p class="text-base md:text-lg text-gray-600 mb-6 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                            Discover handcrafted furniture that combines exceptional quality with contemporary design. Create the home you've always dreamed of.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start mb-6">
                            <Link href="#products" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-semibold text-base transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                Shop Collection
                            </Link>
                            <Link href="#about" class="border-2 border-gray-300 text-gray-700 hover:border-amber-600 hover:text-amber-600 px-6 py-3 rounded-lg font-semibold text-base transition-all duration-200">
                                Explore More
                            </Link>
                        </div>
                        
                        <!-- Trust Indicators -->
                        <div class="flex items-center justify-center lg:justify-start space-x-6 text-xs text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Free Shipping
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                5-Year Warranty
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Easy Returns
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Side - Image -->
                    <div class="relative">
                        <div class="relative z-10">
                            <img src="/product_images/chair1.jpg" 
                                 alt="Modern Living Room Furniture" 
                                 class="rounded-2xl shadow-2xl w-full h-auto object-cover">
                        </div>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-amber-400 rounded-full opacity-20 blur-xl"></div>
                        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-orange-400 rounded-full opacity-20 blur-xl"></div>
                        
                        <!-- Floating Card -->
                        <div class="absolute -bottom-8 -left-8 bg-white rounded-xl shadow-xl p-4 z-20">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Satisfaction Guaranteed</p>
                                    <p class="text-xs text-gray-500">30-day return policy</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Categories -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Shop by Category</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Explore our curated collections designed for every room in your home
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center group cursor-pointer">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-4 group-hover:shadow-xl transition-shadow">
                            <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Living Room</h3>
                        <p class="text-gray-600">Sofas, chairs, coffee tables & more</p>
                    </div>
                    <div class="text-center group cursor-pointer">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-4 group-hover:shadow-xl transition-shadow">
                            <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Bedroom</h3>
                        <p class="text-gray-600">Beds, dressers, nightstands & storage</p>
                    </div>
                    <div class="text-center group cursor-pointer">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-4 group-hover:shadow-xl transition-shadow">
                            <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Home Office</h3>
                        <p class="text-gray-600">Desks, chairs, bookshelves & accessories</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="bg-white py-16" id="products">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Products</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Customers also purchased
                    </p>
                </div>

                <div
                    class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8"
                >
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="group relative"
                    >
                        <div
                            class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80"
                        >
                            <img
                                v-if="product.product_images.length > 0"
                                :src="`/${product.product_images[0].image} `"
                                :alt="`${product.title}`"
                                class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                            />
                            <img
                                v-else
                                src="https://cdn.vectorstock.com/i/500p/82/99/no-image-available-like-missing-picture-vector-43938299.jpg"
                                :alt="product.title"
                                class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                            />
                            <!--add to cart icon-->
                            <div
                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out cursor-pointer"
                            >
                                <div class="bg-blue-700 p-2 rounded-full">
                                    <a @click="addToCart(product)">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-6 h-6 text-white"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                                            />
                                        </svg>
                                    </a>
                                </div>
                                <div class="bg-blue-700 p-2 rounded-full ml-2">
                                    <a href="detail">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-6 h-6 text-white"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="#">
                                        <span
                                            aria-hidden="true"
                                            class="inset-0"
                                        ></span>
                                        {{ product.title }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">Black</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">
                                M{{ product.price }}
                            </p>
                        </div>
                    </div>

                    <!-- More products... -->
                </div>
            </div>
        </div>
    </UserLayout>
</template>
