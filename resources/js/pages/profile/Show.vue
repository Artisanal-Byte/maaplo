<script setup>
import Button from '@/components/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Head, Link, router } from '@inertiajs/vue3';
const toast = new ToastMagic();
const props = defineProps(['user']);
function goToEdit() {
    router.visit(route('profile.edit'));
}
</script>

<template>
    <Head :title="`${props.user.name}-Profile`" />
    <AppLayout>
        <div class="mx-auto max-w-4xl px-4 py-10 lg:px-0">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex">
                    <Icon icon="healthicons:ui-user-profile" width="42" height="42" class="text-xl font-bold text-primary" />
                    <h1 class="ml-3 mt-1 text-3xl font-bold text-primary">Profile</h1>
                </div>
                <Link :href="route('dashboard')" class="flex items-center gap-2 text-gray-500 hover:text-gray-800">
                    <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
                    <span class="text-base font-medium">Back</span>
                </Link>
            </div>

            <div class="rounded-xl border border-gray-100 bg-[#DEEFF4] p-3 shadow-lg transition-all hover:shadow-xl lg:p-6">
                <div class="grid grid-cols-3">
                    <div class="col-span-2 grid grid-cols-1 space-y-4 text-lg">
                        <div class="mt-3 flex items-center gap-3">
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
                            <Icon icon="mdi:badge-account-outline" class="text-gray-500" width="20" />
                            <p><strong>Subscription:</strong> {{ props.user.subscription_plan }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <Icon icon="mdi:calendar-check" class="text-gray-500" width="20" />
                            <p>
                                <strong>Validity:</strong>
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
                        <img
                            :src="props.user.avatar ? `/storage/${props.user.avatar.replace(/^storage\//, '')}` : '/images/man_avatar.avif'"
                            alt="Profile Image"
                            class="mr-0 mt-3 inline-block h-24 w-24 rounded-full border object-cover lg:mr-12 lg:mt-10 lg:h-48 lg:w-48"
                        />
                        <!-- </Link> div-->
                    </div>
                </div>
                <!-- <div class="pt-6 text-center"> -->
                <Button @click="goToEdit" class="hover:bg-primary-dark mt-6 w-auto w-full bg-primary text-white transition-colors">
                    Edit Profile
                </Button>
                <!-- </div> -->
            </div>
        </div>
    </AppLayout>
</template>
