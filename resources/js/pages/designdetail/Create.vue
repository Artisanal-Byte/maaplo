<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, watch, onMounted, defineProps } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';

const toast = new ToastMagic();
const form = useForm({
    body_section: '',
    gender: '',
    body_part_id: '',
    new_body_part: '',
    value: '',
    image: '',
});

const props = defineProps({
    bodyParts: Array,
});
const existingBodyParts = ref(props.bodyParts || []);


onMounted(() => {
    // Fetch existing body parts from backend API route or via props
    fetch('web/body-parts')  // create this API route to return all body parts
        .then(res => res.json())
        .then(data => existingBodyParts.value = data);
});

watch(() => form.image, (newVal) => {
    if (newVal?.includes('<svg')) {
        let updated = newVal;

        // Replace width/height if they exist
        updated = updated.replace(/width="[^"]*"/, 'width="50"');
        updated = updated.replace(/height="[^"]*"/, 'height="50"');

        // Add width/height if missing
        if (!/width="/.test(updated)) {
            updated = updated.replace('<svg', '<svg width="50"');
        }
        if (!/height="/.test(updated)) {
            updated = updated.replace('<svg', '<svg height="50"');
        }

        if (updated !== newVal) {
            form.image = updated;
        }
    }
});


watch(() => form.body_part_id, (newVal) => {
    if (newVal) form.new_body_part = '';
});

watch(() => form.new_body_part, (newVal) => {
    if (newVal) form.body_part_id = '';
});


const isSvgMarkup = (str = '') => {
    return str.trim().startsWith('<svg');
};

const isValidPathData = (str = '') => {
    return /^[Mm]/.test(str.trim());
};

const createDesignDetail = () => {
    if (!form.body_part_id && !form.new_body_part) {
        toast.error('Please select an existing Body Part or enter a new one.');
        return;
    }
    if (form.image?.includes('<svg')) {
        form.image = form.image
            .replace(/width="[^"]*"/, 'width="50"')
            .replace(/height="[^"]*"/, 'height="50"');
    }
    form.post(route('design-details.store'), {
        onSuccess: () => toast.success('Design Detail created successfully!'),
        onError: () => toast.error('Error creating Design Detail.'),
    });
};
</script>

<template>

    <Head title="DesignDetail-Create" />
    <AppLayout>
        <div class="px-6 py-10 max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-10">
                <h1 class="lg:text-3xl text-xl font-extrabold text-primary flex items-center gap-3">
                    <Icon icon="mdi:vector-square-edit" width="28" height="28" />
                    Create Design Detail
                </h1>
                <Link :href="route('design-details.index')"
                    class="flex items-center gap-2 text-gray-500 hover:text-primary transition-colors duration-200">
                <Icon icon="material-symbols:arrow-back-rounded" width="26" height="26" />
                <span class="font-semibold text-lg">Back</span>
                </Link>
            </div>

            <!-- Form -->
            <div class="bg-white lg:p-7 p-5 rounded-xl shadow-lg space-y-8 border-primary border-t-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Body Section -->
                    <div>
                        <label for="body_section" class="block text-lg font-semibold text-gray-800 mb-2">
                            Body Section <span class="text-red-600">*</span>
                        </label>
                        <select id="body_section" v-model="form.body_section"
                            class="w-full rounded-md border border-primary px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            required="true">
                            <option disabled value="">Select body section</option>
                            <option value="Upper">Upper Body</option>
                            <option value="Lower">Lower Body</option>
                        </select>
                        <p v-if="form.errors.body_section" class="mt-1 text-sm text-red-600">{{ form.errors.body_section
                            }}</p>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-lg font-semibold text-gray-800 mb-2">
                            Gender <span class="text-red-600">*</span>
                        </label>
                        <select id="gender" v-model="form.gender"
                            class="w-full rounded-md border border-primary px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            required>
                            <option disabled value="">Select Gender</option>
                            <option value="m">Male</option>
                            <option value="f">Female</option>
                            <option value="o">Other</option>
                        </select>
                        <p v-if="form.errors.gender" class="mt-1 text-sm text-red-600">{{ form.errors.gender }}</p>
                    </div>

                    <!-- Body Part Dropdown -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Select Existing Body Part <span
                                class="text-red-500">*</span></label>
                        <select v-model="form.body_part_id" :disabled="form.new_body_part.length > 0"
                            class="w-full border border-primary rounded-md py-3 mt-1 px-3 text-sm shadow-sm focus:ring-primary focus:border-primary">
                            <option value="">-- Select Body Part --</option>
                            <option v-for="part in existingBodyParts" :key="part.id" :value="part.id">
                                {{ part.body_part }}
                            </option>
                        </select>

                        <div class="text-gray-500 text-sm mt-1">Or enter a new body part below:</div>

                        <!-- New Body Part Text Input -->
                        <label class="block text-lg font-semibold">New Body Part</label>
                        <Input type="text" v-model="form.new_body_part" :disabled="form.body_part_id !== ''"
                            placeholder="Enter new body part" class="mt-2">
                        <template #icon>
                            <Icon icon="mdi:human-male-height" width="20" height="20" />
                        </template>
                        </Input>
                        <p v-if="form.errors.new_body_part" class="text-red-600 text-sm mt-1">
                            {{ form.errors.new_body_part }}
                        </p>


                    </div>
                    <!-- Value -->
                    <Input type="text" label="Value" v-model="form.value" :error="form.errors.value"
                        placeholder="Enter value" required="true">
                    <template #icon>
                        <Icon icon="mdi:tag-text" width="22" height="22" />
                    </template>
                    </Input>
                </div>

                <!-- SVG Path -->
                <Input type="textarea" label="SVG Path or full SVG markup" v-model="form.image"
                    :error="form.errors.image" placeholder="Paste your SVG path or full SVG markup here" required="true"
                    rows="5" class="w-22 h-22 mt-4">
                <template #icon>
                    <Icon icon="mdi:vector-line" width="22" height="22" />
                </template>
                </Input>

                <!-- SVG Preview -->
                <div
                    class="mt-6 border border-gray-300 rounded-lg p-6 bg-gray-50 flex justify-center items-center min-h-[120px]">
                    <label class="sr-only">SVG Preview</label>

                    <!-- Full SVG Markup -->
                    <div v-if="isSvgMarkup(form.image)" v-html="form.image" class="max-w-[120px] max-h-[120px]"></div>

                    <!-- Path Only -->
                    <svg v-else-if="isValidPathData(form.image)" width="120" height="120" viewBox="0 0 100 100"
                        class="stroke-black fill-none">
                        <path :d="form.image" stroke="black" stroke-width="2" />
                    </svg>

                    <!-- Invalid -->
                    <div v-else class="text-red-500 text-sm">
                        ⚠️ Invalid SVG path or markup. Must start with &lt;svg&gt;.
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <Button @click="createDesignDetail" :color="'primary'" :rounded="'full'" :textSize="'md'"
                        class="w-full h-4 py-6 font-semibold flex items-center justify-center gap-3 bg-primary text-white"
                        :disabled="form.processing">
                        <Icon icon="mdi:check-bold" width="22" class="mr-3" />
                        Save Design Detail
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
