<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';
const toast = new ToastMagic();

const props = defineProps({
    errors: Object,
    organization: Object,
});

const logoPreview = ref(null);

const form = useForm({
    organization_name: props.organization.organization_name,
    organization_logo: '',
    gst_number: props.organization.gst_number,
    address: props.organization.address,
});
console.log('organization data',props.organization);
console.log('form data',form);

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

const updateOrganization = () => {
    form.transform((data) => ({
        ...data,
        _method: 'put',
    })).post(route('organization.update', { organization: props.organization.id }), {
        onSuccess: () => toast.success("Organization updated successfully!"),
        onError: () => toast.error("Update failed. Please check the fields."),
    });
};
</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl text-primary font-bold text-gray-800 font-[Convergence] flex items-center gap-2">
                    <Icon icon="mdi:account-edit" width="28" height="28" />
                    Edit Organization
                </h1>
                <Link :href="route('organization.index')" class="flex items-center gap-1 hover:text-black text-gray-600">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white lg:p-6 rounded-lg lg:shadow-md space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <Input v-model="form.organization_name" label="Organization Name"
                        placeholder="Enter organization name" :error="props.errors.organization_name" required>
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

                    <!-- Address -->
                    <Input v-model="form.address" label="Address" placeholder="Enter Address"
                        :error="props.errors.address" required>
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
