<!-- DateIcon.vue -->

<script setup>
import { computed, ref, defineEmits } from 'vue'
import { Icon } from '@iconify/vue';
import { useOrderFormStore } from '@/stores/orderFormStore';

const props = defineProps({
    error: String,
    today: Boolean // Add this line to accept the prop
})

const emits = defineEmits(["setOrderData"])
const selectedDate = useOrderFormStore()
const dateInput = ref(null)

// Convert today to YYYY-MM-DD format for input min attribute
const minDate = computed(() => {
    return props.today ? new Date().toISOString().split('T')[0] : null
})

function openCalendar() {
    if (dateInput.value?.showPicker) {
        dateInput.value.showPicker()
    } else {
        dateInput.value?.focus()
    }
}

const formattedDate = computed(() => {
    if (!selectedDate.delivery_date) return ''
    const d = new Date(selectedDate.delivery_date)
    return d.toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: '2-digit'
    })
})
</script>

<template>
    <div class="mt-2 flex items-center gap-3">
        <label class="font-lato text-base font-medium leading-4 tracking-normal">
            Delivery Date <span class="text-red-500">*</span>
            <p class="text-red-600 text-sm">{{ error }}</p>
        </label>

        <Icon icon="pixel:calender-solid" width="22" height="22"
            class="cursor-pointer text-gray-700 hover:text-gray-900" @click="openCalendar" />

        <input ref="dateInput" type="date" v-model="selectedDate.delivery_date" :min="minDate" @change="onDateChange"
            class="hidden" />

        <span v-if="selectedDate" class="ml-2 text-gray-800">
            {{ formattedDate }}
        </span>
    </div>
</template>
