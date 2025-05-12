<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';

const toast = new ToastMagic();
const { item } = usePage().props;

const measurements = [
    'Length', 'Arms', 'Back Neck', 'Waist', 'Sleeve Circle',
    'Chest', 'Sleeve Length', 'Shoulder', 'Seat', 'Front Neck'
];

const form = reactive({
    name: item.name,
    gender: item.gender,
    body_part: item.body_part,
    svg_logo: item.svg_logo ?? '',
    required_measurements: item.required_measurements ?? [],
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
    router.put(route('items.update', item.id), form, {
        onSuccess: () => {
            toast.success("Item updated successfully!");
            router.visit(route('items.index'));
        },
        onError: (error) => {
            toast.error("Failed to update item. Please fix errors and try again.");
            console.error(error);
        },
    });
};
</script>

<template>
    <AppLayout>
        <div class="px-4 py-8 max-w-6xl mx-auto">
            <div>
                <h1 class="text-[24px] leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                    Edit Template
                </h1>
            </div>

            <div class="flex flex-col mt-10 gap-4 rounded-lg border border-primary p-4">

                <!-- Name -->
                <Input v-model="form.name" label="Template Name" placeholder="Enter Template Name" margin="md"
                    width="full" fonttype="normal" textSize="base" rounded="md" error="" />

                <!-- Gender -->
                <div class="flex flex-col">
                    <label class="text-md mb-2">Gender:</label>
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

                <!-- Body Part -->
                <div class="flex flex-col mt-4">
                    <label class="text-md mb-2">Body Part:</label>
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

                <!-- SVG Logo -->
                <Input v-model="form.svg_logo" label="SVG Logo" placeholder="Paste SVG path here" margin="md"
                    width="full" fonttype="normal" textSize="base" rounded="md" error="" />

                <!-- Required Measurements -->
                <div>
                    <label class="text-md mb-2">Required Measurements:</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div v-for="measurement in measurements" :key="measurement">
                            <Input type="checkbox" :label="measurement"
                                :modelValue="form.required_measurements.includes(measurement)" @update:modelValue="checked => {
                                    if (checked && !form.required_measurements.includes(measurement)) {
                                        form.required_measurements.push(measurement);
                                    } else if (!checked) {
                                        form.required_measurements = form.required_measurements.filter(m => m !== measurement);
                                    }
                                }" width="sm" error="" />
                        </div>
                    </div>
                </div>


                <!-- Submit Button -->
                    <Button @click="submitForm" color="primary" textSize="lg" padding="md" rounded="full">
                        Save Changes
                    </Button>
            </div>
        </div>
    </AppLayout>
</template>
