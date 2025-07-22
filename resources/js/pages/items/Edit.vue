<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';
import SearchSelect from '@/components/SearchSelect.vue';
import Loader from '@/components/Loader.vue';
import { ref, computed, watch } from 'vue';
const toast = new ToastMagic();

const props = defineProps({
    errors: Object,
    item: Object,
    designDetails: Object,
    publicTemplates: Array,
    privateTemplates: Array,
    measurements: Object
});
const loading = ref(false);
console.log('designDetails', props.designDetails);
console.log('measurements', props.measurements);



const form = useForm({
    name: props.item.name,
    svg_logo: props.item.svg_logo,
    gender: props.item.gender === "Male" ? "m" : (props.item.gender === "Female" ? "f" : "o"),
    body_part: props.item.body_part === "Upper" ? "upper" : "lower",
    required_measurements: props.measurements.selected,
    design_details: {},
    errors: props.errors,
    _method: 'put',
});
console.log('All designDetails:', props.designDetails);
props.designDetails.forEach(detail => {
    form.design_details[detail.id] = props.item.design_details?.includes(detail.id) ?? false;
});

const updateTemplate = () => {
    const trueDesignDetailIds = Object.entries(form.design_details)
        .filter(([_, value]) => value === true)
        .map(([key]) => Number(key));

    if (trueDesignDetailIds.length === 0) {
        toast.error('Please select at least one Design Detail.');
        return;
    }
    const dataToSend = {
        ...form.data(),
        required_measurements: form.required_measurements,
        design_details: form.design_details,
    };
    form.transform(data => {
        loading.value = true;
        return {
            ...dataToSend,
            _method: 'put',
        };
    }).post(route('items.update', props.item.id), {
        onSuccess: () => {
            toast.success('Template updated successfully!');
            setTimeout(() => router.visit(route('items.index')), 1000);
            loading.value = false;
        },
        onError: (errors) => {
            toast.error('Update failed. Please try again.');
            loading.value = false;
        }
    });
};

const fillFormFromTemplate = (selectedTemplate) => {
    form.name = selectedTemplate.name || '';
    form.svg_logo = selectedTemplate.svg_logo || '';
    form.gender = selectedTemplate.gender === 'Male' ? 'm' : (selectedTemplate.gender === 'Female' ? 'f' : '');
    form.body_part = selectedTemplate.body_part === 'Upper' ? 'upper' : (selectedTemplate.body_part === 'Lower' ? 'lower' : '');

    form.required_measurements = Array.isArray(selectedTemplate.measurements)
        ? selectedTemplate.measurements.map(m => m.slug)
        : [];

};

