<template>
  <AdminLayout>
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order Details</h1>
          <p class="text-gray-600 dark:text-gray-400">View order information and items</p>
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

      <!-- Order Information -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Order Information</h2>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">General Information</h3>
                  <dl class="space-y-2">
                    <div>
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Order ID</dt>
                      <dd class="text-sm text-gray-900 dark:text-white">#{{ order.id }}</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                      <dd>
                        <span
                          :class="getStatusClass(order.status)"
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        >
                          {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
                        </span>
                      </dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</dt>
                      <dd class="text-sm text-gray-900 dark:text-white">${{ parseFloat(order.total).toFixed(2) }}</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Session ID</dt>
                      <dd class="text-sm text-gray-900 dark:text-white">{{ order.session_id }}</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                      <dd class="text-sm text-gray-900 dark:text-white">{{ new Date(order.created_at).toLocaleDateString() }}</dd>
                    </div>
                  </dl>
                </div>

                <div>
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Customer Information</h3>
                  <dl class="space-y-2">
                    <div>
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer Name</dt>
                      <dd class="text-sm text-gray-900 dark:text-white">{{ order.createdBy?.name || 'Unknown' }}</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                      <dd class="text-sm text-gray-900 dark:text-white">{{ order.createdBy?.email || '' }}</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer ID</dt>
                      <dd class="text-sm text-gray-900 dark:text-white">{{ order.createdBy?.id || 'N/A' }}</dd>
                    </div>
                  </dl>
                </div>
              </div>

              <!-- Shipping Address -->
              <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Shipping Address</h3>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                  <div v-if="order.userAddress" class="text-sm text-gray-900 dark:text-white">
                    <p>{{ order.userAddress.address }}</p>
                    <p>{{ order.userAddress.city }}, {{ order.userAddress.state }} {{ order.userAddress.zip }}</p>
                    <p>{{ order.userAddress.country }}</p>
                  </div>
                  <p v-else class="text-sm text-gray-500 dark:text-gray-400">No shipping address available</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div>
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Actions</h3>
              <div class="space-y-3">
                <Link
                  :href="route('admin.orders.edit', order.id)"
                  class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                  Edit Order
                </Link>
                <button
                  @click="deleteOrder"
                  class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                  Delete Order
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Items -->
      <div class="mt-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Order Items</h2>
            
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
            </div>
            <div v-else class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No items</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This order doesn't have any items yet.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Pages/Components/AdminLAyout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
  order: Object
});

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    processing: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    completed: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    cancelled: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
  };
  return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
};

const deleteOrder = () => {
  if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
    router.delete(route('admin.orders.destroy', order.id));
  }
};
</script>
