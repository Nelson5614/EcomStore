<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import {computed, ref, onMounted, onUnmounted} from "vue";

const auth = usePage().props.auth;
const canLogin = usePage().props.canLogin;
const canRegister = usePage().props.canRegister;
const cart = computed(() => usePage().props.cart);
const isMenuOpen = ref(false);
const isUserDropdownOpen = ref(false);

// Close dropdown when clicking outside
const closeUserDropdown = (event) => {
    const dropdown = document.querySelector('.user-dropdown');
    if (dropdown && !dropdown.contains(event.target)) {
        isUserDropdownOpen.value = false;
    }
};

// Add click outside listener
onMounted(() => {
    document.addEventListener('click', closeUserDropdown);
});

// Remove listener when component is destroyed
onUnmounted(() => {
    document.removeEventListener('click', closeUserDropdown);
});
</script>

<template>
    <nav class="bg-white shadow-lg border-b border-gray-200 fixed top-0 left-0 right-0 z-50">
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
                        <span v-if="cart && cart.data && cart.data.count > 0" class="absolute -top-1 -right-1 bg-amber-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ cart.data.count }}
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
                        <div v-else class="relative user-dropdown">
                            <button @click="isUserDropdownOpen = !isUserDropdownOpen" class="flex items-center space-x-2 text-gray-700 hover:text-amber-600 transition-colors focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm font-medium">{{ auth.user.name }}</span>
                                <svg class="w-4 h-4 ml-1" :class="{'rotate-180': isUserDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div v-if="isUserDropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth.user.name }}</p>
                                    <p class="text-sm text-gray-500">{{ auth.user.email }}</p>
                                </div>
                                <Link :href="route('profile.show')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-amber-600 transition-colors">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>Profile</span>
                                    </div>
                                </Link>
                                <Link :href="route('orders.index')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-amber-600 transition-colors">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        <span>My Orders</span>
                                    </div>
                                </Link>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" :action="route('logout')">
                                    <input type="hidden" name="_token" :value="$page.props.csrf_token">
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-50 hover:text-red-700 transition-colors">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            <span>Logout</span>
                                        </div>
                                    </button>
                                </form>
                            </div>
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
