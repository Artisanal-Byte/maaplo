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
                    <Icon icon="material-symbols:add-rounded" width="30" height="30" />
                    <div
                        class="absolute top-full mt-1 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition pointer-events-none z-10">
                        Create Plan
                    </div>
                </Link>
            </div>

            <!-- Mobile Cards -->
            <div class="grid grid-cols-1 md:hidden gap-6">
                <div v-for="plan in subscriptionPlans" :key="plan.id" class="bg-white shadow rounded-lg p-4 border">
                    <h2 class="text-lg font-semibold text-gray-800">{{ plan.plan_title }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ plan.plan_description }}</p>
                    <div class="mt-2 text-sm text-gray-500">
                        <p>Price: {{ plan.plan_price }} {{ plan.plan_currency }}</p>
                        <p>Users: {{ plan.user_limit }}</p>
                        <p>Visibility: {{ plan.visibility ? 'Visible' : 'Hidden' }}</p>
                    </div>
                    <div class="flex gap-4 mt-4">
                        <Link :href="route('subscription-plans.edit', plan.id)" class="text-blue-600 hover:underline">
                            Edit
                        </Link>
                        <button @click="confirmDelete(plan)" class="text-red-600 hover:underline">Delete</button>
                    </div>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden md:block mt-10 overflow-hidden rounded-lg">
                <table class="min-w-full border">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="p-3 border text-center">Title</th>
                            <th class="p-3 border text-center">Description</th>
                            <th class="p-3 border text-center">Price</th>
                            <th class="p-3 border text-center">Currency</th>
                            <th class="p-3 border text-center">User Limit</th>
                            <th class="p-3 border text-center">Visibility</th>
                            <th class="p-3 border text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="plan in subscriptionPlans" :key="plan.id" class="hover:bg-gray-100 transition">
                            <td class="p-3 border text-center">{{ plan.plan_title }}</td>
                            <td class="p-3 border text-center">{{ plan.plan_description }}</td>
                            <td class="p-3 border text-center">{{ plan.plan_price }}</td>
                            <td class="p-3 border text-center">{{ plan.plan_currency }}</td>
                            <td class="p-3 border text-center">{{ plan.user_limit }}</td>
                            <td class="p-3 border text-center">{{ plan.visibility ? 'Visible' : 'Hidden' }}</td>
                            <td class="p-3 border text-center">
                                <div class="flex justify-center gap-4">
                                    <Link :href="route('subscription-plans.edit', plan.id)" class="relative group">
                                        <Icon icon="ri:edit-fill" class="text-primary" width="20" height="20" />
                                        <div
                                            class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-black text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 z-10">
                                            Edit
                                        </div>
                                    </Link>
                                    <button @click="confirmDelete(plan)" class="relative group">
                                        <Icon icon="mdi:delete" class="text-red-600" width="20" height="20" />
                                        <div
                                            class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 bg-black text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 z-10">
                                            Delete
                                        </div>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Delete Modal -->
            <div v-if="showDeletePopup" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded shadow-lg w-11/12 max-w-md">
                    <h2 class="text-xl font-bold mb-3">Confirm Deletion</h2>
                    <p class="text-gray-700 mb-4">
                        Are you sure you want to delete plan
                        <strong>{{ planToDelete.plan_title }}</strong>?
                    </p>
                    <div class="flex justify-end gap-3">
                        <Button @click="cancelDelete" color="gray">Cancel</Button>
                        <Button @click="proceedDelete" color="red">Delete</Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
