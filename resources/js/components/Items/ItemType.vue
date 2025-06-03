<script setup>
import { ref, defineProps, computed, watch } from 'vue';
import { Icon } from '@iconify/vue';

const props = defineProps(["itemTypes"]);
const emits = defineEmits(['setItemId', 'setSelectDesignDetails']);

const searchQuery = ref('');
const modelValue = ref('');
const showDropdown = ref(false);
const showDropdownitemTypes = ref(false);
const check = ref([]); // Optional: Populate this if needed for validation

const toggleDropdownitemTypes = () => {
  showDropdownitemTypes.value = !showDropdownitemTypes.value;
};

const combinedTemplates = computed(() => props.itemTypes || []);

const filteredOptions = computed(() => {
  if (searchQuery.value.length < 3) return combinedTemplates.value;
  return combinedTemplates.value.filter(opt =>
    opt.name?.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const isInvalid = computed(() => {
  return (
    searchQuery.value.length >= 3 &&
    !check.value.includes(searchQuery.value) &&
    !filteredOptions.value.some(opt => opt.name?.toLowerCase() === searchQuery.value.toLowerCase())
  );
});

const showClearIcon = computed(() => searchQuery.value.length > 0);

const selectOption = (option) => {
  emits('setItemId', option);
  emits('setSelectDesignDetails', option.id);
  modelValue.value = option.name;
  searchQuery.value = option.name;
  showDropdown.value = false;
};

const handleBlur = () => {
  setTimeout(() => {
    showDropdown.value = false;
  }, 200);
};

const clearSelection = () => {
  modelValue.value = '';
  searchQuery.value = '';
  showDropdown.value = false;
};

watch(modelValue, (val) => {
  searchQuery.value = val || '';
});
</script>

<template>
  <div class="mt-5">
    <div @click="toggleDropdownitemTypes" class="flex items-center cursor-pointer">
      <h1 class="font-medium leading-4 tracking-normal font-lato">
        Item Type<span class="text-red-500 text-lg">*</span>
      </h1>
      <Icon :icon="showDropdownitemTypes ? 'icon-park-outline:up' : 'icon-park-outline:down'" width="20" height="20"
        class="text-black ml-2 mt-1" />
    </div>

    <div v-show="showDropdownitemTypes" class="z-10">
      <div class="relative">
        <!-- Clear Icon -->
        <Icon v-if="showClearIcon" icon="mdi:close-circle" width="20" height="20"
          class="absolute top-2.5 right-8 text-gray-500 cursor-pointer" @click="clearSelection" />

        <!-- Search Input -->
        <input type="text" v-model="searchQuery" @focus="showDropdown = true" @blur="handleBlur"
          placeholder="Search Item Type" class="w-full border border-primary rounded px-3 py-2 focus:outline-none"
          :class="{ 'border-red-500': isInvalid }" />

        <!-- Dropdown Icon -->
        <Icon icon="icon-park-outline:down" width="20" height="20"
          class="absolute top-2.5 right-3 text-gray-500 pointer-events-none" />

        <!-- Dropdown List -->
        <ul v-if="showDropdown" class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-40 overflow-auto">
          <li v-for="option in filteredOptions" :key="option.name" @click="selectOption(option)"
            class="px-4 py-2 cursor-pointer hover:bg-gray-100">
            {{ option.name }}
          </li>
          <li v-if="filteredOptions.length === 0" class="px-4 py-2 text-gray-500 italic">
            No matching items
          </li>
        </ul>

        <!-- Invalid Message -->
        <p v-if="isInvalid" class="text-sm text-red-600 mt-1">Invalid Search</p>
      </div>
    </div>
  </div>
</template>
