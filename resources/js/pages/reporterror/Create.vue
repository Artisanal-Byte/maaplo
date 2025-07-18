
<script setup>
import { reactive } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3'

const errorTypes = [
  'Functionality Issue',
  'App Crash / Freezing',
  'Slow Performance / Loading Time',
  'UI/UX Issue',
  'Security Issue',
  'Upload/Download Issue',
  'Calculation/Logic Error',
  'Search/Filter Not Working',
  'Others'
]

const form = reactive({
  description: '',
  screenshot: null,
  url: '',
  error_type: '',
  other_error_type: ''
})

const errors = usePage().props.errors || {}

const handleScreenshot = (event) => {
  form.screenshot = event.target.files[0]
}

const submitForm = () => {
  const formData = new FormData()
  formData.append('description', form.description)
  if (form.screenshot) formData.append('screenshot', form.screenshot)
  if (form.url) formData.append('url', form.url)
  formData.append('error_type', form.error_type)
  if (form.error_type === 'Others') {
    formData.append('other_error_type', form.other_error_type)
  }

  router.post('/reporterror', formData)
}
</script>

<template>
  <div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">Report an Error</h1>

    <form @submit.prevent="submitForm" enctype="multipart/form-data">
      <!-- Error Description -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Error Description *</label>
        <textarea
          v-model="form.description"
          class="w-full mt-1 p-2 border rounded"
          required
          rows="4"
        ></textarea>
        <p v-if="errors.description" class="text-red-600 text-sm">{{ errors.description }}</p>
      </div>

      <!-- Screenshot Upload -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Screenshot (Optional)</label>
        <input
          type="file"
          @change="handleScreenshot"
          accept="image/*"
          class="mt-1"
        />
        <p v-if="errors.screenshot" class="text-red-600 text-sm">{{ errors.screenshot }}</p>
      </div>

      <!-- URL of Page -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">URL of Page (Optional)</label>
        <input
          type="url"
          v-model="form.url"
          class="w-full mt-1 p-2 border rounded"
        />
        <p v-if="errors.url" class="text-red-600 text-sm">{{ errors.url }}</p>
      </div>

      <!-- Error Type -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Error Type *</label>
        <select
          v-model="form.error_type"
          class="w-full mt-1 p-2 border rounded"
          required
        >
          <option value="" disabled>Select error type</option>
          <option v-for="type in errorTypes" :key="type" :value="type">{{ type }}</option>
        </select>
        <p v-if="errors.error_type" class="text-red-600 text-sm">{{ errors.error_type }}</p>
      </div>

      <!-- Other Error Type -->
      <div v-if="form.error_type === 'Others'" class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Please Specify *</label>
        <input
          type="text"
          v-model="form.other_error_type"
          class="w-full mt-1 p-2 border rounded"
          required
        />
        <p v-if="errors.other_error_type" class="text-red-600 text-sm">{{ errors.other_error_type }}</p>
      </div>

      <!-- Submit Button -->
      <div class="mt-6">
        <button
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded"
        >
          Submit
        </button>
      </div>
    </form>
  </div>
</template>

