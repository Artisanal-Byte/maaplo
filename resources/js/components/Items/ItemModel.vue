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
const props = defineProps(['showModal', 'form', 'itemType', 'itemIndex', 'measurements']);
const emit = defineEmits(['close', 'setOrderItemsData']);

let data =  reactive({
    template_id: null,
    measurements: [],
    design_details_ids: [],
    colors: '',
    work_type: '',
    material_type: '',
    material_code: '',
    refrence_dress: '',
    is_urgent: '',
    material_cost: null,
    stiching_cost: null,
    cost: null,
    notes: [{}],
    trial_dates: '',
    status: '',
    cloth_img1: null,
    cloth_img2: null,
    Pattern_img1: null,
    Pattern_img2: null
})

// const form = reactive({
//     template_id: null,
//     measurements: [],
//     design_details_ids: [],
//     colors: '',
//     work_type: '',
//     material_type: '',
//     material_code: '',
//     refrence_dress: '',
//     is_urgent: '',
//     material_cost: null,
//     stiching_cost: null,
//     cost: null,
//     notes: [{}],
//     trial_dates: '',
//     status: '',
//     cloth_img1: null,
//     cloth_img2: null,
//     Pattern_img1: null,
//     Pattern_img2: null
// })

const resetData = () => {
    data = {
        template_id: null,
        measurements: [],
        design_details_ids: [],
        colors: '',
        work_type: '',
        material_type: '',
        material_code: '',
        refrence_dress: '',
        is_urgent: '',
        material_cost: null,
        stiching_cost: null,
        cost: null,
        notes: [{}],
        trial_dates: '',
        status: '',
        cloth_img1: null,
        cloth_img2: null,
        Pattern_img1: null,
        Pattern_img2: null
    }
}


// Save and emit
function saveItem() {
    // each items added in order items
    emit('setOrderItemsData', JSON.parse(JSON.stringify(data)))
    resetData();
    // emit('setOrderItemsData', data)
    emit('close')
}

const setItemId = (id) => {
    console.log('checking data:', data);
    data.template_id = id
}

const setMaterialCode = (materialCode) => {
    data.material_code = materialCode
}

const setMaterialCost = (materialCost) => {
    data.material_cost = materialCost
}
const setAlteringCost = (materialCost) => {
    // data.material_cost = materialCost
}
const setStichingCost = (stichingCost) => {
    data.stiching_cost = stichingCost
}
const setMaterialType = (materialType) => {
    data.material_type = materialType
}
const setPrice = (price) => {
    data.cost = price
}
const setColor = (color) => {
    data.colors = color
}
const setNotes = (notes) => {
    data.notes = notes
}
const setIfReferenceDress = (val) => {
    data.refrence_dress = val
}
const setIfUrgent = (val) => {
    data.is_urgent = val
}
const setClothImage1 = (imgPath) => {
    data.cloth_img1 = imgPath
}
const setClothImage2 = (imgPath) => {
    data.cloth_img2 = imgPath
}
const setPatternImage1 = (imgPath) => {
    data.Pattern_img1 = imgPath
}
const setPatternImage2 = (imgPath) => {
    data.Pattern_img2 = imgPath
}
const setTrialDate = (date) => {
    data.trial_dates = date
}
const setdDeliveryDate = (imgPath) => {
    data.delivery_date = imgPath
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
                    <!-- done -->
                    <WorkType :formData="data" @setMaterialCode="setMaterialCode" @setMaterialCost="setMaterialCost"
                        @setAlteringCost="setAlteringCost" @setStichingCost="setStichingCost"
                        @setMaterialType="setMaterialType" @setPrice="setPrice" />
                    <ItemType :itemType="itemType" @setItemId="setItemId" />
                    <Measurements :measurements="measurements" />
                    <DesignDetails />

                    <div class="flex flex-col">
                        <Colors @setColor="setColor" />
                        <Notes v-model:notes="notes" @setNotes="setNotes" class="mt-5" />
                    </div>

                    <div class="flex items-center gap-4">
                        <h1 class="text-[16px] font-normal font-lato">Reference dress given?</h1>
                        <ToggleButton @setIfReferenceDress="setIfReferenceDress" />
                    </div>

                    <!-- Trail Date && Delivery Date  -->

                    <TrialAndDeliveryDate @setTrialDate="setTrialDate" @setdDeliveryDate="setdDeliveryDate" />

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
