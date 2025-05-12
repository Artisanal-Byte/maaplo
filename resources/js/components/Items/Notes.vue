<script setup lang="ts">
import { Icon } from '@iconify/vue';
import Input from '../InputWithLabel.vue';
import { toRefs, watch } from 'vue';
import Button from '../Button.vue';

const props = defineProps<{
    notes: Array<{ label: string; text: string }>;
}>();

const emit = defineEmits(['update:notes','setNotes']);
const { notes } = toRefs(props);

const addNote = () => {
    const newNotes = [...notes.value, { label: '', text: '' }];
    emit('update:notes', newNotes);
};

const removeNote = (index: number) => {
    const newNotes = [...notes.value];
    newNotes.splice(index, 1);
    emit('update:notes', newNotes);
};

const updateLabel = (index: number, value: string) => {
    const newNotes = [...notes.value];
    newNotes[index].label = value;
    emit('update:notes', newNotes);
};

const updateText = (index: number, value: string) => {
    const newNotes = [...notes.value];
    newNotes[index].text = value;
    emit('update:notes', newNotes);
};

watch(()=>notes.value,(val)=>{
    emit('setNotes',val)
})
</script>

<template>
    <div class="notes-section">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3 mb-3">
                <label class="">Notes</label>
                <span class="text-sm text-gray-600">Total: {{ notes.length }}</span>

                <!-- Add Note Button -->
                <button type="button" @click="addNote"
                    class="flex items-center text-green-500 hover:text-green-600 transition">
                    <Icon icon="mdi:plus-circle-outline" width="20" height="20" class="mr-1" />
                </button>
            </div>
        </div>

        <div v-for="(note, index) in notes" :key="index" class="mb-2 flex flex-row gap-2 lg:items-start">
            <div class="w-full">
                <div>
                    <Input type="text" :label="`Label ${index + 1}`" :modelValue="note.label"
                        @update:modelValue="updateLabel(index, $event)" :id="`label-${index}`" :name="`label-${index}`"
                        color="grayBorder" margin="md" placeholder="Write your label" />
                </div>
                <div class="mt-3">
                    <Input type="textarea" :label="`Notes ${index + 1}`" :modelValue="note.text"
                        @update:modelValue="updateText(index, $event)" :id="`text-${index}`" :name="`text-${index}`"
                        color="grayBorder" margin="md" placeholder="Write your Note..." />
                </div>
            </div>

            <!-- Remove Note Button -->
            <div class="flex items-center p-1" v-if="notes.length > 1">
                <button type="button" @click="removeNote(index)"
                    class="flex items-center text-red-500 hover:text-red-600 transition">
                    <Icon icon="mdi:minus-circle-outline" width="20" height="20" class="mr-1" />
                </button>
            </div>
        </div>
    </div>
</template>
