<script setup>
import { ref, watch } from 'vue';
import Input from './InputWithLabel.vue';
import { Icon } from '@iconify/vue';
import { useOrderFormStore } from '@/stores/orderFormStore';
const formStore = useOrderFormStore()
const props = defineProps(["order", "currentEditIndex"])

watch(() => props.currentEditIndex, (index) => {
    if (
        index !== null &&
        index !== undefined &&
        props.order?.order_items?.length > index
    ) {
        const item = props.order.order_items[index];

        // Set the trial date and delivery date in the form store
        formStore.order_items_template.trial_dates = formatDateForInput(item.trial_dates);
        formStore.order_items_template.delivery_date = formatDateForInput(item.delivery_date);
    }
}, { immediate: true });

// Utility: Convert DD-MM-YYYY to YYYY-MM-DD for <input type="date">
function formatDateForInput(dateStr) {
    if (!dateStr) return '';
    const [day, month, year] = dateStr.split('-');
    return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
}

</script>
<template>
    <div class="flex flex-col lg:flex-row justify-between">
        <div class="lg:gap-2 gap-2 flex flex-col lg:w-96">
            <div class="flex justify-between">
                <Input type="date" id="trail-date" v-model="formStore.order_items_template.trial_dates"
                    label="Trial Date" width="lg" name="trail-date" color="grayBorder" margin="md" class="w-96"
                    :required="true">
                <template #icon>
                    <Icon icon="material-symbols:date-range-rounded" width="24" height="24" class="mt-2" />
                </template>
                </Input>
                <Icon icon="mingcute:delete-fill" width="24" height="24" class="mt-11 ml-2" />
            </div>
        </div>

        <!-- Delivery Date -->
        <div class="lg:gap-2 gap-2 flex flex-col lg:w-96 mt-5 lg:mt-0">
            <div>
                <Input type="date" id="delivery-date" v-model="formStore.order_items_template.delivery_date"
                    label="Delivery Date" width="lg" name="delivery-date" color="grayBorder" margin="md"
                    :required="true">
                <template #icon>
                    <Icon icon="material-symbols:date-range-rounded" width="24" height="24" class="mt-2" />
                </template>
                </Input>
            </div>
        </div>
    </div>
</template>
