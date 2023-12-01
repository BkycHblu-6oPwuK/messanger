<script setup>
import { nextTick, onUnmounted, reactive } from 'vue';
import { onMounted, computed, ref } from 'vue';
import MessagesHeader from './MessagesHeader.vue'
import MessageBlock from './MessageBlock.vue'
import axios from 'axios';
import { watch } from 'vue';
import { useStore } from 'vuex';
import { defineAsyncComponent } from 'vue';
import FormMessage from './FormMessage.vue';
import { findIndexById } from '@/Functions/helpers';
const store = useStore()
const props = defineProps({
    data: Object,
})
const Gallary = defineAsyncComponent(() => import('./Gallary.vue'));
const messageBlock = ref()
const hasLoadMessage = ref(false)
const topMessageId = ref()
const hasScrollBottom = ref(true)
const showGallary = computed(() => store.getters.getShowGallary);
const images = ref([])
const activeIndex = ref(0)
const activeMessageId = ref(null);
const menuPosition = ref({ x: 0, y: 0 });
const isHighlight = ref(false);
const idsMessages = ref([]);
const countIdsMessages = computed(() => idsMessages.value.length);
const formOptions = reactive({
    isUpdate: false,
    messageId: null,
    index: null,
    reset: () => {
        formOptions.isUpdate = false
        formOptions.isDelete = false
        formOptions.messageId = null
        formOptions.index = null
    }
})

onMounted(() => {
    scrollToBottom()
    window.addEventListener('click', closeMenu);
})

onUnmounted(() => {
    window.removeEventListener('click', closeMenu);
});

const scrollToBottom = async (hasScroll) => {
    if (hasScroll) {
        hasScrollBottom.value = hasScroll
    }
    await nextTick()
    if (messageBlock?.value && hasScrollBottom.value) {
        messageBlock.value.scrollTop = messageBlock.value.scrollHeight;
    }
}

const handleScroll = async () => {
    if (messageBlock.value?.scrollTop <= messageBlock.value?.clientHeight * 0.071 && !hasLoadMessage.value) {
        store.commit('setUserIsScrollingUp', true);
        hasLoadMessage.value = true
        hasScrollBottom.value = false
        try {
            await loadMoreMessages();
        } catch (error) {
            console.error(error)
        }
        hasLoadMessage.value = false
    }
    store.commit('setUserIsScrollingUp', false);
}

const loadMoreMessages = async () => {
    if (props.data.pagination.currentPage < props.data.pagination.lastPage) {
        //let lastmessageId = await new Promise((resolve, reject) => resolve(props.data.messages[props.data.messages.length - 1].id));
        let lastmessageId = props.data.messages[props.data.messages.length - 1].id;
        props.data.pagination.currentPage += 1;
        try {
            let topMessageElement = document.getElementById(`message-${lastmessageId}`);
            const res = await axios.get(route('message.get', { chat: props.data.chat.id, page: props.data.pagination.currentPage }))
            await res.data.forEach((item) => {
                props.data.messages.push(item)
            });
            if(topMessageElement){
                topMessageElement.scrollIntoView();
            }

        } catch (error) {
            console.error(error)
            throw error
        }
    }
}

//const getTopVisibleMessageId = () => {
    // const boundsTop = container.getBoundingClientRect().top;
    // console.log(container.getBoundingClientRect())
    // for (const message of props.data.messages) {
    //     const messageEl = document.getElementById(`message-${message.id}`);
    //     if (messageEl) {
    //         const bounds = messageEl.getBoundingClientRect();
    //         if (bounds.top >= boundsTop) {
    //             return `message-${message.id}`;
    //         }
    //     }
    // }
    // return null;
//}

const getGallary = () => {
    images.value = store.getters.getGallary;
    activeIndex.value = store.getters.getCurrentIndex;
}

