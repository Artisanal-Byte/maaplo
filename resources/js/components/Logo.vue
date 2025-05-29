<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

// Correct spelling: thumbnailLogo
const thumbnailLogo = computed(() => {
  const logo = (page.props.user?.thumbnail_logo || '') as string
  return logo ? `/storage/${logo.replace(/^storage\//, '')}` : null
})

const fallbackLogo = '/images/maaplologo.png'
</script>

<template>
  <Link :href="route('dashboard')">
  <img
    :src="thumbnailLogo || fallbackLogo"
    alt="Organization Logo"
    class="h-10 w-10"
  />
  </Link>
</template>
