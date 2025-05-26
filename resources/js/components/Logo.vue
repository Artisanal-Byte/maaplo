<script setup lang="ts">
const props = defineProps<{
  thumbnail_logo: string | null
}>()
console.log('Thumbnail logo:', props.thumbnail_logo);
</script>

<template>
  <img
    :src="props.thumbnail_logo ? '/' + props.thumbnail_logo : '/images/maaplologo.png'"
    alt="Logo"
    class="h-10 w-10"
  />
</template>
