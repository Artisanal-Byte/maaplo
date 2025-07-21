<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router,Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { Icon } from '@iconify/vue';
const props = defineProps({
    closedOrders: Array,
});

function viewOrder(orderId) {
    router.visit(route('orders.show', orderId) + '?source=fullclosed');
}
</script>

<template>

    <Head title="Closed Orders" />
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 py-10">

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-primary flex items-center gap-2">
                    <Icon icon="material-symbols:order-approve" class="text-primary" width="28" height="28" />
                    Closed Orders
                </h1>
                  <Link :href="route('orders.index')"
                    class="flex items-center gap-1 hover:text-black text-gray-600">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>
            <div v-if="closedOrders.length" class="space-y-6">
                <div v-for="order in closedOrders" :key="order.id"
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
        </div>
    </AppLayout>
</template>
