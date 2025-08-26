<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { Icon } from '@iconify/vue';
import InvoiceGenerator from '@/components/InvoiceGenerator.vue';
const props = defineProps({
    order: Object,
    designDetails: Object,
    source: String,
    allDesignDetails: Array,
});
const page = usePage()
const routeSource = computed(() => {
    if (page.url.includes('source=fullclosed')) {
        return 'fullclosed';
    } else if (page.url.includes('source=closed')) {
        return 'closed';
    } else {
        return 'normal';
    }
});
const parsedItemDetails = computed(() => {
    return props.order?.order_items?.map(item => ({
        ...item,
        parsedNotes: JSON.parse(item?.notes || '[]'),
        parsedDesignDetail: JSON.parse(item?.design_detail || '[]'),
        parsedMeasurements: JSON.parse(item?.measurements || '{}'),
    })) || []
})
const showModal = ref(false)
const selectedImage = ref(null)
const showBill = ref(false)
function openImageModal(imageUrl) {
    selectedImage.value = imageUrl
    showModal.value = true
}

function closeModal() {
    showModal.value = false
    selectedImage.value = null
}

const pendingAmount = computed(() => {
    const total = Number(props.order?.total_amount) || 0;
    const advance = Number(props.order?.advance_paid) || 0;
    return total - advance;
});


const designDetailMap = computed(() => {
    const map = {};
    props.allDesignDetails?.forEach(detail => {
        map[detail.id] = detail;
    });
    return map;
});

function formatKey(key) {
    if (!key) return 'N/A'
    return key
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ')
}

function capitalizeFirst(str) {
    if (!str) return 'N/A'
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase()
}


</script>

