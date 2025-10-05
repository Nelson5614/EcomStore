<template>
  <AdminLayout>
    <div class="p-4 sm:p-6">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
        <div class="mb-4 sm:mb-0">
          <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Brands Management</h1>
          <p class="text-sm sm:text-base text-gray-600">Manage your product brands</p>
        </div>
        <Link
          :href="route('admin.brands.create')"
          class="inline-flex items-center px-3 sm:px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto justify-center"
        >
          Add New Brand
        </Link>
      </div>

      <!-- Flash Messages -->
      <div v-if="$page.props.flash.success" class="mb-3 sm:mb-4 bg-green-100 border border-green-400 text-green-700 px-3 sm:px-4 py-2 sm:py-3 rounded relative text-sm">
        <span class="block">{{ $page.props.flash.success }}</span>
      </div>
      <div v-if="$page.props.flash.error" class="mb-3 sm:mb-4 bg-red-100 border border-red-400 text-red-700 px-3 sm:px-4 py-2 sm:py-3 rounded relative text-sm">
        <span class="block">{{ $page.props.flash.error }}</span>
      </div>

      <!-- Brands Table -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Mobile Card View -->
        <div class="sm:hidden">
          <div v-for="brand in brands" :key="brand.id" class="border-b border-gray-200 p-4 hover:bg-gray-50">
            <div class="flex justify-between items-start mb-2">
              <div>
                <h3 class="text-base font-medium text-gray-900">{{ brand.name }}</h3>
                <p class="text-xs text-gray-500">ID: {{ brand.id }}</p>
              </div>
              <div class="flex space-x-2">
                <Link
                  :href="route('admin.brands.edit', brand.id)"
                  class="text-blue-600 hover:text-blue-900 text-sm"
                >
                  Edit
                </Link>
                <button
                  @click="confirmDelete(brand)"
                  class="text-red-600 hover:text-red-900 text-sm"
                >
                  Delete
                </button>
              </div>
            </div>
            <div class="space-y-1">
              <p class="text-xs text-gray-600"><span class="font-medium">Slug:</span> {{ brand.slug }}</p>
              <p class="text-xs text-gray-600"><span class="font-medium">Description:</span> {{ brand.description || 'No description' }}</p>
              <div class="flex items-center">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  {{ brand.products_count || 0 }} products
                </span>
              </div>
            </div>
          </div>
          <div v-if="brands.length === 0" class="p-4 text-center text-sm text-gray-500">
            No brands found.
          </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products Count</th>
                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="brand in brands" :key="brand.id" class="hover:bg-gray-50">
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ brand.id }}</td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ brand.name }}</div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-500">{{ brand.slug }}</div>
                </td>
                <td class="px-4 sm:px-6 py-4">
                  <div class="text-sm text-gray-500 max-w-xs truncate" :title="brand.description || 'No description'">
                    {{ brand.description || 'No description' }}
                  </div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ brand.products_count || 0 }} products
                  </span>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-1 sm:space-y-0">
                    <Link
                      :href="route('admin.brands.edit', brand.id)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Edit
                    </Link>
                    <button
                      @click="confirmDelete(brand)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="brands.length === 0">
                <td colspan="6" class="px-4 sm:px-6 py-4 text-center text-sm text-gray-500">
                  No brands found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border w-11/12 sm:w-96 shadow-lg rounded-md bg-white">
          <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
              <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900 mt-4">Delete Brand</h3>
            <div class="mt-2 px-4 sm:px-7 py-2 sm:py-3">
              <p class="text-sm text-gray-500">
                Are you sure you want to delete "{{ brandToDelete?.name }}"? This action cannot be undone.
              </p>
            </div>
            <div class="items-center px-4 py-3">
              <button
                @click="deleteBrand"
                class="px-4 py-2 bg-red-500 text-white text-sm sm:text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300"
              >
                Delete
              </button>
              <button
                @click="showDeleteModal = false"
                class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-sm sm:text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from "@/Pages/Components/AdminLAyout.vue";

const props = defineProps({
  brands: Array
});

const showDeleteModal = ref(false);
const brandToDelete = ref(null);

const confirmDelete = (brand) => {
  brandToDelete.value = brand;
  showDeleteModal.value = true;
};

const deleteBrand = () => {
  if (brandToDelete.value) {
    router.delete(route('admin.brands.destroy', brandToDelete.value.id));
    showDeleteModal.value = false;
    brandToDelete.value = null;
  }
};
</script>
