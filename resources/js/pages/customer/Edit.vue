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
import Loader from '@/components/Loader.vue';
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
        state: string;
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
const removeHalfImage = ref(false);
const removeFullImage = ref(false);

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
    state: props.customer.state,
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
    if (removeHalfImage.value) return null; // hide image when remove is triggered
    return faceImagePreview.value
        ? faceImagePreview.value
        : props.customer.face_image
            ? `/storage/${props.customer.face_image.replace(/^storage\//, '')}`
            : null;
});

const fullBodyImageUrl = computed(() => {
    if (removeFullImage.value) return null;
    return fullBodyImagePreview.value
        ? fullBodyImagePreview.value
        : props.customer.full_body_image
            ? `/storage/${props.customer.full_body_image.replace(/^storage\//, '')}`
            : null;
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
const loading = ref(false);
const updateCustomer = () => {
    loading.value = true;
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
            remove_half_image: removeHalfImage.value,
            remove_full_image: removeFullImage.value,
        };
    }).post(route('customers.update', props.customer.id), {
        onSuccess: () => {
            toast.success("Customer updated successfully!");
            loading.value = false;
        },
        onError: (errors) => {
            toast.error("Update failed. Please check the fields and try again.");
            loading.value = false;
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

const openImageModal = (url: string | null) => {
    if (!url || url.includes('undefined') || url.includes('null')) return;
    currentImageUrl.value = url;
    showImageModal.value = true;
};

const closeImageModal = () => {
    showImageModal.value = false;
};

const removeFaceImage = () => {
    form.half_image = null;
    faceImagePreview.value = null;
    removeHalfImage.value = true;
};


const removeFullBodyImage = () => {
    form.full_image = null;
    fullBodyImagePreview.value = null;
    removeFullImage.value = true;
};


</script>

<template>

    <Head title="Costomer-Edit" />
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
            <Loader v-if="loading" :message="'Customer Updating...'" />
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


                    <!-- Address -->
                    <div>
                        <Input type="textarea" v-model="form.address" color="grayBorder" :required="true"
                            label="Address" :error="errors.address"></Input>
                    </div>

                    <div>
                        <label class="font-medium block mb-1">
                            State <span class="text-red-500">*</span>
                        </label>
                        <select v-model="form.state" class="form-input border border-primary w-full p-4 rounded-lg"
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
                            <option value="Dadra_and_Nagar_Haveli_and_Daman_and_Diu">Dadra and Nagar Haveli and Daman
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
                    <h2 class="block font-medium leading-[16px] tracking-[0] mb-5 mt-8 ">Photos</h2>
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Face Image -->
                        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Face Image</h2>
                            <div @click="openImageModal(faceImageUrl)"
                                class="w-full h-64 bg-gray-50 flex items-center justify-center rounded-md overflow-hidden border">
                                <img :src="(faceImageUrl && !faceImageUrl.includes('undefined')) ? faceImageUrl : '/images/by_default_user.png'"
                                    alt="Face Image" class="object-scale-down h-64 w-[500px]" />

                            </div>
                            <div class="mt-4 flex items-center gap-4">
                                <label class="block w-full">
                                    <span class="text-sm text-gray-600">Upload new image</span>
                                    <input type="file" @change="handleFaceImageChange"
                                        class="block w-full text-sm text-gray-500 mt-1 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                                </label>

                                <button @click="removeFaceImage" type="button"
                                    class="flex items-center gap-1 text-red-600 text-sm underline hover:text-red-800 whitespace-nowrap mt-6">
                                    <Icon icon="mdi:trash-can-outline" width="18" height="18" />
                                    <span>Remove Image</span>
                                </button>
                            </div>


                        </div>

                        <!-- Full Body Image -->
                        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Full Image</h2>
                            <div @click="openImageModal(fullBodyImageUrl)"
                                class="w-full h-64 bg-gray-50 flex items-center justify-center rounded-md overflow-hidden border">
                                <!-- <pre>{{ fullBodyImageUrl }}</pre> -->
                                <img :src="(fullBodyImageUrl && !fullBodyImageUrl.includes('undefined')) ? fullBodyImageUrl : '/images/by_default_user.png'"
                                    alt="Full Body Image" class="object-scale-down h-[250px]  w-[500px]" />

                            </div>
                            <div class="mt-4 flex items-center gap-4">
                                <label class="block w-full">
                                    <span class="text-sm text-gray-600">Upload new image</span>
                                    <input type="file" @change="handleFullBodyImageChange"
                                        class="block w-full text-sm text-gray-500 mt-1 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                                </label>

                                <button @click="removeFullBodyImage" type="button"
                                    class="flex items-center gap-1 text-red-600 text-sm underline hover:text-red-800 whitespace-nowrap mt-6">
                                    <Icon icon="mdi:trash-can-outline" width="18" height="18" />
                                    <span>Remove Image</span>
                                </button>
                            </div>

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
