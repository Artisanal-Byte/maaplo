<script setup>
import { ref, watch } from 'vue';
import Input from '../InputWithLabel.vue';
import { Icon } from '@iconify/vue';
const color = ref('');
const emits = defineEmits(['setColor']);

watch(() => color.value, (newColorValue) => {
    emits('setColor', newColorValue)
})

</script>
<template>
    <div class="flex flex-col">
        <Input v-model="color" id="favcolor" name="favcolor" label="Color:" type="text" color="grayBorder" margin="md"
            placeholder="Enter color">
        <template #icon>
            <Icon icon="mdi:color" width="22" height="22" class="mt-2" />
        </template>
        </Input>
    </div>
</template>
