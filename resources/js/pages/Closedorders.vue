<script setup>
import { ref, onMounted, reactive, nextTick } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Pagination from '@/components/Pagination.vue';
import Loader from '@/components/Loader.vue';
import SearchList from '@/components/SearchIcon.vue';

const page = usePage();
const toast = new ToastMagic();

const props = defineProps({
    deliveredOrders: Object,
    search: String,
});

// Reactive State
const deliveredOrders = ref(props.deliveredOrders);
const showable = reactive({ showSearch: false });
const searchTerm = ref(props.search ?? '');
const form = reactive({ isLoading: false });

// Modal control
const showDeliverModal = ref(false);
const showCloseModal = ref(false);
const selectedOrder = ref(null);

// Fetch Orders
function fetchOrders(url = null, search = '') {
    form.isLoading = true;

    const handleSuccess = (page) => {
        deliveredOrders.value = page.props.deliveredOrders;
    };
    const handleFinish = () => {
        form.isLoading = false;
    };

    if (url) {
        const urlObj = new URL(url, window.location.origin);
        if (search) urlObj.searchParams.set('search', search);
        router.get(urlObj.pathname + urlObj.search, {}, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: handleSuccess,
            onFinish: handleFinish,
        });
    } else {
        const query = {};
        if (search) query.search = search;
        router.get(route('orders.closed'), query, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: handleSuccess,
            onFinish: handleFinish,
        });
    }
}

// Search, pagination
function onSearchInput(val) {
    searchTerm.value = val;
    fetchOrders(null, val);
}

function handlePaginationClick(url) {
    if (!url) return;
    fetchOrders(url, searchTerm.value);
}

const searchInputRef = ref(null);
function focusSearchInput() {
    nextTick(() => {
        searchInputRef.value?.focus();
    });
}

// Modal triggers
function openDeliverModal(order) {
    selectedOrder.value = order;
    showDeliverModal.value = true;
}

function openCloseModal(order) {
    selectedOrder.value = order;
    showCloseModal.value = true;
}

function closeModal() {
    selectedOrder.value = null;
    showDeliverModal.value = false;
    showCloseModal.value = false;
}

// Action handlers
function deliverOrder() {
    if (!selectedOrder.value) return;

    router.post('/orders/deliverd', {
        order_id: selectedOrder.value.id
    }, {
        onSuccess: () => {
            toast.success('Order delivered successfully');
            closeModal();
            router.visit(route('orders.closed'));
        },
        onError: () => {
            toast.error('Failed to deliver order');
        }
    });
}

function closeOrder() {
    if (!selectedOrder.value) return;

    router.post('/orders/close', {
        order_id: selectedOrder.value.id
    }, {
        onSuccess: () => {
            toast.success('Order closed successfully');
            closeModal();
            router.visit(route('orders.closed'));
        },
        onError: () => {
            toast.error('Failed to close order');
        }
    });
}

function viewOrder(orderId) {
    router.visit(route('orders.show', orderId) + '?source=closed');
}

onMounted(() => {
    if (page.props.flash?.success) toast.success(page.props.flash.success);
    if (page.props.flash?.error) toast.error(page.props.flash.error);
});
</script>


