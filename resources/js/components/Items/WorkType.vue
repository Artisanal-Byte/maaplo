<script setup>
import { reactive, ref, watch, defineProps, defineEmits } from 'vue';
import { Icon } from '@iconify/vue';
import Input from '../InputWithLabel.vue';

const props = defineProps(['formData', 'index'])

const emits = defineEmits(['setPrice', 'setAlteringCost', 'setMaterialCode', 'setMaterial', 'setMaterialCost', 'setStichingCost', 'setItemCost', 'setMaterialType', 'setdataData'])
const modelValue = defineModel(); // enables v-model binding
const data = props.formData
const showDropdownWorkType = ref(false);

function toggleDropdownWorkType() {
  showDropdownWorkType.value = !showDropdownWorkType.value;
}


let material = reactive({
  code: '',
  cost: 0,
  type: '',
})

let cost = reactive({
  stitching: 0,
  altering: 0,
  total: null
})



//reset cost value on change work type
watch(() => modelValue.value, (val) => {
  cost.stitching = 0
  cost.altering = 0
  cost.total = 0
  material.cost = 0
})

//calculate the total cost 
watch(
  () => [material.cost, cost.stitching, cost.altering],
  ([materialCost, stitchingCost, alteringCost]) => {
    // cost.total = cost.stitching = cost.altering = material.cost = 0
    if (modelValue.value == 'New from Material') {
      const m = parseFloat(materialCost) || 0;
      const s = parseFloat(stitchingCost) || 0;
      cost.total = m + s;
      emits('setAlteringCost', null)
      emits('setMaterialCost', materialCost)
      emits('setStichingCost', stitchingCost)
      emits('setPrice', cost.total)
    }

    if (modelValue.value == 'Only Stitching') {
      cost.total = stitchingCost;
      emits('setAlteringCost', null)
      emits('setStichingCost', stitchingCost)
      emits('setPrice', cost.total)
    }
    if (modelValue.value == 'Only Altering') {
      cost.total = alteringCost;
      emits('setStichingCost', null)
      emits('setAlteringCost', alteringCost)
      emits('setPrice', cost.total)
    }
  },
);

//set material code
watch(() => material.code, (val) => {
  data.material_code = val
  emits('setMaterialCode', val)
})

//set work Type 
watch(() => modelValue.value, (val) => {
  data.work_type = val
  emits('setdataData', val)
})

//set material type
watch(() => material.type, (val) => {
  data.material_type = val
  emits('setMaterialType', val)
})

</script>

<template>
  <div>
    <div @click="toggleDropdownWorkType" class="flex cursor-pointer">
      <h1 class="font-medium font-lato">Work Type<span class="text-red-500 text-lg">*</span></h1>
      <Icon :icon="showDropdownWorkType ? 'icon-park-outline:up' : 'icon-park-outline:down'" width="20" height="20"
        class="text-black ml-2 mt-1" />
    </div>

    <div v-show="showDropdownWorkType" class="z-10 mt-2">
      <ul class="text-md text-black">
        <li>
          <div class="flex flex-col ml-2 gap-3 text-black mt-2">
            <div class="flex gap-2">
              <Input type="radio" id="new-from-material" name="work-type" radioValue="New from Material"
                v-model="modelValue" label="New from Material" />

            </div>
            <div class="flex gap-2">
              <Input type="radio" id="only-stitching" name="work-type" radioValue="Only Stitching" v-model="modelValue"
                label="Only Stitching" />

            </div>
            <div class="flex gap-2">
              <Input type="radio" id="only-altering" name="work-type" radioValue="Only Altering" v-model="modelValue"
                label="Only Altering" />

            </div>
          </div>
        </li>
      </ul>

      <!-- Conditional inputs -->
      <div v-if="modelValue === 'New from Material'" class="mt-4 ml-2 flex flex-col gap-3">
        <Input v-model="material.code" type="text" label="Material Code" :required="true"
          placeholder="Enter material code" />
        <Input v-model="material.cost" type="number" label="Material Cost" :required="true"
          placeholder="Enter material cost" />
        <Input v-model="material.type" type="text" label="Material Type" :required="true"
          placeholder="Enter material type" />
        <Input v-model="cost.stitching" type="number" label="Stitching Cost" :required="true"
          placeholder="Enter stitching cost" />
        <h1>Total Cost:{{ cost.total }}</h1>
      </div>

      <div v-if="modelValue === 'Only Stitching'" class="mt-4 ml-2">
        <Input v-model="cost.stitching" type="number" label="Cost" :required="true" placeholder="Enter Cost" />
      </div>

      <div v-if="modelValue === 'Only Altering'" class="mt-4 ml-2">
        <Input v-model="cost.altering" type="number" label="Cost" :required="true" placeholder="Enter Cost" />
      </div>

    </div>
  </div>
</template>
