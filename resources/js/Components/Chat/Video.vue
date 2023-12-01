<template>
    <div class="gallary-video rounded-md">
        <vue-plyr>
            <video controls crossorigin playsinline
                :data-poster="props.gallary.preview_path">
                <source :src="props.gallary.media_path" :type="props.gallary.type" />
            </video>
        </vue-plyr>
        <video v-if="isVideoGallary" ref="videoElement" class="video-js vjs-matrix rounded-md max-h-96"
            :poster="'/storage/users/1/photo_2023-09-28_18-44-30.jpg'">
            <source :src="props.gallary.media_path" :type="props.gallary.type" />
            Your browser does not support the video tag.
        </video>
    </div>
</template>

<script setup>
import { ref } from 'vue';
// import videojs from 'video.js';
// import 'video.js/dist/video-js.css';
// import { onUnmounted } from 'vue';
// import { onMounted } from 'vue';

const props = defineProps({
    gallary: Object,
});

// const videoElement = ref(null);
const isVideoGallary = ref(false);

// watchEffect(() => {
//     isVideoGallary.value = props.gallary?.type?.startsWith('video/');
// });

// const videoOptions = {
//     controls: true,
// };

// let player = null;

// watchEffect(() => {
//     if (isVideoGallary.value && videoElement.value) {
//         player = videojs(videoElement.value, videoOptions);
//     } else if (player) {
//         player.dispose();
//         player = null;
//     }
// });

// onUnmounted(() => {
//     if (player) {
//         player.dispose();
//     }
// });
</script>
<style>
/* .video-js {
    max-width: 100%;
    height: 100%;
    display: block;
    position: relative;
    cursor: pointer;
}

.video-js .vjs-tech {
    object-fit: contain;
    display: block;
    position: relative;
    border-radius: 0.375rem;
} */
.gallary-video {
    width: auto;
    height: 30vh;
}
</style>
