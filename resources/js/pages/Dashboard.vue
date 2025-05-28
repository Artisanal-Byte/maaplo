<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Chart from '@/components/Chart.vue';
import { Link,router  } from "@inertiajs/vue3";
import { ref } from 'vue';
const showDropdown = ref(false);
const selectedOption = ref('Yesterday'); // Default text inside input

const props = defineProps({
    showOrganizationPopup: Boolean
});

const showPopup = ref(props.showOrganizationPopup);

function closePopup() {
    showPopup.value = false;
}
function noOrganization() {
    // Send a request to set `hash_organization` to false
    router.post('/organization/no-organization', {}, {
        onSuccess: () => {
            showPopup.value = false;
        }
    });
}


function toggleDropdown() {
    showDropdown.value = !showDropdown.value;
}

function selectOption(option) {
    selectedOption.value = option;
    showDropdown.value = false;
}
const activeTab = ref('order')
</script>

<template>

    <Head title="Dashboard" />
    <AppLayout>
        <div v-if="showPopup" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-md shadow-md w-[400px] text-center">
                <h2 class="text-lg font-semibold mb-4">Organization Info Required</h2>
                <p class="mb-6 text-gray-600">Please provide your organization information.</p>
                <div class="flex justify-end gap-4">
                    <button @click="closePopup" class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400">
                        Close
                    </button>
                    <button @click="noOrganization" class="px-4 py-2 bg-primary text-white rounded-md">
                        I Don't Have an Organization
                    </button>
                </div>
            </div>
        </div>
        <div class="mx-auto max-w-7xl px-4 py-8 w-full">
            <h1 class="text-[24px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">Total Orders
                : 0
            </h1>
            <!-- Graph Section Header with Filter Dropdown -->
            <div class="flex flex-row justify-between mt-8">
                <div>
                    <!-- <h1 class="font-normal text-[20px] leading-[16px] tracking-[0] font-[Convergence]">Graph</h1> -->
                </div>
                <div>
                    <div class="relative">
                        <button @click="toggleDropdown" class="flex items-center justify-between gap-5 py-2 bg-white
                             rounded-md focus:outline-none">
                            <span>{{ selectedOption }}</span>
                            <Icon :icon="showDropdown ? 'icon-park-outline:up' : 'icon-park-outline:down'" width="20"
                                height="20" />
                        </button>

                        <div v-show="showDropdown"
                            class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                            <ul class="py-1 text-sm text-gray-700">
                                <li>
                                    <button @click="selectOption('Yesterday')"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                        Yesterday
                                    </button>
                                </li>
                                <li>
                                    <button @click="selectOption('7 Days Ago')"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                        7 Days Ago
                                    </button>
                                </li>
                                <li>
                                    <button @click="selectOption('1 Month Ago')"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                        1 Month Ago
                                    </button>
                                </li>
                                <li>
                                    <button @click="selectOption('1 Year Ago')"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                        1 Year Ago
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column Labels for Chart -->
            <div class="flex flex-row justify-between mt-5">
                <div class="relative">
                    <a @click="activeTab = 'order'" :class="[
                        'cursor-pointer font-[Lato] font-medium text-[20px] leading-[16px] tracking-[0] pb-2 relative z-10',
                        activeTab === 'order' ? 'text-primary ' : 'text-black'
                    ]">
                        Order
                    </a>
                    <div class="absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-500" :style="{
                        width: activeTab === 'order' ? '100%' : '0%'
                    }"></div>
                </div>
                <div class="relative">
                    <a @click="activeTab = 'customer'" :class="[
                        'cursor-pointer font-[Lato] font-medium text-[20px] leading-[16px] tracking-[0] pb-2 relative z-10',
                        activeTab === 'customer' ? 'text-primary ' : 'text-black'
                    ]">
                        Customer
                    </a>
                    <div class="absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-500" :style="{
                        width: activeTab === 'customer' ? '100%' : '0%'
                    }"></div>
                </div>

                <div class="relative">
                    <a @click="activeTab = 'revenue'" :class="[
                        'cursor-pointer font-[Lato] font-medium text-[20px] leading-[16px] tracking-[0] pb-2 relative z-10',
                        activeTab === 'revenue' ? 'text-primary ' : 'text-black'
                    ]">
                        Revenue
                    </a>
                    <div class="absolute bottom-0 left-0 h-0.5 bg-primary transition-all duration-500" :style="{
                        width: activeTab === 'revenue' ? '100%' : '0%'
                    }"></div>
                </div>
            </div>
            <!-- Chart Component Section -->
            <div class="">
                <Chart />
            </div>

            <!-- Quick Links Section -->
            <div class="mt-10 flex gap-4 flex-col">
                <div>
                    <h1 class="font-[Convergence] font-normal text-[24px] tracking-[0]">
                        Quick Links
                    </h1>
                </div>
                <div class="flex flex-col lg:flex-row gap-10">
                    <!-- create customer -->
                    <div
                        class="relative group bg-white shadow overflow-hidden lg:w-[300px] h-[150px] rounded-md flex items-center justify-center border-t-4 border-primary">
                        <div class="flex flex-col items-center gap-2">
                            <Link :href="route('customers.create')" class="flex flex-col items-center gap-2">
                            <div class="bg-primary p-3 rounded-full">
                                <Icon icon="mdi:account-plus" width="32" height="32" class="text-white" />
                            </div>
                            <div class="relative z-10 text-primary font-[Lato] font-medium text-[24px] tracking-[0]">
                                Create Customer
                            </div>
                            </Link>
                        </div>
                    </div>

                    <!-- create order -->
                    <div
                        class="relative group bg-white shadow overflow-hidden lg:w-[300px] h-[150px] rounded-md flex items-center justify-center border-t-4 border-primary">
                        <div class="flex flex-col items-center gap-2">
                            <Link :href="route('orders.create')" class="flex flex-col items-center gap-2">
                            <div class="bg-primary p-3 rounded-full">
                                <Icon icon="lsicon:order-edit-filled" width="32" height="32" class="text-white" />
                            </div>
                            <div class="relative z-10 text-primary font-[Lato] font-medium text-[24px] tracking-[0]">
                                Create Order
                            </div>
                            </Link>
                        </div>
                    </div>

                    <!-- close order -->
                    <div
                        class="relative group bg-white shadow overflow-hidden lg:w-[300px] h-[150px] rounded-md flex items-center justify-center border-t-4 border-primary">
                        <div class="flex flex-col items-center gap-2">
                            <!-- <Link :href="route('orders.create')" class="flex flex-col items-center gap-2"> -->
                            <div class="bg-primary p-3 rounded-full">
                                <Icon icon="lsicon:order-done-filled" width="32" height="32" class="text-white" />
                            </div>
                            <div class="relative z-10 text-primary font-[Lato] font-medium text-[24px] tracking-[0]">
                                Close Order
                            </div>
                            <!-- </Link> -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Section Links -->
            <div class="mt-10 flex gap-4 flex-col">
                <div>
                    <h1 class="font-[Convergence] font-normal text-[24px] tracking-[0]">
                        Data
                    </h1>
                </div>

                <div class="flex flex-col lg:flex-row gap-10">
                    <!-- customer list -->
                    <div
                        class="relative group bg-white shadow overflow-hidden lg:w-[300px] h-[150px] rounded-md flex items-center justify-center border-t-4 border-primary">
                        <div class="flex flex-col items-center gap-2">
                            <Link :href="route('customers.index')" class="flex flex-col items-center gap-2">
                            <div class="bg-primary p-3 rounded-full">
                                <Icon icon="garden:customer-lists-fill-26" width="32" height="32" class="text-white" />
                            </div>
                            <div class="relative z-10 text-primary font-[Lato] font-medium text-[24px] tracking-[0]">
                                Customer List
                            </div>
                            </Link>
                        </div>
                    </div>

                    <!-- order list -->
                    <div
                        class="relative group bg-white shadow overflow-hidden lg:w-[300px] h-[150px] rounded-md flex items-center justify-center border-t-4 border-primary">
                        <div class="flex flex-col items-center gap-2">
                            <Link :href="route('customers.index')" class="flex flex-col items-center gap-2">
                            <div class="bg-primary p-3 rounded-full">
                                <Icon icon="fe:list-order" width="32" height="32" class="text-white" />
                            </div>
                            <div class="relative z-10 text-primary font-[Lato] font-medium text-[24px] tracking-[0]">
                                Order List
                            </div>
                            </Link>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </AppLayout>
</template>
