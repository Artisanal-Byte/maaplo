<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps<{
    user: {
        id: number,
        name: string,
        email: string,
        phone: string,
        address: string,
        organization_name: string,
        subscription_plan: string,
        validity: string
    }
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone,
    address: props.user.address,
    organization_name: props.user.organization_name,
    subscription_plan: props.user.subscription_plan,
    validity: props.user.validity,
});

function submit() {
    router.put(route('user.update', props.user.id), form);
}
</script>

<template>
    <AppLayout>
        <div class="max-w-3xl mx-auto py-10 px-4">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Admin</h1>

            <form @submit.prevent="submit" class="space-y-5 bg-white p-6 rounded-lg shadow">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Name</label>
                    <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Email</label>
                    <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Phone</label>
                    <input v-model="form.phone" type="text" class="w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Address</label>
                    <input v-model="form.address" type="text" class="w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Organization Name</label>
                    <input v-model="form.organization_name" type="text" class="w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Subscription Plan</label>
                    <input v-model="form.subscription_plan" type="text" class="w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Validity</label>
                    <input v-model="form.validity" type="text" class="w-full border rounded px-3 py-2" />
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
