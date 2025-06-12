<script setup>
import FilterList from '@/components/FilterIcon.vue';
import Input from '@/components/InputWithLabel.vue';
import OrderList from '@/components/OrderList.vue';
import SearchList from '@/components/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, computed, reactive, ref, watch } from 'vue';

const props = defineProps(['orders'])
const searchTerm = ref('');
// const orders = ref([]);
const selectedStatus = ref('');
const selectedDelivery = ref('');
const showDropdown = ref(false);
const showDropdownDelivery = ref(false);
let borderColor = ref('#837200');
let bgColor = ref('#FFFCE6');
let showable = reactive({ showSearch: false, showFilter: false })

// make a emitable function to show and hide the search and filter list
const hideOrShow = (whichShow) => {
    if (whichShow == 'search') {
        showable.showSearch = !showable.showSearch
        showable.showFilter = false
    } else if (whichShow == 'filter') {
        showable.showFilter = !showable.showFilter
        showable.showSearch = false
    }
}
// search function
// debounce the function to avoid too many calls
function myFn(val) {
    searchTerm.value = val
    console.log('Searching:', val)
}
watch(selectedStatus, (newStatus) => {
    console.log('Selected Status:', newStatus);
});
watch(selectedDelivery, (newDelivery) => {
    console.log('Selected Delivery:', newDelivery);
});
const filteredOrders = computed(() => {
    return props.orders.value.filter(order => {
        const matchesSearch = !searchTerm.value || order.name.toLowerCase().includes(searchTerm.value.toLowerCase()) || order.status.toLowerCase().includes(searchTerm.value.toLowerCase());
        const matchesStatus = !selectedStatus.value || order.status === selectedStatus.value;
        const matchesDelivery = !selectedDelivery.value || order.deliveryDateRange === selectedDelivery.value;
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
</script>
<template>
    <Head title="Orders" />
    <AppLayout>
        <div class="lg:mx-auto max-w-7xl py-8 px-4">
            <!-- Title And Icon -->
            <div class="flex flex-row justify-between">
                <div>
                    <h1 class="text-[24px] mt-3 leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                        Orders
                    </h1>
                </div>
                <div class="flex gap-4 text-gray-600">
                    <SearchList :showable="showable" @hideOrShow="hideOrShow('search')" />
                    <FilterList :showable="showable" @hideOrShow="hideOrShow('filter')" />
                </div>
            </div>
            <!-- Search and filter section -->
            <div>
                <!-- search input -->
                <div class="mt-4 mb-10">
                    <input v-debounce:400ms="myFn" v-if="showable.showSearch" type="text" placeholder="Search..."
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
                                            <Input type="radio" id="create" name="status" label="Create" value="Create"
                                                v-model="selectedStatus" />
                                            <!-- <label for="create" class="ml-2">Create</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="in-progress" name="status" label="In Progress"
                                                value="In Progress" v-model="selectedStatus" />
                                            <!-- <label for="in-progress" class="ml-2 text-black">In Progress</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="trial-done" label="Trial Done" name="status"
                                                value="Trial Done" v-model="selectedStatus" />
                                            <!-- <label for="trial-done" class="ml-2">Trial Done</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="in-alteration" label="In Alteration" name="status"
                                                value="In Alteration" v-model="selectedStatus" />
                                            <!-- <label for="in-alteration" class="ml-2">In Alteration</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="ready-for-delivery" label="Ready for Delivery"
                                                name="status" value="Ready for Delivery" v-model="selectedStatus" />
                                            <!-- <label for="ready-for-delivery" class="ml-2">Ready for Delivery</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="delivered" label="Delivered" name="status"
                                                value="Delivered" v-model="selectedStatus" />
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
                                                Days" name="status" value="Within 7 Days" v-model="selectedDelivery" />
                                            <!-- <label for="Within-7-Days" class="ml-2 text-black">Within 7
                                                Days</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="7-15-Days" label="7-15 Days" name="status"
                                                value="7-15 Days" v-model="selectedDelivery" />
                                            <!-- <label for="7-15-Days" class="ml-2">7-15 Days</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="Overdue" label="Overdue" name="status"
                                                value="Overdue" v-model="selectedDelivery" />
                                            <!-- <label for="Overdue" class="ml-2">Overdue</label><br> -->
                                        </div>
                                        <div>
                                            <Input type="radio" id="One-Month" label="One Month" name="status"
                                                value="One Month" v-model="selectedDelivery" />
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
                <div class="space-y-4">
                    <OrderList v-if="orders?.orders?.length > 0" v-for="order in orders?.orders" :bgColor="bgColor"
                        :borderColor="borderColor" :order="order" />
                    <span v-else>No Orders</span>
                </div>
            </div>
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
