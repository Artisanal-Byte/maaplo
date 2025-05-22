<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { defineProps, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps<{
    users: Array<{
        id: number,
        name: string,
        email: string,
        phone: string,
        address: string,
        organization_name: string,
        subscription_plan: string,
        validity: string,
    }>,
}>();

const showModal = ref(false);
const selectedUser = ref<any>(null);
const selectedStatus = ref<boolean>(false);

function openConfirmationModal(user: any) {
    selectedUser.value = user;
    showModal.value = true;
}

function confirmToggleStatus() {
    if (!selectedUser.value) return;

    router.post(route('admin.toggleStatus', selectedUser.value.id), {
        status: !selectedUser.value.status
    });

    showModal.value = false;
}

// Toggle enable/disable state
function toggleStatus(userId: number, currentStatus: boolean) {
    const action = currentStatus ? 'deactivate' : 'activate';
    const confirmed = window.confirm(`Are you sure you want to ${action} this user?`);

    if (!confirmed) return;

    router.post(route('admin.toggleStatus', userId), {
        status: !currentStatus
    });
}

</script>

<template>
    <AppLayout>

        <!-- Confirmation Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 shadow-md w-full max-w-md">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Confirm {{ selectedUser?.status ? 'Deactivation' : 'Activation' }}
                </h2>
                <p class="text-gray-700 mb-6">
                    Are you sure you want to {{ selectedUser?.status ? 'deactivate' : 'activate' }} the user
                    <strong>{{ selectedUser?.name }}</strong>?
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="showModal = false"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded">
                        NO
                    </button>
                    <button @click="confirmToggleStatus"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                        YES
                    </button>
                </div>
            </div>
        </div>


        <div class="lg:mx-auto max-w-7xl py-8 px-4">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-[24px] mt-3 leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                        User's
                    </h1>
                    <p class="text-sm text-gray-700 text-right mt-2">
                        Total Users: {{ users.length }}
                    </p>
                </div>

                <!-- Create Admin Button -->
                <div class="relative group mt-1">
                    <Link :href="route('user.create')" class="cursor-pointer">
                    <Icon icon="material-symbols:add-rounded" width="30" height="30" />
                    </Link>
                    <div
                        class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                        Create User
                    </div>
                </div>
            </div>

            <!-- Admin Cards -->
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-10">
                <div v-for="user in users" :key="user.id"
                    class="relative mt-5 h-full rounded-[10px] px-[18px] py-[17px] bg-[#DEEFF4]">
                    <div class="flex-2">
                        <h1 class="text-[22px] font-semibold tracking-wide text-gray-900">
                            Name: {{ user.name }}
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-3">
                            Email: {{ user.email }}
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Contact: {{ user.phone ?? 'N/A' }}
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Organization Name: {{ user.organization_name ?? 'N/A' }}
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Subscription Plan: {{ user.subscription_plan }}
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Validity: {{ user.validity ?? 'N/A' }}
                        </h1>
                    </div>

                    <div class="flex justify-end gap-3">
                        <!-- Edit -->
                        <div class="relative group">
                            <Link :href="route('user.edit', user.id)">
                            <Icon icon="ri:edit-fill" width="18" height="18" class="text-[#005FAF] cursor-pointer" />
                            </Link>
                            <div
                                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                Edit
                            </div>
                        </div>

                        <!-- Active/Deactive Button -->
                        <div class="relative group">
                            <button @click="openConfirmationModal(user)"
                                class="flex items-center text-white px-3 py-1 rounded-full text-sm font-medium"
                                :class="user.status ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'">
                                {{ user.status ? 'Deactive' : 'Active' }}
                            </button>
                            <div
                                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                Toggle Status
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
