<script setup>
import StarterKit from '@tiptap/starter-kit';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import { watch } from 'vue';

const props = defineProps({
    modelValue: { type: String, default: '' },
});
const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
    content: props.modelValue,
    extensions: [StarterKit],
    onUpdate: ({ editor }) => emit('update:modelValue', editor.getHTML()),
});

watch(
    () => props.modelValue,
    (value) => {
        if (editor.value && value !== editor.value.getHTML()) {
            editor.value.commands.setContent(value, false);
        }
    },
);
</script>

<template>
    <div class="rounded-md border border-gray-300">
        <div v-if="editor" class="flex flex-wrap gap-1 border-b border-gray-200 bg-gray-50 p-2">
            <button type="button" class="rounded px-2 py-1 text-sm" :class="{ 'bg-gray-300': editor.isActive('bold') }" @click="editor.chain().focus().toggleBold().run()">B</button>
            <button type="button" class="rounded px-2 py-1 text-sm italic" :class="{ 'bg-gray-300': editor.isActive('italic') }" @click="editor.chain().focus().toggleItalic().run()">I</button>
            <button type="button" class="rounded px-2 py-1 text-sm" :class="{ 'bg-gray-300': editor.isActive('heading', { level: 3 }) }" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()">H3</button>
            <button type="button" class="rounded px-2 py-1 text-sm" :class="{ 'bg-gray-300': editor.isActive('heading', { level: 4 }) }" @click="editor.chain().focus().toggleHeading({ level: 4 }).run()">H4</button>
            <button type="button" class="rounded px-2 py-1 text-sm" :class="{ 'bg-gray-300': editor.isActive('bulletList') }" @click="editor.chain().focus().toggleBulletList().run()">List</button>
            <button type="button" class="rounded px-2 py-1 text-sm" @click="editor.chain().focus().setParagraph().run()">P</button>
        </div>
        <EditorContent :editor="editor" class="prose max-w-none p-3 [&_.ProseMirror]:min-h-[160px] [&_.ProseMirror]:outline-none" />
    </div>
</template>
