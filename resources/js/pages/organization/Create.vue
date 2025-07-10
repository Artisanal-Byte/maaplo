<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';
import Loader from '@/components/Loader.vue';
const toast = new ToastMagic();
const logoPreview = ref(null);
const showLogoError = ref(false);
const loading = ref(false);
const form = useForm({
    organization_name: '',
    organization_logo: null,
    gst_number: '',
    address: '',
    logo_request: false,
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

const requestLogoCreation = () => {
    form.logo_request = true;
    showLogoError.value = false;
    toast.info('Logo creation request has been noted. We will Contect Soon you with creating a logo.');
};
const hasAttemptedSubmit = ref(false);

const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

const createOrganization = () => {
    form.clearErrors(); // Clear previous errors
    const gst = form.gst_number?.toUpperCase() || '';

    // Validate GST
    if (gst && (!gstRegex.test(gst) || gst.length !== 15)) {
        form.errors.gst_number = 'GST Number must be exactly 15 characters and valid format like 12XXXXX1234X1X2';
        return;
    }

    // FIRST click — prompt user if no logo and no request
    if (!form.organization_logo && !form.logo_request && !hasAttemptedSubmit.value) {
        showLogoError.value = true;
        hasAttemptedSubmit.value = true;
        toast.info('Please upload a logo or request a logo to proceed.');
        return;
    }

    // SECOND click — allow form submission
    loading.value = true;

    form.post(route('organization.store'), {
        forceFormData: true,
        onSuccess: () => {
            toast.success("Organization created successfully!");
            hasAttemptedSubmit.value = false;  // Reset for next form
            loading.value = false;
        },
        onError: () => {
            toast.error("Organization creation failed. Please check the fields.");
            loading.value = false;
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
            <!-- Use the Loader Component -->
            <Loader v-if="loading" :message="'Creating Your Organization...'" />
            <!-- Form -->
            <div class="lg:mt-10 gap-3 bg-white lg:p-7 rounded-lg shadow-md p-5 border-t-4 border-primary">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Organization Name -->
                    <Input v-model="form.organization_name" label="Organization Name"
                        placeholder="Enter Organization Name" :error="form.errors.organization_name" required="true">
                    <template #icon>
                        <Icon icon="ic:outline-business" width="24" height="24" />
                    </template>
                    </Input>

                    <!-- GST Number -->
                    <Input v-model="form.gst_number" label="GST Number" placeholder="Enter GST Number"
                        :error="form.errors.gst_number" @input="form.gst_number = form.gst_number.toUpperCase()">
                    <template #icon>
                        <Icon icon="mdi:certificate-outline" width="24" height="24" />
                    </template>
                    </Input>
                </div>
                <!-- Address -->
                <div class="mt-6">
                    <Input type="textarea" v-model="form.address" label="Address" placeholder="Enter Address"
                        :error="form.errors.address" required="true" rows="3">
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

                <!-- Logo Request -->
                <div v-if="showLogoError && !form.logo_request"
                    class="flex items-start gap-4 bg-yellow-100 border-l-4 border-yellow-400 text-yellow-800 p-4 rounded-md mt-4 shadow-sm">
                    <div class="flex-shrink-0 pt-1">
                        <Icon icon="mdi:alert-circle-outline" class="text-yellow-500" width="24" height="24" />
                    </div>
                    <div class="flex-1 flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-sm mb-1">
                                Organization logo Request
                            </p>
                            <p class="text-sm text-yellow-700 mb-0">
                                Do you need to create a logo for your organization ?
                            </p>
                        </div>
                        <Button @click="requestLogoCreation" class="color-primary h-8" :textSize="'sm'">
                            Yes, Create Organization Logo
                        </Button>
                    </div>
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