<template>

    <Head title="Order-Overview" />
    <AppLayout>
        <div class="max-w-6xl mx-auto px-4 py-10 text-gray-800">
            <!-- Header -->
            <div class="relative mb-6 h-10 flex items-center">
                <!-- Centered Heading -->
                <!-- <h1 class="absolute  transform text-xl lg:text-3xl font-extrabold text-primary">
                    🧾 Order Overview
                </h1> -->
                <h1 class="text-xl lg:text-3xl font-extrabold text-primary">
                    {{
                        routeSource === 'fullclosed'
                            ? '📦 Fully Closed Order Overview'
                            : routeSource === 'closed'
                                ? '🧾 Closed Order Overview'
                                : '🧾 Order Overview'
                    }}
                </h1>
                <!-- Back Link on the right -->
                <Link :href="route(routeSource === 'closed' ? 'orders.closed' : 'orders.index')"
                    class="ml-auto flex items-center gap-2 text-gray-500 hover:text-primary transition-colors duration-200">
                <Icon icon="material-symbols:arrow-back-rounded" width="26" height="26" />
                <span class="font-semibold text-lg">Back</span>
                </Link>
            </div>


            <!-- Unified Card for Information + Items -->
            <section class="bg-white shadow-2xl rounded-2xl border border-primary p-4 lg:p-8 space-y-10">

                <!-- Order Info Section -->
                <div>
                    <div class="flex justify-between items-center">
                    <div>
                        <h2
                            class="text-xl lg:text-3xl font-semibold text-primary items-center gap-2">
                            Order Information
                        </h2>
                    </div>
                    <div>
                        <button @click="showBill = true"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            🧾 Generate Bill
                        </button>
                    </div>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6 mt-8 text-sm">
                        <div><span class="font-semibold">Customer Name:</span> {{ order?.customer?.name ?? 'N/A' }}
                        </div>
                        <div><span class="font-semibold">Order Number:</span> {{ order?.order_number ?? 'N/A' }}
                        </div>
                        <div>
                            <span class="font-semibold">Status:</span>
                            <span
                                class="inline-block bg-blue-100 text-primary px-3 py-1 rounded-full text-xs uppercase tracking-wide">{{
                                    order?.status }}</span>
                        </div>
                        <div><span class="font-semibold">Grand Total:</span> ₹ {{ order?.total_amount }} </div>
                        <div><span class="font-semibold">Advance Paid:</span> ₹ {{ order?.advance_paid ?? '00.00' }}
                        </div>
                        <div><span class="font-semibold text-red-600">Pending Amount:</span>
                            ₹ {{ pendingAmount.toLocaleString('en-IN',
                                { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                        </div>



                        <div><span class="font-semibold">Delivery Date:</span> {{ order?.delivery_date ?? '-' }}
                        </div>
                        <div><span class="font-semibold">Close Date:</span> {{
                            order?.close_date
                                ? new Date(order.close_date).toLocaleDateString('en-GB')
                                : '-'
                        }}</div>

                    </div>

                    <div>
                        <h3 class="font-semibold text-yellow-800 mb-2 text-lg">📝 Notes</h3>
                        <div class="bg-yellow-50 border border-yellow-200 p-2 lg:p-4 rounded-lg text-sm">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div v-for="(note, noteIndex) in order?.notes || []" :key="noteIndex"
                                    class="border border-yellow-100 p-3 rounded-md">
                                    <div><strong class="text-gray-700">Label:</strong> {{ note.label ?? 'N/A' }}</div>
                                    <div><strong class="text-gray-700">Text:</strong> {{ note.text ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items Section -->
                <div>
                    <h2
                        class="text-xl lg:text-3xl font-semibold text-primary mb-7 flex items-center justify-center gap-2">
                        Items
                    </h2>

                    <div v-for="(item, index) in parsedItemDetails" :key="item?.id"
                        class="border border-gray-200 shadow-sm hover:shadow-md transition-shadow rounded-xl p-4 lg:p-6 mb-6 bg-gray-50">
                        <h3 class="text-2xl font-bold mb-4 text-primary text-center">Item {{ index + 1 }}</h3>

                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div><span class="font-semibold">Colors :</span> {{ item?.colors }}</div>
                            <div><span class="font-semibold">Material Type :</span> {{
                                capitalizeFirst(item?.material_type) ?? 'N/A'
                                }}</div>
                            <div><span class="font-semibold">Material Code :</span> {{ item?.material_code ?? 'N/A'
                            }}
                            </div>
                            <div><span class="font-semibold">Work Type :</span> {{ item?.work_type ?? 'N/A' }}</div>
                            <div>
                                <span class="font-semibold">Urgent:</span>
                                <span class="inline-block bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs">{{
                                    item?.is_urgent === true ? 'True' : 'False' }}
                                </span>
                            </div>
                            <div>
                                <span class="font-semibold">Status:</span>
                                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">{{
                                    item?.status }}</span>
                            </div>
                            <div><span class="font-semibold">Material Cost:</span> ₹ {{ item?.material_cost ??
                                '0.00' }}</div>
                            <div><span class="font-semibold">Stitching Cost:</span> ₹ {{ item?.stiching_cost ??
                                '0.00' }}</div>
                            <div><span class="font-semibold">Altering Cost:</span> ₹ {{ item?.altering_cost ?? '00.0'
                            }}
                            </div>
                            <div><span class="font-semibold">Item Cost:</span> ₹ {{ item?.item_cost }}</div>
                            <div><span class="font-semibold">Trial Date:</span> {{ item?.trial_dates }}
                            </div>
                            <div><span class="font-semibold">Delivery Date:</span> {{ item?.delivery_date }}
                            </div>
                        </div>

                        <!-- Measurements -->
                        <div class="mb-4">
                            <h4 class="font-semibold text-indigo-800 mb-1 text-lg">📏 Measurements</h4>
                            <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-lg text-sm">
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-0 lg:gap-5">
                                    <div v-for="(value, key) in item?.parsedMeasurements" :key="key" class="mb-1">
                                        <strong class="capitalize">{{ formatKey(key) }} :</strong> {{ value ? value :
                                            'N/A'
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Design Detail -->
                        <div class="mb-4">
                            <h4 class="font-semibold text-green-800 mb-1 text-lg">🎨 Design Detail</h4>
                            <div class="bg-green-50 border border-green-200 p-4 rounded-lg text-sm space-y-4">
                                <div v-for="(designDetailId, bodyPartName) in item.parsedDesignDetail"
                                    :key="bodyPartName" class="flex justify-between items-start gap-4 border-b pb-3">
                                    <!-- Left: Text info -->
                                    <div class="flex-1">
                                        <div><strong>Body Part:</strong> {{ bodyPartName }}</div>
                                        <div><strong>Body Section:</strong> {{ designDetailMap[designDetailId]?.value ||
                                            'N/A' }}</div>
                                    </div>

                                    <!-- Right: SVG image -->
                                    <div v-if="designDetailMap[designDetailId]?.image"
                                        class="w-18 h-18 flex-shrink-0 mr-4"
                                        v-html="designDetailMap[designDetailId].image"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Item Notes -->
                        <div>
                            <h3 class="font-semibold text-yellow-800 mb-1 text-lg">📝 Item Notes</h3>
                            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg text-sm">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2"
                                    v-if="item?.parsedNotes && item.parsedNotes.length">
                                    <div v-for="(note, noteIndex) in item.parsedNotes" :key="noteIndex">
                                        <div><strong class="text-gray-700">Label:</strong> {{ note.label || 'N/A' }}
                                        </div>
                                        <div><strong class="text-gray-700">Text:</strong> {{ note.text || 'N/A' }}</div>
                                    </div>
                                </div>
                                <div v-else class="text-gray-500 text-sm">No notes available.</div>
                            </div>
                        </div>
                        <!-- Reference Dress & Other Images -->
                        <div class="mt-6">
                            <h4 class="font-semibold mb-4 text-lg">Reference Dress & Other Images</h4>
                            <div class="flex flex-col md:flex-row gap-8">

                                <!-- Left: Reference Dress -->
                                <div class="flex-1">
                                    <h5 class="font-semibold text-sm mb-2">Reference Image</h5>
                                    <h6 class="font-semibold text-xs mb-2 text-green-700"> Reference Dress
                                    </h6>
                                    <div v-if="item.refrence_dress"
                                        class="w-[63px] h-[63px] overflow-hidden rounded-md border shadow cursor-pointer"
                                        @click="openImageModal(`/${item.refrence_dress}`)">
                                        <img :src="`/${item.refrence_dress}`" alt="Reference Dress"
                                            class="object-cover w-full h-full" />
                                        <p class="text-xs mt-1 text-center break-all">refrence_dress</p>
                                    </div>
                                    <div v-else class="text-gray-400 text-xs">No Reference Dress available.</div>
                                </div>

                                <!-- Right: Other Images -->
                                <div class="flex-1">
                                    <h5 class="font-semibold text-sm mb-2">Material & Pattern Images</h5>

                                    <div class="flex flex-col md:flex-row gap-8">
                                        <!-- Cloth Images Column -->
                                        <div class="flex-1">
                                            <h6 class="font-semibold text-xs mb-2 text-green-700">Material Images
                                            </h6>
                                            <div v-if="item.cloth_img1 || item.cloth_img2"
                                                class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                                <template v-for="field in ['cloth_img1', 'cloth_img2']" :key="field">
                                                    <div v-if="item[field]"
                                                        class="w-full aspect-square overflow-hidden rounded-md border shadow cursor-pointer"
                                                        @click="openImageModal(`/${item[field]}`)">
                                                        <img :src="`/${item[field]}`" :alt="field"
                                                            class="object-cover w-full h-full" />
                                                        <p class="text-xs mt-1 text-center break-all">{{ field }}</p>
                                                    </div>
                                                </template>
                                            </div>
                                            <div v-else class="text-gray-400 text-xs">No cloth images available.</div>
                                        </div>

                                        <!-- Pattern Images Column -->
                                        <div class="flex-1">
                                            <h6 class="font-semibold text-xs mb-2 text-purple-700">Pattern Images
                                            </h6>
                                            <div v-if="item.Pattern_img1 || item.Pattern_img2"
                                                class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                                <template v-for="field in ['Pattern_img1', 'Pattern_img2']"
                                                    :key="field">
                                                    <div v-if="item[field]"
                                                        class="w-full aspect-square overflow-hidden rounded-md border shadow cursor-pointer"
                                                        @click="openImageModal(`/${item[field]}`)">
                                                        <img :src="`/${item[field]}`" :alt="field"
                                                            class="object-cover w-full h-full" />
                                                        <p class="text-xs mt-1 text-center break-all">{{ field }}</p>
                                                    </div>
                                                </template>
                                            </div>
                                            <div v-else class="text-gray-400 text-xs">No pattern images available.</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <InvoiceGenerator :order="order" :show="showBill" @close="showBill = false" />
                <!-- Image Modal -->
                <div v-if="showModal"
                    class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300">
                    <div
                        class="relative bg-white rounded-lg overflow-hidden max-w-4xl w-full max-h-[90vh] p-4 transform transition-transform duration-300">
                        <button @click="closeModal" class="absolute top-3 right-3 text-primary text-4xl font-bold z-10">
                            &times;
                        </button>
                        <img :src="selectedImage" class="w-full h-auto max-h-[80vh] object-contain" />
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
