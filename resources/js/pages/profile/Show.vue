<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps(['user']);

function goToEdit() {
    router.visit(route('profile.edit'));
}
</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <h1 class="text-[24px] text-primary leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                Profile
            </h1>
            <div class="bg-white rounded-lg p-6 shadow-md max-w-md">
                <p><strong>Name:</strong> {{ props.user.name }}</p>
                <p><strong>Email:</strong> {{ props.user.email }}</p>
                <p><strong>Phone:</strong> {{ props.user.phone }}</p>
                <p><strong>Organization Name:</strong> {{ props.user.organization_name }}</p>
                <p><strong>Subscription Plan:</strong> {{ props.user.subscription_plan }}</p>
                <p><strong>Validity:</strong> {{ props.user.validity ? new
                    Date(props.user.validity).toLocaleDateString() : 'N/A' }}</p>

                <div v-if="props.user.organization_logo">
                    <strong>Organization Logo:</strong>
                    <img :src="`/storage/${props.user.organization_logo.replace(/^storage\//, '')}`" alt="Org Logo"
                        class="mt-2 w-32 h-auto" />

                </div>

                <button @click="goToEdit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Edit Profile
                </button>
            </div>
        </div>
    </AppLayout>
</template>
