<script setup>
defineProps();
</script>

<template>
    <div
        class="fixed inset-0 z-50 bg-black bg-opacity-50 backdrop-blur-xl flex items-center justify-center pointer-events-auto">
        <div class="text-center animate-fade-in flex flex-col items-center space-y-4">
            <!-- Loader Circle with gradient and shadow effects -->
            <div class="relative">
                <div
                    class="h-24 w-24 border-8 border-t-8 border-gradient-to-r from-blue-400 via-purple-500 to-pink-500 border-t-transparent rounded-full animate-spin">
                </div>
            </div>

            <!-- Text Message with animation -->
            <p class="mt-4 text-white font-semibold text-lg animate-slide-up opacity-90">
                Updating order, please wait...
            </p>
        </div>
    </div>
</template>

<style>
@keyframes fade-in {
    0% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}

@keyframes slide-up {
    0% {
        transform: translateY(10px);
        opacity: 0;
    }

    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.6s ease-out;
}

.border-gradient-to-r {
    background: linear-gradient(45deg, #4299e1, #9f7aea, #f56c9e);
    -webkit-mask: linear-gradient(45deg, transparent 20%, rgba(0, 0, 0, 1) 80%);
    mask: linear-gradient(45deg, transparent 20%, rgba(0, 0, 0, 1) 80%);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
}
</style>
