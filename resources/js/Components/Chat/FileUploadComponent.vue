<template>
    <button class="bg-gray-200 text-blue-500 font-bold rounded-l-lg px-2 py-1 lg:p-4" @click="show">
        📎
    </button>
    <Modal :show="modalShow" @close="close">
        <div @keyup.enter="emits('storeMessage', dropzone.all, close)">
            <div class="mb-2 flex justify-end align-top">
                <button @click="close"
                    class="bg-transparent hover:bg-gray-200 text-gray-500 font-semibold py-2 px-2 rounded-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex flex-col w-full cursor-pointer">
                <DropZone @addedFile="props.hasFalesUpdating" class="h-full" ref="dropzone" method="POST"
                    :maxFileSize="600000000" :uploadOnDrop="false" :multipleUpload="true" :parallelUpload="3" />
            </div>
            <div class="flex mt-6 flex-shrink-0">
                <Input v-model="body"></Input>
                <Button v-if="!props.isUpdate" @click="emits('storeMessage', dropzone.all, close)" :isUpdate="props.isUpdate"
                    :disabled="!props.disabledForm"
                    :class="!props.disabledForm ? 'pointer-events-none opacity-70' : ''"></Button>
                <Button v-if="props.isUpdate" @click="emits('updateMessage', dropzone.all, close)" :isUpdate="props.isUpdate"></Button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { onUpdated, ref } from 'vue';
import { onMounted } from 'vue';
import Dropzone from 'dropzone-vue'
import 'dropzone-vue/dist/dropzone-vue.common.css';
import Modal from './../Modal.vue';
import Input from './Input.vue'
import Button from './Button.vue'

const dropzone = ref()
const modalShow = ref(false);
const props = defineProps({
    disabledForm: Boolean,
    isUpdate: Boolean,
    hasFalesUpdating: Function,
})
const body = defineModel('body')
const files = defineModel('files')
const emits = defineEmits(['storeMessage','updateMessage'])
function show() {
    modalShow.value = true
}
function close() {
    //files.value = []
    modalShow.value = false
}
</script>

<style>
.dropzone__progress {
    display: none !important;
}

.dropzone__message {
    align-self: center;
}

.dropzone__box {
    min-height: 10rem !important;
}

.dropzone__filename:hover span {
    white-space: normal !important;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.dropzone__item {
    overflow: hidden;
}
</style>
