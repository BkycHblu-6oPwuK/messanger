<script setup>
import data from "emoji-mart-vue-fast/data/all.json";
import "emoji-mart-vue-fast/css/emoji-mart.css";
import { Picker, EmojiIndex } from "emoji-mart-vue-fast/src";
import { ref } from "vue";

const emojiIndex = ref(new EmojiIndex(data));
const showEmoji = (emoji) => {
    body.value += emoji.native;
}
const isShowEmoji = ref(false)
const cursorInPicker = ref(false)
let hideTimer = null;
const cancelHide = () => {
    if (hideTimer) {
        window.clearTimeout(hideTimer);
        hideTimer = null;
    }
}

const hideEmoji = () => {
    hideTimer = window.setTimeout(cancelHide(), 100)
    if (!cursorInPicker.value) {
        isShowEmoji.value = false;
    }
}
</script>

<template>
    <button
        class="bg-blue-500 mr-1 text-white font-bold uppercase border-blue-500 border-t border-b border-r py-1 px-3 lg:p-4 lg:px-8"
        @mouseover="isShowEmoji = true" @mouseleave="hideEmoji">😄</button>
    <div @mouseenter="cursorInPicker = true" @mouseleave="cursorInPicker = false">
        <Picker v-if="isShowEmoji || cursorInPicker" :data="emojiIndex" set="twitter" @select="showEmoji"
            :showPreview="false" :style="{ position: 'absolute', bottom: '82px', right: '1.5em' }" />
    </div>
</template>
