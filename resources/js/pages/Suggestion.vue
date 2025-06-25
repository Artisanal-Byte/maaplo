<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    suggestion_title: '',
    suggestion_description: ''
});

const submitted = ref(false);

function submitForm() {
    form.post('/suggestion', {
        onSuccess: () => {
            submitted.value = true;
            form.reset(); // Clear form fields

            setTimeout(() => {
                submitted.value = false;
            }, 4000);
        },
        onError: () => {
            console.error('Error submitting suggestion:', form.errors);
        }
    });
}
</script>

<template>
    <Head title="Add Suggestion" />
    <AppLayout>
        <div class="p-6 max-w-xl mx-auto">
            <h1 class="text-2xl font-bold mb-4">Add Suggestion</h1>
            <p class="mb-6 text-gray-600">Submit your suggestion to help us improve:</p>

            <!-- Suggestion Title -->
            <div>
                <label for="suggestionTitle" class="block text-sm font-medium text-gray-700">Suggestion Title</label>
                <input id="suggestionTitle" v-model="form.suggestion_title" type="text"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200"
                    placeholder="Enter suggestion title" />
                <div v-if="form.errors.suggestion_title" class="text-red-500 text-sm mt-1">
                    {{ form.errors.suggestion_title }}
                </div>
            </div>

            <!-- Suggestion Description -->
            <div class="mt-4">
                <label for="suggestionDescription" class="block text-sm font-medium text-gray-700">Suggestion Details</label>
                <textarea id="suggestionDescription" v-model="form.suggestion_description" rows="4"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200"
                    placeholder="Describe your suggestion"></textarea>
                <div v-if="form.errors.suggestion_description" class="text-red-500 text-sm mt-1">
                    {{ form.errors.suggestion_description }}
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="button" @click="submitForm"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded"
                    :disabled="form.processing">
                    Submit Suggestion
                </button>
            </div>

            <!-- Success Message -->
            <div v-if="submitted" class="mt-4 text-green-600 font-semibold">
                Suggestion submitted successfully!
            </div>
        </div>
    </AppLayout>
</template>
