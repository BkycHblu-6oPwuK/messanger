<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import FileUploadComponent from './FileUploadComponent.vue'
import Input from './Input.vue'
import Button from './Button.vue'
import axios from 'axios';
import { watch } from 'vue';
import { findIndexById } from '@/Functions/helpers'
//import cloneDeep from 'lodash/cloneDeep'
import { toRaw } from 'vue';

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
    deletesFiles: [],
})

watch(() => props.formOptions.messageId, (newValue) => {
    if (newValue != null) {
        let index = findIndexById(props.data.messages, newValue)
        if (index) {
            //let message = JSON.parse(JSON.stringify(props.data.messages[index]))
            //let message = cloneDeep(props.data.messages[index])
            let message = structuredClone(toRaw(props.data.messages[index]))
            props.formOptions.isUpdate = true
            props.formOptions.index = index
            form.body = message.body
            form.files = message.gallary
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
            props.formOptions.reset()
            hasFales.value = false
            emits('scrollToBottom');
        },
        onError: (res) => {
            console.log(res)
        }
    })
}
const updateMessage = (files, closeModal) => {
    form.files = []
    if (files) {
        Object.values(files).forEach(file => {
            if (file.accepted) {
                form.files.push(file.file)
            }
        });
    }
    axios.post(route('chats.update', props.formOptions.messageId), form,{ headers: {'Content-Type': 'multipart/form-data', },}).then((res) => {
        console.log(res)
        if (res.data.update == true) {
            if (closeModal) {
                closeModal()
            }
            props.data.messages[props.formOptions.index].body = form.body
            if(res.data.files){
                res.data.files.forEach(item => {
                    props.data.messages[props.formOptions.index].gallary.push(item)
                })
            }

            if(form.deletesFiles.length > 0){
                form.deletesFiles.forEach(item => {
                    let index = findIndexById(props.data.messages[props.formOptions.index].gallary,item)
                    props.data.messages[props.formOptions.index].gallary.splice(index,1)
                })
            }
        }
        if(res.data.delete == true){
            props.data.messages.splice(props.formOptions.index,1)
        }
        props.formOptions.reset()
        form.reset('body', 'files','deletesFiles')
    })
}
const hasFalesUpdating = () => hasFales.value = true;
const fileDelete = (index, id) => {
    form.files.splice(index, 1)
    form.deletesFiles.push(id)
}
</script>
<template>
    <div @keyup.enter="storeMessage" class="sticky bottom-0 bg-white border-gray-200 mt-1 :mt-4">
        <div v-if="props.formOptions.isUpdate && form.files.length > 0"
            class=" bg-white border-gray-200 px-2 pt-2 lg:px-20">
            <div class="flex items-center justify-start flex-wrap -mx-2">
                <div v-for="(file, index) in form.files" :key="file.id">
                    <div class="mx-2 mb-1 w-16 h-16 relative group" v-if="file.media_path">
                        <img v-if="file?.type?.startsWith('image/')" :src="file.media_path" alt="Updated Message Image"
                            class="w-full h-full object-cover rounded-lg shadow group-hover:opacity-80 transition-opacity duration-300 ease-in-out">
                        <img v-else-if="file?.type?.startsWith('video/')" :src="file.preview_path" alt="Updated Message Image"
                            class="w-full h-full object-cover rounded-lg shadow group-hover:opacity-80 transition-opacity duration-300 ease-in-out">
                        <img v-else src="https://avatars.mds.yandex.net/i?id=b7cddfb6d4bb7ba146dd3eedd4619f7d6ab68546-10836825-images-thumbs&n=13" alt="Updated Message Image"
                            class="w-full h-full object-cover rounded-lg shadow group-hover:opacity-80 transition-opacity duration-300 ease-in-out">
                        <div @click="fileDelete(index,file.id)"
                            class="absolute top-0 right-0 backdrop-blur-sm rounded-lg bg-white/30 opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity duration-300 ease-in-out">
                            <svg class="w-5 h-5 text-gray-600 dark:text-white/80" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex p-1 lg:p-6">
            <FileUploadComponent @storeMessage="storeMessage" @updateMessage="updateMessage" :isUpdate="props.formOptions.isUpdate"
                v-model:body="form.body" v-model:files="form.files" :disabledForm="disabledForm"
                :hasFalesUpdating="hasFalesUpdating">
            </FileUploadComponent>
            <Input v-model="form.body"></Input>
            <Button v-if="!props.formOptions.isUpdate" @click="storeMessage" :disabled="!disabledForm"
                :isUpdate="props.formOptions.isUpdate"
                :class="!disabledForm ? 'pointer-events-none opacity-70' : ''"></Button>
            <Button v-if="props.formOptions.isUpdate" @click="updateMessage" :isUpdate="props.formOptions.isUpdate"></Button>
        </div>
    </div>
</template>
