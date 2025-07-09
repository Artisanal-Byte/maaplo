<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="text-center">
            <h1 class="text-5xl font-bold text-red-600 mb-4">403</h1>
            <h2 class="text-2xl font-semibold mb-2">Permission Denied</h2>
            <p class="mb-6 text-gray-600">
                You do not have permission to access this page.
            </p>
            <Link href="/dashboard" class="text-blue-600 underline">← Back to Dashboard</Link>
        </div>
    </div>
</template>
