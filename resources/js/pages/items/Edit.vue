<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import Input from '@/components/InputWithLabel.vue';
import Button from '@/components/Button.vue';

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
    measurements: {
        slug: string[]; // JSON-encoded array of strings
    }
}>();

const form = useForm({
    name: props.item.name,
    svg_logo: props.item.svg_logo,
    gender: props.item.gender === "Male" ? "m" : (props.item.gender === "Female" ? "f" : "o"),
    body_part: props.item.body_part === "Upper" ? "upper" : "lower",
    required_measurements: JSON.parse(props.measurements.slug),
});

const allMeasurements = [
    'Length', 'Arms', 'Back Neck', 'Waist', 'Sleeve Circle',
    'Chest', 'Sleeve Length', 'Shoulder', 'Seat', 'Front Neck'
];

const toggleMeasurement = (label: string) => {
    // Instead of mutating the array directly, create a new array
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
        required_measurements: JSON.stringify(form.required_measurements)
    };

    form.transform(data => ({
        ...dataToSend,
        _method: 'put',
    })).post(route('items.update', props.item.id), {
        onSuccess: () => {
            toast.success('Template updated successfully!');
        },
        onError: () => {
            toast.error('Update failed. Please try again.');
        }
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
                                form.gender === 'Female'
                                    ? 'bg-primary text-white'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                            ]">Female</div>
                        </label>
                        <label>
                            <input type="radio" name="gender" value="m" v-model="form.gender" class="hidden" />
                            <div :class="[
                                'px-4 py-1 rounded border text-sm cursor-pointer',
                                form.gender === 'Male'
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
                                form.body_part === 'Upper'
                                    ? 'bg-primary text-white'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
                            ]">Upper</div>
                        </label>
                        <label>
                            <input type="radio" name="bodyPart" value="lower" v-model="form.body_part" class="hidden" />
                            <div :class="[
                                'px-4 py-1 rounded border text-sm cursor-pointer',
                                form.body_part === 'Lower'
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
                        <div v-for="measurement in allMeasurements" :key="measurement" class="flex items-center gap-2">
                            <input type="checkbox" :id="measurement" :value="measurement"
                                :checked="form.required_measurements.includes(measurement)"
                                @change="toggleMeasurement(measurement)" />
                            <label :for="measurement">{{ measurement }}</label>
                        </div>
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
