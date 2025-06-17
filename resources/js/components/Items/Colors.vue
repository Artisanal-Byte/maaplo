<script setup>
import Input from '../InputWithLabel.vue';
import { Icon } from '@iconify/vue';
import { useOrderFormStore } from '@/stores/orderFormStore';
const props = defineProps({
    errors: {
        type: [Object, String],
        default: () => ({})
    },
    currentEditIndex: {
        type: Number,
        required: true
    }
});
const formStore = useOrderFormStore()

</script>
<template>
    <div class="flex flex-col">

        <Input v-model="formStore.order_items_template.colors" id="favcolor" name="favcolor" label="Color:" type="text"
            color="grayBorder" margin="md" placeholder="Enter color" required="true">
        <template #icon>
            <Icon icon="mdi:color" width="22" height="22" class="mt-2" />
        </template>
        </Input>
        <h1 class="text-red-500 text-sm mt-2">
            {{ typeof errors === 'string' ? errors : '' }}
        </h1>
    </div>
</template>
