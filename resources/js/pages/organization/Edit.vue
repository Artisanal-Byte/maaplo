<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';
import Loader from '@/components/Loader.vue';
const toast = new ToastMagic();

const props = defineProps({
    errors: Object,
    organization: Object,
});

const logoPreview = ref(null);
const loading = ref(false);
const form = useForm({
    organization_name: props.organization.organization_name,
    organization_logo: '',
    gst_number: props.organization.gst_number,
    address: props.organization.address,
    // Add these bank details, assuming they are optional
    account_holder_name: props.organization.account_holder_name || '',
    account_number: props.organization.account_number || '',
    ifsc_code: props.organization.ifsc_code || '',
    branch_name: props.organization.branch_name || '',
    bank_name: props.organization.bank_name || '',
    state: props.organization.state || '',
    qr_payment_img: '',
});

const logoUrl = computed(() => {
    return logoPreview.value
        ? logoPreview.value
        : props.organization.organization_logo
            ? `/storage/${props.organization.organization_logo.replace(/^storage\//, '')}`
            : null;
});

const handleLogoChange = (event) => {
    const file = event.target?.files?.[0];
    if (file) {
        form.organization_logo = file;
        logoPreview.value = URL.createObjectURL(file);
    } else {
        toast.error("Please select a valid image file.");
    }
};

const qrPreview = ref(null);

const handleQrImageChange = (event) => {
    const file = event.target?.files?.[0];
    if (file) {
        form.qr_payment_img = file;
        qrPreview.value = URL.createObjectURL(file);
    } else {
        toast.error("Please select a valid QR image file.");
    }
};

const qrImageUrl = computed(() => {
    return qrPreview.value
        ? qrPreview.value
        : props.organization.qr_payment_img
            ? `/storage/${props.organization.qr_payment_img.replace(/^storage\//, '')}`
            : null;
});


const updateOrganization = () => {
    loading.value = true;

    form.transform((data) => {
        const payload = { ...data };

        // Don't include organization_logo if no new file selected
        if (!form.organization_logo) {
            delete payload.organization_logo;
        }

        payload._method = 'put';
        return payload;
    }).post(route('organization.update', { organization: props.organization.id }), {
        forceFormData: true, // Needed for file upload
        onSuccess: () => {
            toast.success("Organization updated successfully!");

            // Clear the preview and free memory
            if (logoPreview.value) {
                URL.revokeObjectURL(logoPreview.value);
                logoPreview.value = null;
            }

            loading.value = false;
        },
        onError: () => {
            toast.error("Update failed. Please check the fields.");
            loading.value = false;
        },
    });
};


</script>

