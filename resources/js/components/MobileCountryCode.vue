<script setup>
import { defineProps, defineEmits, ref, watch, onMounted } from 'vue';

const props = defineProps({
    modelValue: String,
    countries: Array
});

const emit = defineEmits(['update:modelValue']);

const selected = ref(props.modelValue || '+91');

watch(selected, (newVal) => {
    emit('update:modelValue', newVal);
});

onMounted(() => {
    if (!props.modelValue) {
        emit('update:modelValue', selected.value);
    }
});

function updateValue(event) {
    selected.value = event.target.value;
}

const countries = [
    { code: "+1", flag: "🇺🇸", name: "United States" },
    { code: "+91", flag: "🇮🇳", name: "India" },
    { code: "+44", flag: "🇬🇧", name: "United Kingdom" },
    { code: "+61", flag: "🇦🇺", name: "Australia" },
    { code: "+49", flag: "🇩🇪", name: "Germany" },
    { code: "+33", flag: "🇫🇷", name: "France" },
    { code: "+81", flag: "🇯🇵", name: "Japan" },
    { code: "+82", flag: "🇰🇷", name: "South Korea" },
    { code: "+86", flag: "🇨🇳", name: "China" },
    { code: "+7", flag: "🇷🇺", name: "Russia" },
    { code: "+39", flag: "🇮🇹", name: "Italy" },
    { code: "+55", flag: "🇧🇷", name: "Brazil" },
    { code: "+27", flag: "🇿🇦", name: "South Africa" },
    { code: "+34", flag: "🇪🇸", name: "Spain" },
    { code: "+64", flag: "🇳🇿", name: "New Zealand" },
];
</script>

<template>
    <div class="relative inline-block w-44">
        <select :value="selected" @change="updateValue"
            class="appearance-none w-full bg-white border border-gray-300 rounded-l-md px-4 py-2 text-sm font-medium text-gray-700 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50 cursor-pointer">
            <option v-for="country in countries" :key="country.code" :value="country.code">
                {{ country.flag }} {{ country.name }} ({{ country.code }})
            </option>
        </select>
        <!-- Custom dropdown arrow -->
        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>
</template>
