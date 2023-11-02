<script setup>
import { router } from '@inertiajs/vue3'
import { useStore } from 'vuex';
import CreateGroup from './CreateGroup.vue'
import { unref } from 'vue';
const store = useStore();
const props = defineProps({
    groups: Object,
    friends: Object,
    previewMessages: Object,
    chat: Object,
})

const requestScroll = () => {
    store.dispatch('triggerScroll');
}
function getChat(id) {
    if (props.chat?.id != id) {
        router.get(route('chats.show', id))
    } else {
        requestScroll()
    }
}
</script>
<template>
    <div class="w-full bg-white shadow-md p-6 flex flex-col lg:w-1/4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-xl">Chats</h2>
            <CreateGroup :friends="props.friends"></CreateGroup>
        </div>
        <div class="flex-1 overflow-y-auto">
            <div v-for="(group,index) in props.groups" @click.prevent="getChat(index)" :key="index"
                class="flex justify-between mb-4 cursor-pointer hover:bg-gray-100 p-2 rounded">
                <div class="flex items-center w-full relative">
                    <img src="https://s.ekabu.ru/localStorage/post/e3/df/40/66/e3df4066.jpg" alt="User Avatar"
                        class="w-10 h-10 rounded-full mr-3 object-cover">
                    <div class="flex flex-col max-w-full overflow-hidden">
                        <span class="font-medium truncate">{{ group.name }}</span>
                        <span class="text-gray-500 text-sm truncate">{{ props.previewMessages[index]?.body }}</span>
                    </div>
                    <span class="text-gray-400 text-xs align-top absolute right-0 top-0">{{ props.previewMessages[index]?.date }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
