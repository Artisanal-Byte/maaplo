<script setup>
import { Icon } from "@iconify/vue";

const props = defineProps({
    order: { type: Object, required: true },
    modelValue: { type: String, default: "created" },
    editable: { type: Boolean, default: false }, // 👈 NEW prop
});
const emit = defineEmits(["update:modelValue"]);

// Base labels
const statusLabels = {
    created: "Created",
    in_process: "In Process",
    processed: "Processed",
    trial_done: "Trial Done",
    in_alteration: "In Alteration",
    ready_for_delivery: "Ready for Delivery",
    delivered: "Delivered",
    cancelled: "Cancelled",
};

// Smart label resolver
const getStatusLabel = (status) => {
    if (props.order.display_status === status + "_partially") {
        return statusLabels[status] + " (Partially)";
    }
    return statusLabels[status] || status;
};
</script>

<template>
    <div class="relative w-[320px] mt-2">
        <!-- Editable Mode -->
        <select v-if="editable" :value="modelValue" @change="emit('update:modelValue', $event.target.value)" class="appearance-none w-full pl-4 pr-10 py-2.5 rounded-md border border-gray-300
             text-sm text-gray-700 bg-white shadow-sm focus:outline-none
             focus:ring-2 focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
            <option v-for="(label, value) in statusLabels" :key="value" :value="value">
                {{ getStatusLabel(value) }}
            </option>
        </select>

        <!-- Readonly Mode -->
        <span v-else class="inline-block px-3 py-2 text-sm rounded-md bg-gray-100 border border-gray-300 text-gray-700">
            {{ getStatusLabel(modelValue) }}
        </span>

        <div v-if="editable" class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
            <Icon icon="mdi:chevron-down" width="20" height="20" />
        </div>
    </div>
</template>
