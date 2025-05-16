<script setup>
import { ref } from 'vue';
import { Icon } from '@iconify/vue';
import SvgIcon from '../SvgIcon.vue';
const selectedNeck = ref('')
const selectedfrontNeck = ref('')
const selectedSleeveType = ref('')
const showDropdownDesignDetails = ref(false);
const showDropdownFrontNeckDesign = ref(true);
const showDropdownBackNeckDesign = ref(false);
const showDropdownSleeveType = ref(false);
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


const neckTypes = [
    { name: 'u-neck', label: 'U Neck' },
    { name: 'v-neck-back', label: 'V Neck' },
    { name: 'cross-neck', label: 'Cross Neck' },
    { name: 'close-neck', label: 'Close Neck' },
]
const frontNeck = [
    { name: 'v-neck', label: 'V Neck' },
    { name: 'square-neck', label: 'Square Neck' },
    { name: 'halter-neck', label: 'Halter Neck' },
    { name: 'round-neck', label: 'Round Neck' },
]
const sleeveType = [
    {
        label: 'Full'
    },
    {
        label: 'Half'
    },
    {
        label: 'Cap'
    },
    {
        label: 'No'
    },
]
</script>
<template>
    <div class="mt-5">
        <div @click="toggleDropdownDesignDetails()" class="flex">
            <h1 class="font-normal text-md leading-4 tracking-normal font-lato"> Design Details<span class="text-red-500 text-lg">*</span>
            </h1>
            <Icon v-if="showDropdownDesignDetails == false" icon="icon-park-outline:down" width="20" height="20"
                class="text-black ml-2" />
            <Icon v-if="showDropdownDesignDetails == true" icon="icon-park-outline:up" width="20" height="20"
                class="text-black ml-2" />
        </div>
        <!-- Dropdown -->

        <div v-show="showDropdownDesignDetails" class="z-10">
            <div class="bg-[#DEECF0] mt-3 p-4 shadow-[0_0_7.6px_0_#BDDBDB9C]  rounded-[10px] relative">
                <ul class="text-md text-black dark:text-black" aria-labelledby="dropdownTrigger">
                    <li>
                        <!--  Front Neck Design -->
                        <div>
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
                                            class="flex flex-row gap-5 lg:ml-10 ml-5 py-5 text-black font-lato text-sm">
                                            <div v-for="(neck, index) in frontNeck" :key="index"
                                                @click="selectedfrontNeck = neck.name" :class="[
                                                    'cursor-pointer rounded-md p-2',
                                                    selectedfrontNeck === neck.name
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
                        <div>
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
                                        <div class="flex flex-row gap-5 lg:ml-10 ml-5 py-5 font-lato text-sm">
                                            <div v-for="(neck, index) in neckTypes" :key="index"
                                                @click="selectedNeck = neck.name" :class="[
                                                    'cursor-pointer rounded-md p-2',
                                                    selectedNeck === neck.name
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
                        <div>
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
                                                @click="selectedSleeveType = neck.label" :class="[
                                                    'cursor-pointer rounded-md p-2',
                                                    selectedSleeveType === neck.label
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

                    </li>
                </ul>
            </div>
        </div>

    </div>
</template>