<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Button from '@/components/Button.vue';

const props = defineProps(['user']);
function goToEdit() {
    router.visit(route('profile.edit'));
}

</script>

<template>
    <Head :title="`${props.user.name}-Profile`" />
    <AppLayout>
        <div class="px-4 py-10 max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div class="flex">
                    <Icon icon="healthicons:ui-user-profile" width="42" height="42"
                        class="text-xl font-bold text-primary" />
                    <h1 class="text-3xl font-bold text-primary mt-1 ml-3">
                        Profile
                    </h1>
                </div>
                <Link :href="route('dashboard')" class="flex items-center gap-2 text-gray-500 hover:text-gray-800">
                <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                <span class="text-base font-medium">Back</span>
                </Link>
            </div>

            <div
                class="bg-[#DEEFF4] shadow-lg rounded-xl p-3 lg:p-6 transition-all hover:shadow-xl border border-gray-100">
                <div class="grid grid-cols-2">
                    <div class="space-y-4 grid grid-cols-1 text-lg">
                        <div class="flex items-center gap-3 mt-3">
                            <Icon icon="mdi:account" class="text-gray-500" width="20" />
                            <p><strong>Name:</strong> {{ props.user.name }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <Icon icon="mdi:email-outline" class="text-gray-500" width="20" />
                            <p><strong>Email:</strong> {{ props.user.email }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <Icon icon="mdi:phone" class="text-gray-500" width="20" />
                            <p><strong>Phone:</strong> {{ props.user.phone }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <Icon icon="mdi:office-building" class="text-gray-500" width="20" />
                            <p><strong>Organization:</strong> {{ props.user.organization_name }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <Icon icon="mdi:badge-account-outline" class="text-gray-500" width="20" />
                            <p><strong>Subscription:</strong> {{ props.user.subscription_plan }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <Icon icon="mdi:calendar-check" class="text-gray-500" width="20" />
                            <p><strong>Validity:</strong>
                                {{ props.user.formatted_validity ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- <div v-if="props.user.organization_logo" class="pt-4">
                            <p class="font-semibold text-gray-700 mb-2">Organization Logo:</p>
                            <img :src="`/storage/${props.user.organization_logo.replace(/^storage\//, '')}`"
                                alt="Organization Logo" class="w-36 h-auto rounded shadow border" />
                        </div> -->
                    </div>

                    <div class="w-full text-right">
                        <!-- <Link :href="route('profile.show')"> -->
                        <img :src="props.user.avatar ? `/storage/${props.user.avatar.replace(/^storage\//, '')}` : '/images/man_avatar.avif'"
                            alt="Profile Image"
                            class="lg:w-48 lg:h-48 w-24 h-24 mr-0 lg:mr-12 mt-3 lg:mt-10 inline-block rounded-full object-cover border" />
                        <!-- </Link> -->
                    </div>
                </div>
                <!-- <div class="pt-6 text-center"> -->
                <Button @click="goToEdit"
                    class="mt-6 w-full w-auto bg-primary text-white hover:bg-primary-dark transition-colors">
                    Edit Profile
                </Button>
                <!-- </div> -->
            </div>
        </div>
    </AppLayout>
</template>
