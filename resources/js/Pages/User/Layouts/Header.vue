<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import {computed, ref} from "vue";

const auth = usePage().props.auth;
const canLogin = usePage().props.canLogin;
const canRegister = usePage().props.canRegister;
const cart = computed(() => usePage().props.cart);
const isMenuOpen = ref(false);
</script>

<template>
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <Link :href="route('home')" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-amber-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">FurniStore</span>
                    </Link>
                </div>
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <Link :href="route('home')" class="text-gray-700 hover:text-amber-600 px-3 py-2 text-sm font-medium transition-colors">
                        Home
                    </Link>
                    <Link :href="route('products.index')" class="text-gray-700 hover:text-amber-600 px-3 py-2 text-sm font-medium transition-colors">
                        Products
                    </Link>
                    <Link href="#about" class="text-gray-700 hover:text-amber-600 px-3 py-2 text-sm font-medium transition-colors">
                        About Us
                    </Link>
                    <Link href="#bestsellers" class="text-gray-700 hover:text-amber-600 px-3 py-2 text-sm font-medium transition-colors">
                        Best Sellers
                    </Link>
                </div>

                <!-- Right side buttons -->
                <div class="flex items-center space-x-4">
                    <!-- Cart Button -->
                    <Link :href="route('cart.view')" class="relative p-2 text-gray-700 hover:text-amber-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span v-if="cart && cart.items && cart.items.length > 0" class="absolute -top-1 -right-1 bg-amber-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ cart.items.length }}
                        </span>
                    </Link>

                    <!-- Auth Buttons -->
                    <div v-if="canLogin" class="flex items-center space-x-2">
                        <Link v-if="!auth.user" :href="route('login')" class="text-gray-700 hover:text-amber-600 px-3 py-2 text-sm font-medium transition-colors">
                            Login
                        </Link>
                        <Link v-if="!auth.user && canRegister" :href="route('register')" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Register
                        </Link>
                        <div v-else class="relative">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-amber-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm font-medium">{{ auth.user.name }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <button @click="isMenuOpen = !isMenuOpen" class="md:hidden p-2 text-gray-700 hover:text-amber-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div v-if="isMenuOpen" class="md:hidden bg-white border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <Link :href="route('home')" class="block px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                        Home
                    </Link>
                    <Link :href="route('products.index')" class="block px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                        Products
                    </Link>
                    <Link href="#about" class="block px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                        About Us
                    </Link>
                    <Link href="#bestsellers" class="block px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                        Best Sellers
                    </Link>
                </div>
            </div>
        </div>
    </nav>
</template>
