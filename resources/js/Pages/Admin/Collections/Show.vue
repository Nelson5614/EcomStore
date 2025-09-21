<template>
  <AdminLayout>
    <div class="p-4 sm:p-6">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
        <div class="mb-4 sm:mb-0">
          <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Collection Details</h1>
          <p class="text-sm sm:text-base text-gray-600">View detailed collection information</p>
        </div>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
          <Link
            :href="route('admin.collections.edit', collection.id)"
            class="inline-flex items-center px-3 sm:px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 w-full sm:w-auto justify-center"
          >
            Edit Collection
          </Link>
          <Link
            :href="route('admin.collections.index')"
            class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 w-full sm:w-auto justify-center"
          >
            Back to Collections
          </Link>
        </div>
      </div>

      <!-- Flash Messages -->
      <div v-if="$page.props.flash.success" class="mb-3 sm:mb-4 bg-green-100 border border-green-400 text-green-700 px-3 sm:px-4 py-2 sm:py-3 rounded relative text-sm">
        <span class="block">{{ $page.props.flash.success }}</span>
      </div>
      <div v-if="$page.props.flash.error" class="mb-3 sm:mb-4 bg-red-100 border border-red-400 text-red-700 px-3 sm:px-4 py-2 sm:py-3 rounded relative text-sm">
        <span class="block">{{ $page.props.flash.error }}</span>
      </div>

      <!-- Collection Details Card -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="p-4 sm:p-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            <!-- Left Column - Collection Information -->
            <div>
              <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Collection Information</h2>
              
              <div class="space-y-3 sm:space-y-4">
                <!-- Collection Name -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Collection Name</label>
                  <p class="mt-1 text-sm text-gray-900">{{ collection.name }}</p>
                </div>

                <!-- Collection Slug -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">URL Slug</label>
                  <p class="mt-1 text-sm text-gray-900">{{ collection.slug }}</p>
                </div>

                <!-- Description -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Description</label>
                  <div class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">
                    {{ collection.description || 'No description provided' }}
                  </div>
                </div>

                <!-- Status -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Status</label>
                  <div class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="{
                        'bg-green-100 text-green-800': collection.is_active,
                        'bg-red-100 text-red-800': !collection.is_active
                      }"
                    >
                      {{ collection.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </div>
                </div>

                <!-- Products Count -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Products Count</label>
                  <div class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                      {{ collection.products_count || 0 }} products
                    </span>
                  </div>
                </div>

                <!-- Created/Updated Dates -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Created</label>
                    <p class="mt-1 text-sm text-gray-900">{{ formatDate(collection.created_at) }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                    <p class="mt-1 text-sm text-gray-900">{{ formatDate(collection.updated_at) }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column - Quick Stats -->
            <div>
              <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Quick Stats</h2>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <!-- Total Products -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                      </svg>
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-medium">Total Products</p>
                      <p class="text-2xl font-bold">{{ collection.products_count || 0 }}</p>
                    </div>
                  </div>
                </div>

                <!-- Status Badge -->
                <div class="bg-gradient-to-r rounded-lg p-4 text-white"
                     :class="collection.is_active ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600'">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <svg v-if="collection.is_active" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-medium">Status</p>
                      <p class="text-2xl font-bold">{{ collection.is_active ? 'Active' : 'Inactive' }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Collection Actions -->
              <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Quick Actions</h3>
                <div class="space-y-2">
                  <Link
                    :href="route('collections.show', collection.slug)"
                    target="_blank"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 w-full justify-center"
                  >
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View on Frontend
                  </Link>
                  <Link
                    :href="route('admin.products.create') + `?collection_id=${collection.id}`"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 w-full justify-center"
                  >
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Product to Collection
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Products in Collection -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Products in Collection</h2>
            <p class="text-sm text-gray-500">
              Showing <span class="font-medium">{{ products.length }}</span> 
              product{{ products.length !== 1 ? 's' : '' }}
            </p>
          </div>
        </div>
        
        <div class="p-4 sm:p-6">
          <!-- Mobile Card View -->
          <div class="sm:hidden">
            <div v-for="product in products" :key="product.id" class="border-b border-gray-200 p-4 hover:bg-gray-50">
              <div class="flex justify-between items-start mb-2">
                <div class="flex-1">
                  <h3 class="text-base font-medium text-gray-900">{{ product.title }}</h3>
                  <p class="text-xs text-gray-500">ID: {{ product.id }}</p>
                </div>
                <div class="flex space-x-2">
                  <Link
                    :href="route('admin.products.show', product.id)"
                    class="text-green-600 hover:text-green-900 text-sm"
                  >
                    View
                  </Link>
                  <Link
                    :href="route('admin.products.edit', product.id)"
                    class="text-blue-600 hover:text-blue-900 text-sm"
                  >
                    Edit
                  </Link>
                </div>
              </div>
              <div class="space-y-1">
                <p class="text-xs text-gray-600"><span class="font-medium">Price:</span> {{ formatPrice(product.price) }}</p>
                <p class="text-xs text-gray-600"><span class="font-medium">Stock:</span> {{ product.quantity }}</p>
                <div class="flex items-center space-x-2">
                  <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" :class="product.quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                    {{ product.quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                  </span>
                </div>
              </div>
            </div>
            <div v-if="products.length === 0" class="p-4 text-center text-sm text-gray-500">
              No products found in this collection.
            </div>
          </div>
          
          <!-- Desktop Table View -->
          <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ product.id }}</td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ product.title }}</div>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatPrice(product.price) }}
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ product.quantity }}
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="product.quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                      {{ product.quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                    </span>
                  </td>
                  <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                      <Link
                        :href="route('admin.products.show', product.id)"
                        class="text-green-600 hover:text-green-900"
                      >
                        View
                      </Link>
                      <Link
                        :href="route('admin.products.edit', product.id)"
                        class="text-blue-600 hover:text-blue-900"
                      >
                        Edit
                      </Link>
                    </div>
                  </td>
                </tr>
                <tr v-if="products.length === 0">
                  <td colspan="6" class="px-4 sm:px-6 py-4 text-center text-sm text-gray-500">
                    No products found in this collection.
                  </td>
                </tr>
              </tbody>
            </table>
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
  collection: Object,
  products: Array
});

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-LS', {
    style: 'currency',
    currency: 'LSL',
    minimumFractionDigits: 0
  }).format(price);
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};
</script>
