<script setup lang="ts">

import CustomerLimitPopup from '@/components/CustomerLimitPopup.vue';
import CustomerList from '@/components/CustomerList.vue';
import SearchList from '@/components/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, ref, computed, nextTick } from 'vue';
const showable = reactive({
    showSearch: false
});
const searchTerm = ref('');

const props = defineProps<{
    customers: Array<{
        id: number,
        name: string,
        email: string,
        country_code: string,
        phone: string,
        address: string,
        gender: string,
        active_orders?: number,
        total_payment?: string;
        advance_payment?: string;
        payment_due?: string;
        subscription_plan?: string,
    }>,
    customer_limit_exceeded: boolean,
    plan_title: string,
    plan_limit: number,
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
const filteredCustomers = computed(() => {
    if (!searchTerm.value) {
        return props.customers;
    }

    return props.customers.filter((customer) =>
        customer.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
        customer.email?.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
        customer.phone?.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

const searchInputRef = ref<HTMLInputElement | null>(null);

function focusSearchInput() {
    // Slight delay ensures input is rendered before focus
    nextTick(() => {
        searchInputRef.value?.focus();
    });
}
</script>
<template>

    <Head title="Customer" />
    <AppLayout>
        <!-- Limit Reached Modal -->
        <CustomerLimitPopup :show="showLimitModal" @close="showLimitModal = false" :planTitle="props.plan_title"
            :planLimit="props.plan_limit" />

        <!-- Main Content -->
        <div class="lg:mx-auto max-w-7xl py-8 px-4">
            <div class="flex flex-row justify-between my-6">
                <div>
                    <h1 class="text-[24px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                        Customer
                    </h1>
                    <p class="text-sm text-gray-700 text-right mt-2">
                        Your Customers: {{ props.customers.length }}
                    </p>
                </div>
                <div class="flex gap-4 text-gray-600">
                    <div>
                        <SearchList :showable="showable" @focusSearch="focusSearchInput" />

                    </div>
                    <div>
                        <!-- Use conditional rendering to prevent navigation -->
                        <div class="relative group">
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
                <input ref="searchInputRef" type="text" v-debounce:400ms="myFn" placeholder="Search..."
                    class="w-full lg:max-w-7xl border border-gray-300 rounded-full px-4 py-3 text-sm shadow-[0px_0px_4.3px_0px_#16789333] focus:outline-none focus:ring focus:border-gray-400 transition-all" />
            </div>
            <div>
                <pre>{{ props.customers.country_code }}</pre>

                <div v-if="filteredCustomers.length">
                    <CustomerList v-for="customer in filteredCustomers" :key="customer.id" :customer="customer" />
                </div>
                <div v-else class="text-center text-gray-500 py-10">
                    No customers available.
                </div>
            </div>

        </div>
    </AppLayout>
</template>
