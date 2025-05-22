<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const props = defineProps<{
    user: {
        id: number,
        name: string,
        email: string,
        phone: string,
        address: string,
        organization_name: string,
        subscription_plan: string,
        validity: string,
        status: boolean,
    },
}>();

const emits = defineEmits(['toggleStatus']);
</script>

<template>
    <div class="bg-[#DEEFF4] rounded-lg p-4">
        <h1 class="text-lg font-semibold text-gray-900">Name: {{ user.name }}</h1>
        <p class="mt-2">Email: {{ user.email }}</p>
        <p>Phone: {{ user.phone ?? 'N/A' }}</p>
        <p>Organization: {{ user.organization_name ?? 'N/A' }}</p>
        <p>Subscription Plan: {{ user.subscription_plan }}</p>
        <p>Validity: {{ user.validity ?? 'N/A' }}</p>

        <div class="mt-4 flex justify-end gap-4">
            <!-- Edit -->
            <div class="relative group">
                <Link :href="route('user.edit', user.id)">
                    <Icon icon="ri:edit-fill" class="text-blue-700" width="20" height="20" />
                </Link>
                <div class="absolute top-full mt-1 left-1/2 -translate-x-1/2 text-xs bg-black text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition z-10">
                    Edit User
                </div>
            </div>

            <!-- Status Toggle -->
            <div class="relative group">
                <button @click="$emit('toggleStatus', user)"
                    :class="user.status ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600'"
                    class="text-white px-3 py-1 rounded text-sm font-medium">
                    {{ user.status ? 'Active' : 'Deactivated' }}
                </button>
                <div class="absolute top-full mt-1 left-1/2 -translate-x-1/2 text-xs bg-black text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition z-10">
                    Toggle Status
                </div>
            </div>
        </div>
    </div>
</template>
