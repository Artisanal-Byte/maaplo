<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import DateIcon from '@/components/DateIcon.vue';
import CustomerListDropdown from '@/components/Items/CustomerListDropdown.vue';
import { ref, watch, computed, onMounted } from 'vue';
import ItemModel from '@/components/Items/ItemModel.vue';
import Button from '@/components/Button.vue';
import Notes from '@/components/Items/Notes.vue';
import Input from '@/components/InputWithLabel.vue';
import { useOrderFormStore } from '@/stores/orderFormStore';
import { Head, Link } from '@inertiajs/vue3';
import Loader from '@/components/Loader.vue';
const props = defineProps(["users", "customers", "itemTypes", "errors", "allDesignDetails"])

const showModal = ref(false);
const disabled = ref(false);
const toast = new ToastMagic();
const showDeletePopup = ref(false);
let form = useOrderFormStore();
form.advance_paid = form.advance_paid ?? 0;
let customerMeasurements = ref([]);
const itemToDelete = ref(null);
let deleteOrEditOrderIndex = ref()
let totalAmmount = ref(null)
const loading = ref(false);

const create = () => {
    loading.value = true;

    if (form.advance_paid > form.total_amount) {
        alert("Advance paid can't be greater than total payment!");
        form.advance_paid = null;
        loading.value = false;
        return;
    }

    form.createOrder()
        .then(() => {
            // We expect a toast from the server via flash message — no need to toast here
        })
        .catch(error => {
            // Optionally handle any client-side error here
            console.error("Order creation failed", error);
            toast.error("Something went wrong while creating the order.");
        })
        .finally(() => {
            loading.value = false;
        });
};

onMounted(() => {
    // Always reset when the create page is mounted
    form.resetOrderData();
    form.resetOrderItemTemplate();
});


//total amount of order
watch(form.order_items, (items) => {
    let t = 0
    items.forEach(item => {
        t += item.item_cost
    });
    totalAmmount.value = t
    form.total_amount = t
})

const closeModel = () => {
    showModal.value = false
}

const confirmDelete = (item, index) => {
    deleteOrEditOrderIndex.value = index
    itemToDelete.value = item;
    showDeletePopup.value = true;
};

const cancelDelete = () => {
    showDeletePopup.value = false;
    itemToDelete.value = null;
};

const proceedDelete = () => {
    form.deleteOrderItem(deleteOrEditOrderIndex.value)
    showDeletePopup.value = false;
};

const openItemModel = () => {
    form.order_items_template.mode = 'create'
    if (form.customer_id == null) {
        alert('Please Select a customer')
        return
    }
    showModal.value = true
}

watch(() => form.advance_paid, (nPayVal) => {
    if (nPayVal > totalAmmount.value) {
        alert("Advance paid can't be greater than total payment!");
        form.advance_paid = null
    }
})

const editOrderItem = (index) => {
    form.order_items_template.mode = 'edit'
    form.setOrderItemData(index)
    // form.resetOrderItemTemplate()
    showModal.value = true
}
const errorMessages = computed(() => {
    if (!props.errors) return [];
    return Object.values(props.errors).flat();
});

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const [year, month, day] = dateStr.split('-');
    return `${day}-${month}-${year}`;
};

import axios from 'axios';

watch(() => form.customer_id, async (newCustomerId) => {
    if (!newCustomerId) {
        customerMeasurements.value = [];
        return;
    }

    try {
        const response = await axios.get(`/orders/customers/${newCustomerId}/measurements`);
        customerMeasurements.value = response.data.measurements || [];
    } catch (error) {
        console.error("Failed to load measurements:", error);
        customerMeasurements.value = [];
    }
});

</script>

