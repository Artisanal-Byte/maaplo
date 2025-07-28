<script setup>
import { ref, watch, onMounted, defineProps } from 'vue';
import { useOrderFormStore } from '@/stores/orderFormStore';
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    customers: {
        type: Array,
        default: () => [],
    },
});

const formStore = useOrderFormStore();
const showDropdown = ref(false);
const customers = ref([...props.customers]); // ✅ Use props directly
const loadingCustomers = ref(false);
const selectedCustomer = ref('Select Customer');

// 🔁 Converts image paths
function asset(path) {
    return '/' + path;
}

// ✅ Sets the selected name
function setSelectedCustomerFromStore() {
    if (!formStore.customer_id || customers.value.length === 0) return;

    const match = customers.value.find(c => c.id === formStore.customer_id);
    if (match) {
        selectedCustomer.value = match.name;
    }
}

// 🔃 Dropdown toggle with conditional fetch (if needed)
async function toggleDropdown() {
    showDropdown.value = !showDropdown.value;

    // only fetch if no customers yet
    if (showDropdown.value && customers.value.length === 0) {
        await fetchCustomers();
    }
}

// 🔁 Fetch customers if needed
async function fetchCustomers() {
    loadingCustomers.value = true;
    try {
        const response = await axios.get('/orders/customers/fetch');
        customers.value = response.data.status ? response.data.customers : [];
        setSelectedCustomerFromStore(); // ✅ Reset name after fetching
    } catch (error) {
        console.error('Failed to fetch customers:', error);
        customers.value = [];
    } finally {
        loadingCustomers.value = false;
    }
}

// ✅ When selecting an option
function selectOption(customer) {
    selectedCustomer.value = customer.name;
    formStore.customer_id = customer.id;
    formStore.user_id = customer.user_id;
    showDropdown.value = false;
}

// 🧠 Watch for external changes to customer_id
watch(() => formStore.customer_id, setSelectedCustomerFromStore);

// 🚀 On mount, if editing, prefill name
onMounted(() => {
    setSelectedCustomerFromStore(); // ✅ Works immediately with preloaded props
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
