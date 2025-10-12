<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, reactive } from 'vue';

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
    // Open confirmation modal instead of full navigation
    openPaymentModal();
};

// ----- Payment Modal State & Actions -----
const showPaymentModal = ref(false);
const modal = reactive({
    loading: false,
    step: 'confirm', // confirm | result
    message: '',
    code: null,
    mpesa: null,
    error: null,
});
const editedPhone = ref('');

const mpesaLogoUrl = '/images/mpesa.png';

const csrfToken = () => {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute('content') : '';
};

const openPaymentModal = () => {
    editedPhone.value = form.phone_number || '';
    modal.loading = false;
    modal.step = 'confirm';
    modal.message = '';
    modal.code = null;
    modal.mpesa = null;
    modal.error = null;
    showPaymentModal.value = true;
};

const closePaymentModal = () => {
    showPaymentModal.value = false;
};

const createOrderAjax = async () => {
    const res = await fetch(route('checkout.process'), {
        method: 'POST',
        credentials: 'same-origin', // include session cookies
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken(), // correct header name
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(form.data()),
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok || !data.success) {
        throw { message: data.message || 'Failed to create order', errors: data.errors || null };
    }
    return data; // { success, order_id, payment_id, amount }
};

const initiatePaymentAjax = async (paymentId) => {
    const res = await fetch(route('payments.initiate'), {
        method: 'POST',
        credentials: 'same-origin', // include session cookies
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken(), // correct header name
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ payment_id: paymentId, phone_number: editedPhone.value }),
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
        throw { message: data.message || 'Failed to initiate payment', code: data.response_code || null, error: data.error || null };
    }
    return data; // includes response_code/desc and mpesa
};

const confirmMpesaInModal = async () => {
    try {
        modal.loading = true;
        modal.step = 'confirm';
        modal.message = 'Preparing payment...';
        // 1) Create order & payment via JSON
        const orderResp = await createOrderAjax();
        modal.message = 'Please check your phone and enter your PIN...';
        // 2) Initiate payment via JSON
        const payResp = await initiatePaymentAjax(orderResp.payment_id);
        modal.loading = false;
        modal.step = 'result';
        modal.code = payResp.response_code || (payResp.mpesa && payResp.mpesa.output_ResponseCode) || null;
        modal.message = payResp.response_desc || (payResp.mpesa && payResp.mpesa.output_ResponseDesc) || 'Payment initiated.';
        modal.mpesa = payResp.mpesa || null;

        // Auto-close on INS-0 (success)
        if (modal.code === 'INS-0') {
            // Briefly show success then close
            setTimeout(() => {
                closePaymentModal();
            }, 1500);
        }
    } catch (e) {
        modal.loading = false;
        modal.step = 'result';
        modal.code = e.code || null;
        modal.error = e.error || null;
        modal.message = e.message || 'Something went wrong';
    }
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
        
        <!-- Payment Confirmation Modal (moved inside the main template to keep a single <template>) -->
        <div v-if="showPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white w-full max-w-lg rounded-xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="flex items-center gap-5 px-6 py-4 border-b">
                    <!-- Larger logo without tight circular container -->
                    <img v-if="mpesaLogoUrl" :src="mpesaLogoUrl" alt="M-Pesa" class="h-16 md:h-24 w-auto object-contain" @error="$event.target.style.display='none'"/>
                    <span class="text-emerald-700 font-bold text-3xl" v-else>M‑Pesa</span>
                    <h3 class="text-xl md:text-2xl font-semibold text-gray-900">Confirm M-Pesa Payment</h3>
                    <button class="ml-auto text-gray-500 hover:text-gray-700" @click="closePaymentModal">✕</button>
                </div>

                <!-- Body -->
                <div class="px-6 py-5 space-y-4">
                    <!-- Phone input (editable) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">M-Pesa Phone Number</label>
                        <input v-model="editedPhone" type="tel" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500" placeholder="Enter your M-Pesa number"/>
                        <p class="text-xs text-gray-500 mt-1">You'll receive a prompt on this number to enter your M-Pesa PIN.</p>
                    </div>

                    <!-- Status/result -->
                    <div v-if="modal.step === 'confirm'">
                        <div v-if="modal.loading" class="flex items-center gap-2 text-amber-700">
                            <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>{{ modal.message || 'Preparing payment...' }}</span>
                        </div>
                        <div v-else class="text-sm text-gray-700">
                            Confirm your phone number and press "Confirm & Pay". Check your phone for the M-Pesa prompt and enter your PIN.
                        </div>
                    </div>

                    <div v-else-if="modal.step === 'result'">
                        <div :class="['px-3 py-2 rounded-md text-sm', modal.code === 'INS-0' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200']">
                            <div class="font-medium">{{ modal.code ? `Response: ${modal.code}` : 'Response' }}</div>
                            <div class="mt-1">{{ modal.message }}</div>
                        </div>
                        <div v-if="modal.error" class="mt-2 text-xs text-red-600">{{ modal.error }}</div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t flex items-center gap-3 justify-end">
                    <button class="px-4 py-2 rounded-md border text-gray-700 hover:bg-gray-100" @click="closePaymentModal">Close</button>
                    <button v-if="modal.step === 'confirm' && !modal.loading" class="px-4 py-2 rounded-md bg-amber-600 hover:bg-amber-700 text-white" @click="confirmMpesaInModal">Confirm & Pay</button>
                    <button v-if="modal.loading" disabled class="px-4 py-2 rounded-md bg-gray-400 text-white">Processing...</button>
                </div>
            </div>
        </div>
        </div>
    </div>
</template>
