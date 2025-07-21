<script setup>
import { defineProps, defineEmits } from 'vue';
import { Icon } from '@iconify/vue';
const props = defineProps({
    showable: {
        type: Object,

        // default: () => ({ showSearch: false, showFilter: false })
    }
});
const emit = defineEmits(['hideOrShow', 'focusSearch']);

function toggleSearch() {
    props.showable.showSearch = !props.showable.showSearch;

    if (props.showable.showSearch) {
        emit('focusSearch'); // ✅ only emit
    }
}
</script>

<template>
    <div class="mx-auto max-w-7xl w-full">
        <!-- Search Icon -->
        <div>
            <Icon icon="material-symbols:search" width="32" height="32" class="cursor-pointer " @click="toggleSearch" />
        </div>
        <!-- @click="emit('hideOrShow', 'search')" -->
    </div>
</template>
