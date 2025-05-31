<script setup>
import { ref, defineProps } from 'vue';
import { Icon } from '@iconify/vue';
import SearchSelect from '../SearchSelect.vue';

const props = defineProps(["itemTypes"])
const emit = defineEmits(['setItemId'])

const showDropdownitemTypes = ref(false);
function toggleDropdownitemTypes() {
    showDropdownitemTypes.value = !showDropdownitemTypes.value;
}

const setItemTemplateId = (id) => {
    emit('setItemId', id);
}



</script>
<template>
    <div class="mt-5">
        <div @click="toggleDropdownitemTypes()" class="flex">
            <h1 class="font-medium leading-4 tracking-normal font-lato">Item Type<span
                    class="text-red-500 text-lg">*</span></h1>
            <Icon v-if="showDropdownitemTypes == false" icon="icon-park-outline:down" width="20" height="20"
                class="text-black ml-2 mt-1" />
            <Icon v-if="showDropdownitemTypes == true" icon="icon-park-outline:up" width="20" height="20"
                class="text-black ml-2 mt-1" />
        </div>
        <!-- Dropdown -->
        <div v-show="showDropdownitemTypes" class="z-10">
            <SearchSelect :public-templates="itemTypes.publicTemplates" :private-templates="itemTypes.privateTemplates"
                @setItemId="setItemTemplateId" class="mt-5" />
        </div>
    </div>
</template>