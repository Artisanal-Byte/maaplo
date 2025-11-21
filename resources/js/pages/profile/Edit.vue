<script setup lang="ts">
import Button from '@/components/Button.vue';
import ImageModal from '@/components/ImageModal.vue';
import Input from '@/components/InputWithLabel.vue';
import Loader from '@/components/Loader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
const toast = new ToastMagic();
const props = defineProps<{
    errors: Record<string, string>;
    user: {
        id: number;
        name: string;
        email: string;
        phone: string;
        organization_name: string;
        subscription_plan: string;
        validity: string;
        password?: string;
        organization_logo: string | null;
        avatar: null;
    };
}>();
const loading = ref(false);
const showPassword = ref(false);
const avatarPreview = ref<string | null>(null);
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone,
    password: props.user.password ?? '',
    organization_name: props.user.organization_name,
    organization_logo: null,
    avatar: null,
});

// Image preview for organization logo
const logoPreview = ref<string | null>(null);

const organizationLogoUrl = computed(() => {
    if (logoPreview.value) return logoPreview.value;
    if (props.user.organization_logo) return `/storage/${props.user.organization_logo.replace(/^storage\//, '')}`;
    return null;
});

function handleLogoChange(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        form.organization_logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
}

const avatarUrl = computed(() => {
    if (avatarPreview.value) return avatarPreview.value;
    if (props.user.avatar) return `/storage/${props.user.avatar.replace(/^storage\//, '')}`;
    return null;
});

function handleAvatarChange(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        form.avatar = file;
        avatarPreview.value = URL.createObjectURL(file);
    }
}

function submit() {
    loading.value = true;
    form.transform((data) => ({
        ...data,
        _method: 'put',
    })).post(route('profile.update'), {
        preserveState: true,
        onSuccess: () => {
            toast.success('Profile updated successfully!');
            router.visit(route('profile.show'));
            loading.value = false;
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
            loading.value = false;
        },
    });
}

const showImageModal = ref(false);
const currentImageUrl = ref('');

function openImageModal(url: string) {
    currentImageUrl.value = url;
    showImageModal.value = true;
}

function closeImageModal() {
    showImageModal.value = false;
}
</script>

<template>
    <Head title="Edit-Profile" />
    <AppLayout>
        <div class="mx-auto max-w-6xl px-4 py-8">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex">
                    <Icon icon="mingcute:user-edit-fill" width="42" height="42" class="text-xl font-bold text-primary" />
                    <h1 class="ml-3 mt-1 text-3xl font-bold text-primary">Edit-Profile</h1>
                </div>
                <Link :href="route('profile.show')" class="flex items-center gap-2 text-gray-500 hover:text-gray-800">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-base font-medium">Back</span>
                </Link>
            </div>
            <!-- Use the Loader Component -->
            <Loader v-if="loading" :message="'Updating Profile...'" />
            <!-- Card Layout -->
            <div class="mt-10 flex flex-col gap-5 rounded-lg border-t-4 border-primary bg-white p-5 shadow-md lg:p-7">
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-2">
                    <Input label="Name" v-model="form.name" :error="errors.name" placeholder="Enter Name" required="true">
                        <template #icon>
                            <Icon icon="bitcoin-icons:contacts-filled" width="24" height="24" />
                        </template>
                    </Input>

                    <Input label="Email" type="email" v-model="form.email" :error="errors.email" placeholder="Enter Email" required="true">
                        <template #icon>
                            <Icon icon="ic:round-email" width="18" height="18" />
                        </template>
                    </Input>

                    <Input label="Phone" type="text" v-model="form.phone" :error="errors.phone" placeholder="Enter Mobile Number" required="true">
                        <template #icon>
                            <Icon icon="ic:round-phone" width="20" height="20" />
                        </template>
                    </Input>

                    <!-- <Input label="Organization Name" type="text" v-model="form.organization_name"
                        :error="errors.organization_name" placeholder="Enter Organization Name">
                    <template #icon>
                        <Icon icon="fluent:organization-16-filled" width="20" height="20" />
                    </template>
                    </Input> -->

                    <!-- Organization Logo -->
                    <!-- <div>
                        <label for="organization_logo" class="block mb-1 font-semibold">Organization Logo</label>
                        <input id="organization_logo" type="file" accept="image/*" @change="handleLogoChange"
                            class="border border-primary rounded px-3 py-2 w-full" required />
                        <div v-if="organizationLogoUrl" class="mt-2 cursor-pointer"
                            @click="openImageModal(organizationLogoUrl)">
                            <img :src="organizationLogoUrl" alt="Organization Logo" class="w-32 h-auto rounded" />
                        </div>
                        <p v-if="errors.organization_logo" class="text-red-600 text-sm mt-1">{{ errors.organization_logo
                            }}</p>
                    </div> -->
                    <div class="relative">
                        <Input
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            label="Password"
                            :error="props.errors.password"
                            placeholder="Leave blank to keep current password."
                            required="true"
                        >
                            <template #icon>
                                <Icon icon="carbon:password" width="20" height="20" />
                            </template>
                        </Input>
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-3 top-9 text-gray-600 hover:text-black"
                            tabindex="-1"
                        >
                            <Icon :icon="showPassword ? 'mdi:eye-off' : 'mdi:eye'" width="20" height="20" />
                        </button>
                    </div>
                    <!-- Avatar div -->
                    <div>
                        <label for="avatar" class="mb-1 block font-semibold">Avatar</label>
                        <input
                            id="avatar"
                            type="file"
                            accept="image/*"
                            @change="handleAvatarChange"
                            class="w-full rounded border border-primary px-3 py-2"
                        />
                        <div v-if="avatarUrl" class="mt-2 cursor-pointer" @click="openImageModal(avatarUrl)">
                            <img :src="avatarUrl" alt="Avatar" class="h-24 w-24 rounded-full object-cover" />
                        </div>
                        <p v-if="errors.avatar" class="mt-1 text-sm text-red-600">{{ errors.avatar }}</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-4">
                    <Button
                        @click="submit"
                        :disabled="form.processing"
                        :color="'primary'"
                        :padding="'md'"
                        :rounded="'full'"
                        :textSize="'sm'"
                        class="h-10 w-full font-bold"
                    >
                        Update Profile
                    </Button>
                </div>

                <!-- Modal Viewer modal -->
                <ImageModal :show="showImageModal" :imageUrl="currentImageUrl" @close="closeImageModal" />
            </div>
        </div>
    </AppLayout>
</template>
