
<template>
  <AdminLayout>
    <div class="p-4 sm:p-6">
      <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard Overview</h1>
        <p class="text-gray-600 mt-2 text-sm sm:text-base">Welcome back! Here's what's happening with your store today.</p>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-blue-500">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-blue-100 mr-3 sm:mr-4">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
              </svg>
            </div>
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600">Total Products</p>
              <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ statistics.totalProducts }}</p>
            </div>
          </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-green-500">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-green-100 mr-3 sm:mr-4">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
              </svg>
            </div>
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600">Total Orders</p>
              <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ statistics.totalOrders }}</p>
              <p class="text-xs text-gray-500">{{ statistics.ordersThisMonth }} this month</p>
            </div>
          </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-purple-500">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-purple-100 mr-3 sm:mr-4">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
              </svg>
            </div>
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600">Total Users</p>
              <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ statistics.totalUsers }}</p>
              <p class="text-xs text-gray-500">{{ statistics.newUsersThisMonth }} new this month</p>
            </div>
          </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-yellow-500">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 rounded-full bg-yellow-100 mr-3 sm:mr-4">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <p class="text-xs sm:text-sm font-medium text-gray-600">Monthly Revenue</p>
              <p class="text-xl sm:text-2xl font-bold text-gray-900">${{ formatCurrency(statistics.revenueThisMonth) }}</p>
              <p class="text-xs text-gray-500">This month</p>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Monthly Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-4 sm:p-6">
          <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Monthly Revenue ({{ new Date().getFullYear() }})</h2>
          <div class="h-48 sm:h-64 flex items-end justify-between space-x-1 sm:space-x-2">
            <div v-for="(revenue, index) in monthlyRevenue" :key="index" class="flex-1 flex flex-col items-center">
              <div 
                class="w-full bg-blue-500 rounded-t hover:bg-blue-600 transition-colors duration-200"
                :style="{ height: Math.max((revenue / Math.max(...monthlyRevenue, 1)) * 180, 3) + 'px' }"
                :title="`$${formatCurrency(revenue)}`"
              ></div>
              <span class="text-xs text-gray-600 mt-1 sm:mt-2">{{ getMonthName(index) }}</span>
            </div>
          </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
          <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Order Status</h2>
          <div class="space-y-2 sm:space-y-3">
            <div v-for="(count, status) in orderStatusCounts" :key="status" class="flex items-center justify-between">
              <div class="flex items-center">
                <div :class="getStatusColor(status)" class="w-3 h-3 rounded-full mr-3"></div>
                <span class="text-sm text-gray-700 capitalize">{{ status }}</span>
              </div>
              <span class="text-sm font-medium text-gray-900">{{ count }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
          <div class="mb-3 sm:mb-4">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Recent Orders</h2>
          </div>
          <div v-if="recentOrders.length > 0" class="space-y-2 sm:space-y-3">
            <div v-for="order in recentOrders" :key="order.id" class="flex flex-col sm:flex-row sm:items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
              <div class="mb-2 sm:mb-0">
                <p class="text-sm font-medium text-gray-900">Order #{{ order.id }}</p>
                <p class="text-xs text-gray-600">{{ order.user ? order.user.name : 'Unknown' }}</p>
              </div>
              <div class="text-right sm:text-left">
                <p class="text-sm font-medium text-gray-900">${{ formatCurrency(order.total) }}</p>
                <span :class="getStatusColor(order.status)" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium capitalize">
                  {{ order.status }}
                </span>
              </div>
            </div>
          </div>
          <div v-else class="text-gray-500 text-center py-6 sm:py-8">
            No recent orders
          </div>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 sm:mb-4">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Low Stock Alert</h2>
            <Link :href="route('products.index')" class="text-blue-600 hover:text-blue-800 text-sm">View all</Link>
          </div>
          <div v-if="lowStockProducts.length > 0" class="space-y-2 sm:space-y-3">
            <div v-for="product in lowStockProducts" :key="product.id" class="flex flex-col sm:flex-row sm:items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
              <div class="mb-2 sm:mb-0">
                <p class="text-sm font-medium text-gray-900">{{ product.title }}</p>
                <p class="text-xs text-gray-600">{{ product.brand?.name }} • {{ product.category?.name }}</p>
              </div>
              <div class="text-right sm:text-left">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                  {{ product.quantity }} left
                </span>
              </div>
            </div>
          </div>
          <div v-else class="text-gray-500 text-center py-6 sm:py-8">
            All products are well stocked
          </div>
        </div>
      </div>

      <!-- Top Selling Products -->
      <div class="mt-4 sm:mt-6 bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Top Selling Products</h2>
        <div v-if="topSellingProducts.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
          <div v-for="item in topSellingProducts" :key="item.product_id" class="text-center p-3 sm:p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200">
            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 bg-gray-200 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
              </svg>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ item.product?.title || 'Unknown' }}</p>
            <p class="text-xs text-gray-600 mt-1">{{ item.total_sold }} sold</p>
          </div>
        </div>
        <div v-else class="text-gray-500 text-center py-6 sm:py-8">
          No sales data available
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from "@/Pages/Components/AdminLAyout.vue";

const props = defineProps({
  statistics: Object,
  recentOrders: Array,
  lowStockProducts: Array,
  monthlyRevenue: Array,
  orderStatusCounts: Object,
  topSellingProducts: Array,
});

const formatCurrency = (amount) => {
  return parseFloat(amount || 0).toFixed(2);
};

const getMonthName = (index) => {
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  return months[index];
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
</script>
