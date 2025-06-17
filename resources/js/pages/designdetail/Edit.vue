<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps, ref, computed, watch } from 'vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';

const toast = new ToastMagic();

const props = defineProps({
    designDetail: Object,
    errors: Object,
    bodyParts: Array,
});

const existingPart = props.bodyParts.find(part => part.id === props.designDetail.body_part_id);

const form = useForm({
    body_section: props.designDetail.body_section,
    gender: props.designDetail.gender,
    body_part_id: existingPart ? existingPart.id : '',
    new_body_part: existingPart ? '' : (props.designDetail.body_part_value?.body_part || ''),
    value: props.designDetail.value,
    image: props.designDetail.image,
});


// Watch for SVG and clean it up
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

const updateDesignDetail = () => {
    if (!form.body_part_id && !form.new_body_part) {
        toast.error('Please select an existing Body Part or enter a new one.');
        return;
    }
    form.transform(data => ({
        ...data,
        _method: 'put',
    })).post(route('design-details.update', props.designDetail.id), {
        onSuccess: () => toast.success('Design Detail updated successfully!'),
        onError: () => toast.error('Update failed. Please check the form.'),
    });
};
</script>

<template>

    <Head title="DesignDetail-Edit" />
    <AppLayout>
        <div class="px-6 py-10 max-w-5xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-10">
                <h1 class="text-4xl font-bold text-primary flex items-center gap-3">
                    <Icon icon="mdi:vector-square-edit" width="32" height="32" />
                    Edit Design Detail
                </h1>
                <Link :href="route('design-details.index')"
                    class="flex items-center gap-2 text-gray-500 hover:text-primary transition">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white p-8 rounded-xl shadow space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Body Section -->
                    <div>
                        <label for="body_section" class="block text-sm font-semibold text-gray-800 mb-1">
                            Body Section <span class="text-red-500">*</span>
                        </label>
                        <select id="body_section" v-model="form.body_section" required
                            class="w-full border rounded-md py-2 px-3 text-sm shadow-sm focus:ring-primary focus:border-primary">
                            <option disabled value="">Select body section</option>
                            <option value="Upper">Upper Body</option>
                            <option value="Lower">Lower Body</option>
                        </select>
                        <div v-if="props.errors.body_section" class="text-red-600 text-sm mt-1">
                            {{ props.errors.body_section }}
                        </div>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-800 mb-1">
                            Gender <span class="text-red-500">*</span>
                        </label>
                        <select id="gender" v-model="form.gender" required
                            class="w-full border rounded-md py-2 px-3 text-sm shadow-sm focus:ring-primary focus:border-primary">
                            <option value="m">Male</option>
                            <option value="f">Female</option>
                            <option value="o">Other</option>
                        </select>
                        <div v-if="props.errors.gender" class="text-red-600 text-sm mt-1">
                            {{ props.errors.gender }}
                        </div>
                    </div>

                    <!-- Body Part -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">
                            Body Part <span class="text-red-500">*</span>
                        </label>
                        <select v-model="form.body_part_id" :disabled="!!form.new_body_part"
                            class="w-full border rounded-md py-2 px-3 text-sm shadow-sm focus:ring-primary focus:border-primary">
                            <option value="">-- Select --</option>
                            <option v-for="part in props.bodyParts" :key="part.id" :value="part.id">
                                {{ part.body_part }}
                            </option>
                        </select>
                        <div class="text-gray-500 text-sm mt-1">Or enter a new body part below:</div>
                        <Input type="text" v-model="form.new_body_part" placeholder="New body part"
                            :disabled="!!form.body_part_id" :error="props.errors.body_part" class="mt-2">
                        <template #icon>
                            <Icon icon="mdi:human-male-height" width="20" height="20" />
                        </template>
                        </Input>

                    </div>

                    <!-- Value -->
                    <Input type="text" label="Value" v-model="form.value" :error="props.errors.value"
                        placeholder="Enter value" required="true">
                    <template #icon>
                        <Icon icon="mdi:tag-text" width="20" height="20" />
                    </template>
                    </Input>
                </div>

                <!-- SVG Image Field -->
                <Input type="textarea" label="SVG Path or Markup" v-model="form.image" :error="props.errors.image"
                    placeholder="Paste SVG path or full markup" rows="5" required="true">
                <template #icon>
                    <Icon icon="mdi:vector-line" width="20" height="20" />
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
                <div class="mt-8">
                    <Button @click="updateDesignDetail" :disabled="form.processing" :rounded="'full'" :textSize="'md'"
                        class="w-full h-4 py-6 font-semibold flex items-center justify-center gap-3 bg-primary text-white">
                        <Icon icon="mdi:check-bold" width="24" height="24" />
                        <span>Update Design Detail</span>
                    </Button>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
