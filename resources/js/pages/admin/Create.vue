<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { Icon } from '@iconify/vue';
import { Link, useForm } from '@inertiajs/vue3';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';

const toast = new ToastMagic();
const logoPreview = ref(null);
const showPassword = ref(false);
const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    organization_name: '',
    subscription_plan: 'free',
    validity: '',
    password: '',
    organization_logo: null,
    status: true,
});

const logoUrl = computed(() => {
    return logoPreview.value ? logoPreview.value : null;
});

const handleLogoChange = (event) => {
    const file = event.target?.files?.[0];
    if (file) {
        form.organization_logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

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
                <h1 class="text-3xl text-primary font-bold text-gray-800 font-[Convergence] flex items-center gap-2">
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
                            :error="form.errors.name" placeholder="Enter User Name">
                        <template #icon>
                            <Icon icon="bitcoin-icons:contacts-filled" width="24" height="24" />
                        </template>
                        </Input>
                        <Input type="email" v-model="form.email" label="Email" :error="form.errors.email"
                            placeholder="example@mail.com" :required="true">
                        <template #icon>
                            <Icon icon="ic:round-email" width="24" height="24" />
                        </template>
                        </Input>
                        <Input type="rel" v-model="form.phone" label="Contact Number" :required="true"
                            :error="form.errors.phone" placeholder="Enter Phone Number">
                        <template #icon>
                            <Icon icon="ic:round-phone" width="20" height="20" />
                        </template>
                        </Input>
                        <div class="relative">
                            <Input :type="showPassword ? 'text' : 'password'" v-model="form.password" label="Password"
                                :error="form.errors.password" placeholder="Enter Password" :required="true">
                            <template #icon>
                                <Icon icon="carbon:password" width="20" height="20" />
                            </template>
                            </Input>
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3 top-10 text-gray-600 hover:text-black" tabindex="-1">
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Section: Organization Info -->
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Input type="textarea" v-model="form.address" label="Address" :error="form.errors.address"
                            :required="true" placeholder="Enter Address" />
                        <Input type="textarea" v-model="form.organization_name" label="Organization Name"
                            :error="form.errors.organization_name" :required="true"
                            placeholder="Enter Organization Name" />
                        <!-- Subscription Plan Dropdown -->
                        <div>
                            <label for="subscription_plan" class="block font-medium text-black mb-1">
                                Subscription Plan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="subscription_plan" v-model="form.subscription_plan" required
                                    class="appearance-none block w-full bg-white border border-gray-300 rounded-md py-2 px-3 pr-10 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option disabled value="">Select a plan</option>
                                    <option value="free">Free</option>
                                </select>
                                <!-- Custom dropdown arrow -->
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7l3-3 3 3m0 6l-3 3-3-3" />
                                    </svg>
                                </div>
                            </div>
                            <div v-if="form.errors.subscription_plan" class="text-red-600 text-sm mt-1">
                                {{ form.errors.subscription_plan }}
                            </div>
                        </div>

                        <Input type="date" v-model="form.validity" label="Validity" :error="form.errors.validity"
                            :required="true">
                        <template #icon>
                            <Icon icon="material-symbols:date-range-outline-rounded" width="20" height="20" />
                        </template>
                        </Input>
                    </div>
                </div>

                <!-- Section: Logo Upload & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <!-- Logo Upload with Preview -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Add Organization Logo</h2>
                        <!-- Logo Upload Input -->
                        <label class="block mb-3">
                            <input type="file" @change="handleLogoChange"
                                class="block w-full text-sm text-gray-500 mt-1 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#DEEFF4] file:text-primary  hover:file:bg-indigo-100 cursor-pointer" />
                        </label>

                        <!-- Logo Preview with Default Fallback -->
                        <div
                            class="w-full h-64 bg-gray-50 flex items-center justify-center rounded-md overflow-hidden border mb-3">
                            <img :src="logoUrl || '/images/organization.png'" alt="Organization Logo"
                                class="object-scale-down h-64 w-[500px]" />
                        </div>

                        <!-- Validation Error -->
                        <div v-if="form.errors.organization_logo" class="text-red-600 text-sm mt-1">
                            {{ form.errors.organization_logo }}
                        </div>
                    </div>

                    <!-- Status Switch -->
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
