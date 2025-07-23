<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Icon } from '@iconify/vue';
import Pagination from '@/components/Pagination.vue';
import Loader from '@/components/Loader.vue';
const page = usePage();
const suggestionsData = computed(() => page.props.suggestions?.data || []);
const paginationLinks = computed(() => page.props.suggestions?.links || []);

const expanded = ref({});
const isLoading = ref(false);
function toggleExpand(id) {
    expanded.value[id] = !expanded.value[id];
}


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

    <Head title="Suggestions" />
    <AppLayout>
        <div class="px-6 py-8 max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-extrabold text-primary flex items-center gap-2">
                    <Icon icon="ic:sharp-settings-suggest" width="32" height="32" style="color: #528fab" />
                    Suggestions
                </h1>

                <button @click="router.visit('/dashboard')"
                    class="inline-flex items-center text-sm font-medium text-primary ">
                    <Icon icon="mdi:arrow-left" class="mr-1" width="20" height="20" />
                    Back to Dashboard
                </button>
            </div>

            <div v-if="suggestionsData.length === 0" class="text-gray-500">
                No suggestions found.
            </div>

            <div v-else class="space-y-4">
                <div v-for="suggestion in suggestionsData" :key="suggestion.id" class="p-4 border rounded shadow-sm">
                    <p class="mt-2 text-sm text-indigo-600 font-medium select-none">
                        Submitted by: {{ suggestion.user.name }}
                    </p>
                    <h2 class="text-lg font-semibold">{{ suggestion.suggestion_title }}</h2>

                    <p class="text-gray-700 mt-1">
                        <template v-if="expanded[suggestion.id]">
                            {{ suggestion.suggestion_description }}
                            <button @click="toggleExpand(suggestion.id)"
                                class="ml-2 text-indigo-600 hover:text-indigo-900 text-sm font-semibold">
                                View less
                            </button>
                        </template>

                        <template v-else>
                            {{
                                suggestion.suggestion_description.length > 100
                                    ? suggestion.suggestion_description.slice(0, 100) + '...'
                                    : suggestion.suggestion_description
                            }}
                            <button v-if="suggestion.suggestion_description.length > 100"
                                @click="toggleExpand(suggestion.id)"
                                class="ml-2 text-indigo-600 hover:text-indigo-900 text-sm font-semibold">
                                View more
                            </button>
                        </template>
                    </p>
                </div>
                <!-- Loader during navigation -->
                <Loader v-if="isLoading" />

                <!-- Pagination -->
                <Pagination :links="paginationLinks" :onPageClick="handlePaginationClick" />
            </div>
        </div>
    </AppLayout>
</template>
