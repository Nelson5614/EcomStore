<template>
  <AdminLayout>
    <div class="p-4 sm:p-6">
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Best Selling Products</h1>
        <p class="text-gray-600 mt-2 text-sm sm:text-base">View and manage your top-performing products based on sales data.</p>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-green-500">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-green-100 mr-3 sm:mr-4">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600">Total Revenue</p>
              <p class="text-xl sm:text-2xl font-bold text-gray-900">${{ formatCurrency(statistics.totalRevenue) }}</p>
            </div>
          </div>
        </div>

        <!-- Total Items Sold -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-blue-500">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-blue-100 mr-3 sm:mr-4">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
              </svg>
            </div>
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600">Total Items Sold</p>
              <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ statistics.totalItemsSold }}</p>
            </div>
          </div>
        </div>

        <!-- Products Sold -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-purple-500">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-purple-100 mr-3 sm:mr-4">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
              </svg>
            </div>
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600">Products Sold</p>
              <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ statistics.totalProductsSold }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters and Actions -->
      <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <!-- Date Range Filter -->
          <div class="flex flex-col sm:flex-row gap-4">
            <div>
              <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
              <select
                id="date_range"
                v-model="filters.date_range"
                @change="applyFilters"
                class="block w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              >
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">Last 7 Days</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
              </select>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-2">
            <Link
              :href="route('admin.bestsellers.create')"
              class="inline-flex items-center px-3 sm:px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Feature Product
            </Link>
          </div>
        </div>
      </div>

      <!-- Best Sellers Table -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Top Selling Products</h2>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold</th>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="item in bestSellers.data" :key="item.product_id" class="hover:bg-gray-50">
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ item.product?.title || 'Unknown' }}</div>
                      <div class="text-sm text-gray-500">ID: {{ item.product_id }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ item.product?.brand?.name || 'N/A' }}</div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ item.product?.category?.name || 'N/A' }}</div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ item.total_sold }}
                  </div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  ${{ formatCurrency(item.total_revenue) }}
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <Link
                    :href="route('admin.bestsellers.show', item.product_id)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    View
                  </Link>
                  <Link
                    :href="route('admin.bestsellers.edit', item.product_id)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </Link>
                </td>
              </tr>
              <tr v-if="bestSellers.data.length === 0">
                <td colspan="6" class="px-4 sm:px-6 py-8 text-center text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <p class="mt-2 text-sm">No best selling products found.</p>
                  <p class="text-xs text-gray-400">Products will appear here once they have sales data.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="bestSellers.data.length > 0" class="bg-white px-4 sm:px-6 py-3 border-t border-gray-200 sm:px-6">
          <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <Link
                :href="bestSellers.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': !bestSellers.prev_page_url }"
                :disabled="!bestSellers.prev_page_url"
              >
                Previous
              </Link>
              <Link
                :href="bestSellers.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': !bestSellers.next_page_url }"
                :disabled="!bestSellers.next_page_url"
              >
                Next
              </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{ bestSellers.from }}</span>
                  to
                  <span class="font-medium">{{ bestSellers.to }}</span>
                  of
                  <span class="font-medium">{{ bestSellers.total }}</span>
                  results
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <Link
                    v-for="link in bestSellers.links"
                    :key="link.label"
                    :href="link.url"
                    v-html="link.label"
                    class="relative inline-flex items-center px-3 py-2 border text-sm font-medium"
                    :class="{
                      'z-10 bg-blue-50 border-blue-500 text-blue-600': link.active,
                      'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active && link.url,
                      'bg-gray-100 border-gray-300 text-gray-400 cursor-not-allowed': !link.url
                    }"
                  />
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from "@/Pages/Components/AdminLAyout.vue";

const props = defineProps({
  bestSellers: Object,
  statistics: Object,
  filters: Object,
});

const formatCurrency = (amount) => {
  return parseFloat(amount || 0).toFixed(2);
};

const applyFilters = () => {
  const params = new URLSearchParams(window.location.search);
  if (props.filters.date_range) {
    params.set('date_range', props.filters.date_range);
  } else {
    params.delete('date_range');
  }
  
  window.location.href = `${window.location.pathname}?${params.toString()}`;
};
</script>
