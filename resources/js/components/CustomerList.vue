<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Button from './Button.vue';
const toast = new ToastMagic();

const props = defineProps<{
    customer: {
        id: number;
        name: string;
        phone: string;
        email?: string;
        active_orders?: number;
        payment_due?: number;
        face_image?: string;
    };
}>();

const showDropdown = ref(false);
const showDeletePopup = ref(false);

const confirmDelete = () => {
    showDeletePopup.value = true;
};

const cancelDelete = () => {
    showDeletePopup.value = false;
};

const proceedDelete = () => {
    deleteCustomer(props.customer.id);
    showDeletePopup.value = false;
};


const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value;
};

const deleteCustomer = (customerId: number) => {
    router.delete(route('customers.destroy', customerId), {
        onSuccess: () => {
            toast.success('Customer permanently deleted!');
            router.reload();
        },
        onError: () => {
            toast.error('Failed to delete customer.');
        }
    });
};

</script>

<template>
    <div class="relative mt-5 h-full rounded-[10px] px-[10px] py-[17px] bg-[#DEEFF4]">
        <div class="flex justify-between gap-4">
            <!-- Left Section -->
            <div class="flex-2">
                <h1 class="text-[22px] font-semibold tracking-wide text-gray-900">
                    {{ props.customer.name }}
                </h1>

                <div @click="toggleDropdown()" class="flex items-center gap-2 cursor-pointer mt-4">
                    <h1 class="font-[Lato] font-medium text-[18px] text-black">Contact</h1>
                    <Icon :icon="showDropdown ? 'icon-park-outline:up' : 'icon-park-outline:down'" width="20"
                        height="20" />
                </div>

                <div v-show="showDropdown" class="mt-2">
                    <ul class="text-sm text-gray-700 space-y-2">
                        <li class="flex items-center gap-2">
                            <Icon icon="ic:baseline-phone" width="16" height="16" />
                            <span class="text-black">{{ props.customer.phone }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <Icon icon="ic:outline-email" width="18" height="18" />
                            <span class="text-black">{{ props.customer.email || 'Email Not Available' }}</span>
                        </li>
                    </ul>
                </div>

                <h1 class="font-[Lato] font-medium text-[18px] text-black mt-3">
                    Active Order : {{ props.customer.active_orders || 'N/A' }}
                </h1>
                <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                    Payment Due : ₹ {{ props.customer.payment_due || '0.00' }}
                </h1>
                <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                    Total Payment : ₹ 0.00
                </h1>
            </div>

            <!-- Right Section: Image -->
            <div class="w-[150px] h-[100px] bg-[#E4F7FF] rounded-md overflow-hidden flex items-center justify-center">
                <img v-if="props.customer.face_image" :src="props.customer.face_image" alt="Customer Image"
                    class="w-full h-full object-fill" />
                <img v-else src="/images/by_default_user.png" alt="Default Image" class="w-94 h-full object-fill" />
            </div>

        </div>

        <!-- Edit & Delete Icons with Tooltips -->
        <div class="flex justify-end gap-3">
            <!-- Edit -->
            <div class="relative group">
                <Link :href="route('customers.edit', props.customer.id)">
                <Icon icon="ri:edit-fill" width="18" height="18" class="text-primary" />
                </Link>
                <div
                    class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                    Edit
                </div>
            </div>

            <!-- Delete -->
            <div class="relative group">
                <button @click="confirmDelete" class="flex items-center text-red-500 hover:text-red-600 transition">
                    <Icon icon="ic:baseline-delete" width="18" height="18" class="text-[#E73939]" />
                </button>
                <div
                    class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                    Delete
                </div>
            </div>
        </div>

        <!-- Delete Popup (optional, same as before) -->
        <div v-if="showDeletePopup"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm transition-all">
            <div
                class="bg-white p-8 rounded-2xl shadow-xl mx-4 lg:mx-0 lg:w-1/3 max-w-lg text-gray-800 animate-fade-in">
                <!-- Modal Header -->
                <div class="flex items-center mb-4">
                    <Icon icon="ic:baseline-warning" class="text-red-500 mr-2" width="28" height="28" />
                    <h2 class="text-2xl font-bold text-red-600">Delete Customer ?</h2>
                </div>

                <!-- Modal Body -->
                <div class="mb-6 space-y-3">
                    <p class="text-md lg:text-lg leading-relaxed">
                        You're about to <strong class="text-red-600">permanently delete</strong> customer
                        <strong class="text-primary">{{ props.customer.name }}</strong>.
                    </p>
                    <p class="text-sm text-gray-600">
                        This action will remove all data associated with this customer and
                        <span class="font-semibold text-red-600">cannot be undone.</span>
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-4">
                    <Button @click="cancelDelete" :color="'gray'">Cancel</Button>
                    <Button @click="proceedDelete" :color="'danger'">Delete</Button>
                </div>
            </div>
        </div>

    </div>
</template>
