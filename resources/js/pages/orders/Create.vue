<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import DateIcon from '@/components/DateIcon.vue';
import CustomerListDropdown from '@/components/Items/CustomerListDropdown.vue';
import { ref, watch } from 'vue';
import ItemModel from '@/components/Items/ItemModel.vue';
import Button from '@/components/Button.vue';
import { router, useForm } from '@inertiajs/vue3';
import Notes from '@/components/Items/Notes.vue';
import Input from '@/components/InputWithLabel.vue';
import { useOrder } from '@/composables/useOrderData';
import { useOrderFormStore } from '@/stores/orderFormStore';

const props = defineProps(["users", "customers", "itemTypes", "designDetails", "errors"])

const showModal = ref(false);
const disabled = ref(false);
const toast = new ToastMagic();
const showDeletePopup = ref(false);
let form = useOrderFormStore();
let customerMeasurements = ref({})
const itemToDelete = ref(null);
let totalAmmount = ref(null)


let i = 0

let setOrderItemsData = (data) => {
    form.order_items.push(data)
    form.order_items[i] = data
    i++
}

let create = () => {
    if (form.advance_paid > form.total_amount) {
        alert("advance paid can't be greater than total payment!");
        form.advance_paid = null
        return
    }
    // form.post(route('orders.store'), {
    //     forceFormData: true,
    //     onSuccess: () => {
    //         toast.success('Order Created Successfully');
    //     },
    //     onError: (error) => {
    //         console.log('validation errors:', error);
    //         toast.error('failed To create order.');
    //     }
    // })
}

//total amount of order
watch(form.order_items, (items) => {
    let t = 0
    items.forEach(item => {
        t += item.item_cost
    });
    totalAmmount.value = t
    form.total_amount = t - form.advance_paid
})

const closeModel = () => {
    showModal.value = false
}

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeletePopup.value = true;
};

const cancelDelete = () => {
    showDeletePopup.value = false;
    itemToDelete.value = null;
};

const proceedDelete = () => {
    router.delete(route('orders.destroy', itemToDelete.value.id), {
        onSuccess: () => {
            toast.success('Item deleted successfully!');
            showDeletePopup.value = false;
        },
        onError: () => {
            toast.error('Failed to delete item.');
            alert('Delete failed');
        }
    });
};

const setMeasurements = (m) => {
    customerMeasurements.value = m
}

const openItemModel = () => {
    if (form.customer_id == null) {
        alert('Please Select a customer')
        return
    }
    showModal.value = true
}



watch(() => form.advance_paid, (nPayVal) => {
    if (nPayVal == null || nPayVal == 0) {
        form.total_amount = totalAmmount.value
    } else {
        form.total_amount = totalAmmount.value - nPayVal
    }
})


</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex flex-row justify-between">
                <div>
                    <h1
                        class="flex items-center gap-2 lg:gap-4 text-[24px] leading-[16px] font-bold text-gray-800 font-[Convergence] text-primary tracking-[0]">
                        <Icon icon="lsicon:order-edit-filled" width="30" height="30" />
                        New Order
                    </h1>
                    <pre>
                        {{ form }}
                    </pre>
                </div>
                <div class="self-center">
                    <Button :disabled="disabled" @click="create">Create Order</Button>
                </div>
            </div>
            <div class="flex flex-col mt-10 gap-3 bg-white lg:p-7 rounded-lg shadow-md p-5 border-t-4 border-primary">

                <h1 class="text-xl font-bold lg:mt-0 mt-4">Enter Details</h1>

                <!-- selected customer list -->
                <CustomerListDropdown :customers="customers" @setMeasurements="setMeasurements" />

                <!-- Delivery Date -->
                <DateIcon />

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
                        <ItemModel :errors="form.errors?.order_items" :itemIndex="i" :showModal="showModal"
                            @close="closeModel" :form="form.order_items" @setOrderItemsData="setOrderItemsData"
                            :itemTypes="itemTypes" :measurements="customerMeasurements ?? []"  :allDesignDetails="designDetails"/>
                        <p class="text-red-600 text-sm">
                            <!-- {{ form.errors.order_items }} -->
                        </p>

                        <!-- table -->
                        <!-- Items Table -->
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full border border-gray-300 text-sm text-left">
                                <thead class="bg-[#DEEFF4]">
                                    <tr>
                                        <th class="p-2 border">Work Type</th>
                                        <th class="p-2 border">Item Type</th>
                                        <th class="p-2 border">Delivery Date</th>
                                        <th class="p-2 border">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order_item, index) in form.order_items" :key="index">
                                        <td class="p-2 border">{{ order_item.work_type }}</td>
                                        <td class="p-2 border">
                                            {{itemTypes['privateTemplates'].find(item => item.id ===
                                                order_item.template_id)?.name ?? itemTypes['publicTemplates'].find(item =>
                                                    item.id ===
                                                    order_item.template_id)?.name}}
                                        </td>
                                        <td class="p-2 border">{{ order_item.delivery_date }}</td>
                                        <td class="p-2 border">
                                            <div class="flex gap-4">
                                                <Icon icon="material-symbols:edit-rounded" width="24" height="24"
                                                    class="text-primary cursor-pointer hover:text-blue-700" />
                                                <Icon icon="mingcute:delete-fill" width="24" height="24"
                                                    class="text-red-500 cursor-pointer hover:text-red-700"
                                                    @click="confirmDelete(order_item)" />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                            <Input type="number" label="Advance Paid" :error="errors.advance_paid" :required="true"
                                color="grayBorder" placeholder="Enter Advance Paid" v-model="form.advance_paid">
                            <template #icon>
                                <Icon icon="mdi:rupee" width="20" height="20" />
                            </template>
                            </Input>
                        </div>
                        <div class="mt-5">
                            <Notes v-model:notes="form.notes" />
                        </div>

                    </div>

                </div>
                <!-- Submit Button (Full Width Below) -->

                <Button :color="'primary'" @click="create" :padding="'md'" :rounded="'full'" :textSize="'sm'"
                    class="lg:mt-5 mt-3">
                    Save
                </Button>

            </div>
        </div>

    </AppLayout>
</template>