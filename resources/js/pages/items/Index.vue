<script setup>
import ItemTemplateList from '@/components/ItemTemplateList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';

defineProps({
    items: Array,
    authUser: Object
});
const toast = new ToastMagic();
const showDeletePopup = ref(false);
const itemToDelete = ref(null);

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeletePopup.value = true;
};

const cancelDelete = () => {
    showDeletePopup.value = false;
    itemToDelete.value = null;
};

const proceedDelete = () => {
    router.delete(route('items.destroy', itemToDelete.value.id), {
        onSuccess: () => {
            toast.success('Item deleted successfully!');
            showDeletePopup.value = false;
        },
        onError: () => {
            toast.error('Failed to delete item.');
            alert('Delete failed');
        }
    });
};
</script>

<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto py-8 px-4">
            <!-- Header Section -->
            <div class="flex flex-row justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Template's</h1>

                <div class="flex gap-4 text-gray-600 items-center">
                    <Link :href="route('items.create')" class="relative group">
                    <Icon icon="material-symbols:add-rounded" width="30" height="30" />
                    <div
                        class="absolute top-full mt-1 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                        Create Template
                    </div>
                    </Link>
                </div>
            </div>

            <!-- Mobile View: Card Layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:hidden">
                <ItemTemplateList v-for="item in items" :key="item.id" :item="item" :authUser="authUser" view="card"
                    @delete="confirmDelete" />
            </div>

            <!-- Desktop View: Table Layout -->
            <div class="hidden md:block mt-6">
                <table class="min-w-full border border-gray-300">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="p-2 border border-gray-300 text-center">Item</th>
                            <th class="p-2 border border-gray-300 text-center">Gender</th>
                            <th class="p-2 border border-gray-300 text-center">Body Part</th>
                            <th class="p-2 border border-gray-300 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items" :key="item.id" class="hover:bg-gray-100 transition">
                            <td class="p-2 border border-gray-300 text-center">{{ item.name }}</td>
                            <td class="p-2 border border-gray-300 text-center">{{ item.gender }}</td>
                            <td class="p-2 border border-gray-300 text-center">{{ item.body_part }}</td>
                            <td class="p-2 border border-gray-300 text-center">
                                <div v-if="item.user_id === authUser.id" class="flex justify-center gap-4">
                                    <!-- Edit Button with Tooltip -->
                                    <div class="relative group">
                                        <Link :href="route('items.edit', item.id)">
                                        <Icon icon="ri:edit-fill" class="text-primary" width="20" height="20" />
                                        </Link>
                                        <span
                                            class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-black text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition pointer-events-none z-10">
                                            Edit Template
                                        </span>
                                    </div>

                                    <!-- Delete Button with Tooltip -->
                                    <div class="relative group">
                                        <button @click="confirmDelete(item)">
                                            <Icon icon="ic:baseline-delete" class="text-[#E73939]" width="20"
                                                height="20" />
                                        </button>
                                        <span
                                            class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-black text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition pointer-events-none z-10">
                                            Delete Template
                                        </span>
                                    </div>
                                </div>
                                <div v-else class="text-gray-500">🔒 Not Editable</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Delete Confirmation Modal -->
            <div v-if="showDeletePopup"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded shadow-lg w-11/12 max-w-md">
                    <h2 class="text-xl font-bold mb-3">Are you sure?</h2>
                    <p class="text-gray-700 mb-4">
                        <span class="text-red-600 font-semibold">Warning:</span>
                        This will delete <strong>{{ itemToDelete.name }}</strong>.
                    </p>
                    <div class="flex justify-end gap-3">
                        <Button @click="cancelDelete" color="gray">Cancel</Button>
                        <Button @click="proceedDelete" color="danger">Delete</Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
