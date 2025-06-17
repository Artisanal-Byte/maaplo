<script setup>
import { Icon } from '@iconify/vue';
import Input from '@/components/InputWithLabel.vue';
import { toRefs } from 'vue';

const props = defineProps({
    features: {
        type: Array,
        required: true,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['update:features']);
const { features } = toRefs(props);

const addFeature = () => {
    const newFeatures = [...features.value, ''];
    emit('update:features', newFeatures);
};

const removeFeature = (index) => {
    if (features.value.length > 1) {
        const newFeatures = [...features.value];
        newFeatures.splice(index, 1);
        emit('update:features', newFeatures);
    }
};

const updateFeature = (index, value) => {
    const newFeatures = [...features.value];
    newFeatures[index] = value;
    emit('update:features', newFeatures);
};
</script>

<template>
    <div class="features-section">
        <div class="flex items-center justify-between mb-3">
            <label class="font-medium text-black">Features<span class="text-red-600">*</span></label>

            <!-- Add Feature Button -->
            <button type="button" @click="addFeature"
                class="flex items-center text-green-600 hover:text-green-700 transition" title="Add feature">
                <Icon icon="mdi:plus-circle-outline" width="24" height="24" />
            </button>
        </div>

        <div v-for="(feature, index) in features" :key="index" class="flex items-center gap-2 mb-2">
            <Input type="text" :label="`Feature ${index + 1}`" :modelValue="feature"
                @update:modelValue="val => updateFeature(index, val)" placeholder="Enter feature" class="flex-grow"
                :error="errors?.[`features.${index}`]" required="true">
            <template #icon>
                <Icon icon="material-symbols:add-notes-rounded" width="24" height="24" />
            </template>
            </Input>
            <button v-if="features.length > 1" type="button" @click="removeFeature(index)"
                class="text-red-600 hover:text-red-700 p-1 rounded" title="Remove feature">
                <Icon icon="mdi:minus-circle-outline" width="24" height="24" />
            </button>
        </div>
        <div v-if="errors?.features && typeof errors.features === 'string'" class="text-red-600 text-sm mt-1">
            {{ errors.features }}
        </div>
    </div>
</template>
