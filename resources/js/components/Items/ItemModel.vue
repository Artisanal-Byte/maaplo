<script setup>
import ToggleButton from '../ToggleButton.vue';
import Colors from './Colors.vue';
import DesignDetails from './DesignDetails.vue';
import ItemType from './ItemType.vue';
import Measurements from './Measurements.vue';
import Notes from './Notes.vue';
import WorkType from './WorkType.vue';
import ClothImage from './ClothImage.vue';
import PatternImage from './PatternImage.vue';
import Button from '../Button.vue';
import { ref, defineProps, defineEmits, watch, reactive } from 'vue';
import TrialAndDeliveryDate from '../TrialAndDeliveryDate.vue';
const notes = ref([{ label: '', text: '' }]);
const props = defineProps(['showModal', 'measurements', 'form', "itemType"]);
const emit = defineEmits(['close', 'setFormData']);

let orderItems = reactive({
    item_template_id: [],
    measurements: [],
    design_details_ids: null,
    colors: null,
    work_type: null,
    material_type: null,
    material_code: null,
    refrence_dress: null,
    is_urgent: null,
    material_cost: null,
    stiching_cost: null,
    item_cost: null,
    notes: null,
    trial_dates: null,
    status: null,
    cloth_img1: null,
    cloth_img2: null,
    Pattern_img1: null,
    Pattern_img2: null
})

let data = props.form
// Save and emit
function saveItem() {
    // if (!validateOrderItemValue()) {
    //     alert('Please Fill The All Fields Of Order Item')
    //     return false
    // }

    console.log(orderItems);

    data.order_items.push(orderItems)
    console.log('work type:', data.order_items);

    emit('setFormData', data);
    emit('close');
}

// const validateOrderItemValue = () => {
//     return
//     !orderItems.name
//         || !orderItems.measurements
//         || !orderItems.design_details
//         || !orderItems.colors
//         || !orderItems.notes
//         || !orderItems.trial_dates
//         || !orderItems.price
//         || !orderItems.work_type
//         || !orderItems.material_code
//         || !orderItems.material
//         || !orderItems.refrence_dress
//         || !orderItems.material_cost
//         || !orderItems.stiching_cost
//         || !orderItems.item_cost
// }

watch(
    orderItems,
    (newVal, oldVal) => { data.order_items.work_type = newVal.work_type },
    { deep: true }
)

const setItemTemplateId = (id) => {
    orderItems.item_template_id = id
}

const setColor = (colorValue) => {
    orderItems.colors = colorValue
}

const setNotes = (notes) => {
    orderItems.notes = notes
}

const setIfReferenceDress = (isReferenceDress) => {
    orderItems.refrence_dress = isReferenceDress
}

const setIfUrgent = (isUrgent) => {
    orderItems.is_urgent = isUrgent
}

const setdDeliveryDate = (dateVal) => {
    orderItems.deliveryDate = dateVal
}

const setTrialDate = (dateVal) => {
    orderItems.trial_dates = dateVal
}

const setClothImage1 = (img) => {
    orderItems.cloth_img1 = img
}
const setClothImage2 = (img) => {
    orderItems.cloth_img2 = img
}
const setPatternImage1 = (img) => {
    orderItems.Pattern_img1 = img
}
const setPatternImage2 = (img) => {
    orderItems.Pattern_img2 = img
}

const setPrice = (val) => {
    orderItems.price = val
}
const setMaterialCode = (val) => {
    orderItems.material_code = val
}
const setMaterial = (val) => {
    orderItems.material = val
}
const setMaterialCost = (val) => {
    orderItems.material_cost = val
}
const setStichingCost = (val) => {
    orderItems.stiching_cost = val
}
const setItemCost = (val) => {
    orderItems.item_cost = val
}
const setMaterialType = (val) => {
    // orderItems. = val
}

</script>

<template>

    <div v-if="showModal">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 z-40" @click.self="$emit('close')"></div>

        <!-- Modal Container -->
        <div class="fixed z-50 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
               w-full lg:max-w-6xl 
       max-w-[calc(100%-2.5rem)] bg-white shadow-lg rounded-[10px] overflow-hidden">
            <!-- Close Button -->
            <div class="lg:pl-6 px-6 py-6 lg:pr-10 overflow-y-auto max-h-[80vh]">
                <Button @click="$emit('close')" color="gray" padding="sm" rounded="full" textSize="xl"
                    class="absolute lg:right-4 right-1 top-4">
                    &times;
                </Button>
                <h1 class="font-medium text-2xl mb-5">Add Items</h1>
                <!-- Scrollable Content -->
                <div class=" max-h-[75vh] pr-2 space-y-5">
                    <WorkType v-model="orderItems.work_type" @setPrice="setPrice" @setMaterialCode="setMaterialCode"
                        @setMaterial="setMaterial" @setMaterialCost="setMaterialCost" @setStichingCost="setStichingCost"
                        @setItemCost="setItemCost" />
                    <pre>
                        {{ orderItems }}
                    </pre>
                    <ItemType v-model="orderItems.item_type" :itemType="itemType"
                        @setItemTemplateId="setItemTemplateId" />
                    <Measurements :measurements="measurements" />
                    <DesignDetails />

                    <div class="flex flex-col">
                        <Colors @setColor="setColor" />
                        <Notes class="mt-5" v-model:notes="notes" @setNotes="setNotes" />
                    </div>

                    <div class="flex items-center gap-4">
                        <h1 class="text-[16px] font-normal font-lato">Reference dress given?</h1>
                        <ToggleButton @setIfReferenceDress="setIfReferenceDress" />
                    </div>

                    <!-- Trail Date && Delivery Date  -->

                    <TrialAndDeliveryDate @setdDeliveryDate="setdDeliveryDate" @setTrialDate="setTrialDate" />

                    <div class="flex items-center gap-4">
                        <h1 class="text-[16px] font-normal font-lato">Mark as Urgent</h1>
                        <ToggleButton @setIfUrgent="setIfUrgent" />
                    </div>

                    <div class="flex flex-col lg:flex-row justify-between gap-4">
                        <ClothImage @setClothImage1="setClothImage1" @setClothImage2="setClothImage2" />
                        <PatternImage @setPatternImage1="setPatternImage1" @setPatternImage2="setPatternImage2" />
                    </div>
                    <div class="">
                        <Button @click="saveItem" color="primary" textSize="lg" class="mb-5 w-full">
                            Save
                        </Button>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>
