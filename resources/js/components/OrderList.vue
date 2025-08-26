<script setup>
import { Icon } from '@iconify/vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps({
    bgColor: {
        default: '#FFFCE6'
    },
    borderColor: {
        type: String,
        default: '#837200'
    },
    order: {
        type: Object,
        required: true
    }

});
const showCloseModal = ref(false);
// Modal control
const showDeleteModal = ref(false);
const selectedOrderId = ref(null);
const selectedOrder = ref(null);
const toast = new ToastMagic();
const loading = ref(false);

const formatStatus = (status) => {
    if (!status) return '';
    return status
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};
function openDeleteModal(id) {
    selectedOrderId.value = id;
    showDeleteModal.value = true;
}
function confirmDelete() {
    router.delete(route('orders.destroy', selectedOrderId.value), {
        onSuccess: () => {
            toast.success('Order deleted successfully!');
        },
        onError: () => {
            toast.error('Failed to delete the order.');
        },
        onFinish: () => {
            showDeleteModal.value = false;
            selectedOrderId.value = null;
        }
    });
}

function openCloseModal(id) {
    selectedOrder.value = props.order;
    selectedOrderId.value = id;
    showCloseModal.value = true;
}

function confirmClose() {
    router.post(route('orders.close'), {
        order_id: selectedOrder.value.id
    }, {
        onSuccess: () => {

            showCloseModal.value = false;
            selectedOrderId.value = null;
            toast.success('Order closed successfully!');  // Show ToastMagic toast here
        },
        onError: (errors) => {
            toast.error('Failed to close order.');
            console.error(errors);
        }
    });
}

