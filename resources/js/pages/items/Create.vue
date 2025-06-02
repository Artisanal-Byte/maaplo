<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import SearchSelect from '@/components/SearchSelect.vue';
import Button from '@/components/Button.vue';

// Import or define ToastMagic instance properly
const toast = new ToastMagic();

const { props: pageProps } = usePage();
const measurements = pageProps.measurements || [];
const designDetails = pageProps.designDetails || [];

const form = useForm({
    name: '',
    gender: '',
    body_part: '',
    required_measurements: [],
    custom_template: false,
    svg_logo: '',
    design_details: {},
});

designDetails.forEach(dd => {
    form.design_details[dd.id] = false;
});
console.log('Design Details:', designDetails);
const uniqueDesignDetails = computed(() => {
    const seen = new Set();
    return designDetails.filter(detail => {
        if (seen.has(detail.body_part)) return false;
        seen.add(detail.body_part);
        return true;
    });
});
console.log('Unique Design Details:', uniqueDesignDetails.value);
const formatSlug = (slug) => {
    return slug
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

const fillFormFromTemplate = (template) => {
    if (!template) return;

    form.name = template.name || '';
    form.gender = template.gender === 'Male' ? 'm' : (template.gender === 'Female' ? 'f' : '');
    form.body_part = template.body_part === 'Upper' ? 'upper' : (template.body_part === 'Lower' ? 'lower' : '');
    form.svg_logo = template.svg_logo || '';
    form.required_measurements = template.measurements?.map(m => m.slug) || [];
};

const isSvgMarkup = (str = '') => {
    return str.trim().startsWith('<svg');
};

const isValidPathData = (str = '') => {
    return /^[Mm]/.test(str.trim());
};

const submitForm = () => {
    form.post(route('items.store'), {
        onSuccess: () => {
            toast.success("Item created successfully!");
            router.visit(route('items.index'));
        },
        onError: () => {
            toast.error("Failed to create Item. Please fill in all the required fields.");
        },
    });
};
</script>

<template>

    <Head title="Template-Create" />
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex justify-between items-center">
                <h1
                    class="flex items-center gap-2 lg:gap-4 text-[24px] leading-[16px] font-bold text-gray-800 font-[Convergence] text-primary tracking-[0]">
                    <Icon icon="ooui:template-add-ltr" width="28" height="28" />
                    Add New Template
                </h1>
                <div class="text-gray-600">
                    <Link :href="route('items.index')" class="flex items-center gap-1 hover:text-black">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-[16px] font-medium">Back</span>
                    </Link>
                </div>
            </div>

            <div class="flex flex-col mt-10 gap-3 bg-white lg:p-7 rounded-lg shadow-md p-5 border-t-4 border-primary">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Select Base Template -->
                    <div>
                        <label class="font-medium">Select Base Template</label>
                        <SearchSelect class="mt-2" :public-templates="pageProps.publicTemplates"
                            :private-templates="pageProps.privateTemplates" @templateSelected="fillFormFromTemplate" />
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
                    <div>
                        <label class="font-medium">Gender <span class="text-red-500">*</span></label>
                        <div class="flex gap-4 mt-2">
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
                    <div>
                        <label class="font-medium">Body Part <span class="text-red-500">*</span></label>
                        <div class="flex gap-4 mt-2">
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

                <!-- Measurements -->
                <h1 class="text-md font-semibold mb-2 mt-4">Measurement Ask :</h1>
                <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 items-center gap-x-16 gap-y-4">
                    <label v-for="measurement in measurements" :key="measurement.id"
                        class="flex items-center gap-3 cursor-pointer rounded w-full">
                        <div v-if="measurement.measurements_logo" class="shrink-0"
                            v-html="measurement.measurements_logo"></div>
                        <div class="flex justify-between items-center lg:w-full w-80">
                            <span class="text-[16px]">{{ formatSlug(measurement.slug) }}</span>
                            <input type="checkbox" :id="measurement.slug" :value="measurement.slug"
                                v-model="form.required_measurements" class="form-checkbox w-4 h-4" />
                        </div>
                    </label>
                </div>
                <div v-if="form.errors.required_measurements" class="text-red-600 text-sm">{{
                    form.errors.required_measurements }}
                </div>


                <!-- Template Logo -->
                <div>
                    <Input class="mt-4" v-model="form.svg_logo" label="Template Logo"
                        placeholder="Only Paste SVG path here" margin="md" width="full" fonttype="normal"
                        textSize="base" rounded="md" :error="form.errors.svg_logo" />
                </div>
                <!-- SVG Preview -->
                <div
                    class="mt-2 border border-gray-300 rounded-lg p-6 bg-gray-50 flex justify-center items-center min-h-[120px]">
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
                        ⚠️ Invalid SVG path or markup. Must start with &lt;svg&gt; or path data starting with M/m.
                    </div>
                </div>

                <!-- Design Details -->
                <div class="mt-2">
                    <h2 class="text-md font-semibold mb-4">Design Details Ask:</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="detail in designDetails" :key="detail.body_part"
                            class="flex items-center justify-between bg-gray-50 p-2 rounded-md">
                            <span class="font-normal text-[16px] tracking-normal font-lato">
                                {{ detail.body_part_value?.body_part || detail.body_part }}
                            </span>
                            <div class="flex rounded overflow-hidden text-sm">
                                <button :class="[
                                    'px-4 py-1 focus:outline-none transition',
                                    form.design_details[detail.id] ? 'bg-primary text-white' : 'bg-gray-200 text-black'
                                ]" @click="form.design_details[detail.id] = true">
                                    Yes
                                </button>
                                <button :class="[
                                    'px-4 py-1 focus:outline-none transition',
                                    form.design_details[detail.id] === false ? 'bg-primary text-white' : 'bg-gray-200 text-black'
                                ]" @click="form.design_details[detail.id] = false">
                                    No
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="form.errors.design_details" class="text-red-600 text-sm mt-2">{{
                        form.errors.design_details }}
                    </div>
                </div>

                <Button @click="submitForm" color="primary" padding="md" rounded="full" textSize="sm"
                    class="lg:mt-5 mt-3">
                    Save
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
