<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';

const props = defineProps({
    order: Object,
    payment: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const form = useForm({
    order_id: props.order?.id,
    phone_number: props.payment?.phone_number || '',
    amount: props.order?.total,
});

const processing = ref(false);
const paymentStatus = ref(props.payment?.status || 'pending');
const errorMessage = ref('');

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-LS', {
        style: 'currency',
        currency: 'LSL',
        minimumFractionDigits: 0
    }).format(price);
};

const initiateMpesaPayment = () => {
    processing.value = true;
    errorMessage.value = '';

    form.post(route('payments.mpesa.initiate'), {
        onSuccess: (response) => {
            // Payment initiated successfully
            console.log('Payment initiated:', response);
            // Start polling for payment status
            pollPaymentStatus();
        },
        onError: (errors) => {
            console.error('Payment initiation errors:', errors);
            errorMessage.value = errors.message || 'Failed to initiate M-Pesa payment. Please try again.';
            processing.value = false;
        },
    });
};

const pollPaymentStatus = () => {
    const pollInterval = setInterval(() => {
        // Check payment status via API
        axios.get(route('payments.status', { transactionId: props.payment.transaction_id }))
            .then(response => {
                const status = response.data.status;
                paymentStatus.value = status;

                if (status === 'completed') {
                    clearInterval(pollInterval);
                    processing.value = false;
                    // Redirect to success page
                    window.location.href = route('checkout.success', { order: props.order.id });
                } else if (status === 'failed') {
                    clearInterval(pollInterval);
                    processing.value = false;
                    errorMessage.value = 'Payment failed. Please try again.';
                }
                // Continue polling if still pending
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
                clearInterval(pollInterval);
                processing.value = false;
                errorMessage.value = 'Error checking payment status. Please contact support.';
            });
    }, 5000); // Poll every 5 seconds

    // Stop polling after 10 minutes
    setTimeout(() => {
        clearInterval(pollInterval);
        if (processing.value) {
            processing.value = false;
            errorMessage.value = 'Payment request timed out. Please try again.';
        }
    }, 600000); // 10 minutes
};

// Auto-initiate payment when component mounts
onMounted(() => {
    if (paymentStatus.value === 'pending') {
        initiateMpesaPayment();
    }
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-3xl sm:text-4xl font-bold mb-4">M-Pesa Payment</h1>
                    <p class="text-lg sm:text-xl opacity-90">Complete your payment with M-Pesa</p>
                </div>
            </div>
        </div>

        <!-- Payment Content -->
        <div class="bg-white py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Payment Instructions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Payment Instructions</h2>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                        <span class="text-amber-600 font-semibold text-sm">1</span>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Check Your Phone</h3>
                                        <p class="text-sm text-gray-600">You will receive an M-Pesa payment prompt on your phone</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                        <span class="text-amber-600 font-semibold text-sm">2</span>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Enter Your M-Pesa PIN</h3>
                                        <p class="text-sm text-gray-600">Enter your M-Pesa PIN to authorize the payment</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                        <span class="text-amber-600 font-semibold text-sm">3</span>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Confirm Payment</h3>
                                        <p class="text-sm text-gray-600">Wait for payment confirmation on this page</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-amber-50 rounded-lg">
                                <h4 class="text-sm font-medium text-amber-800 mb-2">Important Notes:</h4>
                                <ul class="text-sm text-amber-700 space-y-1">
                                    <li>• Ensure you have sufficient M-Pesa balance</li>
                                    <li>• Keep this page open until payment is confirmed</li>
                                    <li>• Payment confirmation may take up to 2 minutes</li>
                                    <li>• Do not refresh this page during payment processing</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="space-y-6">
                        <!-- Order Summary -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                            </div>
                            
                            <div class="p-6">
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Order ID:</span>
                                        <span class="font-medium">#{{ order.id }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Payment Method:</span>
                                        <span class="font-medium">M-Pesa</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Phone Number:</span>
                                        <span class="font-medium">{{ payment?.phone_number }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Transaction ID:</span>
                                        <span class="font-medium">{{ payment?.transaction_id }}</span>
                                    </div>
                                    <div class="border-t pt-3">
                                        <div class="flex justify-between">
                                            <span class="text-base font-medium text-gray-900">Total Amount:</span>
                                            <span class="text-lg font-semibold text-amber-600">{{ formatPrice(payment?.amount || order.total) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Payment Status</h2>
                            </div>
                            
                            <div class="p-6">
                                <div v-if="processing" class="text-center">
                                    <div class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing Payment...
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600">Please check your phone for M-Pesa prompt</p>
                                </div>

                                <div v-else-if="paymentStatus === 'completed'" class="text-center">
                                    <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Payment Completed
                                    </div>
                                </div>

                                <div v-else-if="paymentStatus === 'failed'" class="text-center">
                                    <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Payment Failed
                                    </div>
                                </div>

                                <div v-else class="text-center">
                                    <div class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Awaiting Payment
                                    </div>
                                </div>

                                <!-- Error Message -->
                                <div v-if="errorMessage" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-600">{{ errorMessage }}</p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-6 space-y-3">
                                    <button
                                        v-if="paymentStatus === 'pending' && !processing"
                                        @click="initiateMpesaPayment"
                                        :disabled="form.processing"
                                        class="w-full bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 text-white py-3 px-4 rounded-lg font-semibold transition-colors duration-200"
                                    >
                                        {{ form.processing ? 'Processing...' : 'Retry Payment' }}
                                    </button>

                                    <button
                                        @click="cancelPayment"
                                        :disabled="form.processing"
                                        class="w-full border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:bg-gray-100 py-3 px-4 rounded-lg font-medium transition-colors duration-200"
                                    >
                                        Cancel Payment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
