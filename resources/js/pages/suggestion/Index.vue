<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const suggestions = computed(() => page.props.suggestions || []);
const expanded = ref({});

function toggleExpand(id) {
    expanded.value[id] = !expanded.value[id];
}
</script>

<template>

    <Head title="Suggestions" />
    <AppLayout>
        <div class="px-6 py-8 max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-4">Suggestions</h1>

            <div v-if="suggestions.length === 0" class="text-gray-500">
                No suggestions found.
            </div>

            <div v-else class="space-y-4">
                <div v-for="suggestion in suggestions" :key="suggestion.id" class="p-4 border rounded shadow-sm">
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
            </div>
        </div>
    </AppLayout>
</template>
