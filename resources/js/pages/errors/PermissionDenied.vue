<script setup>
import { Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-100 via-white to-purple-100">
        <div
            class="backdrop-blur-xl bg-white/70 shadow-2xl rounded-2xl px-10 py-12 max-w-md text-center border border-white/40 animate-fade-in">

            <div class="flex justify-center mb-6">
                <Icon icon="fluent:error-circle-24-filled" class="text-red-500 drop-shadow-md" width="72" height="72" />
            </div>

            <h1 class="text-7xl font-extrabold text-red-600 mb-4 drop-shadow-sm">403</h1>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Access Denied</h2>
            <p class="text-red-600 mb-6">
                Sorry, you don’t have permission to view this page.
            </p>

            <Link href="/dashboard"
                class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-medium rounded-lg shadow-lg transition-all duration-200">
            <Icon icon="mdi:arrow-left" class="text-white" width="20" height="20" />
            Back to Dashboard
            </Link>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}
</style>
