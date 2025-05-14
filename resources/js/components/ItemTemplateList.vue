<script setup>
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';

defineProps({
  item: Object,
  authUser: Object,
  view: String
});

const emit = defineEmits(['delete']);
</script>

<template>
  <div v-if="view === 'card'" class="bg-white shadow rounded p-4">
    <h2 class="text-xl font-semibold">{{ item.name }}</h2>
    <p class="text-gray-700 mt-2">Gender: {{ item.gender }}</p>
    <p class="text-gray-700 mt-1">Body Part: {{ item.body_part }}</p>

    <div v-if="item.user_id === authUser.id" class="flex justify-end gap-3 mt-3">
      <Link :href="route('items.edit', item.id)">
        <Icon icon="ri:edit-fill" class="text-blue-600" width="20" height="20" />
      </Link>
      <button @click="$emit('delete', item)">
        <Icon icon="ic:baseline-delete" class="text-red-600" width="20" height="20" />
      </button>
    </div>
  </div>
</template>
