<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    cartItems: Array,
    userAddresses: Array,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const form = useForm({
    user_address_id: '',
    payment_method: 'mpesa',
    phone_number: '',
});

const selectedAddress = ref(null);

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


const handleAddressChange = (addressId) => {
    form.user_address_id = addressId;
    selectedAddress.value = props.userAddresses.find(addr => addr.id === addressId);
};


const handleAddAddress = () => {
    if (!user.value) {
        alert('You should login or register before checkout');
        return;
    }
    // If user is logged in, proceed to address creation page
    window.location.href = route('addresses.create');
};

const submitOrder = () => {
    console.log('Submit order called');
    console.log('Form data:', form.data());
    
    if (!form.user_address_id) {
        alert('Please select an address');
        return;
    }

    if (!form.phone_number) {
        alert('Please enter your M-Pesa phone number');
        return;
    }

    console.log('Submitting form to:', route('checkout.process'));
    
    form.post(route('checkout.process'), {
        onSuccess: (response) => {
            console.log('Order processed successfully:', response);
            // The redirect will be handled by the server
        },
        onError: (errors) => {
            console.error('Order processing errors:', errors);
            // Show specific error messages
            let errorMessage = 'There was an error processing your order.\n\n';
            for (const [key, value] of Object.entries(errors)) {
                errorMessage += `${key}: ${value}\n`;
            }
            alert(errorMessage);
        },
        onFinish: () => {
            console.log('Form submission finished');
        }
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-3xl sm:text-4xl font-bold mb-4">Checkout</h1>
                    <p class="text-lg sm:text-xl opacity-90">Complete your order</p>
                </div>
            </div>
        </div>

        <!-- Login Warning Banner -->
        <div v-if="!user" class="bg-amber-50 border-l-4 border-amber-400 p-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-700">
                            <strong>Login Required!</strong> You need to <Link :href="route('login')" class="font-medium text-amber-800 hover:text-amber-900">login</Link> or <Link :href="route('register')" class="font-medium text-amber-800 hover:text-amber-900">register</Link> to complete your checkout and save addresses for future orders.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Content -->
        <div class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="cartItems && cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Checkout Form -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Address Selection -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Delivery Address</h2>
                            </div>
                            
                            <div class="p-6">
                                <div v-if="userAddresses && userAddresses.length > 0" class="space-y-4">
                                    <div v-for="address in userAddresses" :key="address.id" class="border rounded-lg p-4 cursor-pointer hover:border-amber-500 transition-colors"
                                         :class="{ 'border-amber-500 bg-amber-50': form.user_address_id === address.id }"
                                         @click="handleAddressChange(address.id)">
                                        <div class="flex items-start">
                                            <input type="radio" :value="address.id" v-model="form.user_address_id" 
                                                   class="mt-1 h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300">
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ address.address }}</p>
                                                <p class="text-sm text-gray-600">{{ address.city }}, {{ address.state }} {{ address.zip }}</p>
                                                <p class="text-sm text-gray-600">{{ address.country }}</p>
                                                <p class="text-sm text-gray-600">Phone: {{ address.phone }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No addresses found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Please add a delivery address to continue.</p>
                                    <div class="mt-6">
                                        <button @click="handleAddAddress" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700">
                                            Add Address
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Payment Method</h2>
                            </div>
                            
                            <div class="p-6 space-y-4">
                                <div class="border rounded-lg p-4 cursor-pointer border-amber-500 bg-amber-50">
                                    <div class="flex items-center">
                                        <input type="radio" value="mpesa" v-model="form.payment_method"
                                               class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300" checked>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">M-Pesa</p>
                                            <p class="text-sm text-gray-600">Pay with M-Pesa mobile money</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- M-Pesa Phone Number Field -->
                            <div class="px-6 pb-6">
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    M-Pesa Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="tel"
                                    id="phone_number"
                                    v-model="form.phone_number"
                                    placeholder="Enter your M-Pesa phone number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                    :class="{ 'border-red-500': form.errors.phone_number }"
                                    required
                                >
                                <p v-if="form.errors.phone_number" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.phone_number }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Enter your M-Pesa registered phone number (e.g., 58888888)
                                </p>
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
                                <!-- Cart Items -->
                                <div class="space-y-3">
                                    <div v-for="item in cartItems" :key="item.id" class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-lg overflow-hidden">
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
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ item.product.title }}</p>
                                            <p class="text-sm text-gray-600">Qty: {{ item.quantity }}</p>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ formatPrice(item.product.price * item.quantity) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t pt-4 space-y-4">
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
                                </div>

                                <!-- Place Order Button -->
                                <button
                                    @click="submitOrder"
                                    :disabled="form.processing"
                                    class="w-full bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 text-white py-3 px-4 rounded-lg font-semibold transition-colors duration-200"
                                >
                                    {{ form.processing ? 'Processing...' : 'Place Order' }}
                                </button>

                                <!-- Back to Cart -->
                                <Link
                                    :href="route('cart.view')"
                                    class="w-full border border-gray-300 text-gray-700 hover:bg-gray-50 py-3 px-4 rounded-lg font-medium transition-colors duration-200 text-center block"
                                >
                                    Back to Cart
                                </Link>
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
                    <p class="mt-1 text-gray-500">Add some products to your cart to continue.</p>
                    <div class="mt-6">
                        <Link
                            :href="route('home')"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700"
                        >
                            Continue Shopping
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
