<script setup>
    const body = defineModel()
</script>

<template>
    <input type="text" v-model="body" placeholder="Type your message"
        class="flex-1 mx-1 border-t border-b border-l text-gray-800 border-gray-200 bg-white p-1 lg:p-4">
</template>
