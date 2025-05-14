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
const props = defineProps(["users", "customers", "itemType" ,"errors"])
const showModal = ref(false);
const disabled = ref(false);
const toast = new ToastMagic();

const orderData = useOrder()
let form = useForm(orderData);



// set data which is set by child components 
let setOrderData = (data) => {
    form = data
}

let i = 0

let setOrderItemsData = (data) => {
    form.order_items[i] = data
    i++
}

let create = () => {
    form.post(route('orders.store'), {
        onSuccess: () => {
            toast.success('Order Created Successfully');
        },
        onError: (error) => {
            toast.error('failed To create order! reason:' + error);
        }
    })
}
const submitForm = () => {
    router.post('/orders', {
        ...form,
        // notes: notes.value,
    }, {
        onSuccess: () => {
            toast.success("order created successfully!");
        },
        onError: (errors) => {
            toast.error("Failed to create order. please fill the all the required fields.");
            console.error(errors);
        },
    });
};



//total amount of order
watch(form.order_items,(items)=>{
    let t = 0
    items.forEach(item => {
        t += item.cost
    });
    form.total_amount = t
})
const closeModel = () => {
    showModal.value = false
}



</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex flex-row justify-between">
                <div>
                    <h1 class="text-[24px] mt-3 leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                        New Order
                    </h1>
                </div>
                <div class="self-center">
                    <Button :disabled="disabled" @click="create">Create Order</Button>
                </div>
            </div>
            <div class="flex flex-col lg:mt-5 rounded-lg lg:border lg:border-primary p-0 lg:p-4">

                <pre>
                    {{ form }}
                </pre>

                <h1 class="text-xl font-bold lg:mb-6 lg:mt-0 mt-6">Enter Details</h1>

                <!-- selected customer list -->
                <CustomerListDropdown :customers="customers" :errors="errors" :form="form" @setOrderData="setOrderData" />

                <!-- Delivery Date -->
                <DateIcon :form="form" :errors="errors" @setOrderData="setOrderData" />

                <!-- items -->
                <div class="mt-5">
                    <div>
                        <div class="flex flex-row justify-between items-center">
                            <div>
                                <label class="font-lato text-base font-normal leading-4 tracking-normal">
                                    Items
                                </label>
                            </div>
                            <!-- Add Icon -->
                            <div @click="showModal = true" class="cursor-pointer inline-block">
                                <Icon icon="material-symbols:add-rounded" width="20" height="20" />
                            </div>
                        </div>
                        <!-- Modal Backdrop -->
                        <div v-if="showModal" class="fixed inset-0 bg-black/50 z-40" @click.self="showModal = false">
                        </div>

                        <!-- Modal Content -->
                        <ItemModel :itemIndex="i" :showModal="showModal" @close="closeModel" :form="form.order_items"
                            @setOrderItemsData="setOrderItemsData" :itemType="itemType" :measurements="customers.base_measurements ?? []" />

                        <!-- table -->
                        <!-- Items Table -->
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full border border-gray-300 text-sm text-left">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-2 border">Work Type</th>
                                        <th class="p-2 border">Item type</th>
                                        <th class="p-2 border">Delivery Date</th>
                                        <th class="p-2 border">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order_item, index) in form.order_items" :key="index">
                                        <td class="p-2 border">{{ order_item.work_type }}</td>
                                        <td class="p-2 border">{{itemType.find(item => item.id ===
                                            order_item.template_id)?.name }}</td>
                                        <td class="p-2 border">{{ order_item.delivery_date }}</td>
                                        <td class="p-2 border">
                                            <div class="flex gap-4">
                                                <Icon icon="material-symbols:edit-rounded" width="24" height="24"
                                                    class="text-primary cursor-pointer hover:text-blue-700" />
                                                <Icon icon="mingcute:delete-fill" width="24" height="24"
                                                    class="text-red-500 cursor-pointer hover:text-red-700"
                                                    @click="deleteItem(index)" />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5">
                            <Input type="number" label="Advance Paid" :error="errors.advance_paid" :required="true" color="grayBorder"
                                placeholder="Enter Advance Paid" v-model="form.advance_paid">
                            <template #icon>
                                <Icon icon="mdi:rupee" width="18" height="18" />
                            </template>
                            </Input>
                        </div>
                        <div class="mt-5">
                            <Notes v-model:notes="form.notes" />
                        </div>

                    </div>

                </div>
                <!-- Submit Button (Full Width Below) -->

                <Button :color="'primary'" @click="submitForm" :padding="'md'" :rounded="'full'" :textSize="'sm'">
                    Save
                </Button>

            </div>
        </div>
    </AppLayout>
</template>