const formatSlug = (slug) => {
    return slug
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

const isSvgMarkup = (str) => {
    if (typeof str !== 'string') return false;
    return str.trim().startsWith('<svg');
};

const isValidPathData = (str) => {
    if (typeof str !== 'string') return false;
    return /^[Mm]/.test(str.trim());
};
const filteredMeasurements = computed(() => {
    return props.measurements.all.filter(m => m.body_part === form.body_part);
});

// Watch body_part changes to reset required_measurements to only those valid for that part
watch(() => form.body_part, (newBodyPart) => {
    // Filter required_measurements to keep only measurements for new body part
    const validSlugs = props.measurements.all
        .filter(m => m.body_part === newBodyPart)
        .map(m => m.slug);

    form.required_measurements = form.required_measurements.filter(slug => validSlugs.includes(slug));
});
const filteredDesignDetails = computed(() => {
    if (!form.gender || !form.body_part) return [];
    return props.designDetails.filter(group => {
        // Normalize values for matching
        const genderMatch = group.gender?.includes(form.gender);
        const bodySectionMatch = group.body_section?.some(section => section.toLowerCase() === form.body_part.toLowerCase());

        return genderMatch && bodySectionMatch;
    });
});



// Optional: if you want to reset or update design_details selections when gender or body_part changes, watch them:
watch([() => form.gender, () => form.body_part], () => {
    // Filter keys of design_details to only those currently visible
    const validDetailIds = filteredDesignDetails.value
        .filter(d => d?.id !== undefined && d?.id !== null)
        .map(d => d.id.toString());

    // Remove keys from form.design_details that are no longer valid
    for (const key in form.design_details) {
        if (!validDetailIds.includes(key)) {
            form.design_details[key] = false;
        }
    }
});
</script>

<template>

    <Head title="Template-Edit" />
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex justify-between items-center">
                <h1
                    class="flex items-center gap-2 lg:gap-4 text-[24px] leading-[16px] font-bold text-gray-800 font-[Convergence] text-primary tracking-[0]">
                    <Icon icon="wpf:edit-file" width="28" height="28" />
                    Edit Template
                </h1>
                <div class="text-gray-600">
                    <Link :href="route('items.index')" class="flex items-center gap-1 hover:text-black">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-[16px] font-medium">Back</span>
                    </Link>
                </div>
            </div>
            <!-- Use the Loader Component -->
            <Loader v-if="loading" :message="'Updating Template...'" />
            <div class="flex flex-col mt-10 gap-3 bg-white lg:p-7 rounded-lg shadow-md p-5 border-t-4 border-primary">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Select Base Template -->
                    <div>
                        <label class="text-md">Select Base Template</label>
                        <SearchSelect class="mt-2" :public-templates="props.publicTemplates"
                            :private-templates="props.privateTemplates" @templateSelected="fillFormFromTemplate" />
                    </div>

                    <!-- Template Name -->
                    <div>
                        <Input v-model="form.name" label="Template Name" placeholder="Enter Template Name" margin="md"
                            width="full" fonttype="normal" textSize="base" rounded="md" :error="form.errors.name"
                            required="true">
                        <template #icon>
                            <Icon icon="tdesign:template-filled" width="18" height="18" class="mt-2" />
                        </template>
                        </Input>
                    </div>

                    <!-- Gender -->
                    <div class="flex flex-col">
                        <label class="text-md mb-2">Gender <span class="text-red-500">*</span></label>
                        <div class="flex gap-4">
                            <label>
                                <input type="radio" name="gender" value="f" v-model="form.gender" class="hidden" />
                                <div :class="[
                                    'px-4 py-1 rounded border text-sm cursor-pointer',
                                    form.gender === 'f' ? 'bg-primary text-white' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                                ]">Female</div>
                            </label>
                            <label>
                                <input type="radio" name="gender" value="m" v-model="form.gender" class="hidden" />
                                <div :class="[
                                    'px-4 py-1 rounded border text-sm cursor-pointer',
                                    form.gender === 'm' ? 'bg-primary text-white' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                                ]">Male</div>
                            </label>
                        </div>
                        <div v-if="form.errors.gender" class="text-red-600 text-sm">{{ form.errors.gender }}</div>
                    </div>

                    <!-- Body Part -->
                    <div class="flex flex-col">
                        <label class="text-md mb-2">Body Part <span class="text-red-500">*</span></label>
                        <div class="flex gap-4">
                            <label>
                                <input type="radio" name="bodyPart" value="upper" v-model="form.body_part"
                                    class="hidden" />
                                <div :class="[
                                    'px-4 py-1 rounded border text-sm cursor-pointer',
                                    form.body_part === 'upper' ? 'bg-primary text-white' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                                ]">Upper</div>
                            </label>
                            <label>
                                <input type="radio" name="bodyPart" value="lower" v-model="form.body_part"
                                    class="hidden" />
                                <div :class="[
                                    'px-4 py-1 rounded border text-sm cursor-pointer',
                                    form.body_part === 'lower' ? 'bg-primary text-white' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                                ]">Lower</div>
                            </label>
                        </div>
                        <div v-if="form.errors.body_part" class="text-red-600 text-sm">{{ form.errors.body_part }}</div>
                    </div>
                </div>


                <!-- Required Measurements -->
                <h1 class="text-md font-semibold mb-2 mt-4">Measurement Ask:</h1>

                <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 items-center gap-x-16 gap-y-4">
                    <label v-for="measurement in filteredMeasurements" :key="measurement.id || measurement.slug"
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
                <div v-if="form.errors.required_measurements" class="text-red-600 text-sm mt-1">{{
                    form.errors.required_measurements
                    }}</div>

                <!-- SVG Logo -->
                <Input v-model="form.svg_logo" label="SVG Logo" placeholder="Paste SVG path here" margin="md"
                    width="full" fonttype="normal" textSize="base" rounded="md" :error="form.errors.svg_logo" />

                <div
                    class="mt-6 border border-gray-300 rounded-lg p-6 bg-gray-50 flex justify-center items-center min-h-[120px]">
                    <label class="sr-only">SVG Preview</label>

                    <!-- Full SVG Markup -->
                    <div v-if="isSvgMarkup(form.svg_logo)" v-html="form.svg_logo" class="max-w-[120px] max-h-[120px]">
                    </div>

                    <!-- Path Only -->
                    <svg v-else-if="isValidPathData(form.svg_logo)" width="120" height="120" viewBox="0 0 100 100"
                        class="stroke-black fill-none">
                        <path :d="form.svg_logo" stroke="black" stroke-width="2" />
                    </svg>

                    <!-- Invalid -->
                    <div v-else class="text-red-500 text-sm">
                        ⚠️ Invalid SVG path or markup. Must start with &lt;svg&gt;.
                    </div>
                </div>

                <!-- Design Details -->
                <div class="mt-2">
                    <h2 class="text-md font-semibold mb-4">Design Details Ask:</h2>

                    <!-- Message if Gender or Body Part not selected -->
                    <div v-if="!form.gender || !form.body_part" class="text-gray-600 italic mb-2">
                        Please select <strong>Gender</strong> and <strong>Body Part</strong> to view design details.
                    </div>

                    <!-- Show design details if both selected -->
                    <div v-else class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="(group, index) in filteredDesignDetails" :key="index"
                            class="flex items-center justify-between bg-gray-50 p-2 rounded-md">
                            <span class="font-normal text-[16px] tracking-normal font-lato">
                                {{ group.body_part }}
                            </span>
                            <div class="flex rounded overflow-hidden text-sm">
                                <button :class="[
                                    'px-4 py-1 focus:outline-none transition',
                                    form.design_details[index] ? 'bg-primary text-white' : 'bg-gray-200 text-black'
                                ]" @click="form.design_details[index] = true">
                                    Yes
                                </button>
                                <button :class="[
                                    'px-4 py-1 focus:outline-none transition',
                                    form.design_details[index] === false ? 'bg-primary text-white' : 'bg-gray-200 text-black'
                                ]" @click="form.design_details[index] = false">
                                    No
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="form.errors.design_details" class="text-red-600 text-sm mt-2">
                        {{ form.errors.design_details }}
                    </div>
                </div>

                <!-- Submit Button -->
                <Button @click="updateTemplate" color="primary" textSize="lg" padding="md" rounded="full">
                    Save Changes
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
