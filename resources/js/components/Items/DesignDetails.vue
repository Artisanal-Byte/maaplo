<script setup>
import { ref, watch } from 'vue';
import { Icon } from '@iconify/vue';
import SvgIcon from '../SvgIcon.vue';
import { useOrderFormStore } from '@/stores/orderFormStore';

const props = defineProps(["order", "currentEditIndex", "designDetails"])

let tmp = ref()
const formStore = useOrderFormStore()

watch(() => props.designDetails, (nVal) => {
    tmp.value = nVal
})

// Track body part selections in an object
const showDropdownDesignDetails = ref(true);
const designToggles = ref({});

function toggleDropdownDesignDetails() {
    showDropdownDesignDetails.value = !showDropdownDesignDetails.value;
}

function toggleBodyPartDropdown(part) {

    designToggles.value[part] = !designToggles.value[part];
}

function selectDesign(designId, bodyPart) {
    // Update only the design for the clicked body part
    formStore.order_items_template.design_detail[bodyPart] = designId;

}
</script>

<template>
    <div class="mt-5">
        <!-- Header for main Design Details -->
        <div @click="toggleDropdownDesignDetails" class="flex cursor-pointer">
            <h1 class="font-medium leading-4 tracking-normal font-lato">
                Design Details<span class="text-red-500 text-lg">*</span>
            </h1>
            <Icon :icon="showDropdownDesignDetails ? 'icon-park-outline:up' : 'icon-park-outline:down'" width="20"
                height="20" class="text-black ml-2 mt-1" />
        </div>

        <!-- Main Dropdown Content -->
        <div v-show="showDropdownDesignDetails" class="z-10">
            <div class="bg-white mt-3 p-4 shadow rounded-[10px] relative">
                <ul class="text-md text-black">
                    <li v-for="t in tmp" :key="t.body_part">
                        <!-- Body Part Header -->
                        <div @click="toggleBodyPartDropdown(t.body_part)" class="flex cursor-pointer mb-2">
                            <h1 class="font-normal ml-3 text-[16px] font-lato">{{ t.body_part }}</h1>
                            <Icon :icon="designToggles[t.body_part] ? 'icon-park-outline:up' : 'icon-park-outline:down'"
                                width="20" height="20" class="text-black ml-2" />
                        </div>

                        <!-- Body Part Design Options -->
                        <div  v-show="designToggles[t.body_part]">
                            <div class="flex flex-row flex-wrap gap-4 ml-10 py-3">
                                <div v-for="(V, index) in t.value" :key="V.id" @click="selectDesign(V.id, t.body_part)"
                                    :class="[
                                        'cursor-pointer rounded-md p-2 border',
                                        formStore.order_items_template.design_detail[t.body_part] === V.id
                                            ? 'border-primary bg-[#DEECF0]'
                                            : 'border-gray-300'
                                    ]">
                                    <div v-html="V.img" class="w-[50px] h-[50px]"></div>
                                    <!-- <SvgIcon :name="V.name" /> -->
                                    <h1 class="font-medium mt-2 text-[12px] text-center">{{ V.label }}</h1>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
