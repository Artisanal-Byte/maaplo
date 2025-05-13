<script setup>
import { ref, watch } from 'vue';
import Input from './InputWithLabel.vue';
import { Icon } from '@iconify/vue';
const trialDate = ref('')
const deliveryDate = ref('')

const emits = defineEmits(['setTrialDate','setdDeliveryDate'])

watch(()=>trialDate.value,(newVal)=>{
    emits('setTrialDate',newVal)
})
watch(()=>deliveryDate.value,(newVal)=>{
    emits('setdDeliveryDate',newVal)
})
</script>
<template>
    <div class="flex flex-col lg:flex-row justify-between">
        <div class="lg:gap-2 gap-2 flex flex-col lg:w-96">
            <div class="flex">
                <Input type="date" id="trail-date" v-model="trialDate" label="Trail Date :" width="lg" name="trail-date"
                    color="grayBorder" margin="md" />
                <Icon icon="mingcute:delete-fill" width="24" height="24" class="mt-10 ml-2" />
            </div>
        </div>

        <!-- Delivery Date -->
        <div class="lg:gap-2 gap-2 flex flex-col lg:w-96">
            <div>
                <Input type="date" id="delivery-date"   v-model="deliveryDate"  label="Delivery Date :" width="lg" name="delivery-date"
                    color="grayBorder" margin="md" />
            </div>
        </div>
    </div>
</template>