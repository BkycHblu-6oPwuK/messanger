<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick, onUnmounted, reactive } from 'vue';
import { onMounted, computed, ref } from 'vue';
import FileUploadComponent from './FileUploadComponent.vue'
import MessagesHeader from './MessagesHeader.vue'
import MessageBlock from './MessageBlock.vue'
import Input from './Input.vue'
import Button from './Button.vue'
import axios from 'axios';
import { watch } from 'vue';
import { useStore } from 'vuex';
import { defineAsyncComponent } from 'vue';
import { proxyRefs } from 'vue';

const store = useStore()
const props = defineProps({
    data: Object,
})
const form = useForm({
    body: '',
    chat_group_id: props.data.chat?.id,
    files: [],
})
const formOptions = reactive({
    isUpdate: false,
    isDelete: false,
    messageId: null,
    reset: () => {
        formOptions.isUpdate = false
        formOptions.isDelete = false
        formOptions.messageId = null
    }
})
const Gallary = defineAsyncComponent(() => import('./Gallary.vue'));
const hasFales = ref(false)
const disabledForm = computed(() => (form.body !== '' || hasFales.value))
const messageBlock = ref()
const hasLoadMessage = ref(false)
const topMessageId = ref()
const hasScrollBottom = ref(true)
const showGallary = computed(() => store.getters.getShowGallary);
const images = ref([])
const activeIndex = ref(0)
const activeMessageId = ref(null);
const menuPosition = ref({ x: 0, y: 0 });

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
        topMessageId.value = getTopVisibleMessageId(messageBlock.value);
        props.data.pagination.currentPage += 1;
        try {
            const res = await axios.get(`/api/getMessage/${props.data.chat.id}/${props.data.pagination.currentPage}`)
            res.data.reverse().forEach((item) => {
                props.data.messages.unshift(item)
            });
            let topMessageElement = document.getElementById(topMessageId.value);
            if (topMessageElement) {
                topMessageElement.scrollIntoView();
            }
        } catch (error) {
            console.error(error)
            throw error
        }
    }
}

const getTopVisibleMessageId = (container) => {
    const boundsTop = container.getBoundingClientRect().top;
    for (const message of props.data.messages) {
        const messageEl = document.getElementById(`message-${message.id}`);
        if (messageEl) {
            const bounds = messageEl.getBoundingClientRect();
            if (bounds.top >= boundsTop) {
                return `message-${message.id}`;
            }
        }
    }
    return null;
}

const methods = {
    post: (url, options) => form.post(url, options),
    patch: (url, options) => form.patch(url, options),
    delete: (url, options) => form.delete(url, options),
}

const requestMessage = (url, method, files, closeModal) => {
    if (files) {
        Object.values(files).forEach(file => {
            if (file.accepted) {
                form.files.push(file.file)
            }
        });
    }
    methods[method](url, {
        onSuccess: (res) => {
            if (closeModal) {
                closeModal()
            }
            form.reset('body', 'files', 'isUpdate', 'isDelete', 'messageId')
            formOptions.reset()
            hasFales.value = false
            scrollToBottom();
        },
        onError: (res) => {
            console.log(res)
        }
    })
}

const sendRequestMessage = (files, closeModal) => {
    if (formOptions.isUpdate) {
        return requestMessage(route('chats.update', formOptions.messageId), 'patch', files, closeModal)
    }
    if (formOptions.isDelete) {
        return requestMessage(route('chats.delete', formOptions.messageId), 'delete', files, closeModal)
    }
    requestMessage(route('chats.store'), 'post', files, closeModal)
}

const hasFalesUpdating = () => hasFales.value = true;

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
    let index = props.data.messages.findIndex(message => message.id === messageId)
    if (index) {
        form.body = props.data.messages[index].body
        formOptions.isUpdate = true
        formOptions.messageId = messageId
    }
}

const isDeleteMessage = (messageId) => {
    formOptions.messageId = messageId
    formOptions.isDelete = true
    sendRequestMessage()
}
</script>

<template>
    <div v-if="props.data.chat" ref="messageBlock" @scroll="handleScroll"
        class="flex-1 bg-white shadow-md flex overflow-y-auto flex-col h-full">
        <MessagesHeader :chat="props.data.chat" :isLargeScreen="props.data.isLargeScreen"></MessagesHeader>

        <div v-if="props.data.messages.length == 0" class="flex items-center justify-center w-full">Здесь пока ничего нет
        </div>
        <div class="flex-1 flex flex-col justify-end p-1 lg:p-6">

            <div class="flex-1 flex flex-col justify-end p-1 lg:p-3">
                <div v-if="props.data.messages.length > 0" v-for="message in props.data.messages" :key="message.id"
                    class="flex items-start mb-2 lg:mb-4"
                    :class="props.data.auth.user.id == message.sender_id ? 'flex-row-reverse lg:flex-row' : ''"
                    :id="`message-${message.id}`">

                    <img v-if="props.data.isLargeScreen" src="https://s.ekabu.ru/localStorage/post/e3/df/40/66/e3df4066.jpg"
                        alt="User Avatar" class="w-10 h-10 rounded-full object-cover mr-3"
                        :class="!props.data.isLargeScreen ? 'ml-3' : ''">
                    <MessageBlock @scrollToBottom="scrollToBottom"
                        @mouseup.right="showMenuMessage($event, message.sender_id, message.id)" @contextmenu.prevent
                        :message="message" :isLargeScreen="props.data.isLargeScreen" :user="props.data.auth.user">
                    </MessageBlock>
                    <div v-if="activeMessageId == message.id" :style="{ top: menuPosition.y, left: menuPosition.x }"
                        class="absolute z-10 mt-2 w-48 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5"
                        @click="menuVisible = false">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <a @click.prevent="isUpdateMessage(message.id)" href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                role="menuitem">Изменить</a>
                            <a @click.prevent="isDeleteMessage(message.id)" href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                role="menuitem">Удалить</a>
                        </div>
                    </div>
                </div>
                <Gallary v-if="showGallary" :images="images" :activeIndex="activeIndex"></Gallary>
            </div>

        </div>

        <div @keyup.enter="sendRequestMessage" class="sticky bottom-0 bg-white border-gray-200 mt-1 :mt-4">
            <div class="p-fileupload-content"></div>
            <div class="flex p-1 lg:p-6">
                <FileUploadComponent @storeMessage="sendRequestMessage" :isUpdate="formOptions.isUpdate" :form="form"
                    :disabledForm="disabledForm" :hasFalesUpdating="hasFalesUpdating"></FileUploadComponent>
                <Input :form="form"></Input>
                <Button @click="sendRequestMessage" :disabled="!disabledForm" :isUpdate="formOptions.isUpdate"
                    :class="!disabledForm ? 'pointer-events-none opacity-70' : ''"></Button>
            </div>
        </div>

    </div>
    <div v-else-if="!props.data.chat && props.data.isLargeScreen"
        class="flex-1 bg-white shadow-md flex overflow-y-auto flex-col justify-center items-center h-full">Выберите
        кому вы бы хотели написать</div>
</template>