watch(() => store.state.shouldScroll, (newValue) => {
    if (newValue && !store.state.userIsScrollingUp) {
        scrollToBottom(true);
        store.dispatch('resetScroll');
    }
});
watch(() => store.state.currentIndex, (newValue) => {
    if (newValue !== null) {
        getGallary()
        store.commit('setShowGallary', true)
    } else {
        setTimeout(() => {
            store.commit('setShowGallary', false);
        }, 10)
    }
});
const showMenuMessage = (event, senderId, MessageId) => {
    if (senderId == props.data.auth.user.id) {
        activeMessageId.value = MessageId
        menuPosition.value = {
            x: event.clientX + 'px',
            y: event.clientY + 'px',
        };
    }
};
const closeMenu = () => {
    if (activeMessageId.value != null) {
        activeMessageId.value = null;
    }
};
const isUpdateMessage = (messageId) => {
    formOptions.messageId = messageId
}
const deleteMessage = (messageId) => {
    let index = findIndexById(props.data.messages, messageId)
    axios.delete(route('chats.delete', messageId)).then((res) => {
        if (res.data == true) {
            props.data.messages.splice(index, 1)
        }
    })
}
const destroyMessages = () => {
    if (idsMessages.value.length > 0) {
        axios.post(route('chats.destroy'), { ids: idsMessages.value }).then((res) => {
            if (res.data > 0) {
                idsMessages.value.forEach(item => {
                    let index = findIndexById(props.data.messages, item)
                    props.data.messages.splice(index, 1)
                })
            }
        })
    }
}
</script>

<template>
    <div v-if="props.data.chat" ref="messageBlock" @scroll="handleScroll"
        class="flex-1 bg-white shadow-md flex overflow-y-auto flex-col h-full">
        <MessagesHeader @destroyMessages="destroyMessages" :countIdsMessages="countIdsMessages" :isHighlight="isHighlight"
            :chat="props.data.chat" :isLargeScreen="props.data.isLargeScreen"></MessagesHeader>

        <div v-if="props.data.messages.length == 0" class="flex items-center justify-center w-full">Здесь пока ничего нет
        </div>
        <div class="flex-1 flex flex-col justify-end p-1 lg:p-6">

            <div class="flex-1 flex flex-col-reverse justify-end p-1 lg:p-3">
                <div v-if="props.data.messages.length > 0" v-for="message in props.data.messages" :key="message.id"
                    class="flex items-start mb-2 lg:mb-4"
                    :class="props.data.auth.user.id == message.sender_id ? 'flex-row-reverse lg:flex-row' : ''"
                    :id="`message-${message.id}`">

                    <img v-if="props.data.isLargeScreen" src="https://s.ekabu.ru/localStorage/post/e3/df/40/66/e3df4066.jpg"
                        alt="User Avatar" class="w-10 h-10 rounded-full object-cover mr-3"
                        :class="!props.data.isLargeScreen ? 'ml-3' : ''">
                    <MessageBlock @scrollToBottom="scrollToBottom"
                        @mouseup.right="showMenuMessage($event, message.sender_id, message.id)" @contextmenu.prevent
                        :message="message" :idsMessages="idsMessages" :isHighlight="isHighlight"
                        :isLargeScreen="props.data.isLargeScreen" :user="props.data.auth.user">
                    </MessageBlock>
                    <div v-if="activeMessageId == message.id" :style="{ top: menuPosition.y, left: menuPosition.x }"
                        class="absolute z-10 mt-2 w-48 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5"
                        @click="menuVisible = false">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <a @click.prevent="isHighlight = !isHighlight" href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                role="menuitem">Выделить</a>
                            <a @click.prevent="isUpdateMessage(message.id)" href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                role="menuitem">Изменить</a>
                            <a @click.prevent="deleteMessage(message.id)" href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                role="menuitem">Удалить</a>
                        </div>
                    </div>
                </div>
                <Gallary v-if="showGallary" :images="images" :activeIndex="activeIndex"></Gallary>
            </div>

        </div>

        <FormMessage @scrollToBottom="scrollToBottom" :data="props.data" :formOptions="formOptions"></FormMessage>

    </div>
    <div v-else-if="!props.data.chat && props.data.isLargeScreen"
        class="flex-1 bg-white shadow-md flex overflow-y-auto flex-col justify-center items-center h-full">Выберите
        кому вы бы хотели написать</div>
</template>
