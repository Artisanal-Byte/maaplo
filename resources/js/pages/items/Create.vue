<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import SearchSelect from '@/components/SearchSelect.vue';
import Button from '@/components/Button.vue';

const toast = new ToastMagic();

const props = defineProps<{
    errors: Record<string, string>;
    privateTemplates: Array<{ id: number; name: string }>;
    publicTemplates: Array<{ id: number; name: string }>;
    measurements: Array<{ id: number; slug: string; measurements_logo: string }>;
}>();
const form = reactive({
    name: '',
    gender: '',
    body_part: '',
    required_measurements: [] as string[],
    custom_template: false,
    svg_logo: '',
    design_details: {
        front_neck_design: false,
        back_neck_design: false,
        sleeve_type: false,
    } as Record<string, boolean>,
});

const submitForm = () => {
    router.post(route('items.store'), form, {
        onSuccess: () => {
            toast.success("Item created successfully!");
            router.visit(route('items.index'));
        },
        onError: (error) => {
            toast.error("Failed to create Item. Please fill in all the required fields.");
        },
    });
};

const formatSlug = (slug: string): string => {
    return slug
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};
const fillFormFromTemplate = (template: any) => {
    form.name = template.name;
    form.gender = template.gender === 'Male' ? 'm' : (template.gender === 'Female' ? 'f' : '');
    form.body_part = template.body_part === 'Upper' ? 'upper' : (template.body_part === 'Lower' ? 'lower' : '');
    form.svg_logo = template.svg_logo;
    form.required_measurements = template.measurements.map((m: any) => m.slug);
};

const formatLabel = (key: string): string => {
    return key
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};
</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex justify-between items-center">
                <h1 class="text-[24px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                    Add New Template
                </h1>
                <div class="text-gray-600">
                    <Link :href="route('items.index')" class="flex items-center gap-1 hover:text-black">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-[16px] font-medium">Back</span>
                    </Link>
                </div>
            </div>
            <div class="flex flex-col lg:mt-5 gap-3 mt-6 lg:gap-4 rounded-lg lg:border lg:border-primary p-0 lg:p-4">
                <!-- <h1>Select Base Template</h1> -->
                <div>
                    <label class="text-md">Select Base Template</label>
                    <SearchSelect class="mt-2" :public-templates="props.publicTemplates"
                        :private-templates="props.privateTemplates" @templateSelected="fillFormFromTemplate" />
                </div>
                <div>
                    <Input v-model="form.name" label="Template Name" placeholder="Enter Template Name" margin="md"
                        width="full" fonttype="normal" textSize="base" rounded="md" error="" required="true" />
                </div>
                <div v-if="errors.name" class="text-red-600 text-sm">{{ errors.name }}</div>

                <!-- Gender -->
                <div class="mt-2 lg:mt-4 flex flex-row gap-2 gap-4">
                    <div>
                        <h1 class="lg:mb-2 text-md">Gender <span class="text-red-500">*</span></h1>
                    </div>
                    <div class="flex gap-4">
                        <label>
                            <input type="radio" name="gender" value="f" v-model="form.gender" class="hidden" />
                            <div :class="[
                                'px-4 py-1 rounded border text-sm cursor-pointer',
                                form.gender === 'f'
                                    ? 'bg-primary text-white'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                            ]">
                                Female
                            </div>
                        </label>

                        <label>
                            <input type="radio" name="gender" value="m" v-model="form.gender" class="hidden" />
                            <div :class="[
                                'px-4 py-1 rounded border text-sm cursor-pointer',
                                form.gender === 'm'
                                    ? 'bg-primary text-white'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                            ]">
                                Male
                            </div>
                        </label>
                    </div>
                </div>
                <div v-if="errors.gender" class="text-red-600 text-sm">{{ errors.gender }}</div>

                <!-- Body Part -->
                <div class="mt-2 lg:mt-4 flex flex-row gap-2 gap-4">
                    <div>
                        <h1 class="lg:mb-2 text-md">Body Part <span class="text-red-500">*</span></h1>
                    </div>
                    <div class="flex gap-4">
                        <label>
                            <input type="radio" name="bodyPart" value="upper" v-model="form.body_part" class="hidden" />
                            <div :class="[
                                'px-4 py-1 rounded border text-sm cursor-pointer',
                                form.body_part === 'upper'
                                    ? 'bg-primary text-white'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                            ]">
                                Upper
                            </div>
                        </label>


                        <label>
                            <input type="radio" name="bodyPart" v-model="form.body_part" class="hidden" value="lower" />
                            <div :class="[
                                'px-4 py-1 rounded border text-sm cursor-pointer',
                                form.body_part === 'lower'
                                    ? 'bg-primary text-white'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                            ]">
                                Lower
                            </div>
                        </label>
                    </div>
                </div>
                <div v-if="errors.body_part" class="text-red-600 text-sm">{{ errors.body_part }}</div>

                <!-- temolate logo -->
                <div>
                    <Input v-model="form.svg_logo" label="Template Logo" placeholder="Only Paste SVG path here"
                        margin="md" width="full" fonttype="normal" textSize="base" rounded="md"
                        :error="errors.svg_logo" />
                </div>
                <!--Measurements -->
                <h1 class="text-md font-semibold mb-2">Measurement Ask :</h1>
                <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 items-center gap-x-16 gap-y-4">
                    <label v-for="measurement in props.measurements" :key="measurement.id"
                        class="flex items-center gap-3 cursor-pointer rounded w-full">
                        <!-- SVG icon -->
                        <div v-if="measurement.measurements_logo" class="shrink-0"
                            v-html="measurement.measurements_logo"></div>

                        <!-- Text and checkbox -->
                        <div class="flex justify-between items-center lg:w-full w-80">
                            <span class="text-[16px]">{{ formatSlug(measurement.slug) }}</span>
                            <input type="checkbox" :id="measurement.slug" :value="measurement.slug"
                                v-model="form.required_measurements" class="form-checkbox w-4 h-4" />
                        </div>
                    </label>
                </div>

                <div v-if="errors.required_measurements" class="text-red-600 text-sm">{{ errors.required_measurements }}
                </div>
                <!-- Design Details -->
                <div class="mt-2">
                    <h2 class="text-md font-semibold mb-4">Design Details Ask:</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="(value, key) in form.design_details" :key="key"
                            class="flex items-center justify-between bg-gray-50 p-2 rounded-md">
                            <span class="font-normal text-[16px] tracking-normal font-lato">
                                {{ formatLabel(key) }}
                            </span>

                            <div class="flex rounded overflow-hidden text-sm">
                                <button :class="[
                                    'px-4 py-1 focus:outline-none transition',
                                    form.design_details[key] === true ? 'bg-primary text-white' : 'bg-gray-200 text-black'
                                ]" @click="form.design_details[key] = true">
                                    Yes
                                </button>
                                <button :class="[
                                    'px-4 py-1 focus:outline-none transition',
                                    form.design_details[key] === false ? 'bg-primary text-white' : 'bg-gray-200 text-black'
                                ]" @click="form.design_details[key] = false">
                                    No
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="errors.design_details" class="text-red-600 text-sm mt-2">
                        {{ errors.design_details }}
                    </div>
                </div>


                <Button @click="submitForm" color="primary" padding="md" rounded="full" textSize="sm" class="lg:mt-5 mt-3">
                    Save
                </Button>

            </div>

        </div>
    </AppLayout>
</template>
