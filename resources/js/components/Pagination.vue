<script setup>
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  links: {
    type: Array,
    required: true,
  },
  onPageClick: {
    type: Function,
    required: true,
  },
});
</script>

<template>
  <div class="flex justify-center gap-2 mt-6">
    <template v-for="page in links" :key="page.label">
      <Link
        v-if="page.url"
        :href="page.url"
        class="px-3 py-1 rounded border"
        :class="{
          'bg-primary text-white': page.active,
          'text-gray-700 hover:bg-gray-100': !page.active,
        }"
        v-html="page.label"
        @click.prevent="onPageClick(page.url)"
      />
      <span
        v-else
        class="px-3 py-1 rounded border cursor-not-allowed text-gray-400"
        v-html="page.label"
      />
    </template>
  </div>
</template>
