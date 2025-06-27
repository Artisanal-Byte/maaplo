<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';
import FeaturesInput from '@/components/FeaturesInput.vue';
import Loader from '@/components/Loader.vue';
import {  ref } from 'vue';
const toast = new ToastMagic();
const form = useForm({
    plan_title: '',
    plan_description: '',
    plan_price: 0,
    plan_currency: 'INR',
    features: [''],
    visibility: true,
    user_limit: 5,
});
const loading = ref(false);
const submitForm = () => {
    loading.value = true;
    const filtered = form.features.filter(f => f.trim() !== '');
    form.features = filtered.length > 0 ? filtered : ['']; // ensure at least one input remains
    form.post(route('subscription-plans.store'), {
        onSuccess: () => {
            toast.success("Subscription plan created successfully!");
            router.visit(route('subscription-plans.index'));
            loading.value = false;
        },
        onError: () => {
            toast.error("Failed to create subscription plan. Please check the fields.");
            loading.value = false;
        }
    });
};

</script>

<template>

    <Head title="Create Subscription Plan" />
    <AppLayout>
        <div class="px-4 py-8 max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <Icon icon="carbon:plan" width="28" height="28" />
                    Create Subscription Plan
                </h1>
                <Link :href="route('subscription-plans.index')"
                    class="text-gray-600 hover:text-black flex items-center">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="ml-1">Back</span>
                </Link>
            </div>
  <!-- Use the Loader Component -->
            <Loader v-if="loading" :message="'Creating Subscription Plan...'" />
            <!-- Form Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-primary space-y-5">

                <!-- 2x2 Grid for first 4 inputs -->
                <div class="grid grid-cols-2 gap-6">
                    <Input v-model="form.plan_title" label="Plan Title" placeholder="Enter title"
                        :error="form.errors.plan_title" required="true">
                    <template #icon>
                        <Icon icon="icon-park-solid:plan" width="24" height="24" />
                    </template>
                    </Input>

                    <Input v-model="form.plan_description" label="Plan Description" placeholder="Short description"
                        :error="form.errors.plan_description">
                    <template #icon>
                        <Icon icon="ic:outline-business" width="24" height="24" />
                    </template>
                    </Input>

                    <Input v-model.number="form.plan_price" label="Plan Price" type="number" placeholder="e.g. 99.99"
                        :error="form.errors.plan_price" required="true">
                    <template #icon>
                        <Icon icon="mdi:currency-brl" width="24" height="24" />
                    </template>
                    </Input>

                    <Input v-model="form.plan_currency" label="Currency" placeholder="e.g. INR, USD"
                        :error="form.errors.plan_currency" required="true">
                    <template #icon>
                        <Icon icon="grommet-icons:currency" width="24" height="24" />
                    </template>
                    </Input>
                    <!-- The rest below the grid -->

                    <Input v-model.number="form.user_limit" label="User Limit" type="number" placeholder="e.g. 5"
                        :error="form.errors.user_limit" required="true">
                    <template #icon>
                        <Icon icon="mdi:car-speed-limiter" width="24" height="24" />
                    </template>
                    </Input>
                    <div>
                        <label class="font-medium text-black mb-2 block">
                            Visibility
                            <span class="inline-block align-middle ml-1">
                                <Icon icon="material-symbols:visibility-outline" width="24" height="24" />
                            </span>
                        </label>
                        <div class="flex gap-4">
                            <label>
                                <input type="radio" :value="true" v-model="form.visibility" class="hidden" />
                                <div :class="[
                                    'px-4 py-1 rounded border text-sm cursor-pointer',
                                    form.visibility === true
                                        ? 'bg-primary text-white'
                                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                                ]">
                                    Visible
                                </div>
                            </label>

                            <label>
                                <input type="radio" :value="false" v-model="form.visibility" class="hidden" />
                                <div :class="[
                                    'px-4 py-1 rounded border text-sm cursor-pointer',
                                    form.visibility === false
                                        ? 'bg-primary text-white'
                                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                                ]">
                                    Hidden
                                </div>
                            </label>
                        </div>

                        <div v-if="form.errors.visibility" class="text-red-600 text-sm">
                            {{ form.errors.visibility }}
                        </div>
                    </div>
                </div>
                <div>
                    <FeaturesInput v-model:features="form.features" :errors="form.errors" />
                </div>

                <Button @click="submitForm" class="w-full" color="primary" padding="md" rounded="full" textSize="sm">
                    Create Plan
                </Button>
            </div>

        </div>
    </AppLayout>
</template>
