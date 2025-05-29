<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps, ref, computed, watch } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';
import Input from '@/components/InputWithLabel.vue';

const toast = new ToastMagic();

const props = defineProps({
    designDetail: Object,
    errors: Object,
});

const form = useForm({
    body_section: props.designDetail.body_section,
    gender: props.designDetail.gender,
    body_part: props.designDetail.body_part,
    value: props.designDetail.value,
    image: props.designDetail.image,
});

const imagePreview = computed(() => {
    return form.image?.includes('<svg') ? form.image : null;
});

// Watch for SVG and clean it up
watch(() => form.image, (newVal) => {
    if (typeof newVal === 'string' && newVal.includes('<svg')) {
        let updated = newVal;
        updated = updated.replace(/width="[^"]*"/, 'width="50"');
        updated = updated.replace(/height="[^"]*"/, 'height="50"');
        updated = updated.replace(/style="[^"]*?width:\s*\d+[^;]*;?[^"]*?"/, '');
        updated = updated.replace(/style="[^"]*?height:\s*\d+[^;]*;?[^"]*?"/, '');

        if (updated !== newVal) {
            form.image = updated;
        }
    }
});

const isSvgMarkup = (str = '') => {
    return str.trim().startsWith('<svg');
};

const isValidPathData = (str = '') => {
    return /^[Mm]/.test(str.trim());
};

const updateDesignDetail = () => {
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
                            <option value="Upper">Upper</option>
                            <option value="Lower">Lower</option>
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
                    <Input type="text" label="Body Part" v-model="form.body_part" :error="props.errors.body_part"
                        placeholder="Enter body part" required="true">
                    <template #icon>
                        <Icon icon="mdi:human-male-height" width="20" height="20" />
                    </template>
                    </Input>

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
                        ⚠️ Invalid SVG path or markup. Must start with &lt;svg&gt; or `M`.
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <Button @click="updateDesignDetail" :disabled="form.processing" :color="'primary'" :rounded="'full'"
                        :textSize="'md'"
                        class="w-full py-3 font-semibold hover:scale-105 transition-transform duration-300">
                        <Icon icon="mdi:check-bold" width="20" class="mr-2" />
                        Update Design Detail
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
