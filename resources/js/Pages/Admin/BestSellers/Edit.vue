<template>
  <AdminLayout>
    <div class="p-4 sm:p-6">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Best Seller Settings</h1>
        <p class="text-gray-600 mt-2 text-sm sm:text-base">Manage best seller settings for this product.</p>
      </div>

      <!-- Breadcrumb -->
      <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
          <li class="inline-flex items-center">
            <Link :href="route('admin.bestsellers.index')" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
              </svg>
              Best Sellers
            </Link>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
              </svg>
              <span class="text-gray-500 ml-1 md:ml-2">Edit Settings</span>
            </div>
          </li>
        </ol>
      </nav>

      <!-- Product Information -->
      <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
        <div class="flex items-start space-x-4">
          <div class="flex-shrink-0">
            <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h2 class="text-xl font-semibold text-gray-900">{{ product.title }}</h2>
            <p class="text-gray-600">{{ product.brand?.name }} • {{ product.category?.name }}</p>
            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <p class="text-sm font-medium text-gray-500">Price</p>
                <p class="text-lg font-semibold text-gray-900">{{ formatCurrency(product.price) }}</p>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Stock</p>
                <p class="text-lg font-semibold text-gray-900">{{ product.quantity }} units</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-lg shadow-md">
        <form @submit.prevent="submit" class="p-4 sm:p-6">
          <!-- Featured Until -->
          <div class="mb-6">
            <label for="featured_until" class="block text-sm font-medium text-gray-700 mb-2">
              Featured Until (Optional)
            </label>
            <input
              type="date"
              id="featured_until"
              v-model="form.featured_until"
              :min="minDate"
              :class="{ 'border-red-500': errors.featured_until }"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
            <p class="mt-1 text-sm text-gray-500">Leave empty to feature indefinitely</p>
            <p v-if="errors.featured_until" class="mt-1 text-sm text-red-600">{{ errors.featured_until }}</p>
          </div>

          <!-- Priority -->
          <div class="mb-6">
            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
              Display Priority (Optional)
            </label>
            <input
              type="number"
              id="priority"
              v-model="form.priority"
              min="1"
              max="100"
              :class="{ 'border-red-500': errors.priority }"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              placeholder="Enter priority (1-100)"
            >
            <p class="mt-1 text-sm text-gray-500">Higher numbers appear first in best seller lists</p>
            <p v-if="errors.priority" class="mt-1 text-sm text-red-600">{{ errors.priority }}</p>
          </div>

          <!-- Status Information -->
          <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <h3 class="text-sm font-medium text-blue-900 mb-2">Current Status</h3>
            <div class="space-y-2 text-sm text-blue-800">
              <p><strong>Product:</strong> {{ product.title }}</p>
              <p><strong>Current Sales Rank:</strong> This product is automatically ranked based on sales data</p>
              <p><strong>Manual Override:</strong> You can override the automatic ranking using the settings above</p>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex justify-between">
            <div>
              <button
                type="button"
                @click="confirmRemove"
                class="px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
              >
                Remove from Best Sellers
              </button>
            </div>
            <div class="flex space-x-3">
              <Link
                :href="route('admin.bestsellers.show', product.id)"
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="form.processing" class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Processing...
                </span>
                <span v-else>Update Settings</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from "@/Pages/Components/AdminLAyout.vue";

const props = defineProps({
  product: Object,
  errors: Object,
});

const form = useForm({
  featured_until: '',
  priority: '',
});

const minDate = computed(() => {
  const today = new Date();
  return today.toISOString().split('T')[0];
});

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-LS', {
    style: 'currency',
    currency: 'LSL',
    minimumFractionDigits: 0
  }).format(amount || 0);
};

const submit = () => {
  form.put(route('admin.bestsellers.update', props.product.id), {
    preserveState: true,
    preserveScroll: true,
  });
};

const confirmRemove = () => {
  if (confirm('Are you sure you want to remove this product from best sellers? This action cannot be undone.')) {
    router.delete(route('admin.bestsellers.destroy', props.product.id));
  }
};
</script>
