<script setup>
import UserLayout from "../Layouts/UserLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";

const form = useForm({
    type: 'home',
    address: '',
    city: '',
    district: '', // Changed from 'state' to 'district' for Lesotho
    zip: '',
    country: 'Lesotho', // Set default country to Lesotho
});

const submitAddress = () => {
    form.post(route('addresses.store'), {
        onSuccess: () => {
            // Address added successfully, will be redirected to checkout
        },
        onError: (errors) => {
            console.error('Error adding address:', errors);
        },
    });
};

const goBack = () => {
    router.visit(route('checkout.index'));
};
</script>

<template>
    <UserLayout>
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-3xl sm:text-4xl font-bold mb-4">Add New Address</h1>
                    <p class="text-lg sm:text-xl opacity-90">Enter your shipping address details</p>
                </div>
            </div>
        </div>

        <!-- Address Form -->
        <div class="bg-white py-12">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Address Information</h2>
                    </div>
                    
                    <form @submit.prevent="submitAddress" class="p-6 space-y-6">
                        <!-- Address Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Address Type
                            </label>
                            <select
                                id="type"
                                v-model="form.type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500"
                                :class="{ 'border-red-500': form.errors.type }"
                            >
                                <option value="home">Home</option>
                                <option value="work">Work</option>
                                <option value="other">Other</option>
                            </select>
                            <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">
                                {{ form.errors.type }}
                            </p>
                        </div>

                        <!-- Street Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Street Address *
                            </label>
                            <input
                                type="text"
                                id="address"
                                v-model="form.address"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500"
                                placeholder="123 Main Street"
                                :class="{ 'border-red-500': form.errors.address }"
                            />
                            <p v-if="form.errors.address" class="mt-1 text-sm text-red-600">
                                {{ form.errors.address }}
                            </p>
                        </div>

                        <!-- City and State Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                    City *
                                </label>
                                <input
                                    type="text"
                                    id="city"
                                    v-model="form.city"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Maseru"
                                    :class="{ 'border-red-500': form.errors.city }"
                                />
                                <p v-if="form.errors.city" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.city }}
                                </p>
                            </div>

                            <!-- District -->
                            <div>
                                <label for="district" class="block text-sm font-medium text-gray-700 mb-2">
                                    District *
                                </label>
                                <select
                                    id="district"
                                    v-model="form.district"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500"
                                    :class="{ 'border-red-500': form.errors.district }"
                                >
                                    <option value="">Select District</option>
                                    <option value="Berea">Berea</option>
                                    <option value="Butha-Buthe">Butha-Buthe</option>
                                    <option value="Leribe">Leribe</option>
                                    <option value="Mafeteng">Mafeteng</option>
                                    <option value="Maseru">Maseru</option>
                                    <option value="Mohale's Hoek">Mohale's Hoek</option>
                                    <option value="Mokhotlong">Mokhotlong</option>
                                    <option value="Qacha's Nek">Qacha's Nek</option>
                                    <option value="Quthing">Quthing</option>
                                    <option value="Thaba-Tseka">Thaba-Tseka</option>
                                </select>
                                <p v-if="form.errors.district" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.district }}
                                </p>
                            </div>
                        </div>

                        <!-- ZIP Code and Country Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- ZIP Code -->
                            <div>
                                <label for="zip" class="block text-sm font-medium text-gray-700 mb-2">
                                    ZIP Code *
                                </label>
                                <input
                                    type="text"
                                    id="zip"
                                    v-model="form.zip"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="100"
                                    :class="{ 'border-red-500': form.errors.zip }"
                                />
                                <p v-if="form.errors.zip" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.zip }}
                                </p>
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                    Country *
                                </label>
                                <select
                                    id="country"
                                    v-model="form.country"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500"
                                    :class="{ 'border-red-500': form.errors.country }"
                                    disabled
                                >
                                    <option value="Lesotho">Lesotho</option>
                                </select>
                                <p v-if="form.errors.country" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.country }}
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="flex-1 bg-amber-600 hover:bg-amber-700 text-white py-3 px-4 rounded-lg font-semibold transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span v-if="form.processing">Adding Address...</span>
                                <span v-else>Add Address</span>
                            </button>
                            
                            <button
                                type="button"
                                @click="goBack"
                                class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 py-3 px-4 rounded-lg font-medium transition-colors duration-200"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
