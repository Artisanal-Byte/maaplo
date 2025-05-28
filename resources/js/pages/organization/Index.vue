<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    organizations: {
        type: Array,
        required: true
    }
});
const showModal = ref(false);

// Auto open modal if no orgs
onMounted(() => {
    if (!props.organizations.length) {
        showModal.value = true;
    }
});
</script>

<template>
    <AppLayout>
        <div class="px-4 py-10 max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div class="flex ">
                    <Icon icon="mdi:office-building" class="text-primary" width="36" height="36" />
                    <h1 class="text-3xl font-bold text-primary">Organization</h1>
                </div>
                <Link :href="route('dashboard')" class="flex items-center gap-2 text-gray-500 hover:text-gray-800">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-base font-medium">Back</span>
                </Link>
            </div>

            <div v-if="props.organizations.length" class="space-y-6">
                <div v-for="org in props.organizations" :key="org.id"
                    class="bg-[#DEEFF4] shadow-md border border-gray-100 rounded-xl p-6 hover:shadow-lg transition">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left: Details -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <Icon icon="mdi:badge-account-outline" class="text-gray-500" width="22" />
                                <p><strong>Name:</strong> {{ org.organization_name }}</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <Icon icon="mdi:certificate-outline" class="text-gray-500" width="22" />
                                <p><strong>GST:</strong> {{ org.gst_number || '—' }}</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <Icon icon="mdi:map-marker-outline" class="text-gray-500" width="22" />
                                <p><strong>Address:</strong> {{ org.address || '—' }}</p>
                            </div>

                            <div class="pt-3">
                                <Link :href="route('organization.edit', org.id)"
                                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded hover:bg-primary-dark transition">
                                <Icon icon="mdi:pencil" class="mr-1" width="18" />
                                Edit
                                </Link>
                            </div>
                        </div>

                        <!-- Right: Logo -->
                        <div class="w-full text-right">
                            <img :src="org.organization_logo
                                ? `/storage/${org.organization_logo.replace(/^storage\//, '')}`
                                : '/images/organization.png'" alt="Organization Logo"
                                class="lg:w-48 lg:h-48 w-24 h-24 mr-0 lg:mr-12 mt-3 inline-block rounded-md object-cover border" />
                        </div>
                    </div>

                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center mt-12">
                <p class="text-gray-600 mb-4 text-lg">No organization data available.</p>
                <Link :href="route('organization.create')"
                    class="inline-flex items-center px-5 py-2 bg-primary text-white rounded hover:bg-primary-dark transition">
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
                        class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-700">
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
