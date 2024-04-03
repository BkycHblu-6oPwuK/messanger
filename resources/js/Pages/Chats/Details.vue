
<script setup>
import { computed, onMounted, ref } from 'vue';
import Header from '@/Components/Header.vue';
import { useStore } from 'vuex';
import Modal from '@/Components/Modal.vue';
import Details from '@/Components/Chat/Details/Details.vue';
import { router } from '@inertiajs/vue3';
import { watch } from 'vue';
const store = useStore();
const isLargeScreen = computed(() => store.state.isLargeScreen);
const props = defineProps({
    auth: Object,
    chat: Object,
    modalShow: Boolean,
})

const modalShow = ref(isLargeScreen.value && props.modalShow ? true : false);
function close() {
    modalShow.value = false
    store.commit('setShowDetails',false)
}

const redirectToMessages = () =>{
    if(isLargeScreen.value && !modalShow.value){
        store.commit('setShowDetails',true)
        router.visit(route('chats.show',props.chat.id), { method: 'get' })
    }
    if(!isLargeScreen.value && modalShow.value){
        router.visit(route('chats.details',props.chat.id), { method: 'get' })
    }
}
watch(() => isLargeScreen.value, (newValue) => {
    redirectToMessages()
})

onMounted(() => {
    redirectToMessages()
});
const back = () => {
    router.visit(route('chats.show',props.chat.id), { method: 'get' })
}
</script>

<template>
    <Header v-if="!isLargeScreen" :auth="props.auth"></Header>
    <Modal v-if="isLargeScreen" :show="modalShow" @close="close">
        <Details :chat="props.chat"></Details>
    </Modal>
    <div v-if="!isLargeScreen" class="min-h-screen bg-gray-100 flex flex-col sm:py-12">
        <div @click="back"> ← </div>
        <div class="py-3 sm:max-w-xl mx-auto">
            <Details :chat="props.chat"></Details>
        </div>
    </div>
</template>

