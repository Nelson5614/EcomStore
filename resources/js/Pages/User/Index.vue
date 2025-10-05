<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { defineProps, onMounted } from "vue";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2';

const page = usePage();

defineProps({
    products: Array,
    categories: Array,
    collections: Array,
    bestSellers: Array,
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

// Smooth scrolling function
const scrollToSection = (sectionId) => {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
};

// Handle anchor links with smooth scrolling
const handleAnchorClick = (event) => {
    const href = event.target.getAttribute('href');
    if (href && href.startsWith('#')) {
        event.preventDefault();
        const sectionId = href.substring(1);
        scrollToSection(sectionId);
    }
};

// Initialize smooth scrolling when component is mounted
onMounted(() => {
    // Add click event listeners to all anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', handleAnchorClick);
    });
});
</script>

<template>
    <UserLayout>
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-gray-50 to-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <!-- Left Side - Text Content -->
                    <div class="text-center lg:text-left">
                       
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

        <!-- Collections Section -->
        <div class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">Curated Collections</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Discover our handpicked furniture collections for every space
                    </p>
                </div>

                <div v-if="collections && collections.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        v-for="collection in collections"
                        :key="collection.id"
                        class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 hover:border-amber-200 cursor-pointer"
                        @click="router.get(route('collections.show', collection.slug))"
                    >
                        <div class="aspect-video overflow-hidden bg-gray-50">
                            <img
                                v-if="collection.products && collection.products.length > 0 && collection.products[0].product_images.length > 0"
                                :src="'/' + collection.products[0].product_images[0].image"
                                :alt="collection.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                            <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-amber-100 to-amber-200">
                                <svg class="w-16 h-16 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-amber-600 transition-colors duration-200">
                                    {{ collection.name }}
                                </h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    {{ collection.products ? collection.products.length : 0 }} Items
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">
                                {{ collection.description || 'Explore our curated collection of premium furniture pieces.' }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-amber-600 font-medium text-sm group-hover:text-amber-700 transition-colors duration-200">
                                    Explore Collection →
                                </span>
                                <div class="flex -space-x-2">
                                    <div v-for="i in Math.min(3, collection.products ? collection.products.length : 0)" :key="i" class="w-8 h-8 rounded-full bg-amber-200 border-2 border-white"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Collections Available</h3>
                    <p class="text-gray-500">Check back soon for new curated collections.</p>
                </div>

                <!-- Collection Stats -->
                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-amber-600 mb-1">
                            {{ collections ? collections.reduce((total, collection) => total + (collection.products ? collection.products.length : 0), 0) : 0 }}
                        </div>
                        <div class="text-sm text-gray-600">Total Items</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 mb-1">
                            {{ collections ? collections.length : 0 }}
                        </div>
                        <div class="text-sm text-gray-600">Collections</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 mb-1">
                            {{ categories ? categories.length : 0 }}
                        </div>
                        <div class="text-sm text-gray-600">Categories</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 mb-1">4.8</div>
                        <div class="text-sm text-gray-600">Avg Rating</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- About Us Section -->
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 py-16" id="about">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center bg-amber-100 text-amber-800 px-4 py-2 rounded-full text-sm font-medium mb-4">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        About Us
                    </div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Story</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        Crafting exceptional furniture experiences since 2024, TlaliFurn brings together passion, innovation, and quality to transform houses into homes.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Side - Content -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-amber-100">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Our Mission</h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        To provide high-quality, stylish furniture that enhances living spaces while maintaining affordability and sustainability. We believe everyone deserves a beautiful home.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-6 shadow-sm border border-amber-100">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Quality Promise</h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Every piece of furniture is carefully crafted using premium materials and undergoes rigorous quality checks to ensure durability, comfort, and style.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-6 shadow-sm border border-amber-100">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Customer Focus</h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Our customers are at the heart of everything we do. From personalized service to comprehensive warranties, we ensure your satisfaction every step of the way.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Stats and Image -->
                    <div class="space-y-8">
                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-amber-100">
                                <div class="text-3xl font-bold text-amber-600 mb-2">5+</div>
                                <div class="text-sm text-gray-600">Happy Customers</div>
                            </div>
                            <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-amber-100">
                                <div class="text-3xl font-bold text-amber-600 mb-2">2+</div>
                                <div class="text-sm text-gray-600">Products Sold</div>
                            </div>
                            <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-amber-100">
                                <div class="text-3xl font-bold text-amber-600 mb-2">5+</div>
                                <div class="text-sm text-gray-600">Collections</div>
                            </div>
                            <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-amber-100">
                                <div class="text-3xl font-bold text-amber-600 mb-2">4.8</div>
                                <div class="text-sm text-gray-600">Customer Rating</div>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="relative rounded-2xl overflow-hidden shadow-lg">
                            <img src="/product_images/livingroom.jpg" alt="TlaliFurn Store" class="w-full h-64 object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 text-white">
                                <div class="text-lg font-semibold mb-1">Visit Our Showroom</div>
                                <div class="text-sm opacity-90">Experience quality firsthand</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Values Section -->
                <div class="mt-16 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-8">Our Core Values</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">Quality</h4>
                            <p class="text-sm text-gray-600">Uncompromising standards in every piece</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">Innovation</h4>
                            <p class="text-sm text-gray-600">Modern designs for contemporary living</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">Passion</h4>
                            <p class="text-sm text-gray-600">Love for furniture and customer satisfaction</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">Value</h4>
                            <p class="text-sm text-gray-600">Fair prices for exceptional quality</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Sellers Section -->
        <div class="bg-white py-16" id="bestsellers">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-medium mb-4">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Best Sellers
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Most Popular Products</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Discover our top-selling items loved by customers worldwide
                    </p>
                </div>

                <div v-if="bestSellers && bestSellers.length > 0" class="mt-6 grid grid-cols-1 gap-x-8 gap-y-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4">
                    <div
                        v-for="product in bestSellers"
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
                            
                            <!-- Best Seller Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Best Seller
                                </span>
                            </div>
                            
                            <!-- Sales Count Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="bg-amber-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ product.total_sold || 0 }} sold
                                </span>
                            </div>
                            
                            <!-- Stock Badges -->
                            <div class="absolute top-12 left-3 flex flex-col gap-2">
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
                                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 group-hover:text-red-600 transition-colors duration-200">
                                        <Link :href="route('products.show', product.id)" class="hover:text-red-600">
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
                            <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
                                <button
                                    @click="addToCart(product)"
                                    :disabled="product.quantity === 0"
                                    class="w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg font-medium transition-colors duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center gap-1.5 sm:gap-2 text-sm sm:text-base whitespace-nowrap min-w-0"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <span class="truncate">Add to Cart</span>
                                </button>
                                <Link
                                    :href="route('products.show', product.id)"
                                    class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-800 py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-1.5 sm:gap-2 text-sm sm:text-base whitespace-nowrap min-w-0"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">View</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div v-else class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Best Sellers Yet</h3>
                    <p class="text-gray-500">Check back soon for our most popular products!</p>
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
                    class="mt-6 grid grid-cols-1 gap-x-8 gap-y-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4"
                >
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
                            <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
                                <button
                                    @click="addToCart(product)"
                                    :disabled="product.quantity === 0"
                                    class="w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg font-medium transition-colors duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center gap-1.5 sm:gap-2 text-sm sm:text-base whitespace-nowrap min-w-0"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <span class="truncate">Add to Cart</span>
                                </button>
                                <Link
                                    :href="route('products.show', product.id)"
                                    class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-800 py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-1.5 sm:gap-2 text-sm sm:text-base whitespace-nowrap min-w-0"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">View</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="bg-gradient-to-br from-gray-50 to-amber-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center bg-amber-100 text-amber-800 px-4 py-2 rounded-full text-sm font-medium mb-4">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        Browse Categories
                    </div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Shop by Category</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Explore our curated collections and find exactly what you're looking for
                    </p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div
                        v-for="(category, index) in categories"
                        :key="category.id"
                        class="group relative bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-400 overflow-hidden cursor-pointer transform hover:-translate-y-1"
                    >
                        <Link :href="route('categories.show', category.slug)" class="block">
                            <!-- Category Header with Dynamic Color -->
                            <div 
                                class="h-32 relative overflow-hidden"
                                :class="{
                                    'bg-gradient-to-br from-blue-500 to-blue-600': index % 6 === 0,
                                    'bg-gradient-to-br from-green-500 to-green-600': index % 6 === 1,
                                    'bg-gradient-to-br from-purple-500 to-purple-600': index % 6 === 2,
                                    'bg-gradient-to-br from-red-500 to-red-600': index % 6 === 3,
                                    'bg-gradient-to-br from-indigo-500 to-indigo-600': index % 6 === 4,
                                    'bg-gradient-to-br from-pink-500 to-pink-600': index % 6 === 5
                                }"
                            >
                                <!-- Subtle Pattern Overlay -->
                                <div class="absolute inset-0 opacity-20 bg-repeat" style="background-size: 20px 20px; background-image: linear-gradient(45deg, rgba(255,255,255,0.1) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0.1) 75%, transparent 75%, transparent);"></div>
                                
                                <!-- Category Icon -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                        <svg 
                                            :class="{
                                                'text-blue-200': index % 6 === 0,
                                                'text-green-200': index % 6 === 1,
                                                'text-purple-200': index % 6 === 2,
                                                'text-red-200': index % 6 === 3,
                                                'text-indigo-200': index % 6 === 4,
                                                'text-pink-200': index % 6 === 5
                                            }"
                                            class="w-6 h-6" 
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke="currentColor"
                                        >
                                            <path 
                                                v-if="index % 6 === 0"
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                stroke-width="2" 
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                            />
                                            <path 
                                                v-else-if="index % 6 === 1"
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                stroke-width="2" 
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                            />
                                            <path 
                                                v-else-if="index % 6 === 2"
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                stroke-width="2" 
                                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"
                                            />
                                            <path 
                                                v-else-if="index % 6 === 3"
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                stroke-width="2" 
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                            />
                                            <path 
                                                v-else-if="index % 6 === 4"
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                stroke-width="2" 
                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                            />
                                            <path 
                                                v-else
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                stroke-width="2" 
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Category Content -->
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-amber-600 transition-colors duration-300">
                                        {{ category.name }}
                                    </h3>
                                    <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center group-hover:bg-amber-200 transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-amber-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    Discover our curated collection of {{ category.name.toLowerCase() }} products
                                </p>
                                
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        Explore Category
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-medium mb-4">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        Customer Reviews
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">What Our Customers Say</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Don't just take our word for it - hear from our satisfied customers
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100 relative">
                        <div class="absolute top-4 right-4 text-blue-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                        </div>
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            "Absolutely love my new dining table! The quality is outstanding and it fits perfectly in my space. The delivery team was professional and setup was quick. Highly recommend!"
                        </p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg mr-4">
                                SJ
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Sarah Johnson</h4>
                                <p class="text-sm text-gray-500">Verified Buyer</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100 relative">
                        <div class="absolute top-4 right-4 text-blue-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                        </div>
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            "The bedroom set I purchased exceeded my expectations. Beautiful craftsmanship, fast shipping, and excellent customer service. Will definitely be shopping here again!"
                        </p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-semibold text-lg mr-4">
                                MC
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Michael Chen</h4>
                                <p class="text-sm text-gray-500">Verified Buyer</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100 relative">
                        <div class="absolute top-4 right-4 text-blue-200">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                        </div>
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            "Great experience from start to finish. The website is easy to navigate, prices are competitive, and the furniture quality is exceptional. My living room looks amazing!"
                        </p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-lg mr-4">
                                EP
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Emily Parker</h4>
                                <p class="text-sm text-gray-500">Verified Buyer</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trust Stats -->
                <div class="mt-16 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                        <div>
                            <div class="text-3xl font-bold text-blue-600 mb-2">5+</div>
                            <div class="text-gray-600">Happy Customers</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-blue-600 mb-2">4.2/5</div>
                            <div class="text-gray-600">Average Rating</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-blue-600 mb-2">2+</div>
                            <div class="text-gray-600">Years Experience</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-blue-600 mb-2">93%</div>
                            <div class="text-gray-600">Satisfaction Rate</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
