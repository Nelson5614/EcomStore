<template>
  <AdminLayout>
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Create New Product</h1>
          <p class="text-gray-600">Add a new product to your store</p>
        </div>
        <Link
          :href="route('products.index')"
          class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Back to Products
        </Link>
      </div>

      <!-- Flash Messages -->
      <div v-if="$page.props.flash.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        <span class="block sm:inline">{{ $page.props.flash.success }}</span>
      </div>
      <div v-if="$page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <span class="block sm:inline">{{ $page.props.flash.error }}</span>
      </div>

      <!-- Form Card -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <form @submit.prevent="submit" class="p-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
              <!-- Product Name -->
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                  Product Name <span class="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  id="name"
                  v-model="form.name"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{ 'border-red-500': form.errors.name }"
                  placeholder="Enter product name"
                />
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
              </div>

              <!-- Product Slug (Read-only, auto-generated) -->
              <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">
                  URL Slug
                </label>
                <input
                  type="text"
                  id="slug"
                  :value="generateSlug(form.name)"
                  readonly
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-50 sm:text-sm"
                  placeholder="Auto-generated from name"
                />
                <p class="mt-1 text-sm text-gray-500">This will be automatically generated from the product name.</p>
              </div>

              <!-- Brand -->
              <div>
                <label for="brand_id" class="block text-sm font-medium text-gray-700">
                  Brand <span class="text-red-500">*</span>
                </label>
                <select
                  id="brand_id"
                  v-model="form.brand_id"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{ 'border-red-500': form.errors.brand_id }"
                >
                  <option value="">Select a brand</option>
                  <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                    {{ brand.name }}
                  </option>
                </select>
                <p v-if="form.errors.brand_id" class="mt-1 text-sm text-red-600">{{ form.errors.brand_id }}</p>
              </div>

              <!-- Category -->
              <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">
                  Category <span class="text-red-500">*</span>
                </label>
                <select
                  id="category_id"
                  v-model="form.category_id"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{ 'border-red-500': form.errors.category_id }"
                >
                  <option value="">Select a category</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
                <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">{{ form.errors.category_id }}</p>
              </div>

              <!-- Price -->
              <div>
                <label for="price" class="block text-sm font-medium text-gray-700">
                  Price ($) <span class="text-red-500">*</span>
                </label>
                <input
                  type="number"
                  id="price"
                  v-model="form.price"
                  step="0.01"
                  min="0"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{ 'border-red-500': form.errors.price }"
                  placeholder="0.00"
                />
                <p v-if="form.errors.price" class="mt-1 text-sm text-red-600">{{ form.errors.price }}</p>
              </div>

              <!-- Quantity -->
              <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700">
                  Quantity <span class="text-red-500">*</span>
                </label>
                <input
                  type="number"
                  id="quantity"
                  v-model="form.quantity"
                  min="0"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{ 'border-red-500': form.errors.quantity }"
                  placeholder="0"
                />
                <p v-if="form.errors.quantity" class="mt-1 text-sm text-red-600">{{ form.errors.quantity }}</p>
              </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
              <!-- Description -->
              <div>
                <label for="description" class="block text-sm font-medium text-gray-700">
                  Description
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="4"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{ 'border-red-500': form.errors.description }"
                  placeholder="Enter product description"
                ></textarea>
                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
              </div>

              <!-- Status -->
              <div>
                <label for="status" class="block text-sm font-medium text-gray-700">
                  Status
                </label>
                <select
                  id="status"
                  v-model="form.status"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{ 'border-red-500': form.errors.status }"
                >
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                  <option value="out_of_stock">Out of Stock</option>
                </select>
                <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
              </div>

              <!-- Featured -->
              <div>
                <div class="flex items-center">
                  <input
                    id="featured"
                    type="checkbox"
                    v-model="form.featured"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  />
                  <label for="featured" class="ml-2 block text-sm text-gray-900">
                    Featured Product
                  </label>
                </div>
                <p class="mt-1 text-sm text-gray-500">Featured products will be displayed prominently on the homepage.</p>
              </div>

              <!-- Product Images -->
              <div>
                <label class="block text-sm font-medium text-gray-700">
                  Product Images
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                  <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                      <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                      <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                        <span>Upload files</span>
                        <input id="images" name="images" type="file" multiple accept="image/*" @change="handleImageUpload" class="sr-only" />
                      </label>
                      <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                  </div>
                </div>
                
                <!-- Image Preview -->
                <div v-if="imagePreviews.length > 0" class="mt-4 grid grid-cols-3 gap-4">
                  <div v-for="(preview, index) in imagePreviews" :key="index" class="relative">
                    <img :src="preview" class="h-24 w-24 object-cover rounded-lg" />
                    <button
                      type="button"
                      @click="removeImage(index)"
                      class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                    >
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="mt-6 flex justify-end space-x-3">
            <Link
              :href="route('products.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
            >
              <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ form.processing ? 'Creating...' : 'Create Product' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from "@/Pages/Components/AdminLAyout.vue";

const props = defineProps({
  brands: Array,
  categories: Array
});

const form = useForm({
  name: '',
  description: '',
  brand_id: '',
  category_id: '',
  price: '',
  quantity: '',
  status: 'active',
  featured: false,
  images: [],
});

const imagePreviews = ref([]);

const handleImageUpload = (event) => {
  const files = Array.from(event.target.files);
  
  files.forEach(file => {
    if (file.type.startsWith('image/')) {
      form.images.push(file);
      
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreviews.value.push(e.target.result);
      };
      reader.readAsDataURL(file);
    }
  });
};

const removeImage = (index) => {
  form.images.splice(index, 1);
  imagePreviews.value.splice(index, 1);
};

const submit = () => {
  const formData = new FormData();
  
  formData.append('name', form.name);
  formData.append('description', form.description);
  formData.append('brand_id', form.brand_id);
  formData.append('category_id', form.category_id);
  formData.append('price', form.price);
  formData.append('quantity', form.quantity);
  formData.append('status', form.status);
  formData.append('featured', form.featured);
  
  form.images.forEach((image, index) => {
    formData.append(`images[${index}]`, image);
  });
  
  form.post(route('products.store'), {
    data: formData,
    onSuccess: () => {
      form.reset('name', 'description', 'brand_id', 'category_id', 'price', 'quantity', 'status', 'featured');
      form.images = [];
      imagePreviews.value = [];
    },
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