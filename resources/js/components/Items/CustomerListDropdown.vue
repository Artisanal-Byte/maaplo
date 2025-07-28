<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import { useOrderFormStore } from '@/stores/orderFormStore';
import { Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const formStore = useOrderFormStore();

const showDropdown = ref(false);
const customers = ref([]);
const loadingCustomers = ref(false);
const selectedCustomer = ref('Select Customer');

function asset(path) {
    return '/' + path;
}

async function fetchCustomers() {
    loadingCustomers.value = true;
    try {
        const response = await axios.get('/orders/customers/fetch');
        if (response.data.status) {
            customers.value = response.data.customers || [];
        } else {
            customers.value = [];
        }
    } catch (error) {
        console.error("Failed to fetch customers:", error);
        customers.value = [];
    } finally {
        loadingCustomers.value = false;
    }
}

async function ensureSelectedCustomerInList() {
    // If editing and selected customer is not in list, fetch it separately and add to list
    if (formStore.customer_id && !customers.value.find(c => c.id === formStore.customer_id)) {
        try {
            const response = await axios.get(`/orders/customers/${formStore.customer_id}`);
            if (response.data.status && response.data.customer) {
                customers.value.push(response.data.customer);
            }
        } catch (error) {
            console.error("Failed to fetch selected customer:", error);
        }
    }
}

function selectOption(customer) {
    selectedCustomer.value = customer.name;
    formStore.customer_id = customer.id;
    formStore.user_id = customer.user_id;
    showDropdown.value = false;
}

watch(() => formStore.customer_id, (newVal) => {
    const cust = customers.value.find(c => c.id === newVal);
    selectedCustomer.value = cust ? cust.name : 'Select Customer';
});

async function toggleDropdown() {
    showDropdown.value = !showDropdown.value;

    if (showDropdown.value && customers.value.length === 0) {
        await fetchCustomers();
    }
}

onMounted(async () => {
    await fetchCustomers();
    await ensureSelectedCustomerInList();
    if (formStore.customer_id) {
        const selected = customers.value.find(c => c.id === formStore.customer_id);
        selectedCustomer.value = selected ? selected.name : 'Select Customer';
    } else {
        selectedCustomer.value = 'Select Customer';
    }
});
</script>


<template>
    <div class="w-full border-b border-primary flex justify-between items-center relative">
        <div>
            <button @click="toggleDropdown"
                class="flex cursor-pointer items-center justify-between gap-2 py-2 bg-white rounded-md focus:outline-none">
                <span class="font-lato font-medium text-base leading-4 tracking-normal">{{ selectedCustomer }}</span>
                <Icon :icon="showDropdown ? 'icon-park-outline:up' : 'icon-park-outline:down'" width="20" height="20" />
            </button>
            <div v-if="showDropdown"
                class="absolute mt-2 w-full bg-white border border-gray-200 rounded-md shadow-lg z-50 max-h-60 overflow-auto">
                <ul class="py-1 text-sm text-gray-700">
                    <li v-if="loadingCustomers" class="px-4 py-2 text-gray-400">Loading...</li>
                    <li v-else-if="customers.length === 0" class="px-4 py-2 text-gray-400">No customers found.</li>
                    <li v-for="customer in customers" :key="customer.id" @click="selectOption(customer)"
                        class="w-full text-left px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <h1 class="text-gray-800 font-medium text-[18px]">{{ customer.name }}</h1>
                            <img v-if="customer.photos?.[0]?.image_url" :src="asset(customer.photos[0].image_url)"
                                alt="Customer photo" class="w-8 h-8 rounded-full ml-4" />
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
    </div> <!-- ✅ Closing outer div -->
</template>
