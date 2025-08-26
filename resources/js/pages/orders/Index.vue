<script setup>
import FilterList from '@/components/FilterIcon.vue';
import Input from '@/components/InputWithLabel.vue';
import Loader from '@/components/Loader.vue';
import OrderList from '@/components/OrderList.vue';
import Pagination from '@/components/Pagination.vue';
import SearchList from '@/components/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Head, Link,router } from '@inertiajs/vue3';
import { defineProps, computed, reactive, ref, watch, nextTick } from 'vue';

const props = defineProps({
    orders: Object,
    onPageClick: Function, // Add the onPageClick prop here
});

const isLoading = ref(false);
const searchTerm = ref('');
// const orders = ref([]);
const selectedStatus = ref('');
const selectedDelivery = ref('');
const showDropdown = ref(false);
const showDropdownDelivery = ref(false);
const bgColor = ref(['#FFFCE6', '#EAF5FF', '#FFFFFF', '#FFEAEA'])
const borderColor = ref(['#837200', '#005FAF', '#828282', '#FF0000'])
let showable = reactive({ showSearch: false, showFilter: false })

// make a emitable function to show and hide the search and filter list
const hideOrShow = (whichShow) => {
    if (whichShow === 'search') {
        showable.showSearch = !showable.showSearch;
        showable.showFilter = false;
    } else if (whichShow === 'filter') {
        showable.showFilter = !showable.showFilter;
        showable.showSearch = false;
    }
};
// search function
// debounce the function to avoid too many calls
function myFn(val) {
    searchTerm.value = val
}
watch(selectedStatus, (newStatus) => {
});
watch(selectedDelivery, (newDelivery) => {
});
const filteredOrders = computed(() => {
    const ordersArray = props.orders?.data || [];
    const search = searchTerm.value.toLowerCase();

    return ordersArray.filter(order => {
        const orderNumber = order.order_number || '';
        const customerName = order.customer?.name || '';

        const matchesSearch = !searchTerm.value ||
            orderNumber.toLowerCase().includes(search) ||
            customerName.toLowerCase().includes(search);

        const matchesStatus = !selectedStatus.value || order.status.toLowerCase() == selectedStatus.value.toLowerCase();
        const matchesDelivery = !selectedDelivery.value || (() => {
            const [day, month, year] = order.delivery_date.split('-');
            const deliveryDate = new Date(`${year}-${month}-${day}`);
            const today = new Date();
            const diffDays = Math.ceil((deliveryDate - today) / (1000 * 60 * 60 * 24));
            console.log('order.delivery_date', order.delivery_date, diffDays);

            if (selectedDelivery.value === 'Within 7 Days') {
                return diffDays >= 0 && diffDays <= 7;
            }
            if (selectedDelivery.value === '7-15 Days') {
                return diffDays >= 8 && diffDays <= 15;
            }
            if (selectedDelivery.value === 'Overdue') {
                return diffDays < 0;
            }
            if (selectedDelivery.value === 'One Month') {
                return diffDays > 15 && diffDays <= 30;
            }

            return true;
        })();

        return matchesSearch && matchesStatus && matchesDelivery;
    });
});

// dropdown function

function toggleDropdown() {
    showDropdown.value = !showDropdown.value;
}
function toggleDropdownDelivery() {
    showDropdownDelivery.value = !showDropdownDelivery.value;
}

const searchInputRef = ref(null);

function focusSearchInput() {
    nextTick(() => {
        searchInputRef.value?.focus();
    });
}

const getBgColor = (deliveryDateStr) => {
    const [day, month, year] = deliveryDateStr.split('-');
    const deliveryDate = new Date(`${year}-${month}-${day}`);
    const today = new Date();
    const diffDays = Math.ceil((deliveryDate - today) / (1000 * 60 * 60 * 24));

    if (diffDays < 0) return '#FFEAEA';           // Overdue
    if (diffDays <= 7) return '#FFFCE6';          // Within 7 Days
    if (diffDays <= 15) return '#EAF5FF';         // 7–15 Days
    return '#E0F2F1';                              // 15+ Days (primary-like fallback)
};

const getBorderColor = (deliveryDateStr) => {
    const [day, month, year] = deliveryDateStr.split('-');
    const deliveryDate = new Date(`${year}-${month}-${day}`);
    const today = new Date();
    const diffDays = Math.ceil((deliveryDate - today) / (1000 * 60 * 60 * 24));

    if (diffDays < 0) return '#FF0000';           // Overdue
    if (diffDays <= 7) return '#837200';          // Within 7 Days
    if (diffDays <= 15) return '#005FAF';         // 7–15 Days
    return '#167893';                              // 15+ Days (primary fallback)
};

