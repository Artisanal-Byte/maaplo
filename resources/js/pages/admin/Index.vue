<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
</script>
<template>
    <AppLayout>
        <div class="lg:mx-auto max-w-7xl py-8 px-4">
            <div>
                <h1 class="text-[24px] mt-3 leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                    Admin
                </h1>

            </div>
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-10">
                <div v-for="i in 5" class="relative mt-5 h-full rounded-[10px] px-[10px] py-[17px] bg-[#DEEFF4]">
                    <div class="flex-2">
                        <h1 class="text-[22px] font-semibold tracking-wide text-gray-900">
                            Name: riya patel
                        </h1>

                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-3">
                            Email: riyapatel@gmail.com
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Contact :123456789
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Organization Name: xyz
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Subscription Plan:
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Validity:
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
