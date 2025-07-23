<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps, ref, computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Loader from '@/components/Loader.vue';
import Pagination from '@/components/Pagination.vue';
const props = defineProps({
    featureRequests: Object,
});
const page = usePage();
const expanded = ref({});
const featureRequestsData = computed(() => page.props.featureRequests?.data || []);
const paginationLinks = computed(() => page.props.featureRequests?.links || []);
const isLoading = ref(false);
function toggleExpand(id) {
    expanded.value[id] = !expanded.value[id];
}

const getExperience = (feature) => feature.feature_experience || '';
function handlePaginationClick(url) {
    if (!url) return;
    isLoading.value = true;

    router.visit(url, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            isLoading.value = false;
        }
    });
}
</script>

<template>

    <Head title="Feature Requests" />
    <AppLayout>
        <div class="px-6 py-8 max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-extrabold text-primary flex items-center gap-2">
                    <Icon icon="mage:light-bulb" width="32" height="32" style="color: #528fab" />
                    Feature Requests
                </h1>

                <button @click="router.visit('/dashboard')"
                    class="inline-flex items-center text-sm font-medium text-primary ">
                    <Icon icon="mdi:arrow-left" class="mr-1" width="20" height="20" />
                    Back to Dashboard
                </button>
            </div>
            <div>
                <div v-if="featureRequestsData.length">
                    <ul>
                        <li v-for="feature in featureRequestsData" :key="feature.id"
                            class="mb-6 border border-gray-200 rounded-lg shadow-lg p-6 hover:shadow-indigo-300 transition-shadow duration-300">
                            <h2 class="text-2xl font-semibold mb-2 text-gray-800">Feature Name: <span class="text-primary">{{ feature.feature_name }}</span></h2>
                            <p class="text-sm text-indigo-600 font-medium mb-4 select-none">
                                Submitted by: <span class="text-black">{{ feature.user ? feature.user.name : 'Unknown' }}</span>
                            </p>
                             <p class="text-sm text-indigo-600 font-medium mb-4 select-none">
                                {{ feature.user ? feature.user.name : 'Unknown' }} Contact number: <span class="text-black">{{ feature.user.phone ||'N/A' }}</span>
                            </p>
                             <p class="text-sm text-indigo-600 font-medium mb-4 select-none">Description: <span class="text-black">{{ feature.feature_description }}</span></p>

                            <div>
                                <h3 class="font-medium text-gray-600 mb-1">Feature Experience:</h3>
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
                                        <button v-if="getExperience(feature).length > 100"
                                            @click="toggleExpand(feature.id)"
                                            class="ml-2 text-indigo-600 hover:text-indigo-900 text-sm font-semibold">
                                            View more
                                        </button>
                                    </template>
                                </p>
                            </div>
                        </li>
                    </ul>
                    <Loader v-if="isLoading" class="mt-4" />
                    <Pagination :links="paginationLinks" :onPageClick="handlePaginationClick" class="mt-6" />
                </div>

                <p v-else class="text-gray-500 italic">No feature requests found.</p>
            </div>
        </div>
    </AppLayout>
</template>
