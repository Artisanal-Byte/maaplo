<script setup>
import { ref, defineEmits, watch, onMounted } from 'vue'
import { Icon } from '@iconify/vue'
import { useOrderFormStore } from '@/stores/orderFormStore'

const fileInputGallery1 = ref(null)
const fileInputCamera1 = ref(null)
const fileInputGallery2 = ref(null)
const fileInputCamera2 = ref(null)
const formStore = useOrderFormStore()

const props = defineProps(["orderItems", "currentEditIndex"]);
const previewUrl1 = ref(null)
const previewUrl2 = ref(null)

// Function to convert URL to File object (needed for the store)
const urlToFile = async (url, filename, mimeType) => {
    const res = await fetch(url);
    const buf = await res.arrayBuffer();
    return new File([buf], filename, { type: mimeType });
}

const getCurrentItem = () => props.orderItems?.[props.currentEditIndex] || null;
const loadPatternImages = async () => {
    const item = getCurrentItem();
    if (!item) {
        return;
    }

    const hasAnyUrl = !!(item.Pattern_img1_url || item.Pattern_img2_url);
    if (!hasAnyUrl) {
        return;
    }

    formStore.order_items_template.Pattern_img1 = null;
    formStore.order_items_template.Pattern_img2 = null;
    previewUrl1.value = null;
    previewUrl2.value = null;

    if (item.Pattern_img1_url) {
        try {
            const file1 = await urlToFile(item.Pattern_img1_url, 'Pattern_img1.webp', 'image/webp');
            formStore.order_items_template.Pattern_img1 = file1;
            previewUrl1.value = URL.createObjectURL(file1);
        } catch (error) {
            console.error("Error loading Pattern_img1:", error);
        }
    }

    if (item.Pattern_img2_url) {
        try {
            const file2 = await urlToFile(item.Pattern_img2_url, 'Pattern_img2.webp', 'image/webp');
            formStore.order_items_template.Pattern_img2 = file2;
            previewUrl2.value = URL.createObjectURL(file2);
        } catch (error) {
            console.error("Error loading Pattern_img2:", error);
        }
    }
};

onMounted(loadPatternImages);

watch(() => props.currentEditIndex, () => {
    loadPatternImages();
});

function triggerUpload(type, index) {
    if (type === 'gallery') {
        index === 1 ? fileInputGallery1.value.click() : fileInputGallery2.value.click()
    } else if (type === 'camera') {
        index === 1 ? fileInputCamera1.value.click() : fileInputCamera2.value.click()
    }
}

function onFileChange(event, index) {
    const file = event.target.files[0]
    if (file && file.type.startsWith('image/')) {

        if (index === 1) {
            formStore.order_items_template.Pattern_img1 = file
        } else if (index === 2) {
            formStore.order_items_template.Pattern_img2 = file
        }
    }
}

watch(() => formStore.order_items_template.Pattern_img1, (newFile, oldFile) => {
    if (previewUrl1.value) {
        URL.revokeObjectURL(previewUrl1.value)
        previewUrl1.value = null
    }
    if (newFile && newFile instanceof File) {
        previewUrl1.value = URL.createObjectURL(newFile)
    }
})

watch(() => formStore.order_items_template.Pattern_img2, (newFile, oldFile) => {
    if (previewUrl2.value) {
        URL.revokeObjectURL(previewUrl2.value)
        previewUrl2.value = null
    }
    if (newFile && newFile instanceof File) {
        previewUrl2.value = URL.createObjectURL(newFile)
    }
})

watch(() => formStore.order_items_template.Pattern_img1, (newFile) => {
    if (previewUrl1.value) {
        URL.revokeObjectURL(previewUrl1.value)
        previewUrl1.value = null
    }

    if (newFile) {
        if (newFile.size > 2 * 1024 * 1024) {
            alert("Pattern Image 1 must be less than 2MB.");
            formStore.order_items_template.Pattern_img1 = null;
            return;
        }
        previewUrl1.value = URL.createObjectURL(newFile)
    }
})

watch(() => formStore.order_items_template.Pattern_img2, (newFile) => {
    if (previewUrl2.value) {
        URL.revokeObjectURL(previewUrl2.value)
        previewUrl2.value = null
    }

    if (newFile) {
        if (newFile.size > 2 * 1024 * 1024) {
            alert("Pattern Image 2 must be less than 2MB.");
            formStore.order_items_template.Pattern_img2 = null;
            return;
        }
        previewUrl2.value = URL.createObjectURL(newFile)
    }
})
</script>

<template>
    <div>
        <h1 class="font-medium leading-4 tracking-normal font-lato">Pattern Images</h1>
        <div class="flex gap-10">
            <!-- Pattern 1 -->
            <div class="w-40 mt-5 h-full rounded-md p-2 shadow-[0px_0px_6.1px_0px_#00000040] bg-white">
                <div class="flex items-center justify-between mb-2">
                    <h1 class="font-normal text-[14px] leading-[8px] text-[#8C8C8C] font-lato">Pattern 1</h1>
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
                    <img v-if="previewUrl1" :src="previewUrl1" alt="Preview" class="w-32 h-20 object-cover rounded" />
                </div>
                <input ref="fileInputGallery1" type="file" class="hidden" accept="image/*"
                    @change="e => onFileChange(e, 1)" />
                <input ref="fileInputCamera1" type="file" class="hidden" accept="image/*" capture="environment"
                    @change="e => onFileChange(e, 1)" />
            </div>

            <!-- Pattern 2 -->
            <div class="w-40 mt-5 h-full rounded-md p-2 shadow-[0px_0px_6.1px_0px_#00000040] bg-white">
                <div class="flex items-center justify-between mb-2">
                    <h1 class="font-normal text-[14px] leading-[8px] text-[#8C8C8C] font-lato">Pattern 2</h1>
                    <div class="flex gap-2">
                        <button @click="triggerUpload('gallery', 2)" class="relative group">
                            <Icon icon="material-symbols:upload" width="16" height="16"
                                class="text-primary hover:text-gray-700" />
                            <div
                                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                upload
                            </div>
                        </button>
                        <button @click="triggerUpload('camera', 2)" class="relative group">
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
                    <img v-if="previewUrl2" :src="previewUrl2" alt="Preview" class="w-32 h-20 object-cover rounded" />
                </div>
                <input ref="fileInputGallery2" type="file" class="hidden" accept="image/*"
                    @change="e => onFileChange(e, 2)" />
                <input ref="fileInputCamera2" type="file" class="hidden" accept="image/*" capture="environment"
                    @change="e => onFileChange(e, 2)" />
            </div>
        </div>
    </div>
</template>
