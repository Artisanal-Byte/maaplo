<script setup>
import { ref, defineProps, watch } from 'vue';
import { Icon } from '@iconify/vue';
import SvgIcon from '../SvgIcon.vue';

const props = defineProps(["designDetails"])
const selectedNeck = ref('')
const selectedfrontNeck = ref('')
const selectedSleeveType = ref('')
const showDropdownDesignDetails = ref(false);
const showDropdownFrontNeckDesign = ref(true);
const showDropdownBackNeckDesign = ref(false);
const showDropdownSleeveType = ref(false);
const model = defineModel('modelValue');

function toggleDropdownDesignDetails() {
    showDropdownDesignDetails.value = !showDropdownDesignDetails.value;
}
function toggleDropdownFrontNeckDesign() {
    showDropdownFrontNeckDesign.value = !showDropdownFrontNeckDesign.value;
}
function toggleDropdownBackNeckDesign() {
    showDropdownBackNeckDesign.value = !showDropdownBackNeckDesign.value;
}
function toggleDropdownSleeveType() {
    showDropdownSleeveType.value = !showDropdownSleeveType.value;
}

watch(selectedfrontNeck, (newVal) => {
    console.log(newVal);

    model.value = newVal
})
watch(selectedNeck, (newVal) => {
    console.log(newVal);

    model.value = newVal
})
watch(selectedSleeveType, (newVal) => {
    console.log(newVal);

    model.value = newVal
})

const neckTypes = [
    { name: 'u-neck', label: 'U Neck', },
    { name: 'v-neck-back', label: 'V Neck', img: '' },
    { name: 'cross-neck', label: 'Cross Neck', img: '' },
    { name: 'close-neck', label: 'Close Neck', img: '' },
]

const frontNeck = [
    { name: 'v-neck', label: 'V Neck', img: '' },
    { name: 'square-neck', label: 'Square Neck', img: '' },
    { name: 'halter-neck', label: 'Halter Neck', img: '' },
    { name: 'round-neck', label: 'Round Neck', img: '' },
]

const sleeveType = [
    {
        label: 'Full', img: ''
    },
    {
        label: 'Half', img: ''
    },
    {
        label: 'Cap', img: ''
    },
    {
        label: 'No', img: ''
    },
]


