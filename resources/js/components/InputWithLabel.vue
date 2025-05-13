<script setup>
import { defineProps, defineModel } from 'vue'

const props = defineProps([
    'type',
    'id',
    'name',
    'color',
    'padding',
    'margin',
    'rounded',
    'fullWidth',
    'textSize',
    'disabled',
    'placeholder',
    'modelValue',
    'width',
    'fonttype',
    'label',
    'radioValue',
    'error',
    'required',
    'icon'
])

const model = defineModel('modelValue');

// Color classes
const colorClass = {
    primary: 'bg-primary border border-primary text-black focus:border-primary',
    gray: 'border text-gray-800 focus:border-gray-500',
    grayBorder: 'border border-gray-600 text-gray-800 focus:border-gray-500',
}[props.color] ?? 'bg-white border border-gray-300 text-black'

// Padding classes
const paddingClass = {
    sm: 'py-1 px-3',
    md: 'py-2 px-4',
    lg: 'py-3 px-6',
}[props.padding] ?? 'py-2 px-4'

// Rounded classes
const roundedClass = {
    none: '',
    sm: 'rounded-sm',
    md: 'rounded-md',
    lg: 'rounded-lg',
    full: 'rounded-full',
}[props.rounded] ?? 'rounded-md'

// Text size classes
const textSizeClass = {
    xs: 'text-xs',
    sm: 'text-sm',
    base: 'text-base',
    lg: 'text-lg',
    xl: 'text-xl',
}[props.textSize] ?? ''

// Margin class
const marginClass = {
    sm: 'mt-1',
    md: 'mt-2',
    lg: 'mt-4',
}[props.margin] ?? ''

const widthClass = {
    sm: 'w-4',
    md: 'w-20',
    lg: 'w-80',
    full: 'w-full',
}[props.width] ?? ''
</script>

<template>
    <div class="flex flex-col" :class="{ 'flex-row space-x-2': type === 'radio' }">
        <!-- RADIO BUTTON -->

        <template v-if="type === 'radio'">
            <div class="flex gap-2">
                <input :type="type" :id="id" :name="name" :value="radioValue" v-model="model" :disabled="disabled"
                    :class="[
                        'transition duration-200 focus:outline-none accent-[#167893]',
                        colorClass,
                        paddingClass,
                        roundedClass,
                        textSizeClass,
                        marginClass,
                        widthClass,
                        disabled ? 'opacity-50 cursor-not-allowed' : ''
                    ]" />
                <label :for="id" class="text-sm">{{ label }}</label>
            </div>
        </template>

        <template v-else-if="type === 'checkbox'">
            <div class="flex gap-2">
                <input :type="type" :id="id" :name="name" :value="radioValue" v-model="model" :disabled="disabled"
                    :class="[
                        'transition duration-200 focus:outline-none',
                        colorClass,
                        paddingClass,
                        roundedClass,
                        textSizeClass,
                        marginClass,
                        widthClass,
                        'accent-[#167893]',
                        disabled ? 'opacity-50 cursor-not-allowed' : ''
                    ]" />
                <label :for="id" class="text-md">{{ label }}</label>
            </div>
        </template>
        <!-- TEXTAREA -->
        <template v-else-if="type === 'textarea'">
            <label v-if="label" :for="id" :class="[textSizeClass, fonttype]">
                {{ label }}
                <span v-if="required" class="text-red-500 text-lg">*</span>
                <span v-else class="text-gray-400 text-sm">(optional)</span>
            </label>
            <textarea :id="id" :name="name" :placeholder="placeholder" :disabled="disabled" v-model="model" :class="[
                'transition duration-200 focus:outline-none resize-none',
                colorClass,
                paddingClass,
                roundedClass,
                textSizeClass,
                marginClass,
                widthClass,
                fullWidth ? 'w-full' : '',
                disabled ? 'opacity-50 cursor-not-allowed' : ''
            ]" rows="2" />
        </template>

        <!-- INPUT (text, email, etc.) -->
        <template v-else>
            <div class="relative">
                <label v-if="label" :for="id" :class="[textSizeClass, fonttype]">
                    {{ label }}
                    <span v-if="required" class="text-red-500 text-lg">*</span>
                    <span v-else class="text-gray-400 text-sm">(optional)</span>
                </label>
                <div class="relative">
                    <input :type="type" :id="id" :name="name" :placeholder="placeholder" :disabled="disabled"
                        v-model="model" :class="[
                            'transition duration-200 focus:outline-none w-full',
                            colorClass,
                            paddingClass,
                            roundedClass,
                            textSizeClass,
                            marginClass,
                            widthClass,
                            fullWidth ? 'w-full' : '',
                            disabled ? 'opacity-50 cursor-not-allowed' : ''
                        ]" />

                    <!-- Icon slot (optional) -->
                    <div class="absolute top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                        <slot name="icon" />
                    </div>
                </div>
                <slot />
            </div>
        </template>


        <!-- ERROR MESSAGE -->
        <p v-if="error" class="text-red-600 text-sm mt-1">{{ error }}</p>
    </div>
</template>
