<script setup>
import { Link, usePage, router } from "@inertiajs/vue3";
import {computed, ref, onMounted, onUnmounted} from "vue";

const auth = usePage().props.auth;
const canLogin = usePage().props.canLogin;
const canRegister = usePage().props.canRegister;
const cart = computed(() => usePage().props.cart);
const isMenuOpen = ref(false);
const isUserDropdownOpen = ref(false);

const logout = () => {
    router.post(route('logout'));
};

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
                        <span class="text-2xl font-bold text-gray-900">TlaliFurn</span>
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
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Cart Button (Desktop) -->
                    <Link :href="route('cart.view')" class="hidden md:block md:relative md:p-2 md:text-gray-700 md:hover:text-amber-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span v-if="cart && cart.data && cart.data.count > 0" class="absolute -top-1 -right-1 bg-amber-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ cart.data.count }}
                        </span>
                    </Link>

                    <!-- Auth Buttons (Desktop) -->
                    <div v-if="canLogin" class="hidden md:flex items-center space-x-2">
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
                                <span class="text-sm font-medium hidden lg:block">{{ auth.user.name }}</span>
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
                                <button @click="logout" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-50 hover:text-red-700 transition-colors">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Logout</span>
                                    </div>
                                </button>
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
                    <!-- Navigation Links -->
                    <div class="space-y-1">
                        <Link :href="route('home')" class="flex items-center px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Home
                        </Link>
                        <Link :href="route('products.index')" class="flex items-center px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Products
                        </Link>
                        <Link href="#about" class="flex items-center px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            About Us
                        </Link>
                        <Link href="#bestsellers" class="flex items-center px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Best Sellers
                        </Link>
                    </div>
                    
                    <!-- Mobile Cart Button -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <Link :href="route('cart.view')" class="flex items-center justify-between w-full px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Cart
                            </div>
                            <span v-if="cart && cart.data && cart.data.count > 0" class="bg-amber-600 text-white text-xs rounded-full px-2 py-1">
                                {{ cart.data.count }}
                            </span>
                        </Link>
                    </div>
                    
                    <!-- Mobile Auth Section -->
                    <div v-if="canLogin" class="border-t border-gray-200 pt-4 mt-4">
                        <div v-if="!auth.user" class="space-y-2">
                            <Link :href="route('login')" class="flex items-center px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Login
                            </Link>
                            <Link v-if="canRegister" :href="route('register')" class="flex items-center px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-md text-base font-medium">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                Register
                            </Link>
                        </div>
                        <div v-else class="space-y-2">
                            <div class="px-3 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ auth.user.name }}</p>
                                <p class="text-xs text-gray-500">{{ auth.user.email }}</p>
                            </div>
                            <Link :href="route('profile.show')" class="flex items-center px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </Link>
                            <Link :href="route('orders.index')" class="flex items-center px-3 py-2 text-gray-700 hover:text-amber-600 hover:bg-gray-50 rounded-md text-base font-medium">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                My Orders
                            </Link>
                            <button @click="logout" class="w-full flex items-center px-3 py-2 text-red-600 hover:bg-gray-50 hover:text-red-700 rounded-md text-base font-medium">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>
