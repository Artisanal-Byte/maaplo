<script setup>
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';
const props = defineProps({
    bgColor: {
        default: '#FFFCE6'
    },
    borderColor: {
        type: String,
        default: '#837200'
    },
    order: {
        type: Object,
        required: true
    }

});
const formatStatus = (status) => {
    if (!status) return '';
    return status
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};
</script>
<template>

    <div class=" gap-[10px] rounded-[10px] px-[10px] py-[17px]" :style="{
        backgroundColor: bgColor,
        border: `1px solid ${borderColor}`,

    }">
        <div class="flex flex-row">
            <div>
                <h1
                    class="font-[Lato] font-medium text-[15px] lg:text-[18px] leading-[16px] tracking-[0] text-secondary p-2">
                    # Order No.{{ order.order_number }}</h1>
            </div>
            <div>
                <h1
                    class="font-[Lato] font-medium text-[15px] lg:text-[18px] leading-[16px] tracking-[0] text-secondary p-2">
                    Customer: {{ order?.customer?.name }}
                </h1>
            </div>

        </div>
        <div class="flex flex-row justify-between">
            <div>
                <p
                    class="font-[Lato] font-medium text-[16px] leading-[16px] tracking-[0] text-center text-secondary p-2">
                    Items: {{ order?.order_items?.length }}</p>
            </div>
            <div>
                <p
                    class="font-[Lato] font-medium text-[16px] leading-[16px] tracking-[0] text-center text-secondary p-2">
                    Delivery Date: {{ order?.delivery_date }}</p>
            </div>
        </div>
        <div>
            <p class="font-[Lato] font-medium text-[16px] leading-[16px] tracking-[0]  text-secondary p-2">
                Status: {{ formatStatus(order?.status) }}
            </p>
        </div>
        <div class="flex justify-end gap-3">
            <Link :href="route('orders.show', props.order.id)">
            <Icon icon="teenyicons:eye-solid" width="18" height="18" class="text-primary" />
            </Link>
            <Link :href="route('orders.edit', props.order.id)">
            <Icon icon="ri:edit-fill" width="18" height="18" class="text-primary" />
            </Link>
            <Link :href="route('orders.destroy', props.order.id)">
            <Icon icon="ic:baseline-delete" width="18" height="18" class="text-[#E73939]" />
            </Link>
        </div>

    </div>



</template>
