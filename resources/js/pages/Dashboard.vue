<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Chart from '@/components/Chart.vue';
import { Link, router } from "@inertiajs/vue3";
import { ref, watch } from 'vue';
import axios from 'axios';

const showDropdown = ref(false);
const selectedOption = ref('Yesterday'); // Default text inside input
const chartLabels = ref([]);
const chartData = ref([]);
const chartRows = ref([]);

const props = defineProps({
    showOrganizationPopup: Boolean,
    organizationName: String,
    totalOrders: Number,
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

watch([selectedOption, activeTab], fetchChartData, { immediate: true });

async function fetchChartData() {
    try {
        const response = await axios.get('/dashboard/chart-data', {
            params: {
                range: selectedOption.value,
                type: activeTab.value
            }
        });

        chartLabels.value = response.data.labels;
        chartData.value = response.data.data;
        chartRows.value = response.data.chartRows; // ✅ capture the full row data

    } catch (error) {
        console.error('Failed to fetch chart data:', error);
    }
}

</script>

<template>

    <Head title="Dashboard" />
    <AppLayout>
        <!-- Alert-style Notification -->
        <div v-if="showPopup"
            class=" fixed top-6 left-1/2 transform -translate-x-1/2 z-50 w-[90%] max-w-md bg-white border-t-4 border-yellow-500 text-yellow-800 rounded-lg shadow-xl px-4 py-3 flex flex-col items-start items-center justify-between animate-slide-in">
            <button @click="closePopup" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition">
                <Icon icon="mdi:close" width="20" height="20" />
            </button>
            <div class="flex items-start gap-4">
                <div class="bg-yellow-100 p-2 rounded-full">
                    <Icon icon="fluent:info-24-filled" class="text-yellow-500" width="26" height="26" />
                </div>
                <div>
                    <p class="text-sm text-gray-800 mt-1">Provide your organization information to continue.</p>
                </div>
            </div>

            <div class="flex gap-2 ml-auto mt-3 sm:mt-0">
                <Link :href="route('organization.create')"
                    class="px-3 py-1.5 text-sm font-medium bg-primary text-white rounded-md transition">
                Create Organization
                </Link>
                <button @click="noOrganization"
                    class="px-3 py-1.5 text-sm font-medium bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition">
                    I don't have a Organization
                </button>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-8 w-full">
            <div class="flex justify-between items-center mb-6">

                <h2
                    class="text-[20px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence] whitespace-nowrap">
                    Total Orders: <span class="font-bold">{{ props.totalOrders ?? 0 }}</span>
                </h2>
                <h1
                    class="text-[20px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence] text-center lg:w-full">
                    {{ props.organizationName }}
                </h1>
            </div>

            <!-- Graph Section Header with Filter Dropdown -->
            <div class="flex flex-row justify-between">
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
                <Chart :chart-labels="chartLabels" :chart-data="chartData" :chart-rows="chartRows"
                    :chart-type="activeTab" />

            </div>

            <!-- Quick Links Section -->
            <div class="mt-10 flex gap-4 flex-col">
                <div>
                    <h1 class="font-[Convergence] font-normal text-[24px] tracking-[0]">
                        Quick Links
                    </h1>
                </div>
                <div class="flex flex-col lg:flex-row gap-10 w-full">
                    <!-- create customer -->
                    <div
                        class="relative group bg-teal-50 shadow overflow-hidden w-full h-[150px] rounded-md flex items-center justify-center border-t-4 border-primary">
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
                        class="relative group bg-teal-50 shadow overflow-hidden w-full h-[150px] rounded-md flex items-center justify-center border-t-4 border-primary">
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
                        class="relative group bg-teal-50 shadow overflow-hidden w-full h-[150px] rounded-md flex items-center justify-center border-t-4 border-primary">
                        <div class="flex flex-col items-center gap-2">
                            <Link :href="route('orders.closed')" class="flex flex-col items-center gap-2">
                            <div class="bg-primary p-3 rounded-full">
                                <Icon icon="material-symbols:order-approve" width="32" height="32" class="text-white" />
                            </div>
                            <div class="relative z-10 text-primary font-[Lato] font-medium text-[24px] tracking-[0]">
                                Deliver Orders 
                            </div>
                            </Link>
                        </div>
                    </div>
                    <div
                        class="relative group bg-teal-50 shadow overflow-hidden w-full h-[150px] rounded-md flex items-center justify-center border-t-4 border-primary">
                        <div class="flex flex-col items-center gap-2">
                            <Link :href="route('orders.viewClosed')" class="flex flex-col items-center gap-2">
                            <div class="bg-primary p-3 rounded-full">
                                <Icon icon="lsicon:order-done-filled" width="32" height="32" class="text-white" />
                            </div>
                            <div class="relative z-10 text-primary font-[Lato] font-medium text-[24px] tracking-[0]">
                                Close Orders
                            </div>
                            </Link>
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
                        class="relative group bg-teal-50 shadow overflow-hidden w-full h-[150px] rounded-md flex items-center justify-center border-t-4 border-primary">
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
                        class="relative group bg-teal-50 shadow overflow-hidden w-full h-[150px] rounded-md flex items-center justify-center border-t-4 border-primary">
                        <div class="flex flex-col items-center gap-2">
                            <Link :href="route('orders.index')" class="flex flex-col items-center gap-2">
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
<style scoped>
@keyframes slide-in {
    0% {
        opacity: 0;
        transform: translateX(-50%) translateY(-20px);
    }

    100% {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

.animate-slide-in {
    animation: slide-in 0.4s ease-out;
}
</style>
