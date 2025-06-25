<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    featureRequests: Array,
});
</script>

<template>

    <Head title="Feature Requests" />
    <AppLayout>
        <div class="px-6 py-8 max-w-3xl mx-auto">
            <h1 class="text-2xl font-bold mb-4">Feature Requests</h1>

            <div v-if="featureRequests.length">
                <ul>
                    <li v-for="feature in featureRequests" :key="feature.id" class="mb-4 border p-4 rounded shadow">
                        <h2 class="text-xl font-semibold">{{ feature.feature_name }}</h2>
                        <p class="text-gray-700">{{ feature.feature_description }}</p>
                        <p class="mt-2 italic text-sm text-gray-500">Experience: {{ feature.feature_experience }}</p>
                    </li>
                </ul>
            </div>
            <p v-else class="text-gray-600">No feature requests found.</p>
        </div>
    </AppLayout>
</template>
