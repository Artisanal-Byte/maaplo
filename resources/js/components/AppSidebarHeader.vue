<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const avatarUrl = computed(() => user.value?.avatar
    ? `/${user.value.avatar}` // ensure avatar path is prefixed correctly for your setup
    : '/images/man_avatar.avif');
defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>();
</script>

<template>
    <header
        class="h-[64px] shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12 md:px-4">
        <div class="flex items-center gap-2">
            <!-- <SidebarTrigger class="-ml-1" /> -->
            <template v-if="breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <div class="mt-3 lg:mx-10 mx-0">
            <Link :href="route('profile.show')">
            <!-- <img src="/images/man_avatar.avif" alt="Profile Image" height="45px" width="45px"/> -->
            <div class="w-[45px] h-[45px] rounded-full overflow-hidden border border-gray-300 shadow-sm">
                <img :src="avatarUrl" alt="Profile Image" class="w-full h-full object-cover" />
            </div>
            </Link>
        </div>

    </header>
</template>
