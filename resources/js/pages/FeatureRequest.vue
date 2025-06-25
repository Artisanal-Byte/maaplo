    <script setup>
    import AppLayout from '@/layouts/AppLayout.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    const form = useForm({
        feature_name: '',
        feature_description: ''
    });

    const submitted = ref(false);

    function submitForm() {
        form.post('/feature-request', {
            onSuccess: () => {
                submitted.value = true;
                form.reset(); // Clear form fields

                setTimeout(() => {
                    submitted.value = false;
                }, 4000);
            },
            onError: () => {
                console.error('Error submitting feature request:', form.errors);
            }
        });
    }
</script>

    <template>

        <Head title="Feature Request" />
        <AppLayout>
            <div class="p-6 max-w-xl mx-auto">
                <h1 class="text-2xl font-bold mb-4">Feature Request</h1>
                <p class="mb-6 text-gray-600">Submit a request for new features below:</p>

                <!-- Feature Name -->
                <div>
                    <label for="featureName" class="block text-sm font-medium text-gray-700">Feature Name</label>
                    <input id="featureName" v-model="form.feature_name" type="text"
                        class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200"
                        placeholder="Enter feature name" />
                    <div v-if="form.errors.feature_name" class="text-red-500 text-sm mt-1">{{ form.errors.feature_name
                        }}
                    </div>
                </div>

                <!-- Feature Description -->
                <div>
                    <label for="featureDescription" class="block text-sm font-medium text-gray-700">What is the use of
                        this
                        feature?</label>
                    <textarea id="featureDescription" v-model="form.feature_description" rows="4"
                        class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200"
                        placeholder="Describe how this feature will help"></textarea>
                    <div v-if="form.errors.feature_description" class="text-red-500 text-sm mt-1">{{
                        form.errors.feature_description }}</div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="button" @click="submitForm"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded"
                        :disabled="form.processing">
                        Submit Request
                    </button>
                </div>

                <!-- Success Message -->
                <div v-if="submitted" class="mt-4 text-green-600 font-semibold">
                    Feature request submitted successfully!
                </div>
            </div>
        </AppLayout>
    </template>
