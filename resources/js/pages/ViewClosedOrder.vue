<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
  closedOrders: Array,
});
</script>

<template>
  <Head title="Closed Orders" />
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 py-8">
      <h1 class="text-2xl font-bold text-gray-800 mb-6">Closed Orders</h1>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="order in closedOrders"
          :key="order.id"
          class="bg-white shadow rounded-lg p-4 border-t-4 border-gray-500"
        >
          <h2 class="text-lg font-semibold mb-2">Order #{{ order.id }}</h2>

          <p class="text-sm text-gray-700">Customer Name: {{ order.customer.name ?? 'N/A' }}</p>
          <p class="text-sm text-gray-700">Email: {{ order.customer.email ?? 'N/A' }}</p>
          <p class="text-sm text-gray-700">Phone: {{ order.customer.country_code }} {{ order.customer.phone }}</p>
          <p class="text-sm text-gray-700">Gender: {{ order.customer.gender ?? 'N/A' }}</p>
          <p class="text-sm text-gray-700">Address: {{ order.customer.address ?? 'N/A' }}</p>

          <p class="text-sm text-gray-700 mt-3">Total: ₹{{ order.total_amount }}</p>
          <p class="text-sm text-gray-700">Advance Paid: ₹{{ order.advance_paid }}</p>
          <p class="text-sm text-gray-700">Delivery Date: {{ order.delivery_date }}</p>

          <p class="text-sm text-gray-700 mt-2"><strong>Status:</strong> {{ order.status }}</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
