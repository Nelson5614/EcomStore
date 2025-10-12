<template>
  <AdminLayout>
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Store Settings</h1>
          <p class="text-gray-600 dark:text-gray-400">Configure delivery charges and other preferences</p>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div v-if="$page.props.flash?.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
          {{ $page.props.flash.success }}
        </div>

        <form @submit.prevent="save" class="space-y-6">
          <div>
            <label for="local_delivery_charge" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Local Delivery Charge (LSL)
            </label>
            <input
              id="local_delivery_charge"
              type="number"
              step="0.01"
              min="0"
              v-model.number="form.local_delivery_charge"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
              :class="{ 'border-red-500': form.errors.local_delivery_charge }"
              placeholder="0.00"
            />
            <p v-if="form.errors.local_delivery_charge" class="mt-1 text-sm text-red-600">{{ form.errors.local_delivery_charge }}</p>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">This flat fee will be added when customers choose "Local Delivery" during checkout.</p>
          </div>

          <div class="flex justify-end">
            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg disabled:opacity-50">
              {{ form.processing ? 'Saving...' : 'Save Settings' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Pages/Components/AdminLAyout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  local_delivery_charge: { type: Number, default: 0 }
})

const form = useForm({
  local_delivery_charge: props.local_delivery_charge
})

const save = () => {
  form.post(route('admin.settings.update'), {
    preserveScroll: true,
  })
}
</script>
