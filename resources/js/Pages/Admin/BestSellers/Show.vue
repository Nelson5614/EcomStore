<template>
  <AdminLayout>
    <div class="p-4 sm:p-6">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Best Seller Details</h1>
        <p class="text-gray-600 mt-2 text-sm sm:text-base">View detailed sales information for this product.</p>
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
              <span class="text-gray-500 ml-1 md:ml-2">Product Details</span>
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
            <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <p class="text-sm font-medium text-gray-500">Price</p>
                <p class="text-lg font-semibold text-gray-900">{{ formatCurrency(product.price) }}</p>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Stock</p>
                <p class="text-lg font-semibold text-gray-900">{{ product.quantity }} units</p>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Status</p>
                <span :class="getStockStatusClass(product)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                  {{ getStockStatus(product) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6">
        <!-- Total Sold -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-green-500">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-green-100 mr-3 sm:mr-4">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600">Total Units Sold</p>
              <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ statistics.totalSold }}</p>
            </div>
          </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-blue-500">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-blue-100 mr-3 sm:mr-4">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600">Total Revenue</p>
              <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ formatCurrency(statistics.totalRevenue) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Monthly Sales Chart -->
      <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Sales Trend (Last 12 Months)</h3>
        <div class="space-y-3">
          <div v-for="(data, index) in monthlySales" :key="index" class="flex items-center">
            <div class="w-24 text-sm text-gray-600">{{ data.month }}</div>
            <div class="flex-1 mx-4">
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div
                  class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                  :style="{ width: getSalesPercentage(data.sales) + '%' }"
                ></div>
              </div>
            </div>
            <div class="w-16 text-sm font-medium text-gray-900 text-right">{{ data.sales }}</div>
          </div>
        </div>
      </div>

      <!-- Recent Sales -->
      <div class="bg-white rounded-lg shadow-md">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Recent Sales</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="sale in salesData" :key="sale.id" class="hover:bg-gray-50">
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  #{{ sale.order?.id || 'N/A' }}
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(sale.created_at) }}
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ sale.quantity }}
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatCurrency(sale.price) }}
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ formatCurrency(sale.price * sale.quantity) }}
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <span :class="getStatusColor(sale.order?.status)" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium capitalize">
                    {{ sale.order?.status || 'Unknown' }}
                  </span>
                </td>
              </tr>
              <tr v-if="salesData.length === 0">
                <td colspan="6" class="px-4 sm:px-6 py-8 text-center text-gray-500">
                  No sales data available for this product.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Actions -->
      <div class="mt-6 flex justify-end space-x-3">
        <Link
          :href="route('admin.bestsellers.index')"
          class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Back to Best Sellers
        </Link>
        <Link
          :href="route('admin.bestsellers.edit', product.id)"
          class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Edit Settings
        </Link>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from "@/Pages/Components/AdminLAyout.vue";

const props = defineProps({
  product: Object,
  salesData: Array,
  statistics: Object,
  monthlySales: Array,
});

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-LS', {
    style: 'currency',
    currency: 'LSL',
    minimumFractionDigits: 0
  }).format(amount || 0);
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

const getStockStatus = (product) => {
  if (product.quantity === 0) return 'Out of Stock';
  if (product.quantity < 10) return 'Low Stock';
  return 'In Stock';
};

const getStockStatusClass = (product) => {
  if (product.quantity === 0) return 'bg-red-100 text-red-800';
  if (product.quantity < 10) return 'bg-yellow-100 text-yellow-800';
  return 'bg-green-100 text-green-800';
};

const getStatusColor = (status) => {
  const colors = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    shipped: 'bg-purple-100 text-purple-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
  };
  return colors[status] || 'bg-gray-100 text-gray-800';
};

const getSalesPercentage = (sales) => {
  const maxSales = Math.max(...props.monthlySales.map(m => m.sales));
  return maxSales > 0 ? (sales / maxSales) * 100 : 0;
};
</script>
