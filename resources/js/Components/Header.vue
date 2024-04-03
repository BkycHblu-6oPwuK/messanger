<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ref } from 'vue';
import { useStore } from 'vuex';
const props = defineProps({
    auth: Object,
})
const menuOpen = ref(false);
const toggleMenu = () => menuOpen.value = !menuOpen.value;
</script>
<template>
    <header>
        <nav class="bg-white border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800">
            <div class="flex justify-center items-center mx-auto">
                <!-- Лого будет всегда слева -->
                <div v-if="false" class="hidden lg:block w-32"></div>

                <div v-if="props?.auth?.user" class="flex justify-center items-center space-x-8">
                    <Link :href="route('chats.index')"
                        class="text-gray-700 hover:text-primary-700 dark:text-gray-400 lg:dark:hover:text-white">Messages
                    </Link>
                    <Link :href="route('users.index')"
                        class="text-gray-700 hover:text-primary-700 dark:text-gray-400 lg:dark:hover:text-white">Profile
                    </Link>
                    <Link :href="route('logout')" method="post" as="button"
                        class="text-gray-700 hover:text-primary-700 dark:text-gray-400 lg:dark:hover:text-white">Log Out
                    </Link>
                </div>

                <div v-else class="flex justify-center items-center space-x-8">
                    <Link :href="route('login')"
                        class="text-gray-700 hover:text-primary-700 dark:text-gray-400 lg:dark:hover:text-white">Login
                    </Link>
                    <Link :href="route('register')"
                        class="text-gray-700 hover:text-primary-700 dark:text-gray-400 lg:dark:hover:text-white">Register
                    </Link>
                </div>

                <div v-if="false" class="flex items-center space-x-4">
                    <div class="flex items-end lg:hidden mb-4 lg:mb-0">
                        <span v-if="!props?.auth?.user">
                            <Link :href="route('login')"
                                class="text-gray-800 dark:text-white hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">
                            Log in</Link>
                            <a :href="route('register')"
                                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">Get
                                started</a>
                        </span>
                        <button @click="toggleMenu" type="button"
                            class="lg:hidden inline-flex items-center p-2 text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <div v-if="menuOpen" class="w-full bg-white border-t mt-2 border-gray-200 lg:hidden">
                        <div v-if="props?.auth?.user" class="flex flex-col">
                            <Link :href="route('chats.index')" class="px-4 py-2 text-gray-700 hover:bg-gray-50">Messages
                            </Link>
                            <Link :href="route('users.index')" class="px-4 py-2 text-gray-700 hover:bg-gray-50">Profile
                            </Link>
                            <Link :href="route('logout')" method="post" as="button"
                                class="px-4 py-2 text-gray-700 hover:bg-gray-50">Log Out</Link>
                        </div>
                        <span v-if="!props?.auth?.user" class="flex flex-col">
                            <Link :href="route('login')" class="px-4 py-2 text-gray-800 hover:bg-gray-50">Log in</Link>
                            <a :href="route('register')"
                                class="px-4 py-2 text-white bg-primary-700 hover:bg-primary-800">Get
                                started</a>
                        </span>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>
