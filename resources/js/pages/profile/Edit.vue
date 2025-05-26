<script setup lang="ts">
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';
import ImageModal from '@/components/ImageModal.vue';

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
        organization_logo: string | null;
    };
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone,
    organization_name: props.user.organization_name,
    organization_logo: null,
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

function submit() {
    form.transform((data) => ({
        ...data,
        _method: 'put',
    })).post(route('profile.update'), {
        preserveState: true,
        onSuccess: () => {
            router.visit(route('profile.show'));
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
        }
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

    <Head title="Edit Profile" />
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-[24px] font-bold text-primary">Edit Profile</h1>
                <Link href="/" class="flex items-center gap-1 text-gray-600 hover:text-black">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-[16px] font-medium">Back</span>
                </Link>
            </div>

            <!-- <div > -->
            <Input label="Name" v-model="form.name" :error="errors.name" required="true">
            <template #icon>
                <Icon icon="bitcoin-icons:contacts-filled" width="24" height="24" />
            </template>
            </Input>

            <Input label="Email" type="email" v-model="form.email" :error="errors.email" required="true">
            <template #icon>
                <Icon icon="ic:round-email" width="18" height="18" />
            </template>
            </Input>

            <Input label="Phone" type="text" v-model="form.phone" :error="errors.phone" required="true">
            <template #icon>
                <Icon icon="ic:round-phone" width="20" height="20" />
            </template>
            </Input>

            <Input label="Organization Name" type="text" v-model="form.organization_name"
                :error="errors.organization_name" required="true">
            <template #icon>
                <Icon icon="fluent:organization-16-filled" width="20" height="20" />
            </template>
            </Input>

            <div>
                <label for="organization_logo" class="block mb-1 font-semibold">Organization Logo</label>
                <input id="organization_logo" type="file" accept="image/*" @change="handleLogoChange"
                    class="border border-gray-300 rounded px-3 py-2 w-full" required="true"/>
                <div v-if="organizationLogoUrl" class="mt-2 cursor-pointer"
                    @click="openImageModal(organizationLogoUrl)">
                    <img :src="organizationLogoUrl" alt="Organization Logo" class="w-32 h-auto rounded" />
                </div>
                <p v-if="errors.organization_logo" class="text-red-600 text-sm mt-1">{{ errors.organization_logo }}
                </p>
            </div>

            <!-- <Input label="Subscription Plan" type="text" v-model="form.subscription_plan"
                :error="errors.subscription_plan" required>
            <template #icon>
                <Icon icon="stash:subscription-list" width="18" height="18" />
            </template>
            </Input>

            <Input label="  " type="date" v-model="form.validity" :error="errors.validity">
            <template #icon>
                <Icon icon="material-symbols:date-range-outline-rounded" width="18" height="18" />
            </template>
            </Input> -->
            <!-- Update Button -->
            <Button @click="submit" :disabled="form.processing" :color="'primary'" :padding="'md'" :rounded="'full'"
                :textSize="'sm'">
                Update Profile
            </Button>

            <ImageModal :show="showImageModal" :imageUrl="currentImageUrl" @close="closeImageModal" />


        </div>
    </AppLayout>
</template>
