<template>
  <AdminLayout>
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Order</h1>
          <p class="text-gray-600 dark:text-gray-400">Add a new order to the system</p>
        </div>
        <Link
          :href="route('admin.orders.index')"
          class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Back to Orders
        </Link>
      </div>

      <!-- Form -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form @submit.prevent="submit" class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Address Selection -->
            <div>
              <label for="user_address_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Shipping Address *
              </label>
              <select
                id="user_address_id"
                v-model="form.user_address_id"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                :class="{ 'border-red-500': errors.user_address_id }"
              >
                <option value="">Select a shipping address</option>
                <option v-for="address in userAddresses" :key="address.id" :value="address.id">
                  {{ address.address }}, {{ address.city }} ({{ address.user?.name }})
                </option>
              </select>
              <p v-if="errors.user_address_id" class="mt-1 text-sm text-red-600">{{ errors.user_address_id }}</p>
            </div>

            <!-- Total Amount -->
            <div>
              <label for="total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Total Amount *
              </label>
              <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">$</span>
                <input
                  type="number"
                  id="total"
                  v-model="form.total"
                  step="0.01"
                  min="0"
                  class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                  :class="{ 'border-red-500': errors.total }"
                  placeholder="0.00"
                />
              </div>
              <p v-if="errors.total" class="mt-1 text-sm text-red-600">{{ errors.total }}</p>
            </div>

            <!-- Status -->
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Status *
              </label>
              <select
                id="status"
                v-model="form.status"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                :class="{ 'border-red-500': errors.status }"
              >
                <option value="">Select status</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
              <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</p>
            </div>
          </div>

          <!-- Session ID -->
          <div class="mt-6">
            <label for="session_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Session ID *
            </label>
            <input
              type="text"
              id="session_id"
              v-model="form.session_id"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
              :class="{ 'border-red-500': errors.session_id }"
              placeholder="Enter session ID"
            />
            <p v-if="errors.session_id" class="mt-1 text-sm text-red-600">{{ errors.session_id }}</p>
          </div>

          <!-- Order Items (Optional - you can extend this) -->
          <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Order Items</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
              Note: Order items can be added after creating the order. This is a basic order creation form.
            </p>
          </div>

          <!-- Submit Button -->
          <div class="mt-6 flex justify-end space-x-3">
            <Link
              :href="route('admin.orders.index')"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="form.processing">Creating...</span>
              <span v-else>Create Order</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Pages/Components/AdminLAyout.vue';
import { Link, useForm } from '@inertiajs/vue3';

// Mock user addresses data - in a real application, you would fetch this from the backend
const userAddresses = [
  { id: 1, address: '123 Main St', city: 'New York', user: { name: 'John Doe' } },
  { id: 2, address: '456 Oak Ave', city: 'Los Angeles', user: { name: 'Jane Smith' } },
  { id: 3, address: '789 Pine Rd', city: 'Chicago', user: { name: 'Bob Johnson' } }
];

defineProps({
  errors: Object
});

const form = useForm({
  user_address_id: '',
  total: '',
  status: '',
  session_id: ''
});

const submit = () => {
  form.post(route('admin.orders.store'), {
    preserveState: true,
    preserveScroll: true
  });
};
</script>
