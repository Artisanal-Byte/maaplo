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
const showFactoryModal = ref(false);
const factoryForm = ref({
    customers: null, // optional — if null or 0, use all existing
    orders: 2,
    order_items: 3,
});


function openFactoryModal() {
    showFactoryModal.value = true;
}
function closeFactoryModal() {
    showFactoryModal.value = false;
    factoryForm.value = {
        customers: null,
        orders: 2,
        order_items: 3,
    };
}

function submitFactoryData() {
    router.post(route('factory.seed'), factoryForm.value, {
        onFinish: () => closeFactoryModal(),
    });
}
</script>

<template>
    <header
        class="h-[64px] shrink-0 items-center justify-center gap-2 border-sidebar-border/70 px-3 lg:px-6 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12 md:px-4">
        <div class="flex items-center gap-2">
            <!-- <SidebarTrigger class="-ml-1" /> -->
            <template v-if="breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <div class="flex flex-row">
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
            <div v-if="user?.email === 'demo@example.com'" class="lg:p-3 pt-4">
                <button class="w-full px-4 py-2 bg-primary text-white rounded" @click="openFactoryModal">
                    Create Factory Data
                </button>
            </div>

            <div class="flex justify-center items-center my-3 lg:mx-10 mx-0">
                <Link :href="route('profile.show')">
                <div class="w-[45px] h-[45px] rounded-full overflow-hidden border border-gray-300 shadow-sm">
                    <img :src="avatarUrl" alt="Profile Image" class="w-full h-full object-cover" />
                </div>
                </Link>
            </div>

        </div>
        <!-- Modal -->
        <!-- <transition name="fade"> -->
            <div v-if="showFactoryModal"
                class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg p-6 w-[400px]">
                    <h2 class="text-lg font-semibold mb-4 text-black">Seed Factory Data</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-medium mb-1 text-black">New Customers (optional)</label>
                            <input type="number" min="0" v-model="factoryForm.customers"
                                placeholder="Leave empty or 0 to use existing customers"
                                class="w-full text-black border border-gray-300 rounded px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-black font-medium mb-1">Orders per Customer</label>
                            <input type="number" v-model="factoryForm.orders"
                                class="w-full text-black border border-gray-300 rounded px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-black font-medium mb-1">Order Items per Order</label>
                            <input type="number" v-model="factoryForm.order_items"
                                class="w-full text-black border border-gray-300 rounded px-3 py-2" />
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button @click="closeFactoryModal" class="px-4 text-black py-2 bg-gray-300 rounded">Cancel</button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            @click="submitFactoryData">Create</button>
                    </div>
                </div>
            </div>
        <!-- </transition> -->

    </header>
</template>
