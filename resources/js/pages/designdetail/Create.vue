<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';

const toast = new ToastMagic(); // optional: if you're using a toast notification system

const imagePreview = ref(null);

const form = useForm({
    body_section: '',
    gender: 'm',
    body_part: '',
    value: '',
    image: '',
});


const createDesignDetail = () => {
    form.post(route('design-details.store'), {
        onSuccess: () => toast.success('Design Detail created successfully!'),
        onError: () => toast.error('Error creating Design Detail.'),
    });
};
</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-primary flex items-center gap-2">
                    <Icon icon="mdi:vector-square-edit" width="28" height="28" />
                    Create Design Detail
                </h1>
                <Link :href="route('design-details.index')"
                    class="flex items-center gap-2 text-gray-600 hover:text-black">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>

            <!-- Form -->
            <div class="bg-white p-8 rounded-lg shadow-md space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Body Section (Dropdown) -->
                    <div>
                        <label class="block font-medium text-black mb-1">Body Section</label>
                        <select v-model="form.body_section"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-primary focus:border-primary" required="true">
                            <option disabled value="">Select body section</option>
                            <option value="Upper">Upper</option>
                            <option value="Lower">Lower</option>
                        </select>
                        <p v-if="form.errors.body_section" class="text-sm text-red-500 mt-1">{{ form.errors.body_section
                            }}</p>
                    </div>

                    <!-- Body Part -->
                    <Input type="text" label="Body Part" v-model="form.body_part" :error="form.errors.body_part"
                        placeholder="Enter body part"  required="true">
                    <template #icon>
                        <Icon icon="mdi:human-male-height" width="20" height="20" />
                    </template>
                    </Input>

                    <!-- Value -->
                    <Input type="text" label="Value" v-model="form.value" :error="form.errors.value"
                        placeholder="Enter value"  required="true">
                    <template #icon>
                        <Icon icon="mdi:tag-text" width="20" height="20" />
                    </template>
                    </Input>

                    <!-- Gender Select -->
                    <div>
                        <label class="block font-medium text-black mb-1">Gender <span
                                class="text-red-500">*</span></label>
                        <select v-model="form.gender"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-primary focus:border-primary" required="true">
                            <option value="m">Male</option>
                            <option value="f">Female</option>
                            <option value="o">Other</option>
                        </select>
                        <p v-if="form.errors.gender" class="text-sm text-red-500 mt-1">{{ form.errors.gender }}</p>
                    </div>
                </div>

                <!-- SVG Path Input -->
                <Input type="textarea" label="SVG Path" v-model="form.image" :error="form.errors.image"
                    placeholder="Enter SVG path (e.g., M10 10 H 90 V 90 H 10 Z)" required="true">
                <template #icon>
                    <Icon icon="mdi:vector-line" width="20" height="20" />
                </template>
                </Input>

                <!-- Optional live SVG preview -->
                <div class="mt-4 border rounded-lg p-4">
                    <label class="block font-medium text-black mb-2">SVG Preview</label>
                    <svg width="50" height="50" viewBox="0 0 100 100" >
                        <path :d="form.image" stroke="black" fill="none" />
                    </svg>
                </div>

                <!-- Submit Button -->
                <div>
                    <Button @click="createDesignDetail" :color="'primary'" :rounded="'full'" :textSize="'sm'"
                        class="w-full justify-center hover:scale-105 transition-transform">
                        <Icon icon="mdi:check-bold" class="mr-2" width="20" />
                        Save Design Detail
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
