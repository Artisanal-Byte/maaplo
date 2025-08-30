<script setup>
import { Icon } from '@iconify/vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Loader from './Loader.vue';
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


// status change
const showStatusChangeModal = ref(false);
const tempStatus = ref(''); // temporarily holds the selected status
const statusFrom = ref('');
const statusTo = ref('');

function handleStatusSelect(newStatus) {
    if (!newStatus || newStatus === props.order.status) return;

    tempStatus.value = newStatus;
    statusFrom.value = formatStatus(props.order.status);
    statusTo.value = formatStatus(newStatus);
    showStatusChangeModal.value = true;
}

function confirmStatusChange() {
    loading.value = true;
    router.post(
        route('orders.updateStatus'),
        {
            order_id: props.order.id,
            status: tempStatus.value
        },
        {
            onSuccess: () => {
                toast.success(`Order status changed to ${statusTo.value}`);
                props.order.status = tempStatus.value;
                resetStatusModal();
            },
            onError: () => {
                toast.error('Failed to change order status.');
            },
            onFinish: () => {
                loading.value = false;
            }
        }
    );
}

function resetStatusModal() {
    showStatusChangeModal.value = false;
    tempStatus.value = '';
    statusFrom.value = '';
    statusTo.value = '';
}

</script>
<template>
    <Loader v-if="loading" class="fixed inset-0 flex items-center justify-center z-50 bg-white/70" />
    <!-- dashbord view -->
    <div class=" hidden lg:block gap-[10px] rounded-[10px]  py-[17px] mt-[6px]" :style="{
        backgroundColor: bgColor,
        border: `1px solid ${borderColor}`,

    }">
        <div class="flex flex-row border-b border-white px-[10px] pb-2">
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
        <!-- <hr class="border-t border-white mb-3"> -->
        <div class="flex flex-row justify-between px-[10px] py-3">
            <div>
                <div>
                    <p class="font-[Lato] font-medium text-[16px] leading-[16px] tracking-[0] text-secondary p-2">
                        Items: {{ order?.order_items?.length }}</p>
                </div>
                <div>
                    <p class="font-[Lato] font-medium text-[16px] leading-[16px] tracking-[0] text-secondary p-2">
                        Delivery Date: {{ order?.delivery_date }}</p>
                </div>
                <div>
                    <p class="font-[Lato] font-medium text-[16px] leading-[16px] tracking-[0]  text-secondary p-2">
                        Status: {{ formatStatus(order?.status) }}
                    </p>
                </div>
            </div>

            <div class=" gap-3">
                <!-- Status -->
                <!-- <div class="relative">
                    <select id="status" name="status" :value="order.status" @change="handleStatusSelect($event.target.value)"
                        class="appearance-none w-full pl-4 py-2 rounded-md border border-gray-300 text-sm text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
                        <option value="" selected>🆕 Change Status</option>
                        <option value="created">🆕 Created</option>
                        <option value="in_process">🔄 In Process</option>
                        <option value="processed">📦 Processed</option>
                        <option value="trial_done">🧪 Trial Done</option>
                        <option value="in_alteration">✂️ In Alteration</option>
                        <option value="ready_for_delivery">📬 Ready for Delivery</option>
                        <option value="delivered">🚚 Delivered</option>
                        <option value="cancelled">❌ Cancelled</option>
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                        <Icon icon="mdi:chevron-down" width="20" height="20" />
                    </div>
                </div> -->
                <!-- Status -->
                <div class="relative">
                    <select id="status" name="status" :value="order.status"
                        @change="handleStatusSelect($event.target.value)"
                        class="appearance-none w-full pl-4 py-2 rounded-md border border-gray-300 text-sm text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-150 ease-in-out">

                        <option value="" selected>Change Status</option>
                        <option value="created">Created 🆕</option>
                        <option value="in_process">In Process 🔄</option>
                        <option value="processed">Processed 📦</option>
                        <option value="trial_done">Trial Done 🧪</option>
                        <option value="in_alteration">In Alteration ✂️</option>
                        <option value="ready_for_delivery">Ready for Delivery 📬</option>
                        <option value="delivered">Delivered 🚚</option>
                        <option value="cancelled">Cancelled ❌</option>
                    </select>

                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                        <Icon icon="mdi:chevron-down" width="20" height="20" />
                    </div>
                </div>

                <!-- Close Order -->
                <button @click="openCloseModal(order)"
                    class="bg-primary w-full mt-3 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                    Close Order
                </button>

                <!-- View Order -->
                <div class="flex justify-between px-8 mt-3">
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


            </div>

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
                <div class="w-full">
                    <button @click="openCloseModal(order)"
                        class="flex-1 w-full bg-gray-300 text-black py-2 rounded-lg text-sm font-medium shadow-sm transition hover:bg-gray-200">
                        Close Order
                    </button>
                </div>
                <!-- Status -->
                <div class="relative w-full">
                    <select id="status" :value="order.status" placeholder="Change Status"
                        @change="handleStatusSelect($event.target.value)"
                        class="appearance-none w-full pl-4 py-2 rounded-md border border-gray-300 text-sm text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
                        <option value="" selected>🆕 Change Status</option>
                        <option value="created">🆕 Created</option>
                        <option value="in_process">🔄 In Process</option>
                        <option value="processed">📦 Processed</option>
                        <option value="trial_done">🧪 Trial Done</option>
                        <option value="in_alteration">✂️ In Alteration</option>
                        <option value="ready_for_delivery">📬 Ready for Delivery</option>
                        <option value="delivered">🚚 Delivered</option>
                        <option value="cancelled">❌ Cancelled</option>
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                        <Icon icon="mdi:chevron-down" width="20" height="20" />
                    </div>
                </div>
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
    <!-- Status Change Modal -->
    <div v-if="showStatusChangeModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8 mx-4 relative">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                    <Icon icon="mdi:swap-horizontal-bold" class="text-primary" width="24" height="24" />
                    Change Order Status
                </h2>
                <button @click="resetStatusModal" class="text-gray-400 hover:text-gray-600 transition">
                    <Icon icon="mdi:close" width="24" height="24" />
                </button>
            </div>

            <!-- Content -->
            <div class="mb-8">
                <p class="text-gray-700 text-base leading-relaxed">
                    Change the order status from
                    <span class="font-semibold text-primary">{{ statusFrom }}</span>
                    to
                    <span class="font-semibold text-green-600">{{ statusTo }}</span>, It's Okay to proceed?
                </p>

            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4">
                <button @click="resetStatusModal"
                    class="px-5 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition font-medium">
                    Cancel
                </button>
                <button @click="confirmStatusChange" :disabled="loading"
                    class="px-5 py-2 rounded-lg bg-primary text-white hover:bg-primary-dark transition font-medium disabled:opacity-60 disabled:cursor-not-allowed">
                    {{ loading ? 'Updating...' : 'Confirm' }}
                </button>
            </div>
        </div>
    </div>

</template>
