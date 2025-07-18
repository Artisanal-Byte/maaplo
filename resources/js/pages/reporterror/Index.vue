<script setup>
defineProps({ reports: Array })
</script>

<template>
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Reported Errors</h1>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Screenshot</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Error Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Page URL</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">Reported At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="report in reports" :key="report.id">
                        <!-- Username -->
                        <td class="px-4 py-3 text-sm text-gray-700 font-medium">
                            {{ report.user?.username || report.user?.name || (report.user?.email ?
                                report.user.email.split('@')[0] : 'Guest') || 'Guest' }}
                        </td>

                        <!-- Screenshot thumbnail -->
                        <td class="px-4 py-3">
                            <div v-if="report.screenshot_path">
                                <a :href="`/${report.screenshot_path}`" target="_blank">
                                    <img :src="`/${report.screenshot_path}`" alt="Screenshot"
                                        class="w-20 h-20 object-cover border rounded" />
                                </a>
                            </div>
                            <div v-else class="text-gray-400 italic">No image</div>
                        </td>

                        <!-- Other details -->
                        <td class="px-4 py-3 max-w-xs">
                            <p class="text-gray-800 text-sm">{{ report.description }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ report.error_type }}</td>
                        <td class="px-4 py-3 text-sm text-blue-600">
                            <a :href="report.url" target="_blank" class="hover:underline">
                                {{ report.url || '—' }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ new Date(report.created_at).toLocaleString() }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
