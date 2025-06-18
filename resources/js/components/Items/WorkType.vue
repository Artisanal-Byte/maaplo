<script setup>
import { ref, watch, defineProps, computed } from 'vue';
import { Icon } from '@iconify/vue';
import Input from '../InputWithLabel.vue';
import { useOrderFormStore } from '@/stores/orderFormStore';

const props = defineProps(["order", "currentEditIndex"]);

let formStore = useOrderFormStore()
const showDropdownWorkType = ref(false);

function toggleDropdownWorkType() {
    showDropdownWorkType.value = !showDropdownWorkType.value;
}

const isEditing = computed(() => props.currentEditIndex !== null && props.currentEditIndex !== undefined);

const currentItem = computed(() => {
    return isEditing.value
        ? formStore.order_items[props.currentEditIndex]
        : formStore.order_items_template;
});

// Watch the whole item deeply — good for initialization
watch(
    () => currentItem.value,
    (item) => {
        if (!item) return;

        const workType = item.work_type;
        const materialCost = item.material_cost;
        const stitchingCost = item.stiching_cost;
        const alteringCost = item.altering_cost;

        let total = 0;

        const m = parseFloat(materialCost) || 0;
        const s = parseFloat(stitchingCost) || 0;
        const a = parseFloat(alteringCost) || 0;

        if (workType === 'New from Material') {
            total = m + s;
            item.material_cost = m;
            item.stiching_cost = s;
        }

        if (workType === 'Only Stitching') {
            total = s;
            item.material_cost = 0;
            item.stiching_cost = s;
        }

        if (workType === 'Only Altering') {
            total = a;
            item.material_cost = 0;
            item.stiching_cost = 0;
            item.altering_cost = a;
        }

        item.item_cost = total;
    },
    { immediate: true, deep: true }
);

// Watch work type for conditional resets
watch(
    () => currentItem.value?.work_type,
    (newType) => {
        if (!currentItem.value) return;

        if (newType === 'Only Stitching') {
            currentItem.value.material_code = '';
            currentItem.value.material_type = '';
            currentItem.value.material_cost = 0;
            currentItem.value.altering_cost = 0;
        } else if (newType === 'Only Altering') {
            currentItem.value.material_code = '';
            currentItem.value.material_type = '';
            currentItem.value.material_cost = 0;
            currentItem.value.stiching_cost = 0;
        } else if (newType === 'New from Material') {
            currentItem.value.altering_cost = 0;
        }
    },
    { immediate: true }
);

// ✅ Added watcher: reactively update item_cost on field changes
watch(
    () => [
        currentItem.value?.material_cost,
        currentItem.value?.stiching_cost,
        currentItem.value?.altering_cost,
        currentItem.value?.work_type
    ],
    ([m, s, a, workType]) => {
        if (!currentItem.value) return;

        let materialCost = parseFloat(m) || 0;
        let stitchingCost = parseFloat(s) || 0;
        let alteringCost = parseFloat(a) || 0;

        let total = 0;

        if (workType === 'New from Material') {
            total = materialCost + stitchingCost;
        } else if (workType === 'Only Stitching') {
            total = stitchingCost;
        } else if (workType === 'Only Altering') {
            total = alteringCost;
        }

        currentItem.value.item_cost = total;
    }
);
</script>


<template>
    <div v-if="currentItem">
        <div @click="toggleDropdownWorkType" class="flex cursor-pointer">
            <h1 class="font-medium font-lato">
                Work Type<span class="text-red-500 text-lg">*</span>
            </h1>
            <Icon :icon="showDropdownWorkType ? 'icon-park-outline:up' : 'icon-park-outline:down'" width="20"
                height="20" class="text-black ml-2 mt-1" />
        </div>

        <div v-show="showDropdownWorkType" class="z-10 mt-2">
            <ul class="text-md text-black">
                <li>
                    <div class="flex flex-col ml-2 gap-3 text-black mt-2">
                        <div class="flex gap-2">
                            <Input type="radio" id="new-from-material" name="work-type" radioValue="New from Material"
                                v-model="currentItem.work_type" label="New from Material" />
                        </div>
                        <div class="flex gap-2">
                            <Input type="radio" id="only-stitching" name="work-type" radioValue="Only Stitching"
                                v-model="currentItem.work_type" label="Only Stitching"  />
                        </div>
                        <div class="flex gap-2">
                            <Input type="radio" id="only-altering" name="work-type" radioValue="Only Altering"
                                v-model="currentItem.work_type" label="Only Altering" />
                        </div>
                    </div>
                </li>
            </ul>

            <!-- Conditional inputs -->
            <div v-if="currentItem.work_type === 'New from Material'" class="mt-4 ml-2 flex flex-col gap-3">
                <Input v-model="currentItem.material_code" type="text" label="Material Code" :required="true"
                    placeholder="Enter material code" />
                <Input v-model="currentItem.material_cost" type="number" label="Material Cost" :required="true"
                    placeholder="Enter material cost" />
                <Input v-model="currentItem.material_type" type="text" label="Material Type" :required="true"
                    placeholder="Enter material type" />
                <Input v-model="currentItem.stiching_cost" type="number" label="Stitching Cost" :required="true"
                    placeholder="Enter stitching cost" />
                <h1>Total Cost: {{ currentItem.item_cost }}</h1>
            </div>

            <div v-if="currentItem.work_type === 'Only Stitching'" class="mt-4 ml-2">
                <Input v-model="currentItem.stiching_cost" type="number" label="Cost" :required="true"
                    placeholder="Enter Cost" />
                <h1>Total Cost: {{ currentItem.item_cost }}</h1>
            </div>

            <div v-if="currentItem.work_type === 'Only Altering'" class="mt-4 ml-2">
                <Input v-model="currentItem.altering_cost" type="number" label="Cost" :required="true"
                    placeholder="Enter Cost" />
                <h1>Total Cost: {{ currentItem.item_cost }}</h1>
            </div>
        </div>
    </div>
</template>
