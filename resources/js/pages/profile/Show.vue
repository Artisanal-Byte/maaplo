<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const props = defineProps(['user']);
function goToEdit() {
    router.visit(route('profile.edit'));
}
</script>

<template>
  <AppLayout>
    <div class="px-4 py-10 max-w-4xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">👤 Profile</h1>
        <Link :href="route('dashboard')" class="flex items-center gap-2 text-gray-500 hover:text-gray-800">
          <Icon icon="material-symbols:arrow-back-rounded" width="24" height="24" />
          <span class="text-base font-medium">Back</span>
        </Link>
      </div>

      <div
        class="bg-white shadow-lg rounded-xl p-6 transition-all hover:shadow-xl border border-gray-100"
      >
        <div class="space-y-4">
          <div class="flex items-center gap-3">
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
              {{ props.user.validity ? new Date(props.user.validity).toLocaleDateString() : 'N/A' }}
            </p>
          </div>

          <div v-if="props.user.organization_logo" class="pt-4">
            <p class="font-semibold text-gray-700 mb-2">Organization Logo:</p>
            <img
              :src="`/storage/${props.user.organization_logo.replace(/^storage\//, '')}`"
              alt="Organization Logo"
              class="w-36 h-auto rounded shadow border"
            />
          </div>
        </div>

        <div class="pt-6 text-right">
          <button
            @click="goToEdit"
            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition"
          >
            Edit Profile
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
