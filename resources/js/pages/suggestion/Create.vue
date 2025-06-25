<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';

const toast = new ToastMagic();

const form = useForm({
    suggestion_title: '',
    suggestion_description: ''
});

function submitForm() {
    form.post('/suggestion', {
        onSuccess: () => {
            toast.success("Your suggestion was sent successfully! We will review it soon.");
            form.reset();
        },
        onError: () => {
            toast.error("Error submitting suggestion. Please check the fields.");
        }
    });
}
</script>

<template>


    <Head title="Add Suggestion" />
    <AppLayout>
        <div class="px-6 py-8 max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-primary font-[Convergence] flex items-center gap-2">
                    <Icon icon="mdi:comment-plus-outline" width="28" height="28" />
                    Add Suggestion
                </h1>
                <Link :href="route('dashboard')" class="flex items-center gap-2 text-gray-600 hover:text-black">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>
            <p class="mb-8 text-gray-600">Submit your suggestion to help us improve:</p>

            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-primary">
                <Input v-model="form.suggestion_title" label="Suggestion Title" placeholder="Enter suggestion title"
                    :error="form.errors.suggestion_title" required="true">
                <template #icon>
                    <Icon icon="mdi:title" width="24" height="24" />
                </template>
                </Input>

                <Input v-model="form.suggestion_description" label="Suggestion Details"
                    placeholder="Describe your suggestion" type="textarea" rows="4"
                    :error="form.errors.suggestion_description" class="mt-6" required="true">
                <template #icon>
                    <Icon icon="mdi:text-box-outline" width="24" height="24" />
                </template>
                </Input>

                <div class="mt-6 flex">
                    <Button @click="submitForm" :disabled="form.processing" :color="'primary'" :rounded="'md'"
                        class="w-full flex justify-center items-center hover:scale-105 transition-transform duration-200">
                        <Icon icon="mdi:send" width="20" height="20" class="mr-2" />
                        Submit Suggestion
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
