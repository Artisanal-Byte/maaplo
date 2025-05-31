<script setup>
import ToggleButton from '../ToggleButton.vue';
import Colors from './Colors.vue';
import { Icon } from '@iconify/vue';
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
import { useOrderFormStore } from '@/stores/orderFormStore';
const fileInputGallery = ref(null)
const fileInputCamera = ref(null)
const previewImage = ref(null)
const notes = ref([{ label: '', text: '' }]);
const props = defineProps(['showModal', 'form', 'itemTypes', 'itemIndex', 'measurements', 'errorMessage', 'errors']);
let findDesign = ref()
let formStore = useOrderFormStore()


const emit = defineEmits(['close', 'setOrderItemsformStore']);

const showImageUpload = ref(false)

const resetformStore = () => {
    formStore = {
        template_id: null,
        measurements: [],
        design_detail: [],
        colors: '',
        work_type: '',
        material_type: '',
        material_code: '',
        refrence_dress: '',
        is_urgent: '',
        material_cost: null,
        stiching_cost: null,
        item_cost: null,
        notes: [{}],
        trial_dates: '',
        delivery_date: '',
        status: '',
        cloth_img1: null,
        cloth_img2: null,
        Pattern_img1: null,
        Pattern_img2: null
    }
}
const designDetails = ref({})
// Save and emit
function saveItem() {
    emit('setOrderItemsformStore', JSON.parse(JSON.stringify(formStore)))
    previewImage.value = null
    showImageUpload.value = false
    resetformStore();
    // emit('setOrderItemsformStore', formStore)
    emit('close')
}

const setItemId = (id) => {
    formStore.template_id = id
}

const setMaterialCode = (materialCode) => {
    formStore.material_code = materialCode
}

const setMaterialCost = (materialCost) => {
    formStore.material_cost = materialCost
}

const setStichingCost = (stichingCost) => {
    formStore.stiching_cost = stichingCost
}
const setMaterialType = (materialType) => {
    formStore.material_type = materialType
}
const setPrice = (price) => {
    formStore.item_cost = price
}
const setColor = (color) => {
    formStore.colors = color
}
const setNotes = (notes) => {
    formStore.notes = notes
}
const setShowReferenceDress = (val) => {
    showImageUpload.value = val
}
const setIfUrgent = (val) => {
    formStore.is_urgent = val
}
const setClothImage1 = (file) => {
    formStore.cloth_img1 = file
}
const setClothImage2 = (file) => {
    formStore.cloth_img2 = file
}
const setPatternImage1 = (file) => {
    formStore.Pattern_img1 = file
}
const setPatternImage2 = (file) => {
    formStore.Pattern_img2 = file
}
const setTrialDate = (date) => {
    formStore.trial_dates = date
}
const setdDeliveryDate = (file) => {
    formStore.delivery_date = file
}

// Trigger function for each input
function triggerUpload(type, index) {
    if (type === 'gallery' && index === 1) {
        fileInputGallery.value.click();
    } else if (type === 'camera' && index === 1) {
        fileInputCamera.value.click();
    }
}

function onFileChange(event, index) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const url = URL.createObjectURL(file);
        if (index === 1) {
            if (showImageUpload.value) {
                formStore.refrence_dress = file
            }
            previewImage.value = url;
        }
    }
}
watch(() => showImageUpload.value, (nVal) => {
    if (!nVal) {
        formStore.refrence_dress = null
        previewImage.value = null
    }
})
watch(() => formStore.template_id, (newId) => {
    designDetails.value = props.itemTypes.privateTemplates.find(item => item.id === newId).design_details
})

</script>

<template>
    <div v-if="showModal">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-white/25 z-40" @click.self="$emit('close')"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 bg-black bg-opacity-25 z-50"></div>
        <div
            class="fixed z-[999] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full lg:max-w-6xl max-w-[calc(100%-2.5rem)] bg-[#DEEFF4] shadow-lg rounded-[10px] overflow-hidden">
            <!-- Close Button -->
            <div class=" px-4 lg:px-6 py-6 overflow-y-auto max-h-[80vh]">
                <div class="flex justify-between">
                    <div>
                        <h1 class="font-medium text-primary text-2xl mb-5">Add Items</h1>
                    </div>
                    <div>
                        <Button @click="$emit('close')" color="gray" padding="sm" rounded="full" textSize="xl"
                            class="lg:right-4 right-1 top-4">
                            &times;
                        </Button>
                    </div>
                </div>
                <!-- Scrollable Content -->
                <div class=" max-h-[75vh] pr-2 space-y-5">
                    <!-- done -->
                    <WorkType  />
                    <ItemType :itemTypes="itemTypes" @setItemId="setItemId" />
                    <Measurements :toAsk="measurements" :isOrder="true" />
                    <DesignDetails :designDetails="designDetails" v-model="formStore.design_detail" />

                    <div class="flex flex-col">
                        <Colors @setColor="setColor" />
                        <Notes v-model:notes="notes" @setNotes="setNotes" class="mt-5" />
                    </div>

                    <div class="flex items-center gap-4">
                        <h1 class="font-medium font-lato">Reference dress given?</h1>
                        <ToggleButton @setShowReferenceDress="setShowReferenceDress" />
                    </div>

                    <div v-if="showImageUpload"
                        class="w-40 mt-5 h-full rounded-md p-2 shadow-[0px_0px_6.1px_0px_#00000040] bg-white">
                        <div class="flex items-center justify-between mb-2">
                            <h1 class="font-normal text-[14px] leading-[8px] text-[#8C8C8C] font-lato">Cloth 1</h1>
                            <div class="flex gap-2">
                                <button @click="triggerUpload('gallery', 1)" class="relative group">
                                    <Icon icon="material-symbols:upload" width="16" height="16"
                                        class="text-primary hover:text-gray-700" />
                                    <div
                                        class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                        upload
                                    </div>
                                </button>

                                <button @click="triggerUpload('camera', 1)" class="relative group">
                                    <Icon icon="tabler:capture" width="16" height="16"
                                        class="text-primary hover:text-gray-700" />
                                    <div
                                        class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                        capture
                                    </div>
                                </button>
                            </div>

                        </div>
                        <div class="bg-[#BDDBDB3D] p-2 rounded h-24 mb-2">
                            <img v-if="previewImage" :src="previewImage" alt="Preview"
                                class="w-32 h-20 object-cover rounded" />
                        </div>
                        <input ref="fileInputGallery" type="file" class="hidden" accept="image/*"
                            @change="e => onFileChange(e, 1)" />
                        <input ref="fileInputCamera" type="file" class="hidden" accept="image/*" capture="environment"
                            @change="e => onFileChange(e, 1)" />
                    </div>
                    <!-- Trail Date && Delivery Date  -->

                    <TrialAndDeliveryDate @setTrialDate="setTrialDate" @setdDeliveryDate="setdDeliveryDate" />

                    <div class="flex items-center gap-4">
                        <h1 class="font-medium font-lato">Mark as Urgent</h1>
                        <ToggleButton @setIfUrgent="setIfUrgent" />
                    </div>
                    <!-- Upload icon, only shown when toggle is ON -->
                    <div class="flex flex-col lg:flex-row justify-between gap-4">
                        <ClothImage @setClothImage1="setClothImage1" @setClothImage2="setClothImage2" />

                        <!-- <ClothImage @setClothImage1="payload => emit('setClothImage1', payload)"
                            @setClothImage2="payload => emit('setClothImage2', payload)" /> -->
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
