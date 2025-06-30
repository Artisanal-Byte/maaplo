<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
})

const organizationLogo = computed(() => {
  const rawLogo = props.user?.organization?.organization_logo || ''
  return rawLogo
    ? `/storage/${rawLogo.replace(/^storage\//, '')}`
    : '/images/maaplologo.png'
})
</script>

<template>
  <Link :href="route('dashboard')">
    <img :src="organizationLogo" alt="Organization Logo" class="h-10 w-10 object-contain" />
  </Link>
</template>
