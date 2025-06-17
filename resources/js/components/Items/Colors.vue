<script setup>
import Input from '../InputWithLabel.vue';
import { Icon } from '@iconify/vue';
import { useOrderFormStore } from '@/stores/orderFormStore';
const props = defineProps(["errors","currentEditIndex"])
const formStore = useOrderFormStore()

</script>
<template>
    <div class="flex flex-col">
        <h1>{{ errors[`order_items.${currentEditIndex}.colors`] }}</h1>
        <Input v-model="formStore.order_items_template.colors" id="favcolor" name="favcolor" label="Color:" type="text"
            color="grayBorder" margin="md" placeholder="Enter color" required="true">
        <template #icon>
            <Icon icon="mdi:color" width="22" height="22" class="mt-2" />
        </template>
        </Input>
    </div>
</template>
