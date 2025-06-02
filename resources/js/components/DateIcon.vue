<script setup>
import { computed, ref, defineEmits } from 'vue'
import { Icon } from '@iconify/vue';
import { useOrderFormStore } from '@/stores/orderFormStore';

let emits = defineEmits(["setOrderData"])
const selectedDate = useOrderFormStore()
const dateInput = ref(null)



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
            <!-- <p class="text-red-600 text-sm">{{ errors?.delivery_date }}</p> -->
        </label>

        <!-- Calendar icon acts as the “open picker” trigger -->
        <Icon icon="pixel:calender-solid" width="22" height="22"
            class="cursor-pointer text-gray-700 hover:text-gray-900" @click="openCalendar" />

        <!-- Hidden native date input -->
        <input ref="dateInput" type="date" v-model="selectedDate.delivery_date" @change="onDateChange" class="hidden" />

        <!-- Show the picked date -->
        <span v-if="selectedDate" class="ml-2 text-gray-800">
            {{ formattedDate }}
        </span>
    </div>
</template>