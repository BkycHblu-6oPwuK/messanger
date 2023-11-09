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
import { findIndexById } from '@/Functions/helpers'

const props = defineProps({
    data: Object,
    formOptions: Object,
})
const emits = defineEmits(['scrollToBottom'])
const hasFales = ref(false)
const disabledForm = computed(() => (form.body !== '' || hasFales.value))
const form = useForm({
    body: '',
    chat_group_id: props.data.chat?.id,
    files: [],
})

watch(() => props.formOptions.messageId, (newValue) => {
    if (newValue != null) {
        let index = findIndexById(props.data.messages, newValue)
        console.log(findIndexById(props.data.messages, newValue))
        console.log(index)
        console.log(newValue)
        if (index) {
            props.formOptions.isUpdate = true
            props.formOptions.index = index
            form.body = props.data.messages[index].body
        }
    }
})

const storeMessage = (files, closeModal) => {
    if (files) {
        Object.values(files).forEach(file => {
            if (file.accepted) {
                form.files.push(file.file)
            }
        });
    }
    form.post(route('chats.store'), {
        onSuccess: (res) => {
            if (closeModal) {
                closeModal()
            }
            form.reset('body', 'files')
            formOptions.reset()
            hasFales.value = false
            emits('scrollToBottom');
        },
        onError: (res) => {
            console.log(res)
        }
    })
}
const updateMessage = (files, closeModal) => {
    if (files) {
        Object.values(files).forEach(file => {
            if (file.accepted) {
                form.files.push(file.file)
            }
        });
    }
    axios.patch(route('chats.update', props.formOptions.messageId), form).then((res) => {
        if (res.data === 1) {
            props.data.messages[props.formOptions.index].body = form.body
            props.formOptions.reset()
            form.reset('body', 'files')
        }
    })
}
const hasFalesUpdating = () => hasFales.value = true;
</script>
<template>
    <div @keyup.enter="storeMessage" class="sticky bottom-0 bg-white border-gray-200 mt-1 :mt-4">
        <div class="p-fileupload-content"></div>
        <div class="flex p-1 lg:p-6">
            <FileUploadComponent @storeMessage="storeMessage" :isUpdate="props.formOptions.isUpdate"
                v-model:body="form.body" v-model:files="form.files" :disabledForm="disabledForm"
                :hasFalesUpdating="hasFalesUpdating">
            </FileUploadComponent>
            <Input v-model="form.body"></Input>
            <Button v-if="!props.formOptions.isUpdate" @click="storeMessage" :disabled="!disabledForm"
                :isUpdate="props.formOptions.isUpdate"
                :class="!disabledForm ? 'pointer-events-none opacity-70' : ''"></Button>
            <Button v-if="props.formOptions.isUpdate" @click="updateMessage" :disabled="!disabledForm"
                :isUpdate="props.formOptions.isUpdate"
                :class="!disabledForm ? 'pointer-events-none opacity-70' : ''"></Button>
        </div>
    </div></template>
