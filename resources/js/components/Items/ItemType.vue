<script setup>
import { ref,defineProps } from 'vue';
import { Icon } from '@iconify/vue';
import Input from '../InputWithLabel.vue';
import SearchSelect from '../SearchSelect.vue';

const props = defineProps(["itemType"]) 
const emits = defineEmits(["setItemTemplateId"])
const setId = (id) => {
    emits('setItemTemplateId',id)
}

const showDropdownItemType = ref(false);
const modelValue = defineModel();
function toggleDropdownItemType() {
    showDropdownItemType.value = !showDropdownItemType.value;
}
</script>
<template>
    <div class="mt-5">
        <div @click="toggleDropdownItemType()" class="flex">
            <h1 class="font-normal text-md leading-4 tracking-normal font-lato">Item Type<span class="text-red-500 text-lg">*</span></h1>
            <Icon v-if="showDropdownItemType == false" icon="icon-park-outline:down" width="20" height="20"
                class="text-black ml-2" />
            <Icon v-if="showDropdownItemType == true" icon="icon-park-outline:up" width="20" height="20"
                class="text-black ml-2" />
        </div>
        <!-- Dropdown -->
        <div v-show="showDropdownItemType" class="z-10">
            <SearchSelect v-model="modelValue" class="mt-5" :itemType="itemType" @setId="setId"/>
        </div>
    </div>
</template>