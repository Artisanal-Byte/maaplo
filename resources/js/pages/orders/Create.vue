<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import DateIcon from '@/components/DateIcon.vue';
import CustomerListDropdown from '@/components/Items/CustomerListDropdown.vue';
import { computed, reactive, ref } from 'vue';
import ItemModel from '@/components/Items/ItemModel.vue';
import Button from '@/components/Button.vue';
import { useForm } from '@inertiajs/vue3';
import Notes from '@/components/Items/Notes.vue';
const props = defineProps(["users", "customers", "itemType"])
const showModal = ref(false);
const toast = new ToastMagic();
const notes = ref([{ label: '', text: '' }]);
let form = useForm({
    user_id: null,
    customer_id: null,
    order_number: null,
    status: 'create',
    total_amount: null,
    advance_paid: null,
    delivery_date: '',
    close_date: '',
    notes: [],
    order_items: [],
});


const items = ref([]);


let measurements = reactive({});

let setMeasurements = (value) => {
    measurements = value
}



// set data which is set by child components 
let setFormData = (data) => {
    // console.log('form data:', data);

    form.user_id = data.user_id //done
    form.customer_id = data.customer_id //done
    // form.order_number = data.order_number 
    // form.status = data.status
    form.total_amount = data.total_amount
    form.advance_paid = data.advance_paid
    form.delivery_date = data.delivery_date
    form.close_date = data.close_date
    form.notes = data.notes
    console.log('check:', data.order_items.length > 0);

    data.order_items && items.value.push({ ...data.order_items });
    form.order_items = items.value
    showModal.value = false;
}

// check if any required field is blank or null so create order button does not be active
// Disable button if any required field is empty
let disabled = computed(() => {
    return false
    // return (
    //     !form.user_id ||
    //     !form.customer_id ||
    //     !form.order_number ||
    //     !form.status ||
    //     !form.total_amount ||
    //     !form.advance_paid ||
    //     !form.delivery_date //||
    //     // !form.order_items
    //     //  ||
    //     // form.order_items.length === 0 ||
    //     // form.order_items.some(item =>
    //     //     !item.name ||
    //     //     !item.colors ||
    //     //     item.price == null || // use `==` to catch both null and undefined
    //     //     !item.work_type ||
    //     //     !item.material_code ||
    //     //     !item.material
    //     // )
    // );
});

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

                <h1 class="text-xl font-bold lg:mb-6 lg:mt-0 mt-6">Enter Details</h1>

                <!-- selected customer list -->
                <CustomerListDropdown :customers="customers" @setMeasurements="setMeasurements" :form="form"
                    @setFormData="setFormData" />

                <!-- Delivery Date -->
                <DateIcon :form="form" @setFormData="setFormData" />

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
                        <ItemModel :measurements="measurements" :showModal="showModal" @close="showModal = false"
                            :form="form" @setFormData="setFormData" :itemType="itemType" />

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
                                    <tr v-for="(item, index) in items" :key="index">
                                        <td class="p-2 border">{{ item.workType }}</td>
                                        <td class="p-2 border">{{ item.itemType }}</td>
                                        <td class="p-2 border">{{ item.deliveryDate }}</td>
                                        <td class="p-2 border">
                                            <div class="flex gap-4">
                                                <Icon icon="material-symbols:edit-rounded" width="24" height="24"
                                                    class="text-primary cursor-pointer hover:text-blue-700" />
                                                <Icon icon="mingcute:delete-fill" width="24" height="24"
                                                    class="text-red-500 cursor-pointer hover:text-red-700" />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5">
                            <Notes v-model:notes="notes" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>