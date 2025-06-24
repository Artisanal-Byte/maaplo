<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const page = usePage();
const toast = new ToastMagic();

const props = defineProps({
    deliveredOrders: Array
});

const showModal = ref(false);
const selectedOrder = ref(null);
const deliveredOrders = ref(props.deliveredOrders);

onMounted(() => {
    if (page.props.flash?.success) {
        toast.success(page.props.flash.success);
    }
    if (page.props.flash?.error) {
        toast.error(page.props.flash.error);
    }
});

function openCloseOrderModal(order) {
    selectedOrder.value = order;
    showModal.value = true;
}

function closeModal() {
    selectedOrder.value = null;
    showModal.value = false;
}

function closeOrder() {
    if (!selectedOrder.value) return;

    router.post('/orders/close', {
        order_id: selectedOrder.value.id
    }, {
        onSuccess: () => {
            deliveredOrders.value = deliveredOrders.value.filter(
                order => order.id !== selectedOrder.value.id
            );
            toast.success('Order closed successfully'); // ✅ Toast on success
            closeModal();
        },
        onError: () => {
            toast.error('Failed to close order'); // ✅ Toast on failure
        }
    });
}

function viewOrder(orderId) {
    router.visit(route('orders.show', orderId) + '?source=closed');
}
</script>

<template>

    <Head title="Delivered Orders" />
    <AppLayout>
        <div class="max-w-7xl mx-auto py-10 px-4">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <Icon icon="mdi:truck-delivery-outline" class="text-blue-600" width="28" height="28" />
                    Delivered Orders
                </h1>
            </div>

            <div class="overflow-x-auto bg-white shadow-md rounded-lg border border-gray-200">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Order Number</th>
                            <th class="px-6 py-3 text-left">Total Amount</th>
                            <th class="px-6 py-3 text-left">Delivery Date</th>
                            <th class="px-6 py-3 text-left">Customer Name</th>
                            <th class="px-6 py-3 text-left">Phone</th>
                            <th class="px-6 py-3 text-left">Address</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="deliveredOrders.length === 0">
                            <td colspan="7" class="text-center text-gray-500 py-6">
                                No delivered orders found.
                            </td>
                        </tr>
                        <tr v-else v-for="order in deliveredOrders" :key="order.id"
                            class="hover:bg-blue-50 transition-all border-t border-gray-100">
                            <td class="px-6 py-4 font-medium text-gray-800">#{{ order.order_number }}</td>
                            <td class="px-6 py-4 text-gray-700">₹{{ order.total_amount }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ order.delivery_date }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ order.customer?.name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ order.customer?.country_code }} {{ order.customer?.phone }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ order.customer?.address }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-3">
                                    <!-- View Icon Button -->
                                    <button @click="viewOrder(order.id)"
                                        class="p-2 rounded-md hover:bg-gray-100 transition" title="View Order">
                                        <Icon icon="ic:round-visibility" class="text-primary" width="20" height="20" />
                                    </button>

                                    <!-- Close Order Button -->
                                    <button @click="openCloseOrderModal(order)"
                                        class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium transition hover:bg-primary/90">
                                        Close Order
                                    </button>
                                </div>


                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Confirm Close</h2>
                <p class="text-gray-600 mb-6">Are you sure you want to close <strong>Order #{{
                    selectedOrder.order_number
                        }}</strong> ?</p>
                <div class="flex justify-end gap-3">
                    <button @click="closeModal"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition">
                        No
                    </button>
                    <button @click="closeOrder" class="px-4 py-2 bg-primary text-white rounded-md transition">
                        Yes
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
