<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { Icon } from '@iconify/vue';
const props = defineProps({
    closedOrders: Array,
});
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
            </div>
            <div v-if="closedOrders.length" class="space-y-6">
                <div v-for="order in closedOrders" :key="order.id"
                    class="w-full bg-white shadow-md rounded-lg border-l-4 border-primary p-6 hover:shadow-lg transition-shadow">
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
                </div>
            </div>

            <div v-else class="text-center text-gray-400 py-16">
                <p class="text-5xl mb-4">📦</p>
                <p class="text-lg font-medium">No closed orders found</p>
            </div>
        </div>
    </AppLayout>
</template>
