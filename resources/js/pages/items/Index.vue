<script setup>
import ItemTemplateList from '@/components/ItemTemplateList.vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps({
    items: Array,
    authUser: Object  // Ensure authUser is passed down here
});
</script>


<template>
    <AppLayout>
        <div class="lg:mx-auto max-w-7xl py-8 px-4">
            <div class="flex gap-4 text-gray-600">
                <div>
                    <h1 class="text-[24px] mt-3 leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                        Template's
                    </h1>
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
