<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Button from './Button.vue';

const toast = new ToastMagic();

const props = defineProps({
    item: {
        type: Object,
        required: true
    },
    authUser: {
        type: Object,
        required: true
    }
});

const showDeletePopup = ref(false);

const confirmDelete = () => {
    showDeletePopup.value = true;
};

const cancelDelete = () => {
    showDeletePopup.value = false;
};

const proceedDelete = () => {
    router.delete(route('items.destroy', props.item.id), {
        onSuccess: () => {
            toast.success('Item deleted successfully!');
            router.reload(); // optional: or just remove from list manually
        },
        onError: () => {
            toast.error('Failed to delete item.');
        }
    });
    showDeletePopup.value = false;
};
</script>

<template>
    <div class="relative mt-5 h-full rounded-[10px] px-[10px] py-[17px] bg-[#DEEFF4]">
        <!-- Table for Desktop View -->
        <div class="hidden md:block">
            <table class="min-w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="border border-gray-300 p-2 text-left">Item</th>
                        <th class="border border-gray-300 p-2 text-left">Gender</th>
                        <th class="border border-gray-300 p-2 text-left">Body Part</th>
                        <th class="border border-gray-300 p-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 p-2">{{ item.name }}</td>
                        <td class="border border-gray-300 p-2">{{ item.gender }}</td>
                        <td class="border border-gray-300 p-2">{{ item.body_part }}</td>
                        <td class="border border-gray-300 p-2">
                            <div v-if="item.user_id === authUser.id" class="flex justify-end gap-3">
                                <!-- Edit Icon -->
                                <div class="relative group">
                                    <Link :href="route('items.edit', props.item.id)">
                                        <Icon icon="ri:edit-fill" width="20" height="20" class="text-[#005FAF]" />
                                    </Link>
                                    <div class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                        Edit
                                    </div>
                                </div>

                                <!-- Delete Icon -->
                                <div class="relative group">
                                    <button @click="confirmDelete" class="flex items-center text-red-500 hover:text-red-600 transition">
                                        <Icon icon="ic:baseline-delete" width="20" height="20" class="text-[#E73939]" />
                                    </button>
                                    <div class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                        Delete
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Card for Mobile View -->
        <div class="block md:hidden space-y-4">
            <div class="bg-white shadow-lg rounded-lg p-4 border border-gray-200">
                <h1 class="text-[22px] font-semibold tracking-wide text-gray-900">{{ item.name }}</h1>
                <h2 class="font-[Lato] font-medium text-[18px] text-black mt-3">Gender: {{ item.gender }}</h2>
                <h2 class="font-[Lato] font-medium text-[18px] text-black mt-2">Body Part: {{ item.body_part }}</h2>

                <div v-if="item.user_id === authUser.id" class="flex justify-end gap-3 mt-4">
                    <!-- Edit Icon -->
                    <div class="relative group">
                        <Link :href="route('items.edit', props.item.id)">
                            <Icon icon="ri:edit-fill" width="20" height="20" class="text-[#005FAF]" />
                        </Link>
                        <div class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                            Edit
                        </div>
                    </div>

                    <!-- Delete Icon -->
                    <div class="relative group">
                        <button @click="confirmDelete" class="flex items-center text-red-500 hover:text-red-600 transition">
                            <Icon icon="ic:baseline-delete" width="20" height="20" class="text-[#E73939]" />
                        </button>
                        <div class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                            Delete
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeletePopup"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg mx-5 lg:w-1/3">
                <h2 class="text-xl font-bold mb-4">Are you sure?</h2>
                <p class="mb-4 text-md lg:text-lg text-black font-semibold">
                    <span class="text-md text-red-600 lg:text-xl">Warning:</span> This will delete
                    <span class="font-bold text-blue-600">{{ item.name }}</span>.
                </p>
                <div class="flex justify-end gap-4">
                    <Button @click="cancelDelete" :color="'gray'">Cancel</Button>
                    <Button @click="proceedDelete" :color="'danger'">Delete</Button>
                </div>
            </div>
        </div>
    </div>
</template>
