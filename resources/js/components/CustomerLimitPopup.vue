<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    show: Boolean,
    planTitle: {
        type: String,
        default: 'Free',
    },
    planLimit: {
        type: Number,
        default: 5,
    },
});

const emit = defineEmits(['close']);

function goToUpgrade() {
    window.location.href = '/#pricing';
}

function closeAndRedirect() {
    emit('close'); // Close modal
    router.visit(route('customers.index')); // Redirect
}
</script>

<template>
    <div v-if="props.show"
        class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white mx-4 rounded-xl shadow-lg max-w-md w-full p-6 text-center relative animate-blink-loop">
            <button @click="closeAndRedirect"
                class="absolute top-2 right-5 text-gray-400 hover:text-gray-700 text-4xl font-semibold">
                &times;
            </button>

            <div class="flex justify-center mb-4">
                <div class="bg-red-100 text-red-600 w-14 h-14 flex items-center justify-center rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>
                </div>
            </div>

            <h2 class="text-xl font-bold text-red-600 mb-2">Limit Reached</h2>
            <p class="text-gray-700 mb-2">
                You’re currently on a <strong class="text-primary">{{ props.planTitle }}</strong> plan and can create
                up
                to
                <strong>{{ props.planLimit }} customers</strong> only.
            </p>
            <p class="text-gray-600 mb-6">To add more customers, please upgrade your subscription.</p>

            <button @click="goToUpgrade"
                class="bg-primary text-white font-semibold px-5 py-2 rounded-full shadow transition">
                Upgrade Plan
            </button>
        </div>
    </div>
</template>

<style>
@keyframes blink-loop {

    0%,
    100% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.03);
    }
}

.animate-blink-loop {
    animation: blink-loop 1s ease-in-out infinite;
}
</style>
