
<script setup>
import { onUpdated, ref } from 'vue';
import { onMounted } from 'vue';
import Modal from './../Modal.vue';
import { useForm } from '@inertiajs/vue3';
import MultiSelect from 'primevue/multiselect';
import MyDesignMultiselect from '@/ThemesPrime/multiselect';
import { computed } from 'vue';
const form = useForm(({
    name: '',
    description: '',
    members: [],
}))

const disabledForm = computed(() => (form.name !== '' && form.members.length !== 0))

const props = defineProps({
    friends: Object
})
const modalShow = ref(false);
function show() {
    modalShow.value = true
}
function close() {
    modalShow.value = false
}
function store() {
    form.post(route('group.store'), {
        onSuccess: (res) => {
            form.reset('description','members','name')
            close()
        },
        onError: (res) => {
            console.log(res)
        }
    })
}
</script>
<template>
    <button @click="show" class="border border-gray-400 p-2 rounded bg-gray-100 bg-opacity-10 hover:bg-opacity-20">
        Create group
    </button>
    <Modal :show="modalShow" @close="close">
        <div class="p-4">
            <h1 class="text-xl font-semibold mb-4">Create Group</h1>
            <form @submit.prevent="store">

                <div class="mb-4">
                    <label for="groupName" class="block text-sm font-medium mb-2 text-gray-600">Group Name:</label>
                    <input type="text" id="groupName" v-model="form.name"
                        class="border rounded-md w-full p-2 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium mb-2 text-gray-600">Description:</label>
                    <textarea id="description" v-model="form.description" rows="3"
                        class="border rounded-md w-full p-2 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-4">
                    <label for="members" class="block text-sm font-medium mb-2 text-gray-600">Select Members:</label>
                    <MultiSelect unstyled :pt="MyDesignMultiselect.multiselect" v-model="form.members"
                        :options="props.friends" optionLabel="name" placeholder="Select Countries" display="chip"
                        class="w-full md:w-20rem" aria-describedby="text-error">

                        <!-- <template #option="slotProps">
                            <div class="flex align-items-center">
                                {{ slotProps.option.name }}
                            </div>
                        </template>

                        <template #chip="slotProps">
                            {{ slotProps.value.name }}
                        </template> -->

                    </MultiSelect>
                </div>

                <button :disabled="!disabledForm" :class="!disabledForm ? 'pointer-events-none opacity-70' : ''"
                    type="submit"
                    class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-200">
                    Create
                </button>
            </form>
        </div>
    </Modal>
</template>
