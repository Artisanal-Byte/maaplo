<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';

const toast = new ToastMagic();

const form = useForm({
    feature_name: '',
    feature_description: '',
    feature_experience: ''
});

const submitForm = () => {
    form.post('/feature-request', {
        onSuccess: () => {
            toast.success("Your feature suggestion was sent successfully!");
            form.reset();
        },
        onError: () => {
            toast.error("Error submitting feature request. Please check the fields.");
            console.error('Error submitting feature request:', form.errors);
        }
    });
};
</script>

<template>

    <Head title="Feature Request" />
    <AppLayout>
        <div class="px-4 py-8 max-w-3xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-primary font-[Convergence] flex items-center gap-2">
                    <Icon icon="mdi:lightbulb-on-outline" width="28" height="28" />
                    Feature Request
                </h1>
                <Link :href="route('dashboard')" class="flex items-center gap-2 text-gray-600 hover:text-black">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-md border-t-4 border-primary rounded-lg p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Feature Name -->
                    <Input v-model="form.feature_name" label="Feature Name" placeholder="Enter the feature name"
                        :error="form.errors.feature_name" required="true">
                    <template #icon>
                        <Icon icon="mdi:text-box-outline" width="24" height="24" />
                    </template>
                    </Input>

                    <!-- Feature Description -->
                    <Input v-model="form.feature_description" type="textarea" label="Feature Description"
                        placeholder="Describe the feature and its purpose" :error="form.errors.feature_description"
                        required="true">
                    <template #icon>
                        <Icon icon="mdi:comment-text-outline" width="24" height="24" />
                    </template>
                    </Input>

                    <!-- Feature Experience -->
                    <Input v-model="form.feature_experience" type="textarea" label="Feature Experience"
                        placeholder="Describe your experience or context for this feature"
                        :error="form.errors.feature_experience" required="true">
                    <template #icon>
                        <Icon icon="mdi:star-circle-outline" width="24" height="24" />
                    </template>
                    </Input>

                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex">
                    <Button @click="submitForm" :disabled="form.processing" :color="'primary'" :padding="'md'"
                         :textSize="'sm'"
                        class="w-full flex justify-center items-center hover:scale-105 transition-transform duration-200">
                        <Icon icon="mdi:send" width="20" height="20" class="mr-2" />
                        Submit Feature Request
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

