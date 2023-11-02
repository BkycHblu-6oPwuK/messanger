<script setup>
import { onMounted, ref } from 'vue';
import Header from '@/Components/Header.vue';
import Chats from '@/Components/Chat/Chats.vue';
import Messages from '@/Components/Chat/Messages.vue';
import { useStore } from 'vuex';
import { computed } from 'vue';

const store = useStore();
const isLargeScreen = computed(() => store.state.isLargeScreen);

const props = defineProps({
    chat: Object,
    groups: Object,
    friends: Object,
    previewMessages: Object,
    auth: Object,
    messages: Object,
    pagination: Object,
})

onMounted(() => {
    // console.log(props.chat)
    // console.log(props.chat)
    // let chatId = [props.auth.user.id, props.chat].sort().join('-');
    // window.Echo.channel(`store-chat-${props.auth.user.id}`)
    //     .listen('.store-chat', (res) => {
    //         console.log(res);
    //         props.messages.push(res.message);
    //     });
});
</script>

<template>
    <Header :auth="auth"></Header>
    <div class="big-container flex">
        <Chats :friends="friends" :groups="props.groups" :previewMessages="props.previewMessages" :chat="props.chat" v-if="isLargeScreen || !props.chat">
        </Chats>
        <Messages
            :data="{ isLargeScreen: isLargeScreen, chat: props.chat, auth: props.auth, messages: props.messages, pagination: props.pagination }">
        </Messages>
    </div>
</template>

<style>
.big-container {
    height: calc(100vh - 44px);
}

/* @media screen and (max-width: 380px) {
    .big-container {
        height: calc(100vh - 88px);
    }
} */
</style>