watch([searchTerm, selectedStatus, selectedDelivery], ([search, status, delivery]) => {
    router.get(route('orders.index'), {
        search: search || undefined,
        status: status || undefined,
        delivery: delivery || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        onStart: () => isLoading.value = true,
        onFinish: () => isLoading.value = false,
    });
});

function handlePaginationClick(url) {
    isLoading.value = true;
    router.visit(url, {
        onFinish: () => {
            isLoading.value = false;
        },
    });
}
</script>
<template>

    <Head title="Orders" />
    <AppLayout>
        <div class="lg:mx-auto max-w-7xl py-8 px-4">
            <!-- Title And Icon -->
            <div class="grid grid-cols-3 mt-5">
                <div>
                    <h1 class="text-[24px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence] mt-2">
                        Orders
                    </h1>
                </div>
                <!-- View Closed Orders Button -->
                <div class="flex justify-center items-center">
                    <Link :href="route('orders.viewClosed')"
                        class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-semibold rounded-md shadow">
                    <Icon icon="ic:round-visibility" class="mr-2" width="20" height="20" />
                    View Closed Orders
                    </Link>

                </div>
                <div class="flex justify-end gap-4 text-gray-600 relative z-20">
                    <div class="relative group">
                        <Link :href="route('orders.create')">
                        <Icon icon="mingcute:add-line" width="32" height="32" class=" cursor-pointer mt-1" />
                        </Link>
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2
                    bg-gray-800 text-white text-xs px-3 py-1 rounded-md
                    opacity-0 group-hover:opacity-100 transition duration-200
                    whitespace-nowrap pointer-events-none shadow-lg">
                            Create Order
                        </div>
                    </div>

                    <!-- Reset Tooltip -->
                    <div class="relative group">
                        <Link :href="route('orders.index')">
                        <Icon icon="ic:outline-refresh" width="32" height="32" class="cursor-pointer mt-1" />
                        </Link>
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2
                    bg-gray-800 text-white text-xs px-3 py-1 rounded-md
                    opacity-0 group-hover:opacity-100 transition duration-200
                    whitespace-nowrap pointer-events-none shadow-lg">
                            Reset
                        </div>
                    </div>

                    <!-- Search Tooltip -->
                    <div class="relative group">
                        <SearchList :showable="showable" @focusSearch="focusSearchInput" class="mt-1" />
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2
                    bg-gray-800 text-white text-xs px-3 py-1 rounded-md
                    opacity-0 group-hover:opacity-100 transition duration-200
                    whitespace-nowrap pointer-events-none shadow-lg">
                            Search
                        </div>
                    </div>

                    <!-- Filter Tooltip -->
                    <div class="relative group">
                        <FilterList :showable="showable" @hideOrShow="hideOrShow" class="mt-1" />
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2
                    bg-gray-800 text-white text-xs px-3 py-1 rounded-md
                    opacity-0 group-hover:opacity-100 transition duration-200
                    whitespace-nowrap pointer-events-none shadow-lg">
                            Filter
                        </div>
                    </div>
                </div>


            </div>
            <!-- Search and filter section -->
            <div>
                <!-- search input -->
                <div class="mt-4 mb-10">
                    <input ref="searchInputRef" v-debounce:400ms="myFn" v-if="showable.showSearch" type="text"
                        placeholder="Search..."
                        class="w-full lg:max-w-7xl border border-gray-300 rounded-full px-4 py-3 text-sm shadow-[0px_0px_4.3px_0px_#16789333] focus:outline-none focus:ring focus:border-gray-400 transition-all" />
                </div>

                <!-- filters -->
                <div v-if="showable.showFilter" class="transition-all mt-10">
                    <div
                        class="flex flex-col gap-4 bg-white rounded-md px-4 py-3 text-sm shadow-[0px_0px_8.6px_0px_#005FAF40] focus:outline-none focus:ring focus:border-gray-400 transition-all">
                        <h1 class="font-[Lato] font-medium text-[18px] leading-[16px] tracking-[0] text-black">Filter
                        </h1>
                        <!-- order status dropdown -->
                        <div @click="toggleDropdown()">
                            <div class="flex gap-2">
                                <h1 class="font-[Lato] font-medium text-[18px] leading-[16px] tracking-[0] text-black">
                                    Order
                                    Status
                                </h1>
                                <Icon v-if="showDropdown == false" icon="icon-park-outline:down" width="20" height="20"
                                    class="text-black" />
                                <Icon v-if="showDropdown == true" icon="icon-park-outline:up" width="20" height="20"
                                    class="text-black" />
                            </div>
                        </div>
                        <!-- Dropdown -->
                        <div v-show="showDropdown" class="z-10" v-debounce:400ms="myFn">
                            <ul class="text-md text-black dark:text-black" aria-labelledby="dropdownTrigger">
                                <li>
                                    <div class="flex flex-col ml-2 gap-1 text-black ml-2">
                                        <div>
                                            <Input type="radio" id="create" name="status" label="Created"
                                                radioValue="Created" v-model="selectedStatus" />
                                            <!-- <label for="create" class="ml-2">Create</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="in-progress" name="status" label="in progress"
                                                radioValue="in_process" v-model="selectedStatus" />
                                            <!-- <label for="in-progress" class="ml-2 text-black">In Progress</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="trial-done" label="Trial Done" name="status"
                                                radioValue="trial_done" v-model="selectedStatus" />
                                            <!-- <label for="trial-done" class="ml-2">Trial Done</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="in-alteration" label="In Alteration" name="status"
                                                radioValue="in_alteration" v-model="selectedStatus" />
                                            <!-- <label for="in-alteration" class="ml-2">In Alteration</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="ready-for-delivery" label="Ready for Delivery"
                                                name="status" radioValue="ready_for_delivery"
                                                v-model="selectedStatus" />
                                            <!-- <label for="ready-for-delivery" class="ml-2">Ready for Delivery</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="delivered" label="Delivered" name="status"
                                                radioValue="delivered" v-model="selectedStatus" />
                                            <!-- <label for="delivered" class="ml-2">Delivered</label><br> -->
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- Delivery Date -->
                        <div @click="toggleDropdownDelivery()">
                            <div class="flex gap-2">
                                <h1 class="font-[Lato] font-medium text-[18px] leading-[16px] tracking-[0] text-black">
                                    Delivery
                                    Date
                                </h1>
                                <Icon v-if="showDropdownDelivery == false" icon="icon-park-outline:down" width="20"
                                    height="20" class="text-black" />
                                <Icon v-if="showDropdownDelivery == true" icon="icon-park-outline:up" width="20"
                                    height="20" class="text-black" />
                            </div>
                        </div>
                        <!-- Dropdown -->
                        <div v-show="showDropdownDelivery" class="z-10">
                            <ul class="text-md text-black dark:text-black" aria-labelledby="dropdownTrigger">
                                <li>
                                    <div class="flex flex-col ml-2 gap-1 text-black ml-2">
                                        <div>
                                            <Input type="radio" id="Within-7-Days" label="Within 7
                                                Days" name="date" radioValue="Within 7 Days"
                                                v-model="selectedDelivery" />
                                            <!-- <label for="Within-7-Days" class="ml-2 text-black">Within 7
                                                Days</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="7-15-Days" label="7-15 Days" name="date"
                                                radioValue="7-15 Days" v-model="selectedDelivery" />
                                            <!-- <label for="7-15-Days" class="ml-2">7-15 Days</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="Overdue" label="Overdue" name="date"
                                                radioValue="Overdue" v-model="selectedDelivery" />
                                            <!-- <label for="Overdue" class="ml-2">Overdue</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="One-Month" label="One Month" name="date"
                                                radioValue="One Month" v-model="selectedDelivery" />
                                            <!-- <label for="One-Month" class="ml-2">One Month</label><br> -->
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 mt-10 py-6 gap-[10px] rounded-[10px] shadow-[0px_0px_8.6px_0px_#005FAF40]">
                <!-- Orders list -->
                <div class="space-y-6">
                    <!-- <OrderList v-if="filteredOrders.length > 0" v-for="(order, index) in filteredOrders" :key="order.id"
                        :bgColor="bgColor[index % bgColor.length]"
                        :borderColor="borderColor[index % borderColor.length]" :order="order" /> -->
                    <OrderList v-if="filteredOrders.length > 0" v-for="(order, index) in filteredOrders" :key="order.id"
                        :bgColor="getBgColor(order.delivery_date)" :borderColor="getBorderColor(order.delivery_date)"
                        :order="order" />
                    <span v-else>No Orders</span>
                </div>
            </div>
            <!-- Loader for pagination or page switching -->
            <Loader v-if="isLoading" />

            <!-- Pagination component -->
            <Pagination :links="orders.links" :onPageClick="handlePaginationClick" />
        </div>
    </AppLayout>
</template>

<style scoped>
input[type="radio"]:active+label {
    color: #167893;

}

input[type="radio"]:checked+label {
    color: #167893;

}

input[type=radio] {
    accent-color: #167893;
}
</style>
