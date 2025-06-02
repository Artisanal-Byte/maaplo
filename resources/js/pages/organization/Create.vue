<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';

const toast = new ToastMagic();
const logoPreview = ref(null);

const form = useForm({
    organization_name: '',
    organization_logo: null,
    gst_number: '',
    address: '',
});

const logoUrl = computed(() => {
    return logoPreview.value || null;
});

const handleLogoChange = (event) => {
    const file = event.target?.files?.[0];
    if (file) {
        form.organization_logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const createOrganization = () => {
    form.post(route('organization.store'), {
        forceFormData: true,
        onSuccess: () => {
            toast.success("Organization created successfully!");
        },
        onError: () => {
            toast.error("Organization creation failed. Please check the fields.");
        }
    });
};
</script>

<template>
    <Head title="Organization-Create" />
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl text-primary font-bold text-gray-800 font-[Convergence] flex items-center gap-2">
                    <Icon icon="mdi:domain-plus" width="28" height="28" />
                    Create Organization
                </h1>
                <Link :href="route('organization.index')"
                    class="flex items-center gap-2 text-gray-600 hover:text-black">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>

            <!-- Form -->
            <div class="lg:mt-10 gap-3 bg-white lg:p-7 rounded-lg shadow-md p-5 border-t-4 border-primary">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Organization Name -->
                    <Input v-model="form.organization_name" label="Organization Name"
                        placeholder="Enter Organization Name" :error="form.errors.organization_name" required>
                    <template #icon>
                        <Icon icon="ic:outline-business" width="24" height="24" />
                    </template>
                    </Input>

                    <!-- GST Number -->
                    <Input v-model="form.gst_number" label="GST Number" placeholder="Enter GST Number"
                        :error="form.errors.gst_number">
                    <template #icon>
                        <Icon icon="mdi:certificate-outline" width="24" height="24" />
                    </template>
                    </Input>
                </div>
                <!-- Address -->
                <div class="mt-6">
                    <Input type="textarea" v-model="form.address" label="Address" placeholder="Enter Address"
                        :error="form.errors.address">
                    <template #icon>
                        <Icon icon="mdi:map-marker-outline" width="24" height="24" />
                    </template>
                    </Input>
                </div>

                <!-- Logo Upload -->
                <div>
                    <label class="block font-medium text-gray-700 mb-2 mt-5">Organization Logo</label>
                    <div class="mb-4">
                        <div
                            class="w-full h-64 bg-gray-100 border rounded flex items-center justify-center overflow-hidden">
                            <img :src="logoUrl || '/images/organization.png'" alt="Logo"
                                class="object-contain h-full w-full" />
                        </div>
                    </div>
                    <input type="file" @change="handleLogoChange"
                        class="block w-full text-sm text-gray-600 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <p v-if="form.errors.organization_logo" class="text-red-600 text-sm mt-1">
                        {{ form.errors.organization_logo }}
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex w-full mt-8">
                    <Button @click="createOrganization" :disabled="form.processing" :color="'primary'" :padding="'md'"
                        :rounded="'full'" :textSize="'sm'"
                        class="w-full flex justify-center items-center hover:scale-105 transition-transform duration-200">
                        <Icon icon="mdi:check-bold" width="20" height="20" class="mr-2" />
                        Save & Continue
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
