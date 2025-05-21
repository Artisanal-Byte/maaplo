<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, ref, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';
import Notes from '@/components/Items/Notes.vue';
import Measurements from '@/components/Items/Measurements.vue';
const customerMeasurements = ref({});
const props = defineProps<{
    errors: Record<string, string>,
    user_id: number,
    customer_limit_exceeded: boolean
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

</script>

<template>

    <Head title="Costomer" />
    <AppLayout>
        <!-- Limit Reached Modal -->
        <div v-if="showLimitModal"
            class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white mx-4 rounded-xl shadow-lg max-w-md w-full p-6 text-center relative">
                <button @click="router.visit(route('customers.index'))"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-semibold">
                    &times;
                </button>

                <div class="flex justify-center mb-4">
                    <div class="bg-red-100 text-red-600 w-14 h-14 flex items-center justify-center rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-red-600 mb-2">Limit Reached</h2>
                <p class="text-gray-700 mb-2">
                    You’re currently on a <strong class="text-primary">Free</strong> plan and can create up to <strong>5
                        customers</strong> only.
                </p>
                <p class="text-gray-600 mb-6">
                    To add more customers, please upgrade your subscription.
                </p>

                <button @click="router.visit('/upgrade')"
                    class="bg-primary hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-full shadow transition">
                    Upgrade Plan
                </button>
            </div>
        </div>

        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex justify-between items-center">
                <h1 class="text-[24px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                    New Customer
                </h1>
                <div class="text-gray-600">
                    <Link :href="route('customers.index')" class="flex items-center gap-1 hover:text-black">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-[16px] font-medium">Back</span>
                    </Link>
                </div>
            </div>
            <div class="flex flex-col lg:mt-5 gap-3 rounded-lg lg:border lg:border-primary p-0 lg:p-4">
                <h1 class="text-xl font-bold lg:mt-0 mt-6">Enter Details</h1>
                <!-- Customer Name -->
                <div>
                    <!-- <span class="text-red-500">*</span> -->
                    <Input type="text" v-model="form.name" label="Customer Name" color="grayBorder" :required="true"
                        :error="errors.name" placeholder="Enter Customer Name" />
                </div>

                <!-- Contact Number -->
                <div>
                    <Input type="tel" v-model="form.phone" label="Contact Number" :required="true" :error="errors.phone"
                        color="grayBorder" placeholder="Enter Phone Number" />
                    <!-- <div v-if="phoneError" class="text-red-600 text-sm mt-1">{{ phoneError }}</div> -->
                </div>

                <!-- Email -->
                <div>
                    <Input type="email" v-model="form.email" label="Email" color="grayBorder" :error="errors.email"
                        placeholder="example@mail.com" />
                </div>

                <!-- Date of Birth -->
                <div>
                    <Input type="date" v-model="form.dob" :error="errors.dob" label="Date of Birth"
                        color="grayBorder" />
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <!-- <label class="bblock font-[Lato] text-[18px] leading-[16px] tracking-[0] mb-1">Address <span
                            class="text-red-500">*</span></label> -->
                    <Input type="textarea" v-model="form.address" color="grayBorder" :required="true" label="Address"
                        :error="errors.address"></Input>
                </div>

                <!-- Gender -->
                <div class="flex">
                    <div class="mr-3">
                        <label class="text-[18px]  mb-1">Gender <span class="text-red-500">*</span></label>
                    </div>
                    <div class="flex flex-row items-center space-x-6 text-black">
                        <Input type="radio" v-model="form.gender" name="gender" label="Male" radioValue="m" />
                        <Input type="radio" v-model="form.gender" name="gender" label="Female" radioValue="f" />
                        <Input type="radio" v-model="form.gender" name="gender" label="Other" radioValue="o" />
                    </div>
                </div>
                <div v-if="errors.gender" class="text-red-600 text-sm">{{ errors.gender }}</div>

                <!-- Measurements -->
                <div>
                    <!-- <label class="block font-[Lato] text-[18px] leading-[16px] tracking-[0] mb-2">Measurements</label> -->
                    <Measurements v-model:measurements="form.measurements" />
                </div>

                <!-- Notes Section -->
                <div>
                    <Notes v-model:notes="notes" />
                </div>

                <!-- Upload Section -->
                <div>
                    <h2 class="block font-[Lato] text-[18px] leading-[16px] tracking-[0] mb-5">Photos <span
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
                        <div class="w-32 h-full gap-[10px]  p-2 shadow-[0px_0px_6.1px_0px_#00000040]">
                            <div class="flex">
                                <label class="block font-semibold mb-2 item-center">Full Image</label>
                                <Icon icon="gridicons:image" width="24" height="24" />
                            </div>
                            <label class="upload-box cursor-pointer">
                                <img :src="form.full_image_preview || '/images/full-coustomer.jpeg'"
                                    alt="Full Image Preview" class="w-24 h-24 object-cover mb-2" />

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
                    class="lg:mt-5 mt-3">
                    Save & Continue
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
