<script setup>
const currentYear = new Date().getFullYear();
</script>
<template>
    <footer class="bg-primary text-center py-2">
        <div>
            <h1 class="font-[Lato] text-white font-normal text-[14px] leading-[16px] tracking-[0]">
                Copyright © Maaplo {{ currentYear }}. All rights reserved.
            </h1>
        </div>
    </footer>

</template>
