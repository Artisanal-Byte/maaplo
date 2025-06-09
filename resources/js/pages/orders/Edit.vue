<script setup>
import { onMounted, ref, watch } from 'vue';
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

const props = defineProps(["users", "customers", "itemTypes", "errors", "order"]);
console.log('Items Types', props.itemTypes)

const showModal = ref(false);
const showDeletePopup = ref(false);
const itemToDelete = ref(null);
const deleteOrEditOrderIndex = ref(null);
const totalAmmount = ref(0);
const disabled = ref(false);
const ready = ref(false);
const form = useOrderFormStore();
const toast = new ToastMagic();

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


// Watch for item cost recalculation
watch(form.order_items, (items) => {
    let t = 0;
    items.forEach(item => {
        t += item.item_cost;
    });
    totalAmmount.value = t;
    form.total_amount = t; // ✅ Keep full amount only
});

watch(() => form.order_items, (items) => {
    let t = 0;
    items.forEach(item => {
        t += item.item_cost;
    });
    totalAmmount.value = t;
    form.total_amount = t;
    console.log('Order items:', items);
}, { immediate: true });


watch(() => form.advance_paid, (nPayVal) => {
    if (!ready.value) return; // ⬅️ skip check during hydration

    if (nPayVal > totalAmmount.value) {
        alert("Advance paid can't be greater than total amount.");
        form.advance_paid = null;
    }
});

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

const editOrderItem = (index) => {
    form.setOrderItemData(index);
    form.order_items_template.mode = 'edit';
    showModal.value = true;
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 2,
    }).format(value || 0);
};

const update = () => {
    if (form.advance_paid > totalAmmount.value) {
        alert("Advance paid can't be greater than total amount.");
        return;
    }
    form.updateOrder(props.order.id);
};

const getItemTypeName = (id) => {
    console.log('getItemTypeName input:', id);
    const found = props.itemTypes.find(i => i.id === id);
    return found?.name || `Unknown (ID: ${id})`;
};

watch(() => form.order_items, (items) => {
    console.log('Order items:', items);
}, { immediate: true });
</script>

<template>
    <Head title="Order-Edit" />
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex flex-row justify-between">
                <h1 class="text-[24px] font-bold flex items-center gap-2 text-primary">
                    <Icon icon="lsicon:order-edit-filled" width="30" height="30" />
                    Edit Order
                </h1>
                <Button :disabled="disabled" @click="update">Update Order</Button>
            </div>

            <div class="mt-10 bg-white p-5 lg:p-7 rounded-lg shadow-md border-t-4 border-primary">
                <CustomerListDropdown :customers="customers" :error="props.errors.customer_id"
                    v-model="form.customer_id" />
                <DateIcon :error="props.errors.delivery_date" />

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

                    <ItemModel :errors="form.errors?.order_items" :showModal="showModal" @close="closeModel"
                        :form="form.order_items" :itemTypes="itemTypes" :measurements="[]" />

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
                                        <!-- <pre>{{ item}}</pre> -->
                                        <!-- <pre>
                                            {{ itemTypes }}
                                         </pre> -->

                                        {{ getItemTypeName(item.template_id) }}
                                        <!-- {{ getItemTypeName(item.template_id.name) }} -->
                                    </td>
                                    <td class="px-4 py-2 border-b border-gray-200">{{ item.delivery_date }}</td>
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