<template>

    <Head title="Organization-Edit" />
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl text-primary font-bold text-gray-800 font-[Convergence] flex items-center gap-2">
                    <Icon icon="mdi:account-edit" width="28" height="28" />
                    Edit Organization
                </h1>
                <Link :href="route('organization.index')"
                    class="flex items-center gap-1 hover:text-black text-gray-600">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>
            <!-- Use the Loader Component -->
            <Loader v-if="loading" :message="'Updating Organization...'" />
            <!-- Form Card -->
            <div class="bg-white lg:p-6 rounded-lg lg:shadow-md space-y-6">
                <h1
                    class="text-2xl text-primary font-bold text-gray-800 font-[Convergence] flex items-center gap-2 mb-3">
                    Organization Details</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <Input v-model="form.organization_name" label="Organization Name"
                        placeholder="Enter organization name" :error="props.errors.organization_name" required="true">
                    <template #icon>
                        <Icon icon="ic:outline-business" width="24" height="24" />
                    </template>
                    </Input>

                    <!-- GST Number -->
                    <Input v-model="form.gst_number" label="GST Number" placeholder="Enter GST Number"
                        :error="props.errors.gst_number" required>
                    <template #icon>
                        <Icon icon="mdi:certificate-outline" width="24" height="24" />
                    </template>
                    </Input>
                </div>
                <!-- Address -->
                <div class="w-full">
                    <Input v-model="form.address" label="Address" placeholder="Enter Address"
                        :error="props.errors.address" required="true" type="textarea">
                    <template #icon>
                        <Icon icon="mdi:map-marker-outline" width="24" height="24" />
                    </template>
                    </Input>
                </div>


                <!-- Logo Upload -->
                <div>
                    <label class="block font-medium text-gray-700 mb-2">Organization Logo</label>
                    <div class="mb-4">
                        <div
                            class="w-full h-64 bg-gray-100 border rounded flex items-center justify-center overflow-hidden">
                            <img :src="logoUrl || '/images/organization.png'" alt="Logo"
                                class="object-contain h-full" />
                        </div>
                    </div>
                    <input type="file" @change="handleLogoChange"
                        class="block w-full text-sm text-gray-600 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <p v-if="props.errors.organization_logo" class="text-red-600 text-sm mt-1">
                        {{ props.errors.organization_logo }}
                    </p>
                </div>

                <!-- Account Details -->
                <h1
                    class="text-2xl text-primary font-bold text-gray-800 font-[Convergence] flex items-center gap-2 my-5">
                    Account
                    Details</h1>
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Input v-model="form.account_holder_name" label="Account Holder Name"
                        placeholder="Enter account holder name" :error="props.errors.account_holder_name"
                        required="true">
                    <template #icon>
                        <Icon icon="mdi:account" width="24" height="24" />
                    </template>
                    </Input>

                    <Input v-model="form.account_number" label="Account Number" placeholder="Enter account number"
                        :error="props.errors.account_number" required="true">
                    <template #icon>
                        <Icon icon="mdi:bank" width="24" height="24" />
                    </template>
                    </Input>

                    <Input v-model="form.ifsc_code" label="IFSC Code" placeholder="Enter IFSC code"
                        :error="props.errors.ifsc_code" required="true">
                    <template #icon>
                        <Icon icon="mdi:code-tags" width="24" height="24" />
                    </template>
                    </Input>

                    <Input v-model="form.branch_name" label="Branch Name" placeholder="Enter branch name"
                        :error="props.errors.branch_name">
                    <template #icon>
                        <Icon icon="mdi:office-building" width="24" height="24" />
                    </template>
                    </Input>

                    <Input v-model="form.bank_name" label="Bank Name" placeholder="Enter bank name"
                        :error="props.errors.bank_name">
                    <template #icon>
                        <Icon icon="mdi:bank-outline" width="24" height="24" />
                    </template>
                    </Input>

                    <div>
                        <label class="font-medium block mb-1">
                            State <span class="text-red-500">*</span>
                        </label>
                        <select v-model="form.state" class="form-input border border-primary w-full p-3 rounded-lg"
                            :required="true" :error="errors.state">
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
                            <option value="Dadra_and_Nagar_Haveli_and_Daman_and_Diu">Dadra and Nagar Haveli and
                                Daman
                                and
                                Diu</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Jammu_and_Kashmir">Jammu and Kashmir</option>
                            <option value="Ladakh">Ladakh</option>
                            <option value="Lakshadweep">Lakshadweep</option>
                            <option value="Puducherry">Puducherry</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6">
                    <label class="block font-medium text-gray-700 mb-2">QR Payment Image</label>

                    <!-- QR Image Preview -->
                    <div class="mb-4">
                        <div
                            class="w-full h-64 bg-gray-100 border rounded flex items-center justify-center overflow-hidden">
                            <img v-if="qrImageUrl" :src="qrImageUrl" alt="QR Payment" class="object-contain h-full" />
                            <span v-else class="text-gray-400">No QR Payment Image</span>
                        </div>
                    </div>

                    <!-- File input -->
                    <input type="file" @change="handleQrImageChange" class="block w-full text-sm text-gray-600
           file:py-2 file:px-4 file:rounded file:border-0
           file:text-sm file:font-semibold file:bg-green-50
           file:text-green-700 hover:file:bg-green-100" />
                    <p v-if="props.errors.qr_payment_img" class="text-red-600 text-sm mt-1">
                        {{ props.errors.qr_payment_img }}
                    </p>
                </div>



                <!-- Submit Button -->
                <div class="flex">
                    <Button @click="updateOrganization" :disabled="form.processing" :color="'primary'" :padding="'md'"
                        :rounded="'full'" :textSize="'sm'"
                        class="w-full flex justify-center items-center hover:scale-105 transition-transform duration-200">
                        <Icon icon="mdi:check-bold" width="20" height="20" class="mr-2" />
                        Update Organization
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
