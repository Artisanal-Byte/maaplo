<script setup>
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const props = defineProps({
    deliveredOrders: Array
});

const showModal = ref(false);
const selectedOrder = ref(null);

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
            closeModal();
        }
    });
}
</script>

<template>

    <Head title="Closed Orders" />
    <AppLayout>
        <div class="max-w-7xl mx-auto py-10 px-4">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Delivered Orders</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="order in deliveredOrders" :key="order.id"
                    class="bg-white shadow rounded-lg p-4 border-t-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-semibold">Order #{{ order.id }}</h2>
                            <p class="text-sm text-gray-600">Status: {{ order.status }}</p>
                            <p class="text-sm text-gray-600">Total: ₹{{ order.total_amount }}</p>
                            <p class="text-sm text-gray-600">Advance: ₹{{ order.advance_paid }}</p>
                            <p class="text-sm text-gray-600">Delivery Date: {{ order.delivery_date }}</p>

                            <hr class="my-2" />

                            <p class="text-sm font-medium text-gray-800">Customer Info:</p>
                            <p class="text-sm text-gray-600">Name: {{ order.customer?.name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">Email: {{ order.customer?.email ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">Phone: {{ order.customer?.country_code }} {{
                                order.customer?.phone }}</p>
                            <p class="text-sm text-gray-600">Gender: {{ order.customer?.gender }}</p>
                            <p class="text-sm text-gray-600">Address: {{ order.customer?.address }}</p>
                        </div>
                        <Icon icon="ph:check-circle-fill" class="text-green-600" width="28" height="28" />
                    </div>

                    <div class="mt-4 text-right">
                        <button @click="openCloseOrderModal(order)"
                            class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-md">
                            Close Order
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg w-[90%] max-w-md p-6">
                <h2 class="text-xl font-semibold mb-4">Confirm Close</h2>
                <p class="mb-4">Are you sure you want to close Order #{{ selectedOrder.id }}?</p>
                <div class="flex justify-end gap-4">
                    <button @click="closeModal"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-gray-800">
                        Cancel
                    </button>
                    <button @click="closeOrder" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-white">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
