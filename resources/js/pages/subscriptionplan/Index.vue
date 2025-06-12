<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';

defineProps({
    plans: Array,
    authUser: Object
});

const toast = new ToastMagic();
const showDeletePopup = ref(false);
const planToDelete = ref(null);

const confirmDelete = (plan) => {
    planToDelete.value = plan;
    showDeletePopup.value = true;
};

const cancelDelete = () => {
    showDeletePopup.value = false;
    planToDelete.value = null;
};

const proceedDelete = () => {
    router.delete(route('subscription-plans.destroy', planToDelete.value.id), {
        onSuccess: () => {
            toast.success('Subscription Plan deleted successfully!');
            showDeletePopup.value = false;
        },
        onError: () => {
            toast.error('Failed to delete plan.');
        }
    });
};
</script>

<template>
    <Head title="Subscription Plans" />
    <AppLayout>
        <div class="max-w-7xl mx-auto py-8 px-4">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-primary flex items-center gap-2">
                    <Icon icon="mdi:calendar-star" width="28" height="28" />
                    Subscription Plans
                </h1>
                <Link :href="route('subscription-plans.create')" class="relative group">
                    <Icon icon="material-symbols:add-circle-outline" width="30" height="30" />
                    <div
                        class="absolute top-full mt-1 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 pointer-events-none z-10">
                        Create Plan
                    </div>
                </Link>
            </div>

            <!-- Desktop Table -->
            <div class="hidden md:block mt-6">
                <table class="min-w-full border border-gray-300 text-sm">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="p-2 border border-gray-300 text-center">Title</th>
                            <th class="p-2 border border-gray-300 text-center">Price</th>
                            <th class="p-2 border border-gray-300 text-center">Currency</th>
                            <th class="p-2 border border-gray-300 text-center">User Limit</th>
                            <th class="p-2 border border-gray-300 text-center">Visible</th>
                            <th class="p-2 border border-gray-300 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="plan in plans" :key="plan.id" class="hover:bg-gray-100 transition">
                            <td class="p-2 border text-center">{{ plan.plan_title }}</td>
                            <td class="p-2 border text-center">{{ plan.plan_price }}</td>
                            <td class="p-2 border text-center">{{ plan.plan_currency }}</td>
                            <td class="p-2 border text-center">{{ plan.user_limit }}</td>
                            <td class="p-2 border text-center">
                                <span :class="plan.visibility ? 'text-green-600' : 'text-red-500'">
                                    {{ plan.visibility ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="p-2 border text-center">
                                <div class="flex justify-center gap-3">
                                    <!-- Edit -->
                                    <div class="relative group">
                                        <Link :href="route('subscription-plans.edit', plan.id)">
                                            <Icon icon="ri:edit-fill" class="text-primary" width="20" height="20" />
                                        </Link>
                                        <span
                                            class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-black text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition pointer-events-none z-10">
                                            Edit
                                        </span>
                                    </div>

                                    <!-- Delete -->
                                    <div class="relative group">
                                        <button @click="confirmDelete(plan)">
                                            <Icon icon="ic:baseline-delete" class="text-[#E73939]" width="20" height="20" />
                                        </button>
                                        <span
                                            class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-black text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition pointer-events-none z-10">
                                            Delete
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="grid grid-cols-1 md:hidden gap-4 mt-6">
                <div v-for="plan in plans" :key="plan.id" class="bg-white rounded-lg p-4 shadow border">
                    <h2 class="text-lg font-semibold text-gray-800">{{ plan.plan_title }}</h2>
                    <p class="text-sm text-gray-500">Price: {{ plan.plan_price }} {{ plan.plan_currency }}</p>
                    <p class="text-sm text-gray-500">Users: {{ plan.user_limit }}</p>
                    <p class="text-sm text-gray-500">Visible: {{ plan.visibility ? 'Yes' : 'No' }}</p>
                    <div class="mt-3 flex gap-4">
                        <Link :href="route('subscription-plans.edit', plan.id)" class="text-blue-600">Edit</Link>
                        <button @click="confirmDelete(plan)" class="text-red-600">Delete</button>
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <div v-if="showDeletePopup"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded shadow-lg w-11/12 max-w-md">
                    <h2 class="text-xl font-bold mb-3">Confirm Deletion</h2>
                    <p class="text-gray-700 mb-4">
                        Are you sure you want to delete plan <strong>{{ planToDelete.plan_title }}</strong>?
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
