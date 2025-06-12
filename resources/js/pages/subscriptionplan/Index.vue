<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';

const props = defineProps({
    subscriptionPlans: Array,
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
            <!-- Subscription Plans Table -->
            <div class="mt-10">
                <h2 class="text-xl font-bold mb-4">All Subscription Plans</h2>
                <table class="w-full border">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">Title</th>
                            <th class="border px-4 py-2">Description</th>
                            <th class="border px-4 py-2">Price</th>
                            <th class="border px-4 py-2">Currency</th>
                            <th class="border px-4 py-2">User Limit</th>
                            <th class="border px-4 py-2">Visibility</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="plan in subscriptionPlans" :key="plan.id">
                            <td class="border px-4 py-2">{{ plan.plan_title }}</td>
                            <td class="border px-4 py-2">{{ plan.plan_description }}</td>
                            <td class="border px-4 py-2">{{ plan.plan_price }}</td>
                            <td class="border px-4 py-2">{{ plan.plan_currency }}</td>
                            <td class="border px-4 py-2">{{ plan.user_limit }}</td>
                            <td class="border px-4 py-2">{{ plan.visibility ? 'Visible' : 'Hidden' }}</td>
                            <td class="border px-4 py-2 text-center">
                                <div class="flex justify-center gap-4">
                                    <Link :href="route('subscription-plans.edit', plan.id)"
                                        class="text-blue-600 hover:underline">
                                    Edit
                                    </Link>
                                    <button @click="confirmDelete(plan)" class="text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="grid grid-cols-1 md:hidden gap-4 mt-6">
                <div v-for="plan in subscriptionPlans" :key="plan.id" class="bg-white rounded-lg p-4 shadow border">
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
