<script setup lang="ts">
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';
import ImageModal from '@/components/ImageModal.vue';
import Input from '@/components/InputWithLabel.vue';
import Notes from '@/components/Items/Notes.vue';
import Measurements from '@/components/Items/Measurements.vue';
import MobileCountryCode from '@/components/MobileCountryCode.vue';
const toast = new ToastMagic();
const props = defineProps<{
    errors: Record<string, string>,
    customer: {
        id: number;
        name: string;
        email: string;
        country_code: string;
        phone: string;
        dob: string;
        measurements: Record<string, string> | null;
        countries: Array<{ name: string; code: string }>
        address: string;
        gender: string;
        notes: Array<{ label: string; text: string }>;
        payment_due?: number;
        face_image: string;
        full_body_image: string;
    },
    isOrder?: boolean;
    toAsk?: string | null;

}>();
const countries = props.countries;
const parseToAsk = (): Record<string, string> | null => {
    if (!props.toAsk) return null;

    try {
        const parsed = JSON.parse(props.toAsk);
        // Remove keys with null or empty values
        const filtered = Object.fromEntries(
            Object.entries(parsed).filter(([_, v]) => v !== null && v !== "")
        );
        return Object.keys(filtered).length > 0 ? filtered : null;
    } catch {
        return null;
    }
};


const form = useForm({
    name: props.customer.name,
    email: props.customer.email,
    phone: props.customer.phone,
    address: props.customer.address,
    dob: props.customer.dob,
    country_code: props.customer.country_code,
    measurements: props.customer.measurements && Object.values(props.customer.measurements).some(v => v !== null)
        ? props.customer.measurements
        : parseToAsk() ?? {},
    gender: props.customer.gender,
    payment_due: props.customer.payment_due,
    half_image: null,
    full_image: null,
    notes: Array.isArray(props.customer.notes) && props.customer.notes.length > 0
        ? props.customer.notes
        : [{ label: '', text: '' }],
});

// Image preview refs
const faceImagePreview = ref<string | null>(null);
const fullBodyImagePreview = ref<string | null>(null);

// Computed URLs for displaying current or preview images
const faceImageUrl = computed(() => {
    return faceImagePreview.value
        ? faceImagePreview.value
        : `/storage/${props.customer.face_image?.replace(/^storage\//, '')}`;
});

const fullBodyImageUrl = computed(() => {
    return fullBodyImagePreview.value
        ? fullBodyImagePreview.value
        : `/storage/${props.customer.full_body_image?.replace(/^storage\//, '')}`;
});

const handleFaceImageChange = (event: Event) => {
    const file = (event.target as HTMLInputElement)?.files?.[0];
    if (file) {
        form.half_image = file;
        faceImagePreview.value = URL.createObjectURL(file);
    }
};

const handleFullBodyImageChange = (event: Event) => {
    const file = (event.target as HTMLInputElement)?.files?.[0];
    if (file) {
        form.full_image = file;
        fullBodyImagePreview.value = URL.createObjectURL(file);
    }
};

const onPhoneInput = (event: Event) => {
    const input = event.target as HTMLInputElement;
    // Remove non-numeric characters and trim to max 10 digits
    input.value = input.value.replace(/\D/g, '').slice(0, 10);
    form.phone = input.value;
};

const updateCustomer = () => {
    form.transform((data) => {
        // Clean notes: remove empty note entries
        const cleanedNotes = (data.notes || []).filter(
            note =>
                typeof note.label === 'string' &&
                typeof note.text === 'string' &&
                note.label.trim() !== '' &&
                note.text.trim() !== ''
        );

        return {
            ...data,
            notes: cleanedNotes.length > 0 ? cleanedNotes : null,
            _method: 'put',
        };
    }).post(route('customers.update', props.customer.id), {
        onSuccess: () => {
            toast.success("Customer updated successfully!");
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
            toast.error("Update failed. Please check the fields and try again.");
        }
    });
};

const phoneError = computed(() => {
    if (!form.phone) return '';
    if (!/^\d+$/.test(form.phone)) {
        return 'Phone number must contain only numbers.';
    }
    if (form.phone.length > 10) {
        return 'Phone number cannot exceed 10 digits.';
    }
    return '';
});

const showImageModal = ref(false);
const currentImageUrl = ref('');

const openImageModal = (url: string) => {
    currentImageUrl.value = url;
    showImageModal.value = true;
};

const closeImageModal = () => {
    showImageModal.value = false;
};

</script>

