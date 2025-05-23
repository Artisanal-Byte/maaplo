<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import UserTemplateList from '@/components/UserTemplateList.vue';
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';

const props = defineProps({
    users: Array
});

const showModal = ref(false);
const selectedUser = ref<any>(null);

function openConfirmationModal(user: any) {
    selectedUser.value = user;
    showModal.value = true;
}

function cancelModal() {
    showModal.value = false;
    selectedUser.value = null;
}

function confirmToggleStatus() {
    if (!selectedUser.value) return;

    router.post(route('admin.toggleStatus', selectedUser.value.id), {
        status: !selectedUser.value.status
    });
    showModal.value = false;
}
</script>

<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto py-8 px-4">
            <!-- Header Section -->
            <div class="flex justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Users</h1>
                <div class="flex gap-4 items-center text-gray-600">
                    <Link :href="route('user.create')" class="relative group">
                    <Icon icon="material-symbols:add-rounded" width="30" height="30" />
                    <div
                        class="absolute top-full mt-1 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 bg-gray-800 text-white text-xs rounded px-2 py-1 transition pointer-events-none z-10">
                        Create User
                    </div>
                    </Link>
                </div>
            </div>

            <!-- Mobile View (Card Layout) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:hidden">
                <UserTemplateList v-for="user in users" :key="user.id" :user="user"
                    @toggleStatus="openConfirmationModal" />
            </div>

            <!-- Desktop View (Table Layout) -->
            <div class="hidden md:block mt-6 overflow-hidden rounded-lg">
                <table class="min-w-full">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="p-2 border border-gray-300 text-center">Name</th>
                            <th class="p-2 border border-gray-300 text-center">Email</th>
                            <th class="p-2 border border-gray-300 text-center">Phone</th>
                            <th class="p-2 border border-gray-300 text-center">Organization</th>
                            <th class="p-2 border border-gray-300 text-center">Plan</th>
                            <th class="p-2 border border-gray-300 text-center">Status</th>
                            <th class="p-2 border border-gray-300 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-100 transition">
                            <td class="p-2 border text-center">{{ user.name }}</td>
                            <td class="p-2 border text-center">{{ user.email }}</td>
                            <td class="p-2 border text-center">{{ user.phone ?? 'N/A' }}</td>
                            <td class="p-2 border text-center">{{ user.organization_name ?? 'N/A' }}</td>
                            <td class="p-2 border text-center">{{ user.subscription_plan }}</td>
                            <td class="p-2 border text-center">
                                <span :class="user.status ? 'text-green-600 font-bold' : 'text-red-600'">
                                    {{ user.status ? 'Active' : 'Deactivated' }}
                                </span>
                            </td>
                            <td class="p-2 border text-center">
                                <div class="flex justify-center gap-4">
                                    <Link :href="route('user.edit', user.id)" class="relative group">
                                    <Icon icon="ri:edit-fill" class="text-primary" width="20" height="20" />
                                    <div
                                        class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-black text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 z-10">
                                        Edit
                                    </div>
                                    </Link>

                                    <button @click="openConfirmationModal(user)" class="relative group">
                                        <Icon icon="mdi:account-lock"
                                            :class="user.status ? 'text-green-600' : 'text-red-600'" width="20"
                                            height="20" />
                                        <div
                                            class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-black text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 z-10">
                                            Toggle Status
                                        </div>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Status Confirmation Modal -->
            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded shadow-lg w-11/12 max-w-md">
                    <h2 class="text-xl font-bold mb-3">Are you sure?</h2>
                    <p class="text-gray-700 mb-4">
                        You are about to
                        <span class="font-semibold text-red-500">{{ selectedUser?.status ? 'deactivate' : 'activate'
                            }}</span>
                        <strong>{{ selectedUser?.name }}</strong>.
                    </p>
                    <div class="flex justify-end gap-3">
                        <Button @click="cancelModal" color="gray">Cancel</Button>
                        <Button @click="confirmToggleStatus" color="primary">Yes</Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
