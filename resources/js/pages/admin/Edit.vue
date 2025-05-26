<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';
import { ref, computed } from 'vue';
const toast = new ToastMagic();
const showPassword = ref(false);
const logoPreview = ref<string | null>(null);
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

const logoUrl = computed(() => {
    return logoPreview.value
        ? logoPreview.value
        : props.user.organization_logo
            ? `/storage/${props.user.organization_logo.replace(/^storage\//, '')}`
            : null;
});

const handleLogoChange = (event: Event) => {
    const file = (event.target as HTMLInputElement)?.files?.[0];
    if (file) {
        form.organization_logo = file;
        logoPreview.value = URL.createObjectURL(file);
    } else {
        toast.error("Please select a valid image file.");
    }
};

</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl text-primary font-bold text-gray-800 font-[Convergence] flex items-center gap-2">
                    <Icon icon="mdi:account-edit" width="28" height="28" />
                    Edit User
                </h1>
                <Link :href="route('user.index')" class="flex items-center gap-1 hover:text-black text-gray-600">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white p-4 lg:p-8 rounded-lg shadow-md space-y-5">
                <!-- Section: User Info -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">User Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Input type="text" v-model="form.name" label="User Name" :error="props.errors.name"
                            required="true" placeholder="Enter User Name">
                        <template #icon>
                            <Icon icon="bitcoin-icons:contacts-filled" width="24" height="24" />
                        </template>
                        </Input>

                        <Input type="email" v-model="form.email" label="Email" :error="props.errors.email"
                            required="true" placeholder="example@mail.com">
                        <template #icon>
                            <Icon icon="ic:round-email" width="20" height="20" />
                        </template>
                        </Input>
                        <Input type="text" v-model="form.phone" label="Contact Number" :error="props.errors.phone"
                            required="true" placeholder="Enter Phone Number">
                        <template #icon>
                            <Icon icon="ic:round-phone" width="20" height="20" />
                        </template>
                        </Input>
                        <!-- Password Input with Visibility Toggle -->
                        <div class="relative">
                            <Input :type="showPassword ? 'text' : 'password'" v-model="form.password" label="Password"
                                :error="props.errors.password" placeholder="Leave blank to keep current password."
                                required="true">
                            <template #icon>
                                <Icon icon="carbon:password" width="20" height="20" />
                            </template>
                            </Input>
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3 top-9 text-gray-600 hover:text-black" tabindex="-1">
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Section: Organization Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Input type="textarea" v-model="form.address" label="Address" :error="props.errors.address"
                        required="true" />
                    <Input type="textarea" v-model="form.organization_name" label="Organization Name"
                        :error="props.errors.organization_name" required="true" />
                    <!-- Enhanced Subscription Plan Dropdown -->
                    <div>
                        <label for="subscription_plan" class="block text-sm font-medium text-gray-700 mb-1">
                            Subscription Plan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="subscription_plan" v-model="form.subscription_plan" required
                                class="appearance-none block w-full bg-white border border-gray-300 rounded-md py-2 px-3 pr-10 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option disabled value="">Select a plan</option>
                                <option value="free">Free</option>
                            </select>
                            <!-- Dropdown arrow icon -->
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7l3-3 3 3m0 6l-3 3-3-3" />
                                </svg>
                            </div>
                        </div>
                        <div v-if="props.errors.subscription_plan" class="text-red-600 text-sm mt-1">
                            {{ props.errors.subscription_plan }}
                        </div>
                    </div>
                    <Input type="date" v-model="form.validity" label="Validity" :error="props.errors.validity"
                        required="true">
                    <template #icon>
                        <Icon icon="material-symbols:date-range-outline-rounded" width="20" height="20" />
                    </template>
                    </Input>
                </div>

                <!-- Organization Logo Upload -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <!-- Logo Upload with Preview -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Organization Logo</h2>

                        <!-- Logo Preview with Fallback -->
                        <div
                            class="w-full h-64 bg-gray-50 flex items-center justify-center rounded-md overflow-hidden border mb-3">
                            <img :src="logoUrl || '/images/organization.png'" alt="Organization Logo"
                                class="object-scale-down h-64 w-[500px]" />
                        </div>

                        <!-- Upload Input -->
                        <label class="block">
                            <span class="text-sm text-gray-600">Upload new logo</span>
                            <input type="file" @change="handleLogoChange"
                                class="block w-full text-sm text-gray-500 mt-1 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#DEEFF4] file:text-primary hover:file:bg-indigo-100 cursor-pointer" />
                        </label>

                        <!-- Validation Error -->
                        <div v-if="props.errors.organization_logo" class="text-red-600 text-sm mt-1">
                            {{ props.errors.organization_logo }}
                        </div>
                    </div>

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