<template>

    <Head title="Order-Create" />
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
            <Loader v-if="form.isLoading" />
            <div class="flex flex-row justify-between">
                <div class="flex items-center gap-4 self-center">
                    <h1
                        class="flex items-center gap-2 lg:gap-4 text-[20px] lg:text-[27px] leading-[16px] font-bold text-gray-800 font-[Convergence] text-primary tracking-[0]">
                        <Icon icon="lsicon:order-edit-filled" width="30" height="30" />
                        New Order
                    </h1>

                </div>
                <div class="flex items-center gap-4 self-center">
                    <div class="text-gray-600">
                        <Link :href="route('orders.index')" class="flex items-center gap-1 hover:text-black">
                        <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                        <span class="text-[16px] font-medium">Back</span>
                        </Link>
                    </div>
                    <Button :disabled="disabled" @click="create">Create Order</Button>
                </div>

            </div>

            <div class="flex flex-col mt-10 gap-3 bg-white lg:p-7 rounded-lg shadow-md p-5 border-t-4 border-primary">

                <h1 class="text-xl font-bold lg:mt-0 mt-4">Enter Details</h1>

                <!-- selected customer list -->
                <CustomerListDropdown :customers="customers" :error="props.errors.customer_id" />

                <!-- Delivery Date -->
                <DateIcon :error="props.errors.delivery_date" />

                <!-- items -->
                <div class="mt-2">
                    <div>
                        <div class="flex flex-row justify-between items-center">
                            <div>
                                <label class="font-lato text-primary text-[18px] font-medium leading-4 tracking-normal">
                                    Items :
                                </label>
                            </div>
                            <!-- Add Icon -->
                            <div class="relative group">
                                <!-- Add Icon -->
                                <div @click="openItemModel" class="cursor-pointer inline-block">
                                    <Icon icon="material-symbols:add-rounded" width="20" height="20" />
                                </div>

                                <!-- Tooltip -->
                                <div
                                    class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                    Create Item
                                </div>
                            </div>

                        </div>
                        <!-- Modal Backdrop -->
                        <div v-if="showModal" class="fixed inset-0 bg-black/50 z-40" @click.self="showModal = false">
                        </div>

                        <!-- Modal Content -->
                        <ItemModel :errors="form.errors?.order_items" :showModal="showModal" @close="closeModel"
                            :form="form.order_items" :itemTypes="itemTypes" :allDesignDetails="allDesignDetails"
                            :measurements="Array.isArray(customerMeasurements) ? customerMeasurements : Object.entries(customerMeasurements).map(([slug, value]) => ({ slug, value }))" />
                        <p class="text-red-600 text-sm">
                            {{ errors?.order_items }}
                        </p>

                        <!-- Enhanced Table -->
                        <div class="mt-6 overflow-x-auto rounded-lg shadow-lg">
                            <table class="min-w-full border-collapse bg-white text-sm text-left text-gray-700">
                                <thead class="bg-[#DEEFF4] text-gray-800 font-semibold uppercase tracking-wider">
                                    <tr>
                                        <th class="px-4 py-3 border-b border-gray-300">Work Type</th>
                                        <th class="px-4 py-3 border-b border-gray-300">Item Type</th>
                                        <th class="px-4 py-3 border-b border-gray-300">Delivery Date</th>
                                        <th class="px-4 py-3 border-b border-gray-300">Item Cost</th>
                                        <th class="px-4 py-3 border-b border-gray-300">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order_item, index) in form.order_items" :key="index"
                                        class="hover:bg-gray-100 transition-colors duration-200">
                                        <td class="px-4 py-2 border-b border-gray-200">{{ order_item.work_type }}</td>
                                        <td class="px-4 py-2 border-b border-gray-200">
                                            {{itemTypes.find(item => item.id === order_item.template_id)?.name ?? 'N/A'
                                            }}
                                        </td>
                                        <td class="px-4 py-2 border-b border-gray-200">{{
                                            formatDate(order_item.delivery_date) }}
                                        </td>
                                        <td class="px-4 py-2 border-b border-gray-200">₹ {{ order_item.item_cost || 0 }}
                                        </td>

                                        <td class="px-4 py-2 border-b border-gray-200">
                                            <div class="flex gap-4">
                                                <Icon icon="material-symbols:edit-rounded" width="24" height="24"
                                                    class="text-primary cursor-pointer hover:text-primary mr-4 transition-colors duration-150"
                                                    @click="editOrderItem(index)" />
                                                <Icon icon="mingcute:delete-fill" width="24" height="24"
                                                    class="text-red-500 cursor-pointer hover:text-red-700 transition-colors duration-150"
                                                    @click="confirmDelete(order_item, index)" />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Total-->
                        <div class="mt-6 flex flex-wrap items-center justify-end gap-5 lg:gap-8 text-sm text-gray-800">
                            <h2><span class="font-semibold">Grand Total:</span> ₹ {{ form.total_amount || 0 }}</h2>
                            <h2><span class="font-semibold">Advance Paid:</span> ₹ {{ form.advance_paid || 0 }}</h2>
                            <h2><span class="font-semibold text-red-600 underline">Balance Due:</span> ₹ {{
                                (form.total_amount || 0) - (form.advance_paid || 0)
                            }}</h2>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div v-if="showDeletePopup"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white p-6 rounded shadow-lg w-11/12 max-w-md">
                                <h2 class="text-xl font-bold mb-3">Are you sure?</h2>
                                <p class="text-gray-700 mb-4">
                                    <span class="text-red-600 font-semibold">Warning:</span>
                                    This will delete <strong>{{ itemToDelete?.name }}</strong>.
                                </p>
                                <div class="flex justify-end gap-3">
                                    <Button @click="cancelDelete" color="gray">Cancel</Button>
                                    <Button @click="proceedDelete" color="danger">Delete</Button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <Input type="number" label="Advance Paid" :error="errors.advance_paid" color="grayBorder"
                                placeholder="Enter Advance Paid" v-model="form.advance_paid">
                            <template #icon>
                                <Icon icon="mdi:rupee" width="20" height="20" />
                            </template>
                            </Input>
                        </div>
                        <div class="mt-5">
                            <Notes v-model:notes="form.notes" :error="errors.notes" />
                        </div>

                    </div>

                </div>
                <!-- Submit Button (Full Width Below) -->

                <Button :color="'primary'" @click="create" :padding="'md'" :rounded="'full'" :textSize="'sm'"
                    class="lg:mt-5 mt-3">
                    Create Order
                </Button>

            </div>
        </div>

    </AppLayout>
</template>
