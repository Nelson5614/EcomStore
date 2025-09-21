<template>
  <AdminLayout>
    <div class="p-4 sm:p-6">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
        <div class="mb-4 sm:mb-0">
          <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Edit Collection</h1>
          <p class="text-sm sm:text-base text-gray-600">Update collection information</p>
        </div>
        <Link
          :href="route('admin.collections.index')"
          class="inline-flex items-center px-3 sm:px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 w-full sm:w-auto justify-center"
        >
          Back to Collections
        </Link>
      </div>

      <!-- Flash Messages -->
      <div v-if="$page.props.flash.success" class="mb-3 sm:mb-4 bg-green-100 border border-green-400 text-green-700 px-3 sm:px-4 py-2 sm:py-3 rounded relative text-sm">
        <span class="block">{{ $page.props.flash.success }}</span>
      </div>
      <div v-if="$page.props.flash.error" class="mb-3 sm:mb-4 bg-red-100 border border-red-400 text-red-700 px-3 sm:px-4 py-2 sm:py-3 rounded relative text-sm">
        <span class="block">{{ $page.props.flash.error }}</span>
      </div>

      <!-- Form Card -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <form @submit.prevent="submit" class="p-4 sm:p-6">
          <div class="grid grid-cols-1 gap-4 sm:gap-6">
            <!-- Collection Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">
                Collection Name <span class="text-red-500">*</span>
              </label>
              <input
                type="text"
                id="name"
                v-model="form.name"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 text-sm"
                :class="{ 'border-red-500': form.errors.name }"
                placeholder="Enter collection name"
              />
              <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
            </div>

            <!-- Collection Slug (Read-only, auto-generated) -->
            <div>
              <label for="slug" class="block text-sm font-medium text-gray-700">
                URL Slug
              </label>
              <input
                type="text"
                id="slug"
                :value="generateSlug(form.name)"
                readonly
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-50 text-sm"
                placeholder="Auto-generated from name"
              />
              <p class="mt-1 text-xs sm:text-sm text-gray-500">This will be automatically generated from the collection name.</p>
            </div>

            <!-- Description -->
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">
                Description
              </label>
              <textarea
                id="description"
                v-model="form.description"
                rows="3 sm:4"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 text-sm"
                :class="{ 'border-red-500': form.errors.description }"
                placeholder="Enter collection description (optional)"
              ></textarea>
              <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
              <p class="mt-1 text-xs sm:text-sm text-gray-500">Provide a brief description of the collection.</p>
            </div>

            <!-- Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Status
              </label>
              <div class="flex items-center space-x-4">
                <label class="inline-flex items-center">
                  <input
                    type="radio"
                    v-model="form.is_active"
                    :value="true"
                    class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300"
                  />
                  <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
                <label class="inline-flex items-center">
                  <input
                    type="radio"
                    v-model="form.is_active"
                    :value="false"
                    class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300"
                  />
                  <span class="ml-2 text-sm text-gray-700">Inactive</span>
                </label>
              </div>
              <p v-if="form.errors.is_active" class="mt-1 text-sm text-red-600">{{ form.errors.is_active }}</p>
              <p class="mt-1 text-xs sm:text-sm text-gray-500">Active collections will be visible on the frontend.</p>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0">
            <Link
              :href="route('admin.collections.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 w-full sm:w-auto justify-center"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50 w-full sm:w-auto justify-center"
            >
              <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ form.processing ? 'Updating...' : 'Update Collection' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from "@/Pages/Components/AdminLAyout.vue";

const props = defineProps({
  collection: Object
});

const form = useForm({
  name: props.collection.name,
  description: props.collection.description || '',
  is_active: props.collection.is_active,
});

const submit = () => {
  form.put(route('admin.collections.update', props.collection.id), {
    preserveScroll: true,
  });
};

const generateSlug = (name) => {
  if (!name) return '';
  return name
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .trim();
};
</script>
