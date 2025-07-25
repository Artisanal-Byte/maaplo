<script setup>
import { ref, defineProps, watch, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { useOrderFormStore } from '@/stores/orderFormStore';

const props = defineProps(["error", "customers"]);

const formStore = useOrderFormStore();
const selectedCustomer = ref('Select Customer');
const showDropdown = ref(false);
const customers = ref(props.customers || []);
const loadingCustomers = ref(false);

// Convert image path for display
function asset(path) {
    return '/' + path;
}

// Toggle dropdown and load customers on first open
async function toggleDropdown() {
    showDropdown.value = !showDropdown.value;

    if (showDropdown.value && customers.value.length === 0 && props.customers.length === 0) {

        loadingCustomers.value = true;
        try {
            const response = await axios.get('/orders/customers/fetch');
            customers.value = response.data.customers;
        } catch (error) {
            console.error("Error loading customers:", error);
        } finally {
            loadingCustomers.value = false;
        }
    }
}

// Select customer from dropdown
function selectOption(customer) {
    selectedCustomer.value = customer.name;
    formStore.user_id = customer.user_id;
    formStore.customer_id = customer.id;
    showDropdown.value = false;
}

// Watch for changes in selected customer and update display
watch(() => formStore.customer_id, (newVal) => {
    const found = customers.value.find(c => c.id === newVal);
    if (found) {
        selectedCustomer.value = found.name;
    }
});

onMounted(async () => {
    if (formStore.customer_id && customers.value.length === 0) {
        loadingCustomers.value = true;
        try {
            const response = await axios.get('/orders/customers/fetch');
            customers.value = response.data.customers;

            // Set the selectedCustomer display name
            const found = customers.value.find(c => c.id === formStore.customer_id);
            if (found) {
                selectedCustomer.value = found.name;
            }
        } catch (error) {
            console.error("Error loading customers:", error);
        } finally {
            loadingCustomers.value = false;
        }
    }
});

</script>

<template>
    <div class="w-full border-b border-primary flex justify-between items-center relative">
        <!-- Dropdown Button -->
        <div>
            <button @click="toggleDropdown"
                class="flex cursor-pointer items-center justify-between gap-2 py-2 bg-white rounded-md focus:outline-none">
                <span class="font-lato font-medium text-base leading-4 tracking-normal">
                    {{ selectedCustomer }}
                </span>
                <Icon :icon="showDropdown ? 'icon-park-outline:up' : 'icon-park-outline:down'" width="20" height="20" />
            </button>
            <p class="text-red-600 text-sm">{{ error }}</p>

            <!-- Dropdown Menu -->
            <div v-if="showDropdown"
                class="absolute mt-2 w-full bg-white border border-gray-200 rounded-md shadow-lg z-50 max-h-60 overflow-auto">
                <ul class="py-1 text-sm text-gray-700">
                    <li v-if="loadingCustomers" class="px-4 py-2 text-gray-400">Loading...</li>
                    <li v-else-if="customers.length === 0" class="px-4 py-2 text-gray-400">No customers found</li>
                    <li v-for="customer in customers" :key="customer.id" @click="selectOption(customer)"
                        class="w-full text-left px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <h1 class="text-gray-800 font-medium text-[18px]">{{ customer.name }}</h1>
                            <img v-if="customer.photos?.[0]?.image_url" :src="asset(customer.photos[0].image_url)"
                                alt="Customer Image" class="w-8 h-8 rounded-full ml-4" />
                            <span v-else
                                class="w-8 h-8 rounded-full ml-4 bg-gray-300 flex items-center justify-center text-xs text-white">
                                N/A
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Add Icon -->
        <div class="relative group">
            <Link :href="route('customers.create')">
            <Icon icon="material-symbols:add-rounded" width="20" height="20" />
            </Link>
            <div
                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                Create Customer
            </div>
        </div>
    </div>
</template>
