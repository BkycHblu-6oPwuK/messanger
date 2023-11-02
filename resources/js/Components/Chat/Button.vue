<script setup>
import { computed } from 'vue';
    const props = defineProps({
        isUpdate: Boolean,
    })
    const content = computed(() => props.isUpdate ? '&#10003;' : '&#8594;')
</script>

<template>
    <button
        class="rounded-r-lg bg-blue-500 text-white font-bold uppercase border-blue-500 border-t border-b border-r py-1 px-3 lg:p-4 lg:px-8"
        v-html="content">
    </button>
</template>
