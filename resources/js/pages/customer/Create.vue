<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, ref, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';
import Notes from '@/components/Items/Notes.vue';
import Measurements from '@/components/Items/Measurements.vue';
import CustomerLimitPopup from '@/components/CustomerLimitPopup.vue';
import MobileCountryCode from '@/components/MobileCountryCode.vue';
const customerMeasurements = ref({});
const props = defineProps<{
    errors: Record<string, string>,
    user_id: number,
    customer_limit_exceeded: boolean,
    measurements,
    isOrder?: boolean;
    toAsk?: string | null;

}>();
const toast = new ToastMagic();

const form = reactive({
    user_id: props.user_id,
    name: '',
    gender: '',
    phone: '',
    email: null,
    address: '',
    dob: null,
    measurements: null,
    half_image: '',
    full_image: '',
    half_image_preview: '',
    country_code: '+91',
    full_image_preview: ''
});
// Notes
const notes = ref([{ label: '', text: '' }]);
const showLimitModal = ref(false);

onMounted(() => {
    if (props.customer_limit_exceeded) {
        showLimitModal.value = true;
    }
});
const submitForm = () => {

    router.post('/customers', {
        ...form,
        notes: notes.value,
        base_measurements: customerMeasurements.value,
    }, {
        onSuccess: () => {
            toast.success("Customer created successfully!");
        },
        onError: (errors) => {
            toast.error("Failed to create customer. please fill the all the required fields.");
            console.error(errors);
        },
    });
};


const handleImageUpload = (event: Event, field: 'half_image' | 'full_image') => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file && file.size > 2 * 1024 * 1024) {
        alert('Image must be less than 2MB.');
        return;
    }

    form[field] = file;
    const previewField = field + '_preview' as 'half_image_preview' | 'full_image_preview';
    form[previewField] = file ? URL.createObjectURL(file) : '';
};

const onPhoneInput = (event: Event) => {
    const input = event.target as HTMLInputElement;
    // Remove non-numeric characters and trim to max 10 digits
    input.value = input.value.replace(/\D/g, '').slice(0, 10);
    form.phone = input.value;
};

</script>

