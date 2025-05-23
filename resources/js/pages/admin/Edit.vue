<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';

const toast = new ToastMagic();

const props = defineProps<{
    errors: Record<string, string>,
    user: {
        id: number,
        name: string,
        email: string,
        phone: string,
        address: string,
        organization_name: string,
        subscription_plan: string,
        validity: Date,
        password?: string,
        organization_logo?: string,
        status?: boolean,
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
    password: props.user.password ?? '',
    organization_logo: props.user.organization_logo ?? '',
    status: props.user.status ?? true,
});

const updateUser = () => {
    form.transform((data) => ({
        ...data,
        _method: 'put',
    })).post(route('user.update', props.user.id), {
        onSuccess: () => {
            toast.success("User updated successfully!");
        },
        onError: (errors) => {
            toast.error("Update failed. Please check the fields.");
            console.error(errors);
        }
    });
};
</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 font-[Convergence] flex items-center gap-2">
                    <Icon icon="mdi:account-edit" width="28" height="28" />
                    Edit User
                </h1>
                <Link :href="route('user.index')" class="flex items-center gap-1 hover:text-black text-gray-600">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white p-8 rounded-lg shadow-md space-y-10">
                <!-- Section: User Info -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">User Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Input type="text" v-model="form.name" label="User Name" :error="props.errors.name" required
                            placeholder="Enter User Name" />
                        <Input type="email" v-model="form.email" label="Email" :error="props.errors.email" required
                            placeholder="example@mail.com" />
                        <Input type="text" v-model="form.phone" label="Contact Number" :error="props.errors.phone"
                            required placeholder="Enter Phone Number" />
                        <Input type="textarea" v-model="form.address" label="Address" :error="props.errors.address"
                            required />
                    </div>
                </div>

                <!-- Section: Organization Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Input type="textarea" v-model="form.organization_name" label="Organization Name"
                        :error="props.errors.organization_name" required />
                    <Input type="textarea" v-model="form.subscription_plan" label="Subscription Plan"
                        :error="props.errors.subscription_plan" required />
                    <Input type="date" v-model="form.validity" label="Validity" :error="props.errors.validity"
                        required />
                    <Input type="password" v-model="form.password" label="Password" :error="props.errors.password"
                        placeholder="Enter Password (optional)" />
                    <Input type="text" v-model="form.organization_logo" label="Organization Logo URL"
                        :error="props.errors.organization_logo" placeholder="https://logo.url/image.png" />

                    <!-- Status Select -->
                    <div class="flex items-center gap-3 mt-2 ml-1">
                        <Icon icon="mdi:account-check" class="text-gray-700" width="20" height="20" />
                        <span class="text-[16px] font-bold text-gray-800">Status</span>

                        <div class="flex border border-gray-300 rounded overflow-hidden text-sm">
                            <button :class="[
                                'px-4 py-1 focus:outline-none',
                                form.status ? 'bg-primary text-white' : 'bg-white text-gray-800'
                            ]" @click="form.status = true">
                                Active
                            </button>
                            <button :class="[
                                'px-4 py-1 focus:outline-none',
                                !form.status ? 'bg-red-500 text-white' : 'bg-white text-gray-800'
                            ]" @click="form.status = false">
                                Inactive
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex">
                    <Button @click="updateUser" :disabled="form.processing" :color="'primary'" :padding="'md'"
                        :rounded="'full'" :textSize="'sm'"
                        class="w-full flex justify-center items-center hover:scale-105 transition-transform duration-200">
                        <Icon icon="mdi:check-bold" width="20" height="30" class="mr-2" />
                        Update User
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
