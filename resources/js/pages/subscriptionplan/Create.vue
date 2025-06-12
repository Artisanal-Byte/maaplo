<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';

const form = useForm({
    plan_title: '',
    plan_description: '',
    plan_price: 0,
    plan_currency: 'INR',
    featuresJson: '[]',
    visibility: true,
    user_limit: 5,
});

const submitForm = () => {
    form.post(route('subscription-plans.store'), {
        onSuccess: () => {
            router.visit(route('subscription-plans.index'));
        },
    });
};

const isValidJson = (str) => {
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
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
                <Link :href="route('subscription-plans.index')" class="text-gray-600 hover:text-black flex items-center">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="ml-1">Back</span>
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-primary space-y-5">
                <Input
                    v-model="form.plan_title"
                    label="Plan Title"
                    placeholder="Enter title"
                    :error="form.errors.plan_title"
                    required
                />

                <Input
                    v-model="form.plan_description"
                    label="Plan Description"
                    placeholder="Short description"
                    :error="form.errors.plan_description"
                />

                <Input
                    v-model.number="form.plan_price"
                    label="Plan Price"
                    type="number"
                    placeholder="e.g. 99.99"
                    :error="form.errors.plan_price"
                />

                <Input
                    v-model="form.plan_currency"
                    label="Currency"
                    placeholder="e.g. INR, USD"
                    :error="form.errors.plan_currency"
                />

                <Input
                    v-model.number="form.user_limit"
                    label="User Limit"
                    type="number"
                    placeholder="e.g. 5"
                    :error="form.errors.user_limit"
                />

                <div>
                    <Input
                        v-model="form.featuresJson"
                        label="Features (JSON Array)"
                        placeholder='e.g. ["Feature 1", "Feature 2"]'
                        :error="form.errors.features"
                    />
                    <p class="text-sm text-gray-500 mt-1">
                        Enter features as a JSON array. Example: <code>["Feature A", "Feature B"]</code>
                    </p>
                    <p v-if="!isValidJson(form.featuresJson)" class="text-red-500 text-sm mt-1">
                        ⚠️ Invalid JSON format.
                    </p>
                </div>

                <div>
                    <label class="font-medium text-gray-700 mb-2 block">Visibility</label>
                    <div class="flex gap-4">
                        <label>
                            <input
                                type="radio"
                                value="true"
                                v-model="form.visibility"
                                class="hidden"
                            />
                            <div
                                :class="[
                                    'px-4 py-1 rounded border text-sm cursor-pointer',
                                    form.visibility === true
                                        ? 'bg-primary text-white'
                                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                                ]"
                            >
                                Visible
                            </div>
                        </label>
                        <label>
                            <input
                                type="radio"
                                value="false"
                                v-model="form.visibility"
                                class="hidden"
                            />
                            <div
                                :class="[
                                    'px-4 py-1 rounded border text-sm cursor-pointer',
                                    form.visibility === false
                                        ? 'bg-primary text-white'
                                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                                ]"
                            >
                                Hidden
                            </div>
                        </label>
                    </div>
                    <div v-if="form.errors.visibility" class="text-red-600 text-sm">{{ form.errors.visibility }}</div>
                </div>

                <Button @click="submitForm" color="primary" padding="md" rounded="full" textSize="sm">
                    Save Plan
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
