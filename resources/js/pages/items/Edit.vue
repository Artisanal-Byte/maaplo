<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';
import SearchSelect from '@/components/SearchSelect.vue';
import { nextTick, ref } from 'vue';
const toast = new ToastMagic();

const props = defineProps<{
    errors: Record<string, string>,
    item: {
        id: number;
        name: string;
        svg_logo: string;
        gender: string;
        body_part: string;
    },
    designDetails: {
        front_neck_design: boolean,
        back_neck_design: boolean,
        sleeve_type: boolean
    },
    publicTemplates: Array<{ id: number; name: string }>,
    privateTemplates: Array<{ id: number; name: string }>,
    measurements: {
        all: Array<{ id: number, slug: string }>,
        selected: string[], measurements_logo: string
    }
}>();
const selectedTemplate = ref(null);

const form = useForm({
    name: props.item.name,
    svg_logo: props.item.svg_logo,
    gender: props.item.gender === "Male" ? "m" : (props.item.gender === "Female" ? "f" : "o"),
    body_part: props.item.body_part === "Upper" ? "upper" : "lower",
    required_measurements: props.measurements.selected,
    design_details: {
        front_neck_design: props.designDetails?.front_neck_design ?? false,
        back_neck_design: props.designDetails?.back_neck_design ?? false,
        sleeve_type: props.designDetails?.sleeve_type ?? false,
    },

    _method: 'put',
});

const toggleMeasurement = (label: string) => {
    const updatedMeasurements = [...form.required_measurements];
    const index = updatedMeasurements.indexOf(label);
    if (index > -1) {
        updatedMeasurements.splice(index, 1);
    } else {
        updatedMeasurements.push(label);
    }
    form.required_measurements = updatedMeasurements;
};

const updateTemplate = () => {
    const dataToSend = {
        ...form.data(),
        required_measurements: form.required_measurements,
        design_details: form.design_details,
    };

    form.transform(data => ({
        ...dataToSend,
        _method: 'put',
    })).post(route('items.update', props.item.id), {
        onSuccess: () => {
            toast.success('Template updated successfully!');
            setTimeout(() => router.visit(route('items.index')), 1000);
        },
        onError: () => {
            toast.error('Update failed. Please try again.');
        }
    });
};
const fillFormFromTemplate = (selectedTemplate: any) => {

    form.name = selectedTemplate.name || '';
    form.svg_logo = selectedTemplate.svg_logo || '';
    form.gender = selectedTemplate.gender === 'Male' ? 'm' : (selectedTemplate.gender === 'Female' ? 'f' : '');
    form.body_part = selectedTemplate.body_part === 'Upper' ? 'upper' : (selectedTemplate.body_part === 'Lower' ? 'lower' : '');

    // If template.measurements is an array of objects, map them to slugs
    form.required_measurements = Array.isArray(selectedTemplate.measurements)
        ? selectedTemplate.measurements.map((m: any) => m.slug)
        : [];

};

const formatSlug = (slug: string): string => {
    return slug
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

const formatLabel = (key: string): string => {
    return key
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};
</script>

<template>

    <Head title="Template-Edit" />
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-[24px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                    Edit Template
                </h1>
                <div class="text-gray-600">
                    <Link :href="route('items.index')" class="flex items-center gap-1 hover:text-black">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-[16px] font-medium">Back</span>
                    </Link>
                </div>
            </div>

            <div class="flex flex-col mt-10 gap-4 rounded-lg border border-primary p-4">
                <div>
                    <label class="text-md">Select Base Template</label>
                    <SearchSelect class="mt-2" :public-templates="props.publicTemplates"
                        :private-templates="props.privateTemplates" @templateSelected="fillFormFromTemplate" />
                </div>
                <!-- Name -->
                <Input v-model="form.name" label="Template Name" placeholder="Enter Template Name" margin="md"
                    width="full" fonttype="normal" textSize="base" rounded="md" :error="errors.name" required="true" />
                <div v-if="errors.name" class="text-red-600 text-sm">{{ errors.name }}</div>
                <!-- Gender -->
                <div class="flex flex-col">
                    <label class="text-md mb-2">Gender <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label>
                            <input type="radio" name="gender" value="f" v-model="form.gender" class="hidden" />
                            <div :class="[
                                'px-4 py-1 rounded border text-sm cursor-pointer',
                                form.gender === 'f'
                                    ? 'bg-primary text-white'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                            ]">Female</div>
                        </label>
                        <label>
                            <input type="radio" name="gender" value="m" v-model="form.gender" class="hidden" />
                            <div :class="[
                                'px-4 py-1 rounded border text-sm cursor-pointer',
                                form.gender === 'm'
                                    ? 'bg-primary text-white'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                            ]">Male</div>
                        </label>
                    </div>
                </div>
                <div v-if="errors.gender" class="text-red-600 text-sm">{{ errors.gender }}</div>

                <!-- Body Part -->
                <div class="flex flex-col mt-4">
                    <label class="text-md mb-2">Body Part <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label>
                            <input type="radio" name="bodyPart" value="upper" v-model="form.body_part" class="hidden" />
                            <div :class="[
                                'px-4 py-1 rounded border text-sm cursor-pointer',
                                form.body_part === 'upper'
                                    ? 'bg-primary text-white'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                            ]">Upper</div>
                        </label>
                        <label>
                            <input type="radio" name="bodyPart" value="lower" v-model="form.body_part" class="hidden" />
                            <div :class="[
                                'px-4 py-1 rounded border text-sm cursor-pointer',
                                form.body_part === 'lower'
                                    ? 'bg-primary text-white'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                            ]">Lower</div>
                        </label>
                    </div>
                </div>
                <div v-if="errors.body_part" class="text-red-600 text-sm">{{ errors.body_part }}</div>
                <!-- SVG Logo -->
                <Input v-model="form.svg_logo" label="SVG Logo" placeholder="Paste SVG path here" margin="md"
                    width="full" fonttype="normal" textSize="base" rounded="md" :error="errors.svg_logo" />

                <!-- Required Measurements -->
                <h1 class="text-md font-semibold mb-2 mt-4">Measurement Ask:</h1>

                <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 items-center gap-x-16 gap-y-4">
                    <label v-for="measurement in props.measurements.all" :key="measurement.id || measurement.slug"
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

                <div v-if="errors.required_measurements" class="text-red-600 text-sm mt-1">
                    {{ errors.required_measurements }}
                </div>

                <!-- Design Details -->
                <div class="mt-2">
                    <h2 class="text-md font-semibold mb-4">Design Details</h2>

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
                </div>
                <div v-if="errors.design_details" class="text-red-600 text-sm">{{ errors.design_details }}
                </div>


                <!-- Submit Button -->
                <Button @click="updateTemplate" color="primary" textSize="lg" padding="md" rounded="full">
                    Save Changes
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
