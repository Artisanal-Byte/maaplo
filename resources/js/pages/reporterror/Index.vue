<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { ref, computed } from 'vue'
import Loader from '@/components/Loader.vue'
import Pagination from '@/components/Pagination.vue'

defineProps({ reports: Object })

const showModal = ref(false)
const selectedReport = ref(null)
const page = usePage();
const reportsData = computed(() => page.props.reports?.data || []);
const paginationLinks = computed(() => page.props.reports?.links || []);
const isLoading = ref(false);


function openModal(report) {
    selectedReport.value = report
    showModal.value = true
}

function closeModal() {
    showModal.value = false
    selectedReport.value = null
}


function handlePaginationClick(url) {
    if (!url) return;
    isLoading.value = true;

    router.visit(url, {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
            isLoading.value = false;
        }
    });
}
</script>

<template>

    <Head title="Report Error" />
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-primary flex items-center gap-2 font-[Convergence]">
                    <Icon icon="material-symbols:bug-report-outline" width="28" height="28" />
                    Report an Error
                </h1>
                <Link :href="route('dashboard')" class="flex items-center gap-2 text-gray-600 hover:text-black">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-md font-medium">Back</span>
                </Link>
            </div>

            <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-primary">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-white uppercase">User's</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-white uppercase">Error Type
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-white uppercase">Reported At
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="report in reportsData" :key="report.id" @click="openModal(report)"
                            class="hover:bg-gray-100 cursor-pointer transition-colors duration-150">
                            <td class="px-5 py-3 text-sm font-medium text-gray-800">
                                {{ report.user?.username || report.user?.name || (report.user?.email ?
                                    report.user.email.split('@')[0] : 'Guest') || 'Guest' }}
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ report.error_type }}</td>
                            <td class="px-5 py-3 text-sm text-gray-500">
                                <div>{{ new Date(report.created_at).toLocaleDateString() }}</div>
                                <div class="text-xs text-gray-400">{{ new Date(report.created_at).toLocaleTimeString([],
                                    { hour: '2-digit', minute: '2-digit' }) }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Loader v-if="isLoading" class="mt-4" />

            <Pagination :links="paginationLinks" :onPageClick="handlePaginationClick" class="mt-6" />

            <!-- Modal -->
            <transition name="fade">
                <div v-if="showModal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full overflow-auto max-h-[90vh] p-6 relative">
                        <button @click="closeModal" aria-label="Close modal"
                            class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-3xl font-bold leading-none">&times;</button>

                        <h2 class="text-2xl font-bold mb-6 border-b pb-3">Error Report Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h3 class="font-semibold text-gray-700 mb-1">User:</h3>
                                <p class="text-gray-900">
                                    {{ selectedReport.user?.username || selectedReport.user?.name ||
                                        (selectedReport.user?.email ? selectedReport.user.email.split('@')[0] : 'Guest') ||
                                        'Guest' }}
                                </p>
                            </div>

                            <div>
                                <h3 class="font-semibold text-gray-700 mb-1">Error Type:</h3>
                                <p class="text-gray-900">{{ selectedReport.error_type }}</p>
                            </div>

                            <div>
                                <h3 class="font-semibold text-gray-700 mb-1">Reported At:</h3>
                                <p class="text-gray-700">
                                    <span>{{ new Date(selectedReport.created_at).toLocaleDateString() }}</span><br />
                                    <span class="text-sm text-gray-500">{{ new
                                        Date(selectedReport.created_at).toLocaleTimeString([], {
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        }) }}</span>
                                </p>
                            </div>

                            <div>
                                <h3 class="font-semibold text-gray-700 mb-1">Page URL:</h3>
                                <p>
                                    <a :href="selectedReport.url" target="_blank" rel="noopener noreferrer"
                                        class="text-blue-600 hover:underline break-words">
                                        {{ selectedReport.url || '—' }}
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-700 mb-2">Description:</h3>
                            <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ selectedReport.description
                            }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Screenshot:</h3>
                            <div v-if="selectedReport.screenshot_path" class="flex justify-center">
                                <img :src="`/${selectedReport.screenshot_path}`" alt="Screenshot"
                                    class="max-w-full max-h-[250px] rounded border border-gray-300 object-contain" />
                            </div>
                            <div v-else class="text-gray-400 italic">No screenshot available</div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </AppLayout>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
