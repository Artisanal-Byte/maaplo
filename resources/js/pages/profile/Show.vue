<script setup>
const props = defineProps(['user'])
import AppLayout from '@/layouts/AppLayout.vue';
</script>
<template>
    <AppLayout>
      <pre>
        {{ user }}
      </pre>
    </AppLayout>
</template>

