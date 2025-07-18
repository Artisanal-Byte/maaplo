<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage,Link } from '@inertiajs/vue3';
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
            toast.success('Order closed successfully');
            closeModal();
        },
        onError: () => {
            toast.error('Failed to close order');
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
                <h1 class="text-3xl font-bold text-primary flex items-center gap-2">
                    <Icon icon="material-symbols:order-approve" class="text-primary" width="28" height="28" />
                    Delivered Orders
                </h1>
                  <Link :href="route('dashboard')"
                    class="flex items-center gap-1 hover:text-black text-gray-600">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>

            <div class="overflow-x-auto bg-white shadow-lg rounded-xl border border-gray-200">
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
                        <tr v-if="deliveredOrders.length === 0">
                            <td colspan="7" class="text-center text-gray-500 py-6">
                                No delivered orders found.
                            </td>
                        </tr>
                        <tr v-else v-for="order in deliveredOrders" :key="order.id"
                            class="hover:bg-blue-50 transition duration-200 ease-in-out">
                            <td class="px-6 py-4 font-medium text-center text-gray-800 whitespace-nowrap">#{{ order.order_number }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700 whitespace-nowrap">₹{{ order.total_amount }}</td>
                            <td class="px-6 py-4 text-center text-gray-700 whitespace-nowrap">{{ order.delivery_date }}</td>
                            <td class="px-6 py-4 text-center text-gray-700 whitespace-nowrap">{{ order.customer?.name ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex justify-center items-center gap-2">
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

                                    <button @click="openCloseOrderModal(order)"
                                        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
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
                <p
                    class="text-sm font-medium text-primary px-4 py-2 rounded-md mb-4 flex items-center gap-2">
                    Payment received for this order ?
                </p>
                <p class="text-gray-600 mb-6">
                    Are you sure you want to close <strong>Order #{{ selectedOrder.order_number }}</strong>?
                </p>
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
