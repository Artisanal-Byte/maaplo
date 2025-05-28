<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { Icon } from '@iconify/vue';
import SvgIcon from '../SvgIcon.vue';
import Input from '../InputWithLabel.vue';

const props = defineProps<{
  toAsk: Record<string, string> | null;
}>();

const emit = defineEmits<{
  (e: 'update:measurements', value: Record<string, string>): void;
}>();

const measurements = ref<Record<string, string>>({
  unit: 'in',
});
const unit = ref<'in' | 'cm'>(measurements.value.unit as 'in' | 'cm' || 'in');

watch(unit, (newUnit) => {
  measurements.value.unit = newUnit;
});

watch(measurements, (val) => {
  emit('update:measurements', val);
}, { deep: true });

const showDropdownMeasurements = ref(false);



function toggleDropdownMeasurements() {
  showDropdownMeasurements.value = !showDropdownMeasurements.value;
}
</script>
<template>
  <div class="mt-2">
    <div @click="toggleDropdownMeasurements()" class="flex">
      <h1 class="font-medium leading-4 tracking-normal font-lato">Measurements
      </h1>
      <Icon v-if="showDropdownMeasurements == false" icon="icon-park-outline:down" width="20" height="20"
        class="text-black ml-2" />
      <Icon v-if="showDropdownMeasurements == true" icon="icon-park-outline:up" width="20" height="20"
        class="text-black ml-2" />
    </div>
    <!-- Dropdown -->
    <div v-show="showDropdownMeasurements" class="z-10">
      <div class="flex items-center gap-3 mt-2 ml-1">
        <SvgIcon :name="'unit'" />
        <span
          class="font-normal text-[16px] leading-[8px] tracking-normal font-lato font-normal text-[16px] leading-[8px] tracking-normal font-lato font-normal text-[16px] leading-[8px] tracking-normal font-lato">Unit</span>
        <div class="flex border border-gray-300 rounded overflow-hidden text-sm">
          <button :class="[
            'px-4 py-1 focus:outline-none',
            unit === 'in' ? 'bg-primary text-white' : 'bg-white text-black'
          ]" @click="unit = 'in'">
            Inches
          </button>
          <button :class="[
            'px-4 py-1 focus:outline-none',
            unit === 'cm' ? 'bg-primary text-white' : 'bg-white text-black'
          ]" @click="unit = 'cm'">
            Centimeters
          </button>
        </div>
      </div>

      <ul class="text-md text-black dark:text-black" aria-labelledby="dropdownTrigger">
        <li>
          <div class="grid grid-col-1 lg:grid-cols-2 gap-x-4 gap-y-4 ml-2 mt-4 text-black font-lato text-sm">
            <!-- Length -->

            <div class="flex items-center gap-4 col-span-2 sm:col-span-1" v-for="(value, key) in JSON.parse(toAsk)"
              :key="key">
              <SvgIcon :name="value.replace(/ /g, '-').toLowerCase()" />
              <span
                class="w-32 font-normal text-[16px] leading-[8px] tracking-normal font-lato font-normal text-[16px] leading-[8px] tracking-normal font-lato font-normal text-[16px] leading-[8px] tracking-normal font-lato">{{
                  value }}</span>
              <Input v-model="measurements[key]" type="number" width="md" color="grayBorder" padding="sm"
                rounded="sm" />
              <span class="text-sm text-gray-500">{{ unit }}</span>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
