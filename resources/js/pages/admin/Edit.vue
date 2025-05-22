<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { Icon } from '@iconify/vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';
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

const updateUser = () => {
    form.transform((data) => {

        return {
            ...data,
            _method: 'put',
        };
    }).post(route('user.update', props.user.id), {
        onSuccess: () => {
            toast.success("User updated successfully!");
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
            toast.error("Update failed. Please check the fields and try again.");
        }
    });
};
</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-[24px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                    Edit User
                </h1>
                <div class="text-gray-600">
                    <Link :href="route('user.index')"  class="flex items-center gap-1 hover:text-black">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-[16px] font-medium">Back</span>
                    </Link>
                </div>
            </div>
            <!-- Edit User Form -->
            <div class="flex flex-col lg:mt-5 gap-4 rounded-lg lg:border lg:border-[#167893] p-0 lg:p-4">

                <div class="space-y-5 bg-white p-6 rounded-lg shadow">
                    <div>
                        <Input type="text" v-model="form.name" label="User Name" color="grayBorder" :required="true"
                            :error="errors.name" placeholder="Enter User Name" />
                    </div>

                    <div>
                        <Input type="email" v-model="form.email" label="Email" color="grayBorder" :error="errors.email"
                            placeholder="example@mail.com" />
                    </div>

                    <div>
                        <Input type="text" v-model="form.phone" label="Contact Number" :required="true"
                            :error="errors.phone" color="grayBorder" placeholder="Enter Phone Number" />
                    </div>

                    <div>
                        <Input type="textarea" v-model="form.address" color="grayBorder" :required="true"
                            label="Address" :error="errors.address"></Input>
                    </div>

                    <div>
                        <Input type="textarea" v-model="form.organization_name" color="grayBorder" :required="true"
                            label="Organization Name" :error="errors.organization_name"></Input>
                    </div>

                    <div>
                        <Input type="textarea" v-model="form.subscription_plan" color="grayBorder" :required="true"
                            label="Subscription Plan" :error="errors.subscription_plan"></Input>
                    </div>

                    <div>
                        <Input type="textarea" v-model="form.validity" color="grayBorder" :required="true"
                            label="Validity" :error="errors.validity"></Input>
                    </div>

                    <!-- Update Button -->
                <Button @click="updateUser" :disabled="form.processing" :color="'primary'" :padding="'md'"
                    :rounded="'full'" :textSize="'sm'">
                    Update User
                </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