<template>

    <Head title="Costomer-Create" />
    <AppLayout>
        <!-- Limit Reached Modal -->
        <CustomerLimitPopup :show="showLimitModal" @close="showLimitModal = false" />

        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex justify-between items-center">

                <h1
                    class="flex items-center gap-2 lg:gap-4 text-[24px] leading-[16px] font-bold text-gray-800 font-[Convergence] text-primary tracking-[0]">
                    <Icon icon="mdi:account-plus" width="28" height="28" />
                    New Customer
                </h1>

                <div class="text-gray-600">
                    <Link :href="route('customers.index')" class="flex items-center gap-1 hover:text-black">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-[16px] font-medium">Back</span>
                    </Link>
                </div>
            </div>
            <div class="mt-10 gap-3 bg-white lg:p-7 rounded-lg shadow-md p-5 border-t-4 border-primary">
                <h1 class="text-xl font-bold lg:mt-0 mt-2">Enter Details</h1>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 lg:gap-5 mt-3">
                    <!-- Customer Name -->
                    <div>
                        <Input type="text" v-model="form.name" label="Customer Name" color="grayBorder" :required="true"
                            :error="errors.name" placeholder="Enter Customer Name">
                        <!-- Icon inside input placeholder -->
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
                        <div class="flex">
                            <!-- Use MobileCountryCode component here -->
                            <MobileCountryCode v-model="form.country_code"  class="rounded-r-none"/>

                            <!-- Phone Input -->
                            <Input type="tel" v-model="form.phone" class="rounded-l-none flex-1"
                                placeholder="Enter Phone Number" :error="errors.phone" :required="true"
                                color="grayBorder" inputmode="numeric" pattern="\d*" @input="onPhoneInput">
                            <template #icon>
                                <Icon icon="ic:round-phone" width="20" height="20" />
                            </template>
                            </Input>
                        </div>
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
                    <!-- <label class="bblock font-[Lato] text-[18px] leading-[16px] tracking-[0] mb-1">Address <span
                            class="text-red-500">*</span></label> -->
                    <Input type="textarea" v-model="form.address" color="grayBorder" :required="true" label="Address"
                        :error="errors.address"></Input>
                </div>

                <!-- Gender -->
                <div class="flex mt-5">
                    <div class="mr-3 ">
                        <label class="font-medium">Gender <span class="text-red-500">*</span></label>
                    </div>
                    <div class="flex flex-row items-center space-x-6 text-black">
                        <Input type="radio" v-model="form.gender" name="gender" label="Male" radioValue="m" />
                        <Input type="radio" v-model="form.gender" name="gender" label="Female" radioValue="f" />
                        <Input type="radio" v-model="form.gender" name="gender" label="Other" radioValue="o" />
                    </div>
                </div>
                <div v-if="errors.gender" class="text-red-600 text-sm">{{ errors.gender }}</div>

                <!-- Measurements -->
                <div class="mt-5">
                    <!-- <label class="block font-[Lato] text-[18px] leading-[16px] tracking-[0] mb-2">Measurements</label> -->
                    <Measurements v-model:measurements="form.measurements" :error="errors.measurements"
                        :toAsk="measurements" :isOrder="false"/>
                    <div v-if="errors.measurements" class="text-red-600 text-sm mt-2">{{ errors.measurements }}</div>
                </div>

                <!-- Notes Section -->
                <div class="mt-3">
                    <Notes v-model:notes="notes" />
                </div>

                <!-- Upload Section -->
                <div>
                    <h2 class="block font-medium leading-[16px] tracking-[0] mb-5 mt-3 ">Photos <span
                            class="text-red-500">*</span></h2>
                    <div class="flex flex-row justify-center item-center gap-6">

                        <!-- Half Image Upload -->
                        <div class="w-32 h-full gap-[10px] p-2 shadow-[0px_0px_6.1px_0px_#00000040]">
                            <div class="flex">
                                <label class="block font-semibold mb-2 item-center">Face Image</label>
                                <Icon icon="gridicons:image" width="24" height="24" />
                            </div>
                            <label class="upload-box cursor-pointer">
                                <img :src="form.half_image_preview || '/images/half-coustomer.jpeg'"
                                    alt="Half Image Preview" class="w-28 h-24 object-cover mb-2" />
                                <input type="file" class="hidden" accept="image/*"
                                    @change="handleImageUpload($event, 'half_image')" />
                            </label>
                            <div v-if="props.errors.half_image" class="text-red-600 text-sm mt-1">
                                {{ props.errors.half_image }}
                            </div>
                        </div>

                        <!-- Full Image Upload -->
                        <div class="w-32 h-full gap-[10px] p-2 shadow-[0px_0px_6.1px_0px_#00000040]">
                            <div class="flex">
                                <label class="block font-semibold mb-2 item-center">Full Image</label>
                                <Icon icon="gridicons:image" width="24" height="24" class="ml-2" />
                            </div>
                            <label class="upload-box cursor-pointer">
                                <img :src="form.full_image_preview || '/images/full-coustomer.jpeg'"
                                    alt="Full Image Preview" class="w-28 h-24 object-fit mb-2" />

                                <input type="file" class="hidden" accept="image/*"
                                    @change="handleImageUpload($event, 'full_image')" />
                            </label>
                            <div v-if="props.errors.full_image" class="text-red-600 text-sm mt-1">
                                {{ props.errors.full_image }}
                            </div>
                        </div>
                    </div>
                    <!-- <div v-if="errors.full_image" class="text-red-600 text-sm mt-1">{{ errors.full_image }}</div> -->
                </div>

                <!-- Submit Button (Full Width Below) -->
                <Button @click="submitForm" :color="'primary'" :padding="'md'" :rounded="'full'" :textSize="'sm'"
                    class="lg:mt-5 mt-3 w-full">
                    Save & Continue
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
