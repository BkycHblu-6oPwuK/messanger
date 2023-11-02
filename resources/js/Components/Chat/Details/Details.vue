<script setup>
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore()
const props = defineProps({
    chat: Object,
})
const users = ref([])
const usersNotNull = computed(() => users.value.length > 0)
const usersStoresNotNull = computed(() => store.state.users == null)
const activeChat = computed(() => store.getters.getActiveDetailsChat != props.chat.id)

const getUsers = async () => {
    const res = await axios.post(route('chats.getUsersForIds'),{users_ids:props.chat?.users_id})
    store.commit('setUsers',res.data)
    store.commit('setActiveDetailsChat',props.chat.id)
    setUsers()
}
const setUsers = () => {
    users.value = store.getters.getUsers
}

onMounted(() => {
    console.log(store.getters.getActiveDetailsChat)
    console.log(props.chat.id)
    if(props.chat.users_id && (usersStoresNotNull.value || activeChat.value)) {
        getUsers()
    } else if(props.chat.users_id) {
        setUsers()
    }
});
</script>

<template>
    <div class="bg-white p-6 rounded-lg shadow-lg sm:p-8 md:p-10 lg:p-12 space-y-8 sm:space-y-10 transition-all duration-300">
        <div class="flex items-center space-x-6 md:space-x-8">
            <img src="/storage/users/1/photo_2023-09-28_18-44-30.jpg" class="border-4 border-gray-300 object-cover rounded-full w-20 h-20 md:w-24 md:h-24 transform hover:scale-110 transition-all duration-300"
                alt="User's Avatar">
            <div class="font-semibold text-xl md:text-2xl lg:text-3xl text-gray-800">
                <h2 class="leading-tight">{{ chat.name }}</h2>
                <p class="text-base md:text-lg text-gray-600 leading-tight">{{ chat.description }}</p>
            </div>
        </div>

        <div class="divide-y divide-gray-300">
            <div class="py-6 text-base md:text-lg leading-7 space-y-5 text-gray-700">
                <p>Некоторая информация о пользователе или другой контент.</p>
            </div>
            <div class="pt-6 pb-4 text-base md:text-lg leading-relaxed">
                <a href="#" class="font-medium text-blue-700 hover:text-blue-900 transition-colors duration-200" rel="noopener">Еще какая-то ссылка или действие</a>
            </div>
        </div>

        <div v-if="usersNotNull" class="mt-8">
            <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">Участники группы:</h3>
            <ul class="space-y-5 mt-5">
                <li v-for="user in users" :key="user.id" class="flex items-center space-x-4">
                    <img src="/storage/users/1/photo_2023-09-28_18-44-30.jpg" alt="Member's Avatar"
                        class="border-4 object-cover border-gray-300 rounded-full w-12 h-12 md:w-16 md:h-16 transform hover:scale-110 transition-all duration-300">
                    <span class="text-gray-800 font-medium text-lg">{{ user.name }}</span>
                </li>
            </ul>
        </div>
    </div>
</template>




