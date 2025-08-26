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
const qrPreview = ref(null)
const form = useForm({
    organization_name: '',
    organization_logo: null,
    gst_number: '',
    address: '',
    account_holder_name: '',
    account_number: '',
    ifsc_code: '',
    branch_name: '',
    bank_name: '',
    qr_payment_img: '',
    state: 'Gujarat',
    logo_request: false,
    errors: {}
});

const logoUrl = computed(() => {
    return logoPreview.value || null;
});

const qrUrl = computed(() => {
    return qrPreview.value || null;
});

const handleQRChange = (event) => {
    const file = event.target?.files?.[0];
    if (file) {
        form.qr_payment_img = file;
        qrPreview.value = URL.createObjectURL(file);
    }
};
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
    form.clearErrors();
    console.log("Form state:", form.state);

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
                <!-- Bank Account Details -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Input v-model="form.account_holder_name" label="Account Holder Name"
                        placeholder="Enter Account Holder Name" :error="form.errors.account_holder_name"
                        required="true">
                    <template #icon>
                        <Icon icon="mdi:account" width="24" height="24" />
                    </template>
                    </Input>
                    <Input v-model="form.account_number" label="Account Number" placeholder="Enter Account Number"
                        :error="form.errors.account_number" required="true">
                    <template #icon>
                        <Icon icon="mdi:numeric" width="24" height="24" />
                    </template>
                    </Input>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Input v-model="form.ifsc_code" label="IFSC Code" placeholder="Enter IFSC Code"
                        :error="form.errors.ifsc_code" required="true">
                    <template #icon>
                        <Icon icon="mdi:code-braces" width="24" height="24" />
                    </template>
                    </Input>
                    <Input v-model="form.branch_name" label="Branch Name" placeholder="Enter Branch Name"
                        :error="form.errors.branch_name" required="true">
                    <template #icon>
                        <Icon icon="mdi:bank" width="24" height="24" />
                    </template>
                    </Input>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Input v-model="form.bank_name" label="Bank Name" placeholder="Enter Bank Name"
                        :error="form.errors.bank_name" required="true">
                    <template #icon>
                        <Icon icon="mdi:bank-outline" width="24" height="24" />
                    </template>
                    </Input>


                    <div>
                        <label class="font-medium block mb-1">
                            State <span class="text-red-500">*</span>
                        </label>
                        <select v-model="form.state" class="form-input border border-primary w-full p-4 rounded-lg"
                            :required="true">
                            <option value="Andhra_Pradesh">Andhra Pradesh</option>
                            <option value="Arunachal_Pradesh">Arunachal Pradesh</option>
                            <option value="Assam">Assam</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Chhattisgarh">Chhattisgarh</option>
                            <option value="Goa">Goa</option>
                            <option value="Gujarat" selected>Gujarat</option>
                            <option value="Haryana">Haryana</option>
                            <option value="Himachal_Pradesh">Himachal Pradesh</option>
                            <option value="Jharkhand">Jharkhand</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Madhya_Pradesh">Madhya Pradesh</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Manipur">Manipur</option>
                            <option value="Meghalaya">Meghalaya</option>
                            <option value="Mizoram">Mizoram</option>
                            <option value="Nagaland">Nagaland</option>
                            <option value="Odisha">Odisha</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Sikkim">Sikkim</option>
                            <option value="Tamil_Nadu">Tamil Nadu</option>
                            <option value="Telangana">Telangana</option>
                            <option value="Tripura">Tripura</option>
                            <option value="Uttar_Pradesh">Uttar Pradesh</option>
                            <option value="Uttarakhand">Uttarakhand</option>
                            <option value="West_Bengal">West Bengal</option>
                            <option value="Andaman_and_Nicobar_Islands">Andaman and Nicobar Islands</option>
                            <option value="Chandigarh">Chandigarh</option>
                            <option value="Dadra_and_Nagar_Haveli_and_Daman_and_Diu">Dadra and Nagar Haveli and Daman
                                and Diu</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Jammu_and_Kashmir">Jammu and Kashmir</option>
                            <option value="Ladakh">Ladakh</option>
                            <option value="Lakshadweep">Lakshadweep</option>
                            <option value="Puducherry">Puducherry</option>
                        </select>
                        <p v-if="form.errors?.state" class="text-red-600 text-sm mt-1">
                            {{ form.errors.state }}
                        </p>
                    </div>

                </div>

                <!-- QR Payment Image -->
                <div>
                    <label class="block font-medium text-gray-700 mb-2 mt-5">QR Payment Image</label>
                    <div class="mb-4">
                        <div
                            class="w-full h-64 bg-gray-100 border rounded flex items-center justify-center overflow-hidden">
                            <img alt="QR Payment" class="object-contain h-full w-full" />
                        </div>
                    </div>
                    <input type="file" @change="handleQRChange"
                        class="block w-full text-sm text-gray-600 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <p v-if="form.errors.qr_payment_img" class="text-red-600 text-sm mt-1">
                        {{ form.errors.qr_payment_img }}
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
