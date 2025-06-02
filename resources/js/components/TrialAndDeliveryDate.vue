<script setup>
import { ref, watch } from 'vue';
import Input from './InputWithLabel.vue';
import { Icon } from '@iconify/vue';
import { useOrderFormStore } from '@/stores/orderFormStore';
const formStore = useOrderFormStore()


</script>
<template>
    <div class="flex flex-col lg:flex-row justify-between">
        <div class="lg:gap-2 gap-2 flex flex-col lg:w-96">
            <div class="flex justify-between">
                <Input type="date" id="trail-date"  v-model="formStore.order_items_template.trial_dates" label="Trial Date" width="lg"
                    name="trail-date" color="grayBorder" margin="md" class="w-96" :required="true">
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
                <Input type="date" id="delivery-date" v-model="formStore.order_items_template.delivery_date" label="Delivery Date" width="lg"
                    name="delivery-date" color="grayBorder" margin="md" :required="true">
                <template #icon>
                    <Icon icon="material-symbols:date-range-rounded" width="24" height="24" class="mt-2" />
                </template>
                </Input>
            </div>
        </div>
    </div>
</template>