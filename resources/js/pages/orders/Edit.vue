<script setup>
import { onMounted, ref, watch, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import DateIcon from '@/components/DateIcon.vue';
import CustomerListDropdown from '@/components/Items/CustomerListDropdown.vue';
import ItemModel from '@/components/Items/ItemModel.vue';
import Button from '@/components/Button.vue';
import Notes from '@/components/Items/Notes.vue';
import Input from '@/components/InputWithLabel.vue';
import { useOrderFormStore } from '@/stores/orderFormStore';
import { Head } from '@inertiajs/vue3';
import { nextTick } from 'vue';
import Loader from '@/components/Loader.vue';

const props = defineProps(["users", "customers", "itemTypes", "errors", "order", "orderItems"]);
console.log('itemTypes data in edit page ', props.itemTypes);

const showModal = ref(false);
const showDeletePopup = ref(false);
const itemToDelete = ref(null);
const deleteOrEditOrderIndex = ref(null);
const totalAmmount = ref(0);
const disabled = ref(false);
const ready = ref(false);
const form = useOrderFormStore();
const toast = new ToastMagic();
const loading = ref(false);

const recalculateTotal = () => {
    let t = 0;
    form.order_items.forEach(item => {
        t += item.item_cost;
    });
    totalAmmount.value = t;
    form.total_amount = t;
};

// Hydrate form with existing order data
onMounted(() => {
    if (props.order) {
        form.setEditOrder(props.order);
        nextTick(() => {
            recalculateTotal();
        });
    }
    ready.value = true;
});

watch(() => form.order_items, (items) => {
    let t = 0;
    items.forEach(item => {
        t += item.item_cost;
    });
    totalAmmount.value = t;
    form.total_amount = t;
}, { immediate: true });

const openItemModel = () => {
    if (form.customer_id == null) {
        alert('Please Select a customer');
        return;
    }
    form.order_items_template.mode = 'create';
    showModal.value = true;
};

const closeModel = () => {
    showModal.value = false;
};

const confirmDelete = (item, index) => {
    deleteOrEditOrderIndex.value = index;
    itemToDelete.value = item;
    showDeletePopup.value = true;
};

const cancelDelete = () => {
    showDeletePopup.value = false;
    itemToDelete.value = null;
};

const proceedDelete = () => {
    form.deleteOrderItem(deleteOrEditOrderIndex.value);
    showDeletePopup.value = false;
};
const currentEditIndex = ref(null);
const editOrderItem = (index) => {
    form.setOrderItemData(index);
    form.order_items_template.mode = 'edit';

    // Save this index to ref to pass down
    currentEditIndex.value = index;
    showModal.value = true;
};

const update = () => {
    if (form.advance_paid > totalAmmount.value) {
        alert("Advance paid can't be greater than total amount.");
        return;
    }

    loading.value = true;
    setTimeout(() => {
        loading.value = false;
    }, 1000);

    form.updateOrder(props.order.id).finally(() => {
        loading.value = true;
    });
};

const getItemTypeName = (id) => {
    if (!id) return 'Unknown';
    const found = props.itemTypes.find(i => i.id == id);
    return found?.name || 'Unknown';
};

const errorMessages = computed(() => {
    if (!props.errors) return [];
    return Object.values(props.errors).flat(); // flatten in case of array of messages
});

onMounted(() => {
    if (props.errors && Object.keys(props.errors).length) {
        Object.values(props.errors).flat().forEach(message => {
            toast.error(message);
        });
    }
});
onMounted(() => {
    console.log('Order Items:', form.order_items);
    console.log('Item Types:', props.itemTypes);
});

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const [year, month, day] = dateStr.split('-');
    return `${day}-${month}-${year}`;
};
</script>

