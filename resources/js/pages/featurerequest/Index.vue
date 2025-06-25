<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const props = defineProps({
    featureRequests: Array,
});

const expanded = ref({});

function toggleExpand(id) {
    expanded.value[id] = !expanded.value[id];
}

const getExperience = (feature) => feature.feature_experience || '';
</script>

<template>

    <Head title="Feature Requests" />
    <AppLayout>
        <div class="px-6 py-8 max-w-3xl mx-auto">
            <h1 class="text-3xl font-extrabold mb-6 text-indigo-700">Feature Requests</h1>

            <div v-if="featureRequests.length">
                <ul>
                    <li v-for="feature in featureRequests" :key="feature.id"
                        class="mb-6 border border-gray-200 rounded-lg shadow-lg p-6 hover:shadow-indigo-300 transition-shadow duration-300">
                        <h2 class="text-2xl font-semibold mb-2 text-gray-800">{{ feature.feature_name }}</h2>
                        <p class="text-sm text-indigo-600 font-medium mb-4 select-none">
                            Submitted by: {{ feature.user ? feature.user.name : 'Unknown' }}
                        </p>
                        <p class="text-gray-700 mb-4">{{ feature.feature_description }}</p>

                        <div>
                            <h3 class="font-medium text-gray-600 mb-1">Experience:</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                <template v-if="expanded[feature.id]">
                                    {{ getExperience(feature) }}
                                    <button @click="toggleExpand(feature.id)"
                                        class="ml-2 text-indigo-600 hover:text-indigo-900 text-sm font-semibold">
                                        View less
                                    </button>
                                </template>
                                <template v-else>
                                    {{
                                        getExperience(feature).length > 100
                                            ? getExperience(feature).slice(0, 100) + '...'
                                            : getExperience(feature)
                                    }}
                                    <button v-if="getExperience(feature).length > 100" @click="toggleExpand(feature.id)"
                                        class="ml-2 text-indigo-600 hover:text-indigo-900 text-sm font-semibold">
                                        View more
                                    </button>
                                </template>
                            </p>
                        </div>
                    </li>
                </ul>
            </div>

            <p v-else class="text-gray-500 italic">No feature requests found.</p>
        </div>
    </AppLayout>
</template>
