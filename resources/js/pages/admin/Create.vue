<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { Icon } from '@iconify/vue';
import { Link, useForm } from '@inertiajs/vue3';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';

const toast = new ToastMagic();

const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    organization_name: '',
    subscription_plan: '',
    validity: '',
    password: '',
    organization_logo: null,
    status: true,
});

const createUser = () => {
    form.post(route('user.store'), {
        forceFormData: true,
        onSuccess: () => {
            toast.success("User created successfully!");
        },
        onError: (errors) => {
            toast.error("User creation failed. Please check the fields.");
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
                    <Icon icon="mdi:account-plus" width="28" height="28" />
                    Create User
                </h1>
                <Link :href="route('user.index')" class="flex items-center gap-2 text-gray-600 hover:text-black">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>

            <!-- Form -->
            <div class="bg-white p-8 rounded-lg shadow-md space-y-8">
                <!-- Section: User Info -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">User Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Input type="text" v-model="form.name" label="User Name" :required="true"
                            :error="form.errors.name" placeholder="Enter User Name" />
                        <Input type="email" v-model="form.email" label="Email" :error="form.errors.email"
                            placeholder="example@mail.com" :required="true" />
                        <Input type="text" v-model="form.phone" label="Contact Number" :required="true"
                            :error="form.errors.phone" placeholder="Enter Phone Number" />
                        <Input type="password" v-model="form.password" label="Password" :error="form.errors.password"
                            placeholder="Enter Password" :required="true"/>
                    </div>
                </div>

                <!-- Section: Organization Info -->
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Input type="textarea" v-model="form.address" label="Address" :error="form.errors.address" :required="true" placeholder="Enter Address"/>
                        <Input type="textarea" v-model="form.organization_name" label="Organization Name"
                            :error="form.errors.organization_name" :required="true" placeholder="Enter Organization Name"/>
                        <Input type="textarea" v-model="form.subscription_plan" label="Subscription Plan"
                            :error="form.errors.subscription_plan" :required="true"/>
                        <Input type="date" v-model="form.validity" label="Validity" :error="form.errors.validity" :required="true"/>
                    </div>
                </div>

                <!-- Section: Logo Upload & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <div>
                        <Input type="file" @change="(e) => form.organization_logo = e.target.files[0]"
                            label="Organization Logo" :error="form.errors.organization_logo" />
                        <p v-if="form.organization_logo" class="text-sm text-gray-500 mt-1">
                            Selected: {{ form.organization_logo.name }}
                        </p>
                    </div>
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
                <div class="flex w-full">
                    <Button @click="createUser" :color="'primary'" :padding="'md'" :rounded="'full'" :textSize="'sm'"
                        class="w-full flex justify-center items-center hover:scale-105 transition-transform duration-200">
                        <Icon icon="mdi:check-bold" width="20" height="30" class="mr-2" />
                        Save & Continue
                    </Button>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