<template>

    <Head title="Costomer" />
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex justify-between items-center">
                <h1
                    class="flex items-center gap-2 lg:gap-4 text-[24px] leading-[16px] font-bold text-gray-800 font-[Convergence] text-primary tracking-[0]">
                    <Icon icon="fa-solid:user-edit" width="28" height="28" />
                    Edit Customer
                </h1>
                <div class="text-gray-600">
                    <Link :href="route('customers.index')" class="flex items-center gap-1 hover:text-black">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-[16px] font-medium">Back</span>
                    </Link>
                </div>
            </div>
            <!-- Edit Form -->
            <div class="mt-10 gap-3 bg-white lg:p-7 rounded-lg shadow-md p-5 border-t-4 border-primary">
                <h1 class="text-xl font-bold lg:mt-0 mt-2">Edit Details</h1>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 lg:gap-5 mt-3">
                    <!-- Customer Name -->
                    <div>
                        <Input type="text" v-model="form.name" label="Customer Name" color="grayBorder" :required="true"
                            :error="errors.name" placeholder="Enter Customer Name">
                        <template #icon>
                            <Icon icon="bitcoin-icons:contacts-filled" width="24" height="24" />
                        </template>
                        </Input>
                    </div>

                    <!-- Contact Number -->
                    <div>
                        <label class="font-medium block mb-1">
                            Contact Number <span class="text-red-500">*</span>
                        </label>

                        <div class="flex gap-0">
                            <!-- Country Code Selector -->
                            <MobileCountryCode v-model="form.country_code" :countries="countries"
                                class="rounded-r-none" />

                            <!-- Phone Input -->
                            <Input type="text" v-model="form.phone" :required="true" :error="errors.phone"
                                color="grayBorder" placeholder="Enter Phone Number" class="rounded-l-none flex-1"
                                inputmode="numeric" pattern="\d*" @input="onPhoneInput">
                            <template #icon>
                                <Icon icon="ic:round-phone" width="20" height="20" />
                            </template>
                            </Input>
                        </div>

                        <div v-if="phoneError" class="text-red-600 text-sm mt-1">{{ phoneError }}</div>
                    </div>


                    <!-- Email -->
                    <div>
                        <Input type="email" v-model="form.email" label="Email" color="grayBorder" :error="errors.email"
                            placeholder="example@mail.com">
                        <template #icon>
                            <Icon icon="ic:round-email" width="20" height="20" />
                        </template>
                        </Input>
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <Input type="date" v-model="form.dob" :error="errors.dob" label="Date of Birth"
                            color="grayBorder">
                        <template #icon>
                            <Icon icon="material-symbols:date-range-outline-rounded" width="20" height="20" />
                        </template>
                        </Input>
                    </div>
                </div>

                <!-- Address -->
                <div class="md:col-span-2 mt-3">
                    <Input type="textarea" v-model="form.address" color="grayBorder" :required="true" label="Address"
                        :error="errors.address"></Input>
                </div>

                <!-- Gender -->
                <div class="flex mb-5 mt-4">
                    <div class="mr-3">
                        <label class="font-medium">
                            Gender
                        </label>
                    </div>
                    <div class="flex flex-row items-center space-x-6 text-black">
                        <Input type="radio" v-model="form.gender" name="gender" :error="errors.gender" label="Male"
                            radioValue="m" />
                        <Input type="radio" v-model="form.gender" name="gender" :error="errors.gender" label="Female"
                            radioValue="f" />
                        <Input type="radio" v-model="form.gender" name="gender" :error="errors.gender" label="Other"
                            radioValue="o" />
                    </div>
                    <div v-if="errors.gender" class="text-red-600 text-sm">{{ errors.gender }}</div>
                </div>

                <!-- Measurements -->
                <div class="mt-5">
                    <!-- <label class="block font-[Lato] text-[18px] leading-[16px] tracking-[0] mb-2">Measurements</label> -->
                    <Measurements v-model:measurements="form.measurements" :error="errors.measurements"
                        :toAsk="props.toAsk" :isOrder="props.isOrder" />
                    <div v-if="errors.measurements" class="text-red-600 text-sm mt-2">{{ errors.measurements }}
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="mt-3">
                    <Notes v-model:notes="form.notes" />
                </div>
                <!-- Customer Images -->
                <div>
                    <h2 class="block font-medium leading-[16px] tracking-[0] mb-5 mt-8 ">Photos <span
                            class="text-red-500">*</span></h2>
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Face Image -->
                        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Face Image</h2>
                            <div @click="openImageModal(faceImageUrl)"
                                class="w-full h-64 bg-gray-50 flex items-center justify-center rounded-md overflow-hidden border">
                                <img :src="faceImageUrl" alt="Face Image" class="object-scale-down h-64 w-[500px]" />
                            </div>
                            <label class="mt-4 block">
                                <span class="text-sm text-gray-600">Upload new image</span>
                                <input type="file" @change="handleFaceImageChange"
                                    class="block w-full text-sm text-gray-500 mt-1 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                            </label>
                        </div>

                        <!-- Full Body Image -->
                        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Full Image</h2>
                            <div @click="openImageModal(fullBodyImageUrl)"
                                class="w-full h-64 bg-gray-50 flex items-center justify-center rounded-md overflow-hidden border">
                                <img :src="fullBodyImageUrl" alt="Full Body Image"
                                    class="object-scale-down h-[250px]  w-[500px]" />
                            </div>
                            <label class="mt-4 block">
                                <span class="text-sm text-gray-600">Upload new image</span>
                                <input type="file" @change="handleFullBodyImageChange"
                                    class="block w-full text-sm text-gray-500 mt-1 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Modal Component -->
                <ImageModal :show="showImageModal" :imageUrl="currentImageUrl" @close="closeImageModal" />

                <!-- Update Button -->
                <Button class="lg:mt-5 mt-3 w-full" @click="updateCustomer" :disabled="form.processing"
                    :color="'primary'" :padding="'md'" :rounded="'full'" :textSize="'sm'">
                    Update Customer
                </Button>
            </div>

        </div>
    </AppLayout>
</template>
