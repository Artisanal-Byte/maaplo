<script setup>
import { ref, computed, watch, defineProps } from 'vue';
import { Icon } from '@iconify/vue';

const emits = defineEmits(['setItemId', 'templateSelected']);
const modelValue = defineModel();
const showDropdown = ref(false);
const searchQuery = ref('');

const props = defineProps({
  publicTemplates: {
    type: Array,
    required: true,
    default: () => []
  },
  privateTemplates: {
    type: Array,
    required: false,
    default: () => []
  }
});

const combinedTemplates = computed(() => {
  return [
    ...props.publicTemplates,
    ...props.privateTemplates
  ].map(template => ({
    ...template,
    required_measurements: template.required_measurements || []
  }));
});

const filteredOptions = computed(() => {
  if (searchQuery.value.length < 3) return combinedTemplates.value;
  return combinedTemplates.value.filter(opt =>
    opt.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

let check = computed(() => combinedTemplates.value.map(item => item.name));

const isInvalid = computed(() => {
  return (
    searchQuery.value.length >= 3 &&
    !check.value.includes(searchQuery.value) &&
    !filteredOptions.value.some(opt => opt.name.toLowerCase() === searchQuery.value.toLowerCase())
  );
});

const showClearIcon = computed(() => {
  return filteredOptions.value.length > 0;
});

function formatLabel(option) {
  const genderMap = { Male: 'Male', Female: 'Female' };
  const genderLabel = option.gender ? genderMap[option.gender] || 'Unknown' : 'Unknown';
  return `${option.name} (${genderLabel})`;
}

function selectOption(option) {
  emits('setItemId', option.id);
   emits('templateSelected', {
    ...option,
    required_measurements: Array.isArray(option.required_measurements)
      ? option.required_measurements
      : []
  });
  const label = formatLabel(option);
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

function handleBlur() {
  setTimeout(() => {
    showDropdown.value = false;
  }, 200);
}
</script>


<template>
  <div class="relative">
    <!-- Clear Icon -->
    <Icon v-if="showClearIcon" icon="mdi:close-circle" width="20" height="20"
          class="absolute top-2.5 right-8 text-gray-500 cursor-pointer" @click="clearSelection" />

    <input type="text" v-model="searchQuery" @focus="showDropdown = true" @blur="handleBlur"
           placeholder="Search Item Type" class="w-full border rounded px-3 py-2 focus:outline-none"
           :class="{ 'border-red-500': isInvalid }" />

    <!-- Dropdown Icon -->
    <Icon icon="icon-park-outline:down" width="20" height="20"
          class="absolute top-2.5 right-3 text-gray-500 pointer-events-none" />

    <!-- Dropdown -->
    <ul v-if="showDropdown" class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-40 overflow-auto">
      <li v-if="filteredOptions.length" v-for="option in filteredOptions" :key="option.name"
          @click="selectOption(option)" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
        {{ formatLabel(option) }}
      </li>
      <li v-else class="px-4 py-2 text-gray-500 italic">
        No matching items
      </li>
    </ul>

    <!-- Invalid Message -->
    <p v-if="isInvalid" class="text-sm text-red-600 mt-1">Invalid Search</p>
  </div>
</template>