</script>
<template>

    <!-- dashbord view -->
    <div class=" hidden lg:block gap-[10px] rounded-[10px] px-[10px] py-[17px] mt-[6px]" :style="{
        backgroundColor: bgColor,
        border: `1px solid ${borderColor}`,

    }">
        <div class="flex flex-row">
            <div>
                <h1
                    class="font-[Lato] font-medium text-[15px] lg:text-[18px] leading-[16px] tracking-[0] text-secondary p-2">
                    Order: #{{ order.order_number }}</h1>
            </div>
            <div>
                <h1
                    class="font-[Lato] font-medium text-[15px] lg:text-[18px] leading-[16px] tracking-[0] text-secondary p-2">
                    Customer: {{ order?.customer?.name }}
                </h1>
            </div>

        </div>
        <div class="flex flex-row justify-between">
            <div>
                <p
                    class="font-[Lato] font-medium text-[16px] leading-[16px] tracking-[0] text-center text-secondary p-2">
                    Items: {{ order?.order_items?.length }}</p>
            </div>
            <div>
                <p
                    class="font-[Lato] font-medium text-[16px] leading-[16px] tracking-[0] text-center text-secondary p-2">
                    Delivery Date: {{ order?.delivery_date }}</p>
            </div>
        </div>
        <div>
            <p class="font-[Lato] font-medium text-[16px] leading-[16px] tracking-[0]  text-secondary p-2">
                Status: {{ formatStatus(order?.status) }}
            </p>
        </div>


        <div class="flex justify-end items-center gap-3 mt-4">
            <!-- Close Order -->
            <button @click="openCloseModal(order)"
                class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                Close Order
            </button>
            <!-- View Order -->
            <Link :href="route('orders.show', props.order.id)">
            <Icon icon="teenyicons:eye-solid" width="18" height="18" class="text-primary" />
            </Link>

            <!-- Edit Order -->
            <Link :href="route('orders.edit', props.order.id)">
            <Icon icon="ri:edit-fill" width="18" height="18" class="text-primary" />
            </Link>

            <!-- Delete Order -->
            <button @click="openDeleteModal(order.id)">
                <Icon icon="ic:baseline-delete" width="18" height="18" class="text-[#E73939]" />
            </button>


        </div>
        <!-- Close Order Modal -->
        <div v-if="showCloseModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Confirm Close</h2>

                <p class="text-sm font-medium text-primary px-4 py-2 rounded-md mb-4 flex items-center gap-2">
                    Payment received for this order?
                </p>

                <p class="text-gray-600 mb-6">
                    Are you sure you want to close <strong>Order #{{ selectedOrder?.order_number }}</strong>?
                </p>

                <div class="flex justify-end gap-3">
                    <button @click="showCloseModal = false"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition">
                        No
                    </button>
                    <button @click="confirmClose"
                        class="px-4 py-2 bg-primary text-white hover:bg-primary-dark rounded-md transition">
                        Yes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Order Card -->
    <div class="lg:hidden block rounded-xl mt-3 shadow-md overflow-hidden" :style="{
        backgroundColor: bgColor,
        border: `1px solid ${borderColor}`,
    }">
        <!-- Header -->
        <div class="flex justify-between gap-1 px-4 py-3 border-b">
            <h2 class="font-lato text-lg text-secondary font-semibold">
                Customer: {{ order?.customer?.name }}
            </h2>
            <h1 class="font-lato font-semibold text-base text-secondary">
                Order: #{{ order.order_number }}
            </h1>

        </div>

        <!-- Details -->
        <div class="flex flex-col gap-2 px-4 py-3 text-sm">
            <p class="font-medium text-secondary">
                Items: <span class="font-normal">{{ order?.order_items?.length }}</span>
            </p>
            <p class="font-medium text-secondary">
                Delivery Date: <span class="font-normal">{{ order?.delivery_date }}</span>
            </p>
            <p class="font-medium text-secondary">
                Status: <span class="font-normal">{{ formatStatus(order?.status) }}</span>
            </p>
        </div>

        <!-- Actions -->
        <div class="bg-primary px-4 py-3 mt-5">
            <div class="flex justify-between px-20 items-center mb-3">
                <!-- View -->
                <Link :href="route('orders.show', props.order.id)">
                <Icon icon="teenyicons:eye-solid" width="20" height="20" class="text-white" />
                </Link>
                <!-- Edit -->
                <Link :href="route('orders.edit', props.order.id)">
                <Icon icon="ri:edit-fill" width="20" height="20" class="text-white" />
                </Link>
                <!-- Delete -->
                <button @click="openDeleteModal(order.id)">
                    <Icon icon="ic:baseline-delete" width="20" height="20" class="text-red-400" />
                </button>
            </div>
            <hr class="border-t border-white mb-5">
            <div class="flex gap-3">
                <!-- Close Order -->
                <button @click="openCloseModal(order)"
                    class="flex-1 bg-gray-300 text-black py-2 rounded-lg text-sm font-medium shadow-sm transition hover:bg-red-600">
                    Close Order
                </button>
                <!-- Status -->
                <button
                    class="flex-1 bg-green-500 text-black py-2 rounded-lg text-sm font-medium shadow-sm transition hover:bg-green-600">
                    Status
                </button>
            </div>
        </div>

        <!-- Close Modal -->
        <div v-if="showCloseModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-4">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Close</h2>

                <p class="text-sm font-medium text-primary px-4 py-2 rounded-md mb-4 flex items-center gap-2">
                    Payment received for this order?
                </p>

                <p class="text-gray-600 mb-6">
                    Are you sure you want to close
                    <strong>Order #{{ selectedOrder?.order_number }}</strong>?
                </p>

                <div class="flex justify-end gap-3">
                    <button @click="showCloseModal = false"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition">
                        No
                    </button>
                    <button @click="confirmClose"
                        class="px-4 py-2 bg-primary text-white hover:bg-primary-dark rounded-md transition">
                        Yes
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- Delete Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-md max-w-sm w-full">
            <h2 class="text-lg font-bold mb-4">Delete Confirmation</h2>
            <p class="mb-4">Are you sure you want to delete this order?</p>
            <div class="flex justify-end gap-3">
                <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button @click="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
            </div>
        </div>
    </div>

</template>
