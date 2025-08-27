<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { defineProps, ref, reactive, computed, nextTick } from 'vue';
import Pagination from '@/components/Pagination.vue';
import Loader from '@/components/Loader.vue';
import SearchList from '@/components/SearchIcon.vue';
import { Icon } from '@iconify/vue';
const props = defineProps({
    closedOrders: Object,
    search: String,
});

const showable = reactive({
    showSearch: false,
});
const searchTerm = ref(props.search ?? '');
const form = reactive({ isLoading: false });

function myFn(val) {
    searchTerm.value = val;
    form.isLoading = true;
    router.get(route('orders.viewClosed'), {
        search: val
    }, {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
            form.isLoading = false;
        }
    });
}


function handlePaginationClick(url) {
    form.isLoading = true;
    router.get(url, {}, {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
            form.isLoading = false;
        }
    });
}

const searchInputRef = ref(null);
function focusSearchInput() {
    nextTick(() => {
        searchInputRef.value?.focus();
    });
}

function viewOrder(orderId) {
    router.visit(route('orders.show', orderId) + '?source=fullclosed');
}

function closeOrder(orderId) {
    form.isLoading = true;
    router.post(route('orders.close'), {
        order_id: orderId,
    }, {
        preserveScroll: true,
        onFinish: () => {
            form.isLoading = false;
        }
    });
}


const showModal = ref(false);
const selectedOrder = ref(null);

function openModal(order) {
    selectedOrder.value = order;
    showModal.value = true;
}

function closeModal() {
    selectedOrder.value = null;
    showModal.value = false;
}

function confirmCloseOrder() {
    if (!selectedOrder.value) return;

    form.isLoading = true;
    router.post(route('orders.close'), {
        order_id: selectedOrder.value.id,
    }, {
        preserveScroll: true,
        onFinish: () => {
            form.isLoading = false;
            closeModal();
        }
    });
}

</script>

<template>

    <Head title="Close Orders" />
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 py-10">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-primary flex items-center gap-2">
                        <Icon icon="material-symbols:order-approve" class="text-primary" width="28" height="28" />
                        Close Orders
                    </h1>
                    <h1>THis is close page</h1>
                </div>
                <div class="flex items-center gap-4">
                    <!-- 🔍 Search -->
                    <!-- <div class="flex justify-between mb-4"> -->
                    <SearchList :showable="showable" @focusSearch="focusSearchInput" />
                    <!-- </div> -->
                    <Link :href="route('orders.index')" class="flex items-center gap-1 hover:text-black text-gray-600">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-md font-medium">Back</span>
                    </Link>
                </div>
            </div>

            <div class="mb-6" v-if="showable.showSearch">
                <input ref="searchInputRef" type="text" v-debounce:400ms="myFn" placeholder="Search..."
                    class="w-full lg:max-w-7xl border border-gray-300 rounded-full px-4 py-3 text-sm shadow-[0px_0px_4.3px_0px_#16789333] focus:outline-none focus:ring focus:border-gray-400 transition-all" />
            </div>

            <!-- ⏳ Loader -->
            <Loader v-if="form.isLoading" />

            <div v-if="closedOrders && closedOrders.data && closedOrders.data.length" class="space-y-6">

                <div v-for="order in closedOrders.data" :key="order.id"
                    class="w-full bg-white shadow-md rounded-lg border-2 border-green-500 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-primary">Order Number #{{ order.order_number }}</h2>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700 text-sm">
                        <p><strong>Customer Name :</strong> {{ order.customer?.name ?? 'N/A' }}</p>
                        <p><strong>Phone :</strong> {{ order.customer?.country_code }} {{ order.customer?.phone }}</p>
                        <p><strong>Address :</strong> {{ order.customer?.address ?? 'N/A' }}</p>
                        <p><strong>Delivery Date :</strong> {{ order.delivery_date }}</p>
                        <p class="md:col-span-2"><strong>Total Amount :</strong> ₹{{ order.total_amount }}</p>
                    </div>
                    <div class="flex justify-end mt-4 group relative">
                        <button @click="viewOrder(order.id)" class="p-2 rounded-full hover:bg-gray-100 transition">
                            <Icon icon="ic:round-visibility" class="text-primary" width="24" height="24" />
                        </button>
                        <!-- Close Order Button -->
                        <button @click="openModal(order)"
                            class="ml-2 px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition">
                            Close Order
                        </button>

                        <!-- Tooltip -->
                        <div
                            class="absolute bottom-full right-0 mb-2 px-3 py-1 text-xs text-white bg-gray-800 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                            View Order
                        </div>
                    </div>


                    <!-- Tooltip -->
                    <div
                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-xs text-white bg-gray-800 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 whitespace-nowrap">
                        View Order
                    </div>
                </div>

            </div>

            <div v-else class="text-center text-gray-400 py-16">
                <p class="text-5xl mb-4">📦</p>
                <p class="text-lg font-medium">No closed orders found</p>
            </div>

            <!-- 📄 Pagination -->
            <div class="mt-8">
                <Pagination :links="closedOrders?.links ?? []" :onPageClick="handlePaginationClick" />

            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Confirm Delivered</h2>
                <p class="text-sm font-medium text-primary px-4 py-2 rounded-md mb-4 flex items-center gap-2">
                    Payment received for this order?
                </p>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to mark <strong>Order #{{ selectedOrder?.order_number }}</strong> as
                    Closed?
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="closeModal"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition">
                        No
                    </button>
                    <button @click="confirmCloseOrder" class="px-4 py-2 bg-primary text-white rounded-md transition">
                        Yes
                    </button>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
