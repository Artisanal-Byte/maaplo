<script setup>
import { reactive, ref, watch, defineProps, defineEmits } from 'vue';
import { Icon } from '@iconify/vue';
import Input from '../InputWithLabel.vue';
import { useOrderFormStore } from '@/stores/orderFormStore';

let formStore = useOrderFormStore()
const showDropdownWorkType = ref(false);

function toggleDropdownWorkType() {
  showDropdownWorkType.value = !showDropdownWorkType.value;
}




//calculate the total cost of item
watch(
  () => [formStore.order_items_template.material_cost, formStore.order_items_template.stiching_cost, formStore.order_items_template.altering_cost],
  ([materialCost, stitchingCost, alteringCost]) => {
    let total = 0
    if (formStore.order_items_template.work_type == 'New from Material') {
      const m = parseFloat(materialCost) || 0;
      const s = parseFloat(stitchingCost) || 0;
      total = m + s;
      formStore.order_items_template.material_cost = materialCost
      formStore.order_items_template.stiching_cost = stitchingCost
      formStore.order_items_template.item_cost = total
    }

    if (formStore.order_items_template.work_type == 'Only Stitching') {
      total = stitchingCost;
      formStore.order_items_template.material_cost = 0
      formStore.order_items_template.stiching_cost = stitchingCost
      formStore.order_items_template.item_cost = total
    }
    if (formStore.order_items_template.work_type == 'Only Altering') {
      total = alteringCost;
      formStore.order_items_template.material_cost = 0
      formStore.order_items_template.stiching_cost = 0
      formStore.order_items_template.item_cost = total
    }
  },
);


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
                v-model="formStore.order_items_template.work_type" label="New from Material" />

            </div>
            <div class="flex gap-2">
              <Input type="radio" id="only-stitching" name="work-type" radioValue="Only Stitching"
                v-model="formStore.order_items_template.work_type" label="Only Stitching" />

            </div>
            <div class="flex gap-2">
              <Input type="radio" id="only-altering" name="work-type" radioValue="Only Altering"
                v-model="formStore.order_items_template.work_type" label="Only Altering" />

            </div>
          </div>
        </li>
      </ul>

      <!-- Conditional inputs -->
      <div v-if="formStore.order_items_template.work_type === 'New from Material'"
        class="mt-4 ml-2 flex flex-col gap-3">
        <Input v-model="formStore.order_items_template.material_code" type="text" label="Material Code" :required="true"
          placeholder="Enter material code" />
        <Input v-model="formStore.order_items_template.material_cost" type="number" label="Material Cost"
          :required="true" placeholder="Enter material cost" />
        <Input v-model="formStore.order_items_template.material_type" type="text" label="Material Type" :required="true"
          placeholder="Enter material type" />
        <Input v-model="formStore.order_items_template.stiching_cost" type="number" label="Stitching Cost"
          :required="true" placeholder="Enter stitching cost" />
        <h1>Total Cost:{{ formStore.order_items_template.item_cost }}</h1>
      </div>

      <div v-if="formStore.order_items_template.work_type === 'Only Stitching'" class="mt-4 ml-2">
        <Input v-model="formStore.order_items_template.stiching_cost" type="number" label="Cost" :required="true"
          placeholder="Enter Cost" />
      </div>

      <div v-if="formStore.order_items_template.work_type === 'Only Altering'" class="mt-4 ml-2">
        <Input v-model="formStore.order_items_template.altering_cost" type="number" label="Cost" :required="true"
          placeholder="Enter Cost" />
      </div>

    </div>
  </div>
</template>
