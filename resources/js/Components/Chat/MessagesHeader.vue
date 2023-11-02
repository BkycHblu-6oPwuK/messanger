<script setup>
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { defineAsyncComponent } from 'vue';
import { computed } from 'vue';
import { ref } from 'vue';
import { onMounted } from 'vue';
import { onUnmounted } from 'vue';
import { useStore } from 'vuex';
const store = useStore();
const props = defineProps({
    isLargeScreen: Boolean,
    chat: Object,
})

const settingsMenuOpen = ref(false)
function toggleSettingsMenu(event) {
    if (!event.target.closest('.settings-dropdown')) {
        settingsMenuOpen.value = !settingsMenuOpen.value;
    }
}
function closeMenu(event) {
    if (!event.target.closest('.settings-dropdown')) {
        settingsMenuOpen.value = false;
    }
}
onMounted(() => {
    document.addEventListener('click', closeMenu);
})

onUnmounted(() => {
    document.removeEventListener('click', closeMenu);
});

const detailsComponent = defineAsyncComponent(() => import('../../Pages/Chats/Details.vue'));
const showDetailsRef = computed(() => store.getters.getShowDetails)

const getDetails = () => {
    axios.get(route('chats.details.api', props.chat.id))
        .then(res => {
            console.log(res)
        })
}

const showDetails = () => {
    store.commit('setShowDetails',true)
}

</script>

<template>
    <div class="bg-white flex items-center justify-between py-1 border-b border-gray-200 sticky top-0 z-10">
        <Link v-if="!props.isLargeScreen" :href="route('chats.index')" class="px-3 rounded-lg transition">
        ←
        </Link>

        <Link v-if="!isLargeScreen" :href="route('chats.details', props.chat.id)" class="flex items-center space-x-3">
        <img src="https://s.ekabu.ru/localStorage/post/e3/df/40/66/e3df4066.jpg" alt="User Avatar"
            class="w-10 h-10 rounded-full border-2 border-blue-500 object-cover">
        <span class="text-xl font-medium text-gray-800">{{ props.chat.name }}</span>
        </Link>

        <div v-if="isLargeScreen" @click="showDetails" class="flex items-center space-x-3">
            <img src="https://s.ekabu.ru/localStorage/post/e3/df/40/66/e3df4066.jpg" alt="User Avatar"
                class="w-10 h-10 rounded-full border-2 border-blue-500 object-cover">
            <span class="text-xl font-medium text-gray-800">{{ props.chat.name }}</span>
        </div>

        <button v-if="!props.isLargeScreen" @click.stop="toggleSettingsMenu" class="px-3 rounded-lg transition">
            ⋮
        </button>
    </div>
    <div v-if="settingsMenuOpen"
        class="settings-dropdown absolute right-5 top-16 bg-white shadow-lg rounded-md w-64 border border-gray-200 z-20">
        <ul class="divide-y divide-gray-200">
            <li><a href="#" class="block px-5 py-3 hover:bg-gray-100 transition">Пункт 1</a></li>
            <li><a href="#" class="block px-5 py-3 hover:bg-gray-100 transition">Пункт 2</a></li>
            <li><a href="#" class="block px-5 py-3 hover:bg-gray-100 transition">Пункт 3</a></li>
        </ul>
    </div>
    <detailsComponent v-if="showDetailsRef" :chat="props.chat" :modalShow="true"></detailsComponent>
</template>
