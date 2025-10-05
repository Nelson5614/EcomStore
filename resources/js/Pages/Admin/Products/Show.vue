<template>
  <AdminLayout>
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Product Details</h1>
          <p class="text-gray-600">View detailed product information</p>
        </div>
        <div class="flex space-x-3">
          <Link
            :href="route('admin.products.edit', product.id)"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Edit Product
          </Link>
          <Link
            :href="route('admin.products.index')"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Back to Products
          </Link>
        </div>
      </div>

      <!-- Flash Messages -->
      <div v-if="$page.props.flash.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        <span class="block sm:inline">{{ $page.props.flash.success }}</span>
      </div>
      <div v-if="$page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <span class="block sm:inline">{{ $page.props.flash.error }}</span>
      </div>

      <!-- Product Details Card -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column - Product Images -->
            <div>
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Images</h2>
              
              <!-- Main Image -->
              <div v-if="product.product_images && product.product_images.length > 0" class="mb-4">
                <img 
                  :src="`/storage/${product.product_images[0].path}`" 
                  :alt="product.name"
                  class="w-full h-96 object-cover rounded-lg shadow-md"
                />
              </div>
              
              <!-- Thumbnail Gallery -->
              <div v-if="product.product_images && product.product_images.length > 1" class="grid grid-cols-4 gap-2">
                <div 
                  v-for="(image, index) in product.product_images" 
                  :key="image.id"
                  class="cursor-pointer"
                  @click="setMainImage(index)"
                >
                  <img 
                    :src="`/storage/${image.path}`" 
                    :alt="`${product.name} - Image ${index + 1}`"
                    class="w-full h-20 object-cover rounded border-2"
                    :class="mainImageIndex === index ? 'border-blue-500' : 'border-gray-200'"
                  />
                </div>
              </div>
              
              <!-- No Images Placeholder -->
              <div v-else class="flex items-center justify-center h-96 bg-gray-100 rounded-lg">
                <div class="text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <p class="mt-2 text-sm text-gray-500">No images available</p>
                </div>
              </div>
            </div>

            <!-- Right Column - Product Information -->
            <div>
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h2>
              
              <div class="space-y-4">
                <!-- Product Name -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Product Name</label>
                  <p class="mt-1 text-sm text-gray-900">{{ product.name }}</p>
                </div>

                <!-- Product Slug -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">URL Slug</label>
                  <p class="mt-1 text-sm text-gray-900">{{ product.slug }}</p>
                </div>

                <!-- Brand -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Brand</label>
                  <p class="mt-1 text-sm text-gray-900">{{ product.brand?.name || 'Not specified' }}</p>
                </div>

                <!-- Category -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Category</label>
                  <p class="mt-1 text-sm text-gray-900">{{ product.category?.name || 'Not specified' }}</p>
                </div>

                <!-- Price -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Price</label>
                  <p class="mt-1 text-2xl font-bold text-green-600">{{ formatCurrency(parseFloat(product.price)) }}</p>
                </div>

                <!-- Quantity -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Quantity</label>
                  <div class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="{
                        'bg-green-100 text-green-800': product.quantity > 10,
                        'bg-yellow-100 text-yellow-800': product.quantity > 0 && product.quantity <= 10,
                        'bg-red-100 text-red-800': product.quantity === 0
                      }"
                    >
                      {{ product.quantity }} in stock
                    </span>
                  </div>
                </div>

                <!-- Status -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Status</label>
                  <div class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="{
                        'bg-green-100 text-green-800': product.status === 'active',
                        'bg-gray-100 text-gray-800': product.status === 'inactive',
                        'bg-red-100 text-red-800': product.status === 'out_of_stock'
                      }"
                    >
                      {{ formatStatus(product.status) }}
                    </span>
                  </div>
                </div>

                <!-- Featured -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Featured</label>
                  <div class="mt-1">
                    <span v-if="product.featured" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                      Featured Product
                    </span>
                    <span v-else class="text-sm text-gray-500">Not featured</span>
                  </div>
                </div>

                <!-- Description -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Description</label>
                  <div class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">
                    {{ product.description || 'No description provided' }}
                  </div>
                </div>

                <!-- Created/Updated Dates -->
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Created</label>
                    <p class="mt-1 text-sm text-gray-900">{{ formatDate(product.created_at) }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                    <p class="mt-1 text-sm text-gray-900">{{ formatDate(product.updated_at) }}</p>
                  </div>
                </div>
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
import { ref } from 'vue';
import AdminLayout from "@/Pages/Components/AdminLAyout.vue";

const props = defineProps({
  product: Object
});

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-LS', {
    style: 'currency',
    currency: 'LSL',
    minimumFractionDigits: 0
  }).format(amount || 0);
};

const mainImageIndex = ref(0);

const setMainImage = (index) => {
  mainImageIndex.value = index;
};

const formatStatus = (status) => {
  const statusMap = {
    'active': 'Active',
    'inactive': 'Inactive',
    'out_of_stock': 'Out of Stock'
  };
  return statusMap[status] || status;
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