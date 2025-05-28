<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
    organizations: {
        type: Array,
        required: true
    }
});
const organizations = props.organizations;
const showModal = ref(false);

// Automatically show modal if no organizations
onMounted(() => {
    if (!organizations.length) {
        showModal.value = true;
    }
});
</script>

<template>
    <AppLayout>
        <div class="max-w-6xl mx-auto p-6">
            <h1 class="text-4xl font-bold text-gray-800 mb-10">Organizations</h1>

            <!-- Organizations List -->
            <div v-if="organizations.length" class="grid gap-6">
                <div v-for="org in organizations" :key="org.id"
                    class="flex flex-col md:flex-row items-center justify-between bg-white border shadow-sm rounded-lg p-6 hover:shadow-md transition">
                    <!-- Info Section -->
                    <div class="flex-1 w-full md:w-auto">
                        <h2 class="text-2xl font-semibold text-primary mb-2">{{ org.organization_name }}</h2>
                        <p class="text-gray-600 text-sm mb-1"><strong>GST:</strong> {{ org.gst_number || '-' }}</p>
                        <p class="text-gray-600 text-sm"><strong>Address:</strong> {{ org.address || '-' }}</p>
                    </div>

                    Logo Section
                    <div class="w-28 h-28 flex-shrink-0 mt-4 md:mt-0 md:ml-6">
                        <img :src="org.organization_logo ? `/storage/${org.organization_logo.replace(/^storage\//, '')}` : '/images/organization.png'"
                            alt="Organization Logo"
                            class="w-full h-full object-contain border rounded-lg bg-gray-50 p-2" />
                    </div>
                    <!-- <div v-if="props.user.organization_logo" class="pt-4">
                            <p class="font-semibold text-gray-700 mb-2">Organization Logo:</p>
                            <img :src="`/storage/${props.user.organization_logo.replace(/^storage\//, '')}`"
                                alt="Organization Logo" class="w-36 h-auto rounded shadow border" />
                        </div> -->

                    <!-- Actions -->
                    <div class="mt-4 md:mt-0 md:ml-6">
                        <Link :href="route('organization.edit', org.id)"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        <Icon icon="mdi:pencil" class="mr-1" />
                        Edit
                        </Link>
                    </div>
                </div>
            </div>

            <!-- No Organizations -->
            <div v-else class="text-center mt-12">
                <p class="text-gray-600 mb-4">No organization data available.</p>
                <Link :href="route('organization.create')"
                    class="inline-flex items-center px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                <Icon icon="mdi:plus" class="mr-2" />
                Add Organization
                </Link>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-lg text-center">
                <h2 class="text-xl font-bold mb-4">No Organization Found</h2>
                <p class="text-gray-600 mb-6">Please add your organization information to continue.</p>
                <div class="flex justify-center space-x-4">
                    <Link :href="route('organization.create')"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Add Organization
                    </Link>
                    <button @click="showModal = false"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
