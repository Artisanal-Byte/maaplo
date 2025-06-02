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
    <div v-if="view === 'card'" class="bg-[#DEEFF4] shadow rounded p-4">
        <h2 class="text-lg font-semibold text-primary mb-2">{{ item.body_part_value?.body_part ?? 'N/A' }}</h2>

        <p class="text-sm text-gray-800 mb-1">
            <strong>Gender:</strong>
            {{ item.gender === 'm' ? 'Male' : item.gender === 'f' ? 'Female' : 'Other' }}
        </p>

        <p class="text-sm text-gray-800 mb-1">
            <strong>Body Section:</strong>
            {{ item.body_section ?? 'N/A' }}
        </p>

        <p class="text-sm text-gray-800 mb-2">
            <strong>Value:</strong> {{ item.value }}
        </p>

        <div class="flex justify-end gap-3 mt-3">
            <Link :href="route('design-details.edit', item.id)">
            <Icon icon="ri:edit-fill" class="text-primary" width="20" height="20" />
            </Link>
            <button @click="$emit('delete', item)">
                <Icon icon="ic:baseline-delete" class="text-red-600" width="20" height="20" />
            </button>
        </div>
    </div>
</template>
