<script setup>
import { ref, watch } from 'vue';

const emits = defineEmits(['setShowReferenceDress'])

const setTogle = ref(false)

watch(()=>setTogle.value,(val)=>{
    emits('setShowReferenceDress',val)
})


</script>
<template>
    
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" v-model="setTogle" class="sr-only peer">
        <div
            class="w-11 h-6 bg-gray-300 rounded-full peer dark:bg-gray-700 peer-checked:bg-primary transition duration-300">
        </div>
        <div
            class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-300 transform peer-checked:translate-x-full">
        </div>
    </label>
</template>

