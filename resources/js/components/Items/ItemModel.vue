<script setup>
import ToggleButton from '../ToggleButton.vue';
import Colors from './Colors.vue';
import { Icon } from '@iconify/vue';
import DesignDetails from './DesignDetails.vue';
import ItemType from './ItemType.vue';
import Notes from './Notes.vue';
import WorkType from './WorkType.vue';
import ClothImage from './ClothImage.vue';
import PatternImage from './PatternImage.vue';
import Button from '../Button.vue';
import { ref, defineProps, defineEmits, watch, onMounted } from 'vue';
import TrialAndDeliveryDate from '../TrialAndDeliveryDate.vue';
import { useOrderFormStore } from '@/stores/orderFormStore';
import ItemMeasurements from './ItemMeasurements.vue';

const fileInputGallery = ref(null)
const fileInputCamera = ref(null)
const props = defineProps(['showModal', 'order', 'form', 'orderItems', 'currentEditIndex', 'itemTypes', 'allDesignDetails', 'itemIndex', 'errorMessage', 'errors']);

let formStore = useOrderFormStore()

const emit = defineEmits(['close']);
const measurements = ref([])
const showImageUpload = ref(false)
const previewUrl = ref(null);
const designDetails = ref({})
// Save and emit
function saveItem() {
    previewImage.value = null
    showImageUpload.value = false

    if (formStore.order_items_template.mode === 'create') {
        formStore.pushOrderItem()
    } else if (formStore.order_items_template.mode === 'edit') {
        formStore.updateOrderItem()
    }

    formStore.resetOrderItemTemplate()
    emit('close')
}

const setItemId = (option) => {
    formStore.order_items_template.template_id = option.id
    measurements.value = option.measurements

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
        if (showImageUpload.value) {
            formStore.order_items_template.refrence_dress = file;
            previewUrl.value = URL.createObjectURL(file);
        }
    }
}
watch(() => showImageUpload.value, (nVal) => {
    if (!nVal) {
        formStore.refrence_dress = null
        previewImage.value = null
    }
})

const setSelectDesignDetails = (templateId) => {
    const selectedItemDesign = props.itemTypes.find(item => item.id === templateId);
    if (!selectedItemDesign || !selectedItemDesign.design_details_list) {
        designDetails.value = [];
        return;
    }

    const grouped = {};

    selectedItemDesign.design_details_list.forEach(item => {
        const bodyPart = item.body_part_value.body_part;
        const key = bodyPart.toLowerCase();

        if (!grouped[key]) {
            grouped[key] = {
                body_part: bodyPart,
                value: []
            };
        }

        grouped[key].value.push({
            id: item.id,
            name: item.value.toLowerCase().replace(/\s+/g, '-'),
            label: item.value,
            img: item.image
        });
    });

    designDetails.value = Object.values(grouped);
};

const previewImage = (file) => {
    if (!file || typeof file !== 'object') {
        return null;
    }
    return URL.createObjectURL(file)
}

// Convert image URL to File
const urlToFile = async (url, filename, mimeType) => {
    const res = await fetch(url);
    const buf = await res.arrayBuffer();
    return new File([buf], filename, { type: mimeType });
};

// Load reference dress image based on current item
const loadReferenceDress = async () => {
    const item = props.orderItems?.[props.currentEditIndex] || null;

    if (item?.refrence_dress_url) {
        try {
            const file = await urlToFile(item.refrence_dress_url, 'refrence_dress.webp', 'image/webp');
            formStore.order_items_template.refrence_dress = file;
            previewUrl.value = URL.createObjectURL(file);
            showImageUpload.value = true;
        } catch (error) {
            console.error("Error loading reference dress image:", error);
        }
    } else {
        showImageUpload.value = false;
        formStore.order_items_template.refrence_dress = null;
        previewUrl.value = null;
    }
};

onMounted(loadReferenceDress);

watch(() => props.currentEditIndex, () => {
    loadReferenceDress();
});
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
                     <!-- errors[`order_items.${currentEditIndex}.colors`] -->
                    <WorkType :errors="errors" :order="order" :currentEditIndex="currentEditIndex" />
                    <ItemType :errors="errors" :itemTypes="itemTypes" @setItemId="setItemId"
                        @setSelectDesignDetails="setSelectDesignDetails" :currentEditIndex="currentEditIndex" />
                    <ItemMeasurements :askedMeasurements="measurements" :order="order" :currentEditIndex="currentEditIndex" :errors="errors"/>
                    <DesignDetails :designDetails="designDetails" v-model="formStore.design_detail" :order="order" :currentEditIndex="currentEditIndex" :errors="errors"/>

                    <div class="flex flex-col">
                        <!-- <pre>
                            {{ errors }}
                        </pre> -->
                        <Colors :errors="errors" :currentEditIndex="currentEditIndex"/>
                        <Notes v-model:notes="formStore.order_items_template.notes" class="mt-5" />
                    </div>

                    <div class="flex items-center gap-4">
                        <h1 class="font-medium font-lato">Reference dress given?</h1>
                        <ToggleButton v-model:model="showImageUpload" />
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
                            <img v-if="previewUrl" :src="previewUrl" alt="Preview"
                                class="w-32 h-20 object-cover rounded" />

                        </div>
                        <input ref="fileInputGallery" type="file" class="hidden" accept="image/*"
                            @change="e => onFileChange(e, 1)" />
                        <input ref="fileInputCamera" type="file" class="hidden" accept="image/*" capture="environment"
                            @change="e => onFileChange(e, 1)" />
                    </div>
                    <!-- Trail Date && Delivery Date  -->

                    <TrialAndDeliveryDate :order="order" :currentEditIndex="currentEditIndex"/>

                    <div class="flex items-center gap-4">
                        <h1 class="font-medium font-lato">Mark as Urgent</h1>
                        <ToggleButton v-model:model="formStore.order_items_template.is_urgent" />
                    </div>
                    <!-- Upload icon, only shown when toggle is ON -->
                    <div class="flex flex-col lg:flex-row justify-between gap-4">
                        <ClothImage :orderItems="orderItems" :currentEditIndex="currentEditIndex" />
                        <PatternImage :orderItems="orderItems" :currentEditIndex="currentEditIndex" />
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
