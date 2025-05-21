<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    users: Array
});

console.log(props.users);
</script>

<template>
    <AppLayout>
        <div class="lg:mx-auto max-w-7xl py-8 px-4">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-[24px] mt-3 leading-[16px] font-bold tracking-[0] text-gray-800 font-[Convergence]">
                        Admin
                    </h1>
                    <p class="text-sm text-gray-700 text-right mt-2">
                        Total Admins: {{ users.length }}
                    </p>
                </div>

                <!-- Create Admin Button -->
                <div class="relative group mt-1">
                    <Link :href="route('user.create')" class="cursor-pointer">
                        <Icon icon="material-symbols:add-rounded" width="30" height="30" />
                    </Link>
                    <div
                        class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                        Create Admin
                    </div>
                </div>
            </div>

            <!-- Admin Cards -->
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-10">
                <div
                    v-for="user in users"
                    :key="user.id"
                    class="relative mt-5 h-full rounded-[10px] px-[18px] py-[17px] bg-[#DEEFF4]"
                >
                    <div class="flex-2">
                        <h1 class="text-[22px] font-semibold tracking-wide text-gray-900">
                            Name: {{ user.name }}
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-3">
                            Email: {{ user.email }}
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Contact: {{ user.phone ?? 'N/A' }}
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Organization Name: {{ user.organization_name ?? 'N/A' }}
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Subscription Plan: {{ user.subscription_plan }}
                        </h1>
                        <h1 class="font-[Lato] font-medium text-[18px] text-black mt-2">
                            Validity: {{ user.validity ?? 'N/A' }}
                        </h1>
                    </div>

                    <div class="flex justify-end gap-3">
                        <!-- Edit -->
                        <div class="relative group">
                            <Icon icon="ri:edit-fill" width="18" height="18" class="text-[#005FAF]" />
                            <div
                                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                Edit
                            </div>
                        </div>

                        <!-- Delete -->
                        <!-- <div class="relative group">
                            <button class="flex items-center text-red-500 hover:text-red-600 transition">
                                <Icon icon="ic:baseline-delete" width="18" height="18" class="text-[#E73939]" />
                            </button>
                            <div
                                class="absolute top-full mt-2 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-10">
                                Delete
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