</script>
<template>
    <div class="mt-5">
        <div @click="toggleDropdownDesignDetails()" class="flex">
            <h1 class="font-medium leading-4 tracking-normal font-lato"> Design Details<span
                    class="text-red-500 text-lg">*</span>
            </h1>
            <Icon v-if="showDropdownDesignDetails == false" icon="icon-park-outline:down" width="20" height="20"
                class="text-black ml-2 mt-1" />
            <Icon v-if="showDropdownDesignDetails == true" icon="icon-park-outline:up" width="20" height="20"
                class="text-black ml-2 mt-1" />
        </div>
        <!-- Dropdown -->

        <div v-show="showDropdownDesignDetails" class="z-10">
            <div class="bg-white mt-3 p-4 shadow-[0_0_7.6px_0_#BDDBDB9C] rounded-[10px] relative">
                <ul class="text-md text-black dark:text-black" aria-labelledby="dropdownTrigger">
                    <li>
                        <!--  Front Neck Design -->
                        <div v-if="designDetails.front_neck_design">
                            <div @click="toggleDropdownFrontNeckDesign()" class="flex">
                                <h1 class="font-normal ml-3 text-[16px] leading-4 tracking-normal font-lato">
                                    Front Neck Design
                                </h1>
                                <Icon v-if="showDropdownFrontNeckDesign == false" icon="icon-park-outline:down"
                                    width="20" height="20" class="text-black ml-2" />
                                <Icon v-if="showDropdownFrontNeckDesign == true" icon="icon-park-outline:up" width="20"
                                    height="20" class="text-black ml-2" />
                            </div>
                            <!-- Dropdown -->
                            <div v-show="showDropdownFrontNeckDesign" class="z-10">
                                <ul class="text-md text-black dark:text-black" aria-labelledby="dropdownTrigger">
                                    <li>
                                        <div
                                            class="flex flex-row lg:gap-5 gap-2 lg:ml-10 ml-2 py-5 text-black font-lato text-sm">
                                            <div v-for="(neck, index) in frontNeck" :key="index"
                                                @click="selectedfrontNeck = neck" :class="[
                                                    'cursor-pointer rounded-md lg:p-2',
                                                    selectedfrontNeck.name === neck.name
                                                        ? 'border-2 border-primary bg-[DEECF0)]'
                                                        : ''
                                                ]">
                                                <SvgIcon :name="neck.name" />
                                                <h1
                                                    class="font-medium mt-3 text-[12px] leading-[8px] tracking-normal text-center">
                                                    {{ neck.label }}
                                                </h1>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!--  Back Neck Design -->
                        <div v-if="designDetails.back_neck_design">
                            <div @click="toggleDropdownBackNeckDesign()" class="flex">
                                <h1 class="font-normal ml-3 mt-4 text-[16px] leading-4 tracking-normal font-lato">
                                    Back Neck Design
                                </h1>
                                <Icon v-if="showDropdownBackNeckDesign == false" icon="icon-park-outline:down"
                                    width="20" height="20" class="text-black ml-2 mt-4" />
                                <Icon v-if="showDropdownBackNeckDesign == true" icon="icon-park-outline:up" width="20"
                                    height="20" class="text-black ml-2 mt-4" />
                            </div>
                            <!-- Dropdown -->
                            <div v-show="showDropdownBackNeckDesign" class="z-10">
                                <ul class="text-md text-black dark:text-black" aria-labelledby="dropdownTrigger">
                                    <li>
                                        <div class="flex flex-row lg:gap-5 gap-0 lg:ml-10 ml-2 py-5 font-lato text-sm">
                                            <div v-for="(neck, index) in neckTypes" :key="index"
                                                @click="selectedNeck = neck" :class="[
                                                    'cursor-pointer rounded-md lg:p-2',
                                                    selectedNeck.name === neck.name
                                                        ? 'border-2 border-primary bg-[DEECF0)]'
                                                        : ''
                                                ]">
                                                <SvgIcon :name="neck.name" />
                                                <h1
                                                    class="font-medium mt-3 text-[12px] leading-[8px] tracking-normal text-center">
                                                    {{ neck.label }}
                                                </h1>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!--  Sleeve Type -->
                        <div v-if="designDetails.sleeve_type">
                            <div @click="toggleDropdownSleeveType()" class="flex">
                                <h1 class="font-normal ml-3 mt-4 text-[16px] leading-4 tracking-normal font-lato">
                                    Sleeve Type
                                </h1>
                                <Icon v-if="showDropdownSleeveType == false" icon="icon-park-outline:down" width="20"
                                    height="20" class="text-black ml-2 mt-4" />
                                <Icon v-if="showDropdownSleeveType == true" icon="icon-park-outline:up" width="20"
                                    height="20" class="text-black ml-2 mt-4" />
                            </div>
                            <!-- Dropdown -->
                            <div v-show="showDropdownSleeveType" class="z-10">
                                <ul class="text-md text-black dark:text-black" aria-labelledby="dropdownTrigger">
                                    <li>
                                        <div
                                            class="flex flex-row gap-3 lg:ml-10 ml-5 pt-4 text-black font-lato text-sm">
                                            <div v-for="(neck, index) in sleeveType" :key="index"
                                                @click="selectedSleeveType = neck" :class="[
                                                    'cursor-pointer rounded-md p-2',
                                                    selectedSleeveType.label === neck.label
                                                        ? 'border-2 border-primary bg-[DEECF0)]'
                                                        : ''
                                                ]">

                                                <h1
                                                    class="font-medium text-[12px] leading-[8px] tracking-normal text-center">
                                                    {{ neck.label }}
                                                </h1>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>

                        <div
                            v-if="!designDetails.front_neck_design && !designDetails.back_neck_design && !designDetails.sleeve_type">
                            <div class="flex">
                                <h1 class="font-normal text-red-500  text-[16px] leading-4 tracking-normal font-lato">
                                    Please Select Item Type!
                                </h1>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</template>