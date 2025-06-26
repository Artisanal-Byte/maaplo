<script setup>
import { defineProps, defineEmits, ref, watch, onMounted, computed } from 'vue';
import 'flag-icon-css/css/flag-icons.min.css';
const props = defineProps({
    modelValue: String,
    countries: Array
});

const countries = [
    { code: "+1", countryCode: "us", name: "United States" },
    { code: "+91", countryCode: "in", name: "India" },
    { code: "+44", countryCode: "gb", name: "United Kingdom" },
    { code: "+61", countryCode: "au", name: "Australia" },
    { code: "+49", countryCode: "de", name: "Germany" },
    { code: "+33", countryCode: "fr", name: "France" },
    { code: "+81", countryCode: "jp", name: "Japan" },
    { code: "+82", countryCode: "kr", name: "South Korea" },
    { code: "+86", countryCode: "cn", name: "China" },
    { code: "+7", countryCode: "ru", name: "Russia" },
    { code: "+39", countryCode: "it", name: "Italy" },
    { code: "+55", countryCode: "br", name: "Brazil" },
    { code: "+27", countryCode: "za", name: "South Africa" },
    { code: "+34", countryCode: "es", name: "Spain" },
    { code: "+64", countryCode: "nz", name: "New Zealand" },
];

const selected = ref("+91");
const dropdownOpen = ref(false);

const selectedCountry = computed(() =>
    countries.find(c => c.code === selected.value) || countries[0]
);

function toggleDropdown() {
    dropdownOpen.value = !dropdownOpen.value;
}

function selectCountry(country) {
    selected.value = country.code;
    dropdownOpen.value = false;
}

const emit = defineEmits(['update:modelValue']);

watch(selected, (newVal) => {
    emit('update:modelValue', newVal);
});

onMounted(() => {
    if (props.modelValue) {
        selected.value = props.modelValue;
    } else {
        emit('update:modelValue', selected.value);
    }
});


function updateValue(event) {
    selected.value = event.target.value;
}
</script>

<template>
    <div class="relative inline-block w-48">
        <button type="button" @click="toggleDropdown"
            class="w-full border border-primary rounded px-3 py-2 flex items-center justify-between focus:outline-none">
            <div class="flex items-center gap-2">
                <span :class="`flag-icon flag-icon-${selectedCountry.countryCode}`" class="w-6 h-4 rounded-sm"></span>
                <span>{{ selectedCountry.name }} {{ selectedCountry.code }}</span>
            </div>
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <ul v-if="dropdownOpen"
            class="absolute mt-1 w-full max-h-48 overflow-auto rounded border bg-white shadow-lg z-10">
            <li v-for="country in countries" :key="country.code" @click="selectCountry(country)"
                class="flex items-center gap-2 px-3 py-2 cursor-pointer hover:bg-gray-100">
                <span :class="`flag-icon flag-icon-${country.countryCode}`" class="w-6 h-4 rounded-sm"></span>
                <span>{{ country.name }} {{ country.code }}</span>
            </li>
        </ul>
    </div>
</template>
