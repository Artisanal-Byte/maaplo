<script setup>
import { ref, computed, watch, defineProps } from 'vue';
import { Icon } from '@iconify/vue';

const props = defineProps(['data'])
const emits = defineEmits(['setItemId'])
const modelValue = defineModel();
const showDropdown = ref(false);
const searchQuery = ref('');

<<<<<<< HEAD
const options = props.data   //['Kurta', 'Shirt', 'Kurti', 'Pajama'];

// Show all options until user types 3 or more characters
const filteredOptions = computed(() => {
  if (searchQuery.value.length < 3) return options;
  return options.filter(opt =>
    opt.name.toLowerCase().includes(searchQuery.value.toLowerCase())
=======
const props = defineProps({
  publicTemplates: {
    type: Array,
    required: true,
    default: () => []
  }
});

// const options = ['Kurta', 'Shirt', 'Kurti', 'Pajama'];

// Show all options until user types 3 or more characters
const filteredOptions = computed(() => {
  if (searchQuery.value.length < 3) return props.publicTemplates;
  return props.publicTemplates.filter(opt =>
    opt.name.toLowerCase().includes(searchQuery.value.toLowerCase()) // assuming `name` is the field to search
>>>>>>> sahil
  );
});

let check = options.map(item => item.name);

const isInvalid = computed(() => {


  return (
    searchQuery.value.length >= 3 &&
<<<<<<< HEAD
    !check.includes(searchQuery.value)
=======
    !filteredOptions.value.some(opt => opt.name.toLowerCase() === searchQuery.value.toLowerCase())
>>>>>>> sahil
  );
});

const showClearIcon = computed(() => {
  return filteredOptions.value.length > 0;
});

function selectOption(option) {
<<<<<<< HEAD
  emits('setItemId', option.id)
=======
>>>>>>> sahil
  modelValue.value = option.name;
  searchQuery.value = option.name;
  showDropdown.value = false;
}

function clearSelection() {
  modelValue.value = '';
  searchQuery.value = '';
  showDropdown.value = false;
}

watch(modelValue, (val) => {
  searchQuery.value = val || '';
});
</script>

<template>
  <div class="relative">
    <div class="relative">
<<<<<<< HEAD
      <input type="text" v-model="searchQuery" @focus="showDropdown = true"
        @blur="setTimeout(() => showDropdown = false, 200)" placeholder="Search Item Type"
        class="w-full border rounded px-3 py-2 focus:outline-none" :class="{ 'border-red-500': isInvalid }" />

      <!-- Clear Icon -->
      <Icon v-if="showClearIcon" icon="mdi:close-circle" width="20" height="20"
        class="absolute top-2.5 right-8 text-gray-500 cursor-pointer" @click="clearSelection" />
=======
      <input
        type="text"
        v-model="searchQuery"
        @focus="showDropdown = true"
        @blur="setTimeout(() => showDropdown = false, 200)"
        placeholder="Search Item Type"
        class="w-full border rounded px-3 py-2 focus:outline-none"
        :class="{ 'border-red-500': isInvalid }"
      />

      <!-- Clear Icon -->
      <Icon
        v-if="showClearIcon"
        icon="mdi:close-circle"
        width="20"
        height="20"
        class="absolute top-2.5 right-8 text-gray-500 cursor-pointer"
        @click="clearSelection"
      />
>>>>>>> sahil

      <!-- Dropdown Icon -->
      <Icon icon="icon-park-outline:down" width="20" height="20"
        class="absolute top-2.5 right-3 text-gray-500 pointer-events-none" />

      <!-- Dropdown -->
<<<<<<< HEAD
      <ul v-if="showDropdown" class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-40 overflow-auto">
        <li v-if="filteredOptions.length" v-for="option in filteredOptions" :key="option.name"
          @click="selectOption(option)" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
          {{ option.name }}
        </li>
        <li v-else class="px-4 py-2 text-gray-500 italic">
=======
      <ul
        v-if="showDropdown"
        class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-40 overflow-auto"
      >
        <li
          v-if="filteredOptions.length"
          v-for="option in filteredOptions"
          :key="option"
          @click="selectOption(option)"
          class="px-4 py-2 cursor-pointer hover:bg-gray-100"
        >
          {{ option.name }}
        </li>
        <li
          v-else
          class="px-4 py-2 text-gray-500 italic"
        >
>>>>>>> sahil
          No matching items
        </li>
      </ul>

      <!-- Invalid Message -->
      <p v-if="isInvalid" class="text-sm text-red-600 mt-1">Invalid Search</p>
    </div>
  </div>
</template>
