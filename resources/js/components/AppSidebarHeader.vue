<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>();
const page = usePage();
const user = computed(() => page.props.auth?.user);
const avatarUrl = computed(() => user.value?.avatar
    ? `/${user.value.avatar}`
    : '/images/man_avatar.avif');

const selectedAction = ref('');
function navigateToPage() {
    switch (selectedAction.value) {
        case 'suggestion':
            router.visit('/suggestion/create');
            break;
        case 'report-error':
            router.visit('/report-error');
            break;
        case 'feature-request':
            router.visit('/feature-request/create');
            break;
        default:
            break;
    }
}

</script>

<template>
    <header
        class="h-[64px] shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/70 px-3 lg:px-6 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12 md:px-4">
        <div class="flex items-center gap-2">
            <!-- <SidebarTrigger class="-ml-1" /> -->
            <template v-if="breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <div class="flex flex-row lg:items-center lg:gap-4 gap-5 ">
            <!-- Action Dropdown -->
            <!-- <div class="relative inline-block text-left mt-3 lg:mt-0">
                <select v-model="selectedAction" @change="navigateToPage"
                    class="block w-full appearance-none bg-white border border-gray-300 text-gray-700 text-sm rounded-md px-4 py-2 pr-10 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option disabled value="">➤ Select Action</option>
                    <option value="suggestion">💡 Add Suggestion</option>
                    <option value="report-error">🐞 Report Error</option>
                    <option value="feature-request">🚀 Feature Request</option>
                </select>

             
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div> -->

            <div class="mt-3 lg:mx-10 mx-0">
                <Link :href="route('profile.show')">
                <!-- <img src="/images/man_avatar.avif" alt="Profile Image" height="45px" width="45px"/> -->
                <div class="w-[45px] h-[45px] rounded-full overflow-hidden border border-gray-300 shadow-sm">
                    <img :src="avatarUrl" alt="Profile Image" class="w-full h-full object-cover" />
                </div>
                </Link>
            </div>
        </div>

    </header>
</template>
