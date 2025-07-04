<script setup>
const modelValue = defineModel()       // for default v-model
const model = defineModel('model')     // for v-model:model
</script>

<template>
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" :checked="model ?? modelValue" @change="(e) => {
            if (model !== undefined) model = e.target.checked;
            if (modelValue !== undefined) modelValue = e.target.checked;
        }" class="sr-only peer" />
        <div
            class="w-11 h-6 bg-gray-300 rounded-full peer dark:bg-gray-700 peer-checked:bg-primary transition duration-300">
        </div>
        <div
            class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-300 transform peer-checked:translate-x-full">
        </div>
    </label>
</template>
