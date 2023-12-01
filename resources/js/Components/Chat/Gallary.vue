<template>
    <div class="relative max-h-156 h-full bg-gray-200">
        <div>
            <Galleria unstyled :pt="MyDesignGalleria.galleria" v-model:visible="displayBasic" :value="images"
                :activeIndex="activeIndex" :responsiveOptions="responsiveOptions" :numVisible="3" :circular="true"
                :fullScreen="true" :showItemNavigators="true" :showThumbnails="false">
                <template #item="slotProps">
                    <img v-touch:swipe.left="touchStart" v-touch:swipe.right="touchEndMethod" :src="slotProps.item.itemImageSrc" :alt="slotProps.item.alt" style="width: 100%; display: block" />
                </template>
                <template #thumbnail="slotProps">
                    <img :src="slotProps.item.thumbnailImageSrc" :alt="slotProps.item.alt" style="display: block" />
                </template>
            </Galleria>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import MyDesignGalleria from "@/ThemesPrime/galleria";
import Galleria from 'primevue/galleria';
import { onUnmounted } from "vue";
import { useStore } from "vuex";

const props = defineProps({
    images: Object,
    activeIndex: Number || String,
})
const store = useStore();
const images = ref([]);
const responsiveOptions = [
    { breakpoint: '1500px', numVisible: 5 },
    { breakpoint: '1024px', numVisible: 3 },
    { breakpoint: '768px', numVisible: 2 },
    { breakpoint: '560px', numVisible: 1 }
];
const displayBasic = ref(false);
const activeIndex = ref(0);

watch(() => displayBasic.value, (newValue) => {
    if(newValue === false){
        store.dispatch('resetCurrentIndex')
    }
})

watch(() => store.state.currentIndex, (newValue) => {
    activeIndex.value = store.getters.getCurrentIndex
})

const touchStart = () => {
    let index
    if(activeIndex.value == images.value.length - 1){
        index = 0
        store.commit('setCurrentIndex', index)
    } else {
        index = activeIndex.value + 1
        store.commit('setCurrentIndex',index)
    }
}

const touchEndMethod = () => {
    let index
    if(activeIndex.value == 0){
        index = images.value.length - 1
        store.commit('setCurrentIndex', index)
    } else {
        index = activeIndex.value - 1
        store.commit('setCurrentIndex', index)
    }
}

const closeGallery = () => {
    displayBasic.value = false;
};
let handleMaskClick

onMounted(() => {
    images.value = props.images
    activeIndex.value = props.activeIndex
    displayBasic.value = true;
    handleMaskClick = (event) => {
        if (event.target.getAttribute('data-pc-section') === 'mask') {
            closeGallery();
        }
    };

    window.addEventListener('click', handleMaskClick);
})

onUnmounted(() => {
   window.removeEventListener('click', handleMaskClick);
});

</script>

<style>
.p-overflow-hidden {
    padding-right: 0px !important;
}

div[data-pc-section="thumbnailitems"] img {
    width: 60px;
}
div[data-pc-section="itemcontainer"] img{
    height: 90%;
    max-height: 90vh;
    -o-object-fit: contain;
    object-fit: contain;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 1s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>
