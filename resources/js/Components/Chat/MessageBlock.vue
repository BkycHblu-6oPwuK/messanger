<script setup>
import { ref } from 'vue';
import 'video.js/dist/video-js.css';
import Video from './Video.vue'
import { useStore } from 'vuex';
import { onMounted } from 'vue';

const store = useStore();
const props = defineProps({
    message: Object,
    user: Object,
    isLargeScreen: Boolean,
})
const emits = defineEmits(['scrollToBottom'])
const countColumn = ref(2)
const countColSpan = ref(1)

const updateGridValues = (length, type) => {
    if (type && type.startsWith('video/')) {
        countColumn.value = 1
        countColSpan.value = 1
        return
    }
    if (type && !type.startsWith('image/')) {
        countColumn.value = 1
        countColSpan.value = 1
        return
    }
    if (length > 2 && props.isLargeScreen) {
        countColumn.value = 3
        if (length % 3 === 1) {
            countColSpan.value = 3
        } else {
            countColSpan.value = 1
        }
    } else if (length > 2 && !props.isLargeScreen) {
        countColumn.value = 2
        if (length % 2 === 1) {
            countColSpan.value = 2
        } else {
            countColSpan.value = 1
        }
    } else if (length === 2) {
        countColumn.value = 2
        countColSpan.value = 1
    } else if (length === 1) {
        countColumn.value = 1
        countColSpan.value = 1
    }
}
const setImageToStoreGallary = (image,key) => {
    let data = {}
    data = {
        'id': key,
        'itemImageSrc': image,
        'thumbnailImageSrc': image,
        'alt':"Description for Image 1"
    }
    store.commit('setGallary',data)
}
const setCurrentIndexToStore = (key) => store.commit('setCurrentIndex',key)



</script>

<template>
    <div class="bg-gray-100 rounded-lg max-w-[80%] lg:max-w-screen-md">
        <!-- :class="props.message.gallary && props.message.gallary.length > 1 ? 'flex flex-col p-3' : ''" -->
        <div v-if="props.message.gallary && props.message.gallary.length"
            :class="`grid grid-cols-${countColumn - 1 == 0 ? 1 : countColumn} gap-2 lg:grid-cols-${countColumn} lg:gap-3`">
            <div v-for="gallary in props.message.gallary"
                :ref="updateGridValues(props.message.gallary.length, gallary?.type)" :key="gallary.id"
                :class="`col-span-${countColSpan}`">

                <div v-if="gallary?.type?.startsWith('image/')" class="relative max-h-156 h-full bg-gray-200">
                    <img @load="setImageToStoreGallary(gallary.media_path,gallary.id),emits('scrollToBottom')" @click="setCurrentIndexToStore(gallary.id)" :src="gallary.media_path" alt="Gallery Image"
                        class="w-full h-full object-cover rounded-md shadow-md cursor-pointer">
                </div>

                <Video v-else-if="gallary?.type?.startsWith('video/')" :gallary="gallary"
                    class="relative w-full h-auto"></Video>

                <div v-else class="relative max-h-16 h-full p-3">
                    <a :href="gallary.media_path" target="_blank"
                        class="flex items-center no-underline text-blue-500 hover:text-blue-700 h-full justify-center gap-2 items-center">
                        <span>📄</span>
                        <span class="line-clamp-2">{{ gallary.original_name }}</span>
                    </a>
                </div>

            </div>
        </div>
        <div v-if="props.message.body" class="flex rounded-lg p-2 gap-2 flex-wrap justify-end"
            :class="props.user.id == props.message.sender_id ? 'bg-blue-500 text-white' : '', props.message.gallary.length ? 'mt-2' : ''">

            <div class="flex-grow text-sm break-all lg:text-base">
                {{ props.message.body + '( sender: ' + props.message.sender_id + ')' + '(auth: ' + props.user.id + ')' }}
            </div>

            <span class="text-[10px] self-end">
                {{ props.message.date }}
            </span>

        </div>

    </div>
</template>
