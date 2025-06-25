<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const suggestions = computed(() => page.props.suggestions || []);
</script>

<template>
  <Head title="Suggestions" />
  <AppLayout>
    <div class="px-6 py-8 max-w-4xl mx-auto">
      <h1 class="text-2xl font-bold mb-4">Suggestions</h1>

      <div v-if="suggestions.length === 0" class="text-gray-500">
        No suggestions found.
      </div>

      <div v-else class="space-y-4">
        <div
          v-for="(suggestion, index) in suggestions"
          :key="index"
          class="p-4 border rounded shadow-sm"
        >
          <h2 class="text-lg font-semibold">{{ suggestion.suggestion_title }}</h2>
          <p class="text-gray-700 mt-1">{{ suggestion.suggestion_description }}</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
