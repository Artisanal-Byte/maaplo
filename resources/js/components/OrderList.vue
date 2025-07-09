<script setup>
import { Icon } from '@iconify/vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
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

// Modal control
const showDeleteModal = ref(false);
const selectedOrderId = ref(null);

const formatStatus = (status) => {
    if (!status) return '';
    return status
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};
function openDeleteModal(id) {
    selectedOrderId.value = id;
    showDeleteModal.value = true;
}
function confirmDelete() {
    router.delete(route('orders.destroy', selectedOrderId.value), {
        onFinish: () => {
            showDeleteModal.value = false;
            selectedOrderId.value = null;
        }
    });
}
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
            <button @click="openDeleteModal(order.id)">
                <Icon icon="ic:baseline-delete" width="18" height="18" class="text-[#E73939]" />
            </button>
        </div>

    </div>
    <!-- Delete Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-md max-w-sm w-full">
            <h2 class="text-lg font-bold mb-4">Delete Confirmation</h2>
            <p class="mb-4">Are you sure you want to delete this order?</p>
            <div class="flex justify-end gap-3">
                <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button @click="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
            </div>
        </div>
    </div>

</template>
