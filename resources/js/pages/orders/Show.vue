<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const order = ref({
    customer_name: 'John Doe',
    order_number: 'ORD123456',
    status: 'Placed',
    total_amount: 15000,
    advance_paid: 5000,
    delivery_date: '2025-06-10',
    close_date: null,
    notes: JSON.stringify({ Label: "Urgent order", Notes: "Ritu" }, null, 2)
})

const orderItems = ref([
    {
        id: 1,
        colors: 'Red, Gold',
        material_type: 'Silk',
        material_code: 'S123',
        work_type: 'Embroidery',
        is_urgent: 'yes',
        status: 'Placed',
        material_cost: 3000,
        stiching_cost: 2000,
        altering_cost: 500,
        item_cost: 5500,
        trial_dates: '2025-06-05',
        delivery_date: '2025-06-10',
        measurements: JSON.stringify({ chest: 36, waist: 28 }, null, 2),
        design_detail: JSON.stringify({ pattern: "Lehenga", neckline: "V-neck" }, null, 2),
        refrence_dress: 'http://example.com/image.jpg',
        notes: JSON.stringify({ label: "Amit", notes: "Extra lining" }, null, 2)
    }
])

const parsedOrderNotes = ref(JSON.parse(order.value.notes))

const parsedItemDetails = orderItems.value.map(item => ({
    ...item,
    parsedNotes: JSON.parse(item.notes),
    parsedDesignDetail: JSON.parse(item.design_detail),
    parsedMeasurements: JSON.parse(item.measurements),
}))

function formatDate(date) {
    return date ? new Date(date).toLocaleDateString() : 'N/A'
}
</script>

<template>

    <Head title="Order Overview" />
    <AppLayout>
        <div class="max-w-6xl mx-auto px-4 py-10 text-gray-800">
            <h1 class="text-4xl font-extrabold text-center text-primary mb-12">🧾 Order Overview</h1>

            <!-- Unified Card for Information + Items -->
            <section class="bg-white shadow-2xl rounded-2xl border border-blue-300 p-8 space-y-10">

                <!-- Order Info Section -->
                <div>
                    <h2 class="text-3xl font-semibold  text-primary flex items-center justify-center gap-2">
                        📦 Information
                    </h2>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6 mt-8 text-sm">
                        <div><span class="font-semibold">👤 Customer Name:</span> {{ order.customer_name }}</div>
                        <div><span class="font-semibold">🧾 Order Number:</span> {{ order.order_number }}</div>
                        <div>
                            <span class="font-semibold">🔖 Status:</span>
                            <span
                                class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs uppercase tracking-wide">{{
                                order.status }}</span>
                        </div>
                        <div><span class="font-semibold">💰 Total Amount:</span> ₹{{ order.total_amount }}</div>
                        <div><span class="font-semibold">💵 Advance Paid:</span> ₹{{ order.advance_paid }}</div>
                        <div><span class="font-semibold">📅 Delivery Date:</span> {{ formatDate(order.delivery_date) }}
                        </div>
                        <div><span class="font-semibold">📦 Close Date:</span> {{ formatDate(order.close_date) }}</div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-yellow-800 mb-2">📝 Notes</h3>
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg text-sm">
                            <div v-for="(value, key) in parsedOrderNotes" :key="key" class="mb-1">
                                <strong class="capitalize">{{ key }}:</strong> {{ value }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items Section -->
                <div>
                    <h2 class="text-3xl font-semibold text-primary mb-6 flex items-center justify-center gap-2">🧵 Items</h2>

                    <div v-for="(item, index) in parsedItemDetails" :key="item.id"
                        class="border border-gray-200 shadow-sm hover:shadow-md transition-shadow rounded-xl p-6 mb-6 bg-gray-50">
                        <h3 class="text-2xl font-bold mb-4 text-primary text-center">Item {{ index + 1 }}</h3>

                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div><span class="font-medium">🎨 Colors:</span> {{ item.colors }}</div>
                            <div><span class="font-medium">🧵 Material Type:</span> {{ item.material_type }}</div>
                            <div><span class="font-medium">📄 Material Code:</span> {{ item.material_code }}</div>
                            <div><span class="font-medium">🧶 Work Type:</span> {{ item.work_type }}</div>
                            <div>
                                <span class="font-medium">⚡ Urgent:</span>
                                <span class="inline-block bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs">{{
                                    item.is_urgent }}</span>
                            </div>
                            <div>
                                <span class="font-medium">📌 Status:</span>
                                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">{{
                                    item.status }}</span>
                            </div>
                            <div><span class="font-medium">🧾 Material Cost:</span> ₹{{ item.material_cost }}</div>
                            <div><span class="font-medium">🧵 Stitching Cost:</span> ₹{{ item.stiching_cost }}</div>
                            <div><span class="font-medium">✂ Altering Cost:</span> ₹{{ item.altering_cost }}</div>
                            <div><span class="font-medium">💰 Item Cost:</span> ₹{{ item.item_cost }}</div>
                            <div><span class="font-medium">🧪 Trial Date:</span> {{ formatDate(item.trial_dates) }}
                            </div>
                            <div><span class="font-medium">📦 Delivery Date:</span> {{ formatDate(item.delivery_date) }}
                            </div>
                        </div>

                        <!-- Measurements -->
                        <div class="mb-4">
                            <h4 class="font-semibold text-indigo-800 mb-1">📏 Measurements</h4>
                            <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-lg text-sm">
                                <div v-for="(value, key) in item.parsedMeasurements" :key="key" class="mb-1">
                                    <strong class="capitalize">{{ key }}:</strong> {{ value }}
                                </div>
                            </div>
                        </div>

                        <!-- Design Detail -->
                        <div class="mb-4">
                            <h4 class="font-semibold text-green-800 mb-1">🎨 Design Detail</h4>
                            <div class="bg-green-50 border border-green-200 p-4 rounded-lg text-sm">
                                <div v-for="(value, key) in item.parsedDesignDetail" :key="key" class="mb-1">
                                    <strong class="capitalize">{{ key }}:</strong> {{ value }}
                                </div>
                            </div>
                        </div>

                        <!-- Reference Dress -->
                        <div class="mb-4">
                            <h4 class="font-semibold">👗 Reference Dress</h4>
                            <a :href="item.refrence_dress" target="_blank"
                                class="text-blue-600 underline break-all text-sm">
                                {{ item.refrence_dress }}
                            </a>
                        </div>

                        <!-- Item Notes -->
                        <div>
                            <h3 class="font-semibold text-yellow-800 mb-1">📝 Item Notes</h3>
                            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg text-sm">
                                <div v-for="(value, key) in item.parsedNotes" :key="key" class="mb-1">
                                    <strong class="capitalize">{{ key }}:</strong> {{ value }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </AppLayout>
</template>
