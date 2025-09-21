<template>
  <AdminLayout>
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Details</h1>
          <p class="text-gray-600 dark:text-gray-400">View user information</p>
        </div>
        <Link
          :href="route('admin.users.index')"
          class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Back to Users
        </Link>
      </div>

      <!-- User Information -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
          <div class="flex items-center space-x-6">
            <img
              :src="user.profile_photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&color=7C3AED&background=EBE4FF'"
              :alt="user.name"
              class="w-24 h-24 rounded-full"
            />
            <div>
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ user.name }}</h2>
              <p class="text-gray-600 dark:text-gray-400">{{ user.email }}</p>
              <span
                :class="user.is_admin ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'"
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2"
              >
                {{ user.is_admin ? 'Admin' : 'User' }}
              </span>
            </div>
          </div>

          <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Account Information</h3>
              <dl class="space-y-2">
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User ID</dt>
                  <dd class="text-sm text-gray-900 dark:text-white">{{ user.id }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</dt>
                  <dd class="text-sm text-gray-900 dark:text-white">
                    <span
                      :class="user.email_verified_at ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    >
                      {{ user.email_verified_at ? 'Verified' : 'Not Verified' }}
                    </span>
                  </dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                  <dd class="text-sm text-gray-900 dark:text-white">{{ new Date(user.created_at).toLocaleDateString() }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                  <dd class="text-sm text-gray-900 dark:text-white">{{ new Date(user.updated_at).toLocaleDateString() }}</dd>
                </div>
              </dl>
            </div>

            <div>
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Actions</h3>
              <div class="space-y-3">
                <Link
                  :href="route('admin.users.edit', user.id)"
                  class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                  Edit User
                </Link>
                <button
                  @click="deleteUser"
                  class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                  Delete User
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
  user: Object
});

const deleteUser = () => {
  if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
    router.delete(route('admin.users.destroy', user.id));
  }
};
</script>
