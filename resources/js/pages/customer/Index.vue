<script setup lang="ts">

import CustomerList from '@/components/CustomerList.vue';
import SearchList from '@/components/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
const showable = reactive({
    showSearch: false
});
const searchTerm = ref('');

const props = defineProps<{
    customers: Array<{
        id: number,
        name: string,
        email: string,
        phone: string,
        address: string,
        gender: string,
        active_orders?: number,
        payment_due?: number,
        subscription_plan?: string,
    }>,
    customer_limit_exceeded: boolean,
}>();

const showLimitModal = ref(false);

function handleCreateClick(event: Event) {
    if (props.customer_limit_exceeded) {
        event.preventDefault(); // Block the navigation
        showLimitModal.value = true;
    }
}

function myFn(val: string) {
    searchTerm.value = val
    // console.log('Searching  brj:', val)
}

</script>
<template>
    <AppLayout>
        <!-- Limit Reached Modal -->
        <div v-if="showLimitModal"
            class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity transition-transform duration-200 transform hover:scale-105">
            <div class="bg-white mx-10 lg:mx-0 rounded-xl shadow-2xl max-w-md w-full p-8 text-center relative animate-blink-loop">
                <!-- Close Button -->
                <button @click="showLimitModal = false"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-semibold">
                    &times;
                </button>

                <!-- Icon -->
                <div class="flex justify-center mb-4">
                    <div
                        class="bg-red-100 text-red-600 w-14 h-14 flex items-center justify-center rounded-full shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                    </div>
                </div>

                <!-- Title -->
                <h2 class="text-xl font-bold text-red-600 mb-2">Limit Reached</h2>

                <!-- Message -->
                <p class="text-gray-700 mb-4">
                    You’re currently on a <strong class="text-primary">Free</strong> plan and can create up to
                    <strong>5 customers</strong> only.
                </p>
                <p class="text-gray-600 mb-6">
                    To add more customers, please upgrade your subscription.
                </p>

                <!-- Upgrade Button -->
                <button @click="router.visit('/upgrade')"
                    class="bg-primary hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-full shadow transition">
                    Upgrade Plan
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:mx-auto max-w-7xl py-8 px-4">
            <div class="flex flex-row justify-between mb-6">
                <div>
                    <h1 class="text-[24px] mt-3 leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                        Customer
                    </h1>
                    <p class="text-sm text-gray-700 text-right mt-2">
                        Your Customers: {{ props.customers.length }}
                    </p>
                </div>
                <div class="flex gap-4 text-gray-600">
                    <div>
                        <SearchList :showable="showable" />
                    </div>
                    <div>
                        <!-- Use conditional rendering to prevent navigation -->
                        <div class="relative group mt-1">
                            <component :is="props.customer_limit_exceeded ? 'a' : Link"
                                :href="!props.customer_limit_exceeded ? route('customers.create') : undefined"
                                @click.prevent="handleCreateClick" class="cursor-pointer">
                                <Icon icon="material-symbols:add-rounded" width="30" height="30" />
                            </component>
                            <div
                                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                Create Customer
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- search input -->
            <div class="mb-10" v-if="showable.showSearch">
                <input type="text" v-debounce:400ms="myFn" placeholder="Search..."
                    class="w-full lg:max-w-7xl border border-gray-300 rounded-full px-4 py-3 text-sm shadow-[0px_0px_4.3px_0px_#16789333] focus:outline-none focus:ring focus:border-gray-400 transition-all" />
            </div>
            <div>
                <CustomerList v-for="customer in props.customers" :key="customer.id" :customer="customer" />
            </div>
        </div>
    </AppLayout>
</template>
<style>
@keyframes blink-loop {

    0%,
    100% {
        /* opacity: 1; */
        transform: scale(1);
    }

    50% {
        /* opacity: 0.85; */
        transform: scale(1.03);
    }
}

.animate-blink-loop {
    animation: blink-loop 1s ease-in-out infinite;
}
</style>
