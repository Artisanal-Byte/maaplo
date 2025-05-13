<script setup>
import { ref, defineProps } from 'vue';
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';

let props = defineProps(["customers", "form", "errors"]);
let emit = defineEmits(["setMeasurements", "setFormData"]);
const selectedCustomer = ref('Select Customer');
const showDropdown = ref(false);
function toggleDropdown() {
    showDropdown.value = !showDropdown.value;
}
let data = props.form

//select customer and set value
function selectOption(customer) {
    selectedCustomer.value = customer.name;
    emit('setMeasurements', customer.base_measurements)
    data.user_id = customer.user_id
    data.customer_id = customer.id
    emit('setFormData', data)
    showDropdown.value = false;

}

// set a customer face photo
function asset(path) {
    return '/' + path;
}
</script>
<template>
    <div class="w-full border-b border-black flex justify-between items-center relative">
        <!-- Dropdown Button -->
        <div>
            <button @click="toggleDropdown"
                class="flex cursor-pointer items-center justify-between gap-2 py-2 bg-white rounded-md focus:outline-none">
                <span class="font-lato text-base font-normal leading-4 tracking-normal">{{ selectedCustomer }}
                    <span class="text-red-500">*</span></span>
                <Icon :icon="showDropdown ? 'icon-park-outline:up' : 'icon-park-outline:down'" width="20" height="20" />
            </button>
            <p class="text-red-600 text-sm">{{ errors.customer_id }}</p>

            <!-- Dropdown Menu -->
            <div v-if="showDropdown"
                class="absolute mt-2 w-full bg-white border border-gray-200 rounded-md shadow-lg z-50">
                <ul class="py-1 text-sm text-gray-700">
                    <!-- Static Customer Option -->
                    <li v-for="customer in customers">
                        <div @click="selectOption(customer)" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-gray-800 font-medium text-[18px]">{{ customer.name }}</h1>
                                </div>
                                <div>
                                    <img :src="asset(customer.photos[0].image_url)" alt="Customer Image"
                                        class="w-8 h-8 rounded-full ml-4" />
                                </div>
                            </div>

                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Add Icon -->
        <div>
            <Link :href="route('customers.create')">
            <Icon icon="material-symbols:add-rounded" width="20" height="20" />
            </Link>
        </div>
    </div>
</template>