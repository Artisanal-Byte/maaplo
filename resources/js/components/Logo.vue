<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
const page = usePage()
const thumbnailLogo = computed(() => page.props.thumbnail_logo as string | null)
// console.log('thumbnailLogo:', thumbnailLogo.value);
</script>

<template>
  <img
    :src="thumbnailLogo ? '/' + thumbnailLogo : '/images/maaplologo.png'"
    alt="Logo"
    class="h-10 w-10"
  />
  
</template>
