<template>
  <div class="p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Feature Request</h1>
    <p class="mb-6 text-gray-600">Submit a request for new features below:</p>

    <form @submit.prevent="submitForm" class="space-y-4">
      <!-- Feature Name Input -->
      <div>
        <label for="featureName" class="block text-sm font-medium text-gray-700">Feature Name</label>
        <input
          id="featureName"
          v-model="featureName"
          type="text"
          required
          class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200"
          placeholder="Enter feature name"
        />
      </div>

      <!-- Feature Description Input -->
      <div>
        <label for="featureDescription" class="block text-sm font-medium text-gray-700">
          What is the use of this feature?
        </label>
        <textarea
          id="featureDescription"
          v-model="featureDescription"
          rows="4"
          required
          class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200"
          placeholder="Describe how this feature will help"
        ></textarea>
      </div>

      <!-- Submit Button -->
      <div>
        <button
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded"
        >
          Submit Request
        </button>
      </div>
    </form>

    <!-- Success Message -->
    <div v-if="submitted" class="mt-4 text-green-600 font-semibold">
      Feature request submitted successfully!
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const featureName = ref('');
const featureDescription = ref('');
const submitted = ref(false);

function submitForm() {
  // Here you would normally send the data to the server via Inertia or axios
  console.log('Feature Name:', featureName.value);
  console.log('Description:', featureDescription.value);

  // Reset form and show success message
  featureName.value = '';
  featureDescription.value = '';
  submitted.value = true;

  // Optional: remove the success message after a few seconds
  setTimeout(() => (submitted.value = false), 4000);
}
</script>
