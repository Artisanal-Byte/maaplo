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
    organization_logo: '',
    status: true,
});

const createUser = () => {
    form.post(route('user.store'), {
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
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-[24px] font-bold text-gray-800 font-[Convergence]">
                    Create User
                </h1>
                <div class="text-gray-600">
                    <Link :href="route('user.index')" class="flex items-center gap-1 hover:text-black">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-[16px] font-medium">Back</span>
                    </Link>
                </div>
            </div>

            <div class="space-y-5 bg-white p-6 rounded-lg shadow">
                <div>
                    <Input type="text" v-model="form.name" label="User Name" :required="true" :error="form.errors.name"
                        placeholder="Enter User Name" />
                </div>
                <div>
                    <Input type="email" v-model="form.email" label="Email" :error="form.errors.email"
                        placeholder="example@mail.com" :required="true" />
                </div>
                <div>
                    <Input type="text" v-model="form.phone" label="Contact Number" :required="true"
                        :error="form.errors.phone" placeholder="Enter Phone Number" />
                </div>
                <div>
                    <Input type="textarea" v-model="form.address" label="Address" :error="form.errors.address" />
                </div>
                <div>
                    <Input type="textarea" v-model="form.organization_name" label="Organization Name"
                        :error="form.errors.organization_name" />
                </div>
                <div>
                    <Input type="textarea" v-model="form.subscription_plan" label="Subscription Plan"
                        :error="form.errors.subscription_plan" />
                </div>
                <div>
                    <Input type="date" v-model="form.validity" label="Validity" :error="form.errors.validity" />
                </div>
                <div>
                    <Input type="password" v-model="form.password" label="Password" :error="form.errors.password"
                        placeholder="Enter Password" />
                </div>
                <div>
                    <Input type="text" v-model="form.organization_logo" label="Organization Logo URL"
                        :error="form.errors.organization_logo" placeholder="https://logo.url/image.png" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Status</label>
                    <select v-model="form.status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option :value="true">Active</option>
                        <option :value="false">Inactive</option>
                    </select>
                </div>

                <!-- Submit Button (Full Width Below) -->
                <Button @click="createUser" :color="'primary'" :padding="'md'" :rounded="'full'" :textSize="'sm'"
                    class="lg:mt-5 mt-3">
                    Save & Continue
                </Button>
            </div>

        </div>
    </AppLayout>
</template>