<template>

    <Head title="Deliver Orders" />
    <AppLayout>
        <div class="max-w-7xl mx-auto py-10 px-4">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-primary flex items-center gap-2">
                        <Icon icon="material-symbols:order-approve" class="text-primary" width="28" height="28" />
                        Orders Ready For Deliver
                    </h1>
                </div>
                <div class="flex items-center gap-4">
                    <!-- <div class="flex justify-between mb-4"> -->
                    <SearchList :showable="showable" @focusSearch="focusSearchInput" />
                    <!-- </div> -->
                    <Link :href="route('dashboard')" class="flex items-center gap-1 hover:text-black text-gray-600">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-md font-medium">Back</span>
                    </Link>
                </div>
            </div>
            <!-- Search -->

            <div v-if="showable.showSearch" class="mb-6">
                <input ref="searchInputRef" type="text" v-model="searchTerm" @input="() => onSearchInput(searchTerm)"
                    placeholder="Search by Order Number or Customer"
                    class="w-full lg:max-w-7xl border border-gray-300 rounded-full px-4 py-3 text-sm shadow-sm focus:outline-none focus:ring focus:border-gray-400 transition" />
            </div>

            <!-- Loader -->
            <Loader v-if="form.isLoading" />
            <!-- dashboard view -->
            <div v-if="deliveredOrders && deliveredOrders.data && deliveredOrders.data.length"
                class="overflow-x-auto bg-white shadow-lg rounded-xl border border-gray-200  hidden lg:block">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Order Number</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Amount</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Delivery Date</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Customer</th>


                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <tr v-if="deliveredOrders && deliveredOrders.data && deliveredOrders.data.length === 0">

                            <td colspan="7" class="text-center text-gray-500 py-6">
                                No delivered orders found.
                            </td>
                        </tr>
                        <tr v-else v-for="order in deliveredOrders.data" :key="order.id"
                            class="hover:bg-blue-50 transition duration-200 ease-in-out">
                            <td class="px-6 py-4 font-medium text-center text-gray-800 whitespace-nowrap">#{{
                                order.order_number }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700 whitespace-nowrap">₹{{ order.total_amount }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700 whitespace-nowrap">{{ order.delivery_date }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700 whitespace-nowrap">{{ order.customer?.name ??
                                'N/A' }}
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <!-- Action buttons -->
                                <div class="flex justify-center items-center gap-2">
                                    <!-- View Button -->
                                    <div class="relative group">
                                        <button @click="viewOrder(order.id)"
                                            class="p-2 rounded-full hover:bg-gray-100 transition relative">
                                            <Icon icon="ic:round-visibility" class="text-primary" width="20"
                                                height="20" />
                                        </button>
                                        <!-- Tooltip -->
                                        <div
                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-xs text-white bg-gray-800 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 whitespace-nowrap">
                                            View Order
                                        </div>
                                    </div>

                                    <!-- Deliver Order Button -->
                                    <button @click="openDeliverModal(order)"
                                        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                                        Deliver
                                    </button>

                                    <!-- NEW BUTTON: Deliver/Closed Order -->
                                    <button @click="openCloseModal(order)"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                                        Deliver & Closed
                                    </button>
                                </div>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else class="text-center text-gray-400 py-16">
                <p class="text-5xl mb-4">📦</p>
                <p class="text-lg font-medium">No Delivered Orders found</p>
            </div>
            <!-- Mobile View -->
            <div v-if="deliveredOrders && deliveredOrders.data && deliveredOrders.data.length"
                class="lg:hidden space-y-4 mt-6">
                <div v-for="order in deliveredOrders.data" :key="order.id"
                    class="bg-white shadow-md rounded-lg border border-primary p-4">
                    <div class="mb-2 flex">
                        <p class="text-lg text-gray-500">Order Number : </p>
                        <p class="font-semibold text-gray-800"> #{{ order.order_number }}</p>
                    </div>

                    <div class="mb-2 flex">
                        <p class="text-lg text-gray-500">Amount :</p>
                        <p class="text-gray-700">₹{{ order.total_amount }}</p>
                    </div>

                    <div class="mb-2 flex">
                        <p class="text-lg text-gray-500">Delivery Date : </p>
                        <p class="text-gray-700">{{ order.delivery_date }}</p>
                    </div>

                    <div class="mb-4 flex">
                        <p class="text-lg text-gray-500">Customer : </p>
                        <p class="text-gray-700">{{ order.customer?.name ?? 'N/A' }}</p>
                    </div>

                    <div class="flex justify-between items-center gap-3 mt-4">
                        <button @click="viewOrder(order.id)"
                            class="flex items-center gap-1 text-primary text-sm font-medium">
                            <Icon icon="ic:round-visibility" class="text-primary" width="18" height="18" />
                            View
                        </button>

                        <button @click="openDeliverModal(order)"
                            class="bg-primary text-white px-3 py-1.5 rounded-md text-sm font-medium shadow-sm">
                            Deliver
                        </button>

                        <!-- NEW BUTTON -->
                        <button @click="openCloseModal(order)"
                            class="bg-green-600 text-white px-3 py-1.5 rounded-md text-sm font-medium shadow-sm">
                            Deliver & Closed
                        </button>
                    </div>

                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                <Pagination v-if="deliveredOrders && deliveredOrders.links" :links="deliveredOrders.links"
                    :onPageClick="handlePaginationClick" />
            </div>
        </div>

        <!-- Modal -->
        <!-- Deliver Modal -->
        <div v-if="showDeliverModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Confirm Delivery</h2>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to mark <strong>Order #{{ selectedOrder.order_number }}</strong> as delivered?
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="closeModal"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition">
                        Cancel
                    </button>
                    <button @click="deliverOrder" class="px-4 py-2 bg-primary text-white rounded-md transition">
                        Confirm
                    </button>
                </div>
            </div>
        </div>

        <!-- Close Modal -->
        <div v-if="showCloseModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Confirm Delivery & Close</h2>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to <strong>deliver & close</strong> <strong>Order #{{
                        selectedOrder.order_number }}</strong>?
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="closeModal"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition">
                        Cancel
                    </button>
                    <button @click="closeOrder" class="px-4 py-2 bg-green-600 text-white rounded-md transition">
                        Confirm
                    </button>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