<template>

    <Head title="Order-Edit" />
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div v-if="errorMessages.length" class="mb-6 rounded-md border border-red-300 bg-red-50 p-4 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <Icon icon="mdi:alert-circle" class="h-6 w-6 text-red-600" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800">
                            There {{ errorMessages.length === 1 ? 'is' : 'are' }} {{ errorMessages.length }}
                            error{{ errorMessages.length > 1 ? 's' : '' }} with your submission:
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc space-y-1 pl-5">
                                <li v-for="(error, index) in errorMessages" :key="index">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Use the Loader Component -->
            <Loader v-if="loading" />
            <div class="flex flex-row justify-between">
                <h1 class="text-[24px] font-bold flex items-center gap-2 text-primary">
                    <Icon icon="lsicon:order-edit-filled" width="30" height="30" />
                    Edit Order
                </h1>
                <Button :disabled="disabled" @click="update">Update Order</Button>
            </div>
            <!-- Status Selector -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mt-5">
                    <span class="flex items-center gap-2">
                        <Icon icon="mdi:clipboard-text-outline" class="text-primary" width="18" height="18" />
                        Order Status
                    </span>
                </label>
                <div class="relative w-full max-w-sm mt-2">
                    <select id="status" v-model="form.status"
                        class="appearance-none w-full pl-4 pr-10 py-2.5 rounded-md border border-gray-300 text-sm text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-150 ease-in-out">
                        <option value="created">🆕 Created</option>
                        <option value="in process">🔄 In Process</option>
                        <option value="processed">📦 Processed</option>
                        <option value="delivered">🚚 Delivered</option>
                        <option value="completed">✅ Completed</option>
                        <option value="cancelled">❌ Cancelled</option>
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                        <Icon icon="mdi:chevron-down" width="20" height="20" />
                    </div>
                </div>
                <p v-if="props.errors.status" class="mt-2 text-sm text-red-600">{{ props.errors.status }}</p>
            </div>
            <div class="mt-6 bg-white p-5 lg:p-7 rounded-lg shadow-md border-t-4 border-primary">
                <CustomerListDropdown :customers="customers" :error="props.errors.customer_id"
                    v-model="form.customer_id" />
                <DateIcon v-model="form.delivery_date" :error="props.errors.delivery_date" />

                <!-- Item Table and Modal -->
                <div class="mt-6">
                    <div class="flex justify-between items-center">
                        <label class="text-primary font-medium text-lg">Items:</label>
                        <div class="cursor-pointer group" @click="openItemModel">
                            <Icon icon="material-symbols:add-rounded" width="20" height="20" />
                            <div
                                class="hidden group-hover:block text-xs bg-black text-white px-2 py-1 rounded absolute">
                                Add Item
                            </div>
                        </div>
                    </div>

                    <ItemModel :errors="errors" :showModal="showModal" @close="closeModel"
                        :form="form.order_items_template" :itemTypes="itemTypes" :measurements="[]"
                        :orderItems="orderItems" :currentEditIndex="currentEditIndex" :order="order"
                        @item-updated="recalculateTotal" />

                    <div class="mt-6 overflow-x-auto rounded-lg shadow-lg">
                        <table class="min-w-full border-collapse bg-white text-sm text-left text-gray-700">
                            <thead class="bg-[#DEEFF4] text-gray-800 font-semibold uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3 border-b border-gray-300">Work Type</th>
                                    <th class="px-4 py-3 border-b border-gray-300">Item Type</th>
                                    <th class="px-4 py-3 border-b border-gray-300">Delivery Date</th>
                                    <th class="px-4 py-3 border-b border-gray-300">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in form.order_items" :key="index"
                                    class="hover:bg-gray-100 transition-colors duration-200">
                                    <td class="px-4 py-2 border-b border-gray-200">{{ item.work_type }}</td>
                                    <td class="px-4 py-2 border-b border-gray-200">
                                        {{ getItemTypeName(item.template_id || item.item_template_id) }}

                                    </td>
                                    <td class="px-4 py-2 border-b border-gray-200">{{ formatDate(item.delivery_date) }}
                                    </td>
                                    <td class="px-4 py-2 border-b border-gray-200">
                                        <div class="flex text-center">
                                            <Icon icon="material-symbols:edit-rounded" width="24"
                                                @click="editOrderItem(index)" class="cursor-pointer text-blue-500" />
                                            <Icon icon="mingcute:delete-fill" width="24"
                                                @click="confirmDelete(item, index)"
                                                class="cursor-pointer text-red-500" />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Totals -->
                <div class="mt-6 flex flex-wrap items-center justify-end gap-8 text-sm text-gray-800">
                    <h2 class="mt-3 font-bold text-lg">
                        <span class="font-semibold">Grand Total:</span> ₹ {{ totalAmmount.toLocaleString('en-IN', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }) }}
                    </h2>
                    <h2 class="mt-1 text-base text-gray-700">
                        <span class="font-semibold">Advance Paid:</span> ₹ {{ (form.advance_paid ||
                            0).toLocaleString('en-IN', {
                                minimumFractionDigits:
                                    2, maximumFractionDigits: 2
                            }) }}
                    </h2>
                    <h2 class="mt-1 font-semibold text-base text-green-700">
                        <span class="font-semibold text-red-600 underline">Balance Due:</span> ₹ {{ (totalAmmount -
                            (form.advance_paid || 0)).toLocaleString('en-IN', {
                                minimumFractionDigits: 2, maximumFractionDigits: 2
                            }) }}
                    </h2>
                </div>
                <!-- Advance and Notes -->
                <div class="mt-5">
                    <Input type="number" label="Advance Paid" :error="props.errors.advance_paid"
                        placeholder="Enter advance" v-model="form.advance_paid" color="grayBorder">
                    <template #icon>
                        <Icon icon="mdi:rupee" />
                    </template>
                    </Input>
                </div>
                <div class="mt-5">
                    <Notes v-model:notes="form.notes" :error="props.errors.notes" />
                </div>
                <!-- Update button -->
                <Button :color="'primary'" @click="update" :padding="'md'" :rounded="'full'" :textSize="'sm'"
                    class="lg:mt-5 mt-3 w-full">
                    Update Order
                </Button>
                <!-- Confirm Delete -->
                <div v-if="showDeletePopup"
                    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                        <h2 class="text-xl font-bold mb-3">Are you sure?</h2>
                        <p>This will delete <strong>{{ itemToDelete?.name }}</strong>.</p>
                        <div class="mt-4 flex justify-end gap-2">
                            <Button @click="cancelDelete" color="gray">Cancel</Button>
                            <Button @click="proceedDelete" color="danger">Delete</Button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
