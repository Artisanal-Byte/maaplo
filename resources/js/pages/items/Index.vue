<script setup>
import ItemTemplateList from '@/components/ItemTemplateList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
defineProps({
    items: Array,
    authUser: Object  // Ensure authUser is passed down here
});
</script>


<template>
    <AppLayout>
        <div class="lg:mx-auto max-w-7xl py-8 px-4">
            <div class="flex flex-row justify-between mb-6">
                <div>
                    <h1 class="text-[24px] mt-3 leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                        Template's
                    </h1>

                </div>
                <div class="flex gap-4 text-gray-600">
                    <div>
                        <SearchList :showable="showable" />
                    </div>
                    <div class="mx-auto max-w-7xl w-full">
                        <!-- add customer details -->
                        <div class="relative group mt-1">
                            <Link :href="route('items.create')">
                            <Icon icon="material-symbols:add-rounded" width="30" height="30" />
                            </Link>
                            <!-- Add lable for user move curser in add icon -->
                            <div
                                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                Create Template
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Grid Layout to display 3x3 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                <!-- Loop over the items and pass them to ItemCard -->
                <ItemTemplateList v-for="item in items" :key="item.id" :item="item" :authUser="authUser" />
            </div>
        </div>
    </AppLayout>
</template>
