<script setup>
import { ref, watch } from "vue";
import { Icon } from '@iconify/vue';
const props = defineProps({
    modelValue: { type: String, default: "created" },
    error: { type: String, default: "" },
});

const emit = defineEmits(["update:modelValue"]);

const statuses = [
    { value: "created", label: "Created" },
    { value: "in_process", label: "In Process" },
    { value: "processed", label: "Processed" },
    { value: "trial_done", label: "Trial Done" },
    { value: "in_alteration", label: "In Alteration" },
    { value: "ready_for_delivery", label: "Ready for Delivery" },
    { value: "delivered", label: "Delivered" },
    { value: "completed", label: "Completed" },
    { value: "closed", label: "Closed" },
    { value: "cancelled", label: "Cancelled" },
];
</script>

<template>
    <div class="relative w-[200px]">
        <select :value="props.modelValue" @input="emit('update:modelValue', $event.target.value)"
            class="appearance-none w-full pl-3 pr-8 py-2 rounded-md border border-gray-300 text-sm text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
            <option v-for="status in statuses" :key="status.value" :value="status.value">
                {{ status.label }}
            </option>
        </select>
        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
            <Icon icon="mdi:chevron-down" width="18" height="18" />
        </div>
        <p v-if="props.error" class="mt-1 text-xs text-red-600">{{ props.error }}</p>
    </div>
</template>
