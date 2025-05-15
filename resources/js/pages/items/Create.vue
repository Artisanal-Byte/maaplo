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
    publicTemplates: Array<{ id: number; name: string }>;
    measurements: Array<{ id: number; slug: string; }>;
}>();
const form = reactive({
    name: '',
    gender: '',
    body_part: '',
    required_measurements: [] as string[],
    custom_template: false,
    svg_logo: '',
});

const toggleMeasurement = (label: string) => {
    const index = form.required_measurements.indexOf(label);
    if (index > -1) {
        form.required_measurements.splice(index, 1);
    } else {
        form.required_measurements.push(label);
    }
};

const submitForm = () => {
    router.post(route('items.store'), form, {
        onSuccess: () => {
            toast.success("Item created successfully!");
            router.visit(route('items.index'));
        },
        onError: (error) => {
            toast.error("Failed to create Item. Please fill in all the required fields." + error);
            console.error(error);
        },
    });
};

const formatSlug = (slug: string): string => {
    return slug
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};
</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
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
            <div class="flex flex-col lg:mt-5 gap-3 mt-10 lg:gap-4 rounded-lg lg:border lg:border-primary p-0 lg:p-4">
                <!-- <h1>Select Base Template</h1> -->
                <div>
                    <label class="text-md">Select Base Template</label>
                    <SearchSelect class="mt-2" :public-templates="props.publicTemplates" />
                </div>
                <div>
                    <Input v-model="form.name" label="Template Name" placeholder="Enter Template Name" margin="md"
                        width="full" fonttype="normal" textSize="base" rounded="md" error="" />
                </div>
                <div class="mt-2 lg:mt-4 flex flex-row gap-2 gap-4">
                    <div>
                        <h1 class="lg:mb-2 text-md">Gender:</h1>
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


                <div class="mt-2 lg:mt-4 flex flex-row gap-2 gap-4">
                    <div>
                        <h1 class="lg:mb-2 text-md">Body Part:</h1>
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
                <div>
                    <Input v-model="form.svg_logo" label="SVG Logo" placeholder="Paste SVG path here" margin="md"
                        width="full" fonttype="normal" textSize="base" rounded="md" :error="errors.svg_logo" />
                </div>
                <!--Measurements -->
                <div class="mt-4">
                    <label class="text-md mb-2 block">Required Measurements:</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div v-for="measurement in props.measurements" :key="measurement.id"
                            class="flex items-center gap-2">
                            <input type="checkbox" :id="measurement.slug" :value="measurement.slug"
                                v-model="form.required_measurements" class="form-checkbox" />
                            <label :for="measurement.slug">{{ formatSlug(measurement.slug) }}</label>
                        </div>
                    </div>
                </div>
                <div v-if="errors.required_measurements" class="text-red-600 text-sm">{{ errors.required_measurements }}
                </div>



                <Button @click="submitForm" color="primary" textSize="lg" padding="md" rounded="full">
                    Save
                </Button>

            </div>

        </div>
    </AppLayout>
</template>
