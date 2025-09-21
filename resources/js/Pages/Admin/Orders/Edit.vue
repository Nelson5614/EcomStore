<template>
  <AdminLayout>
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Order</h1>
          <p class="text-gray-600 dark:text-gray-400">Update order information</p>
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

          <!-- Order Items -->
          <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Order Items</h3>
            <div v-if="order.items && order.items.length > 0">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Product
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Quantity
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Price
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Total
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    <tr v-for="item in order.items" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ item.product?.name || 'Unknown Product' }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ item.product?.sku || '' }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">{{ item.quantity }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">${{ parseFloat(item.price).toFixed(2) }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">${{ (parseFloat(item.price) * item.quantity).toFixed(2) }}</div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                Note: Order items cannot be edited here. This functionality can be added separately.
              </p>
            </div>
            <div v-else class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No items</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This order doesn't have any items.</p>
            </div>
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
              <span v-if="form.processing">Updating...</span>
              <span v-else>Update Order</span>
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
  order: Object,
  errors: Object
});

const form = useForm({
  user_address_id: props.order.user_address_id,
  total: props.order.total,
  status: props.order.status,
  session_id: props.order.session_id
});

const submit = () => {
  form.put(route('admin.orders.update', props.order.id), {
    preserveState: true,
    preserveScroll: true
  });
};
</script>
