import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import DropZone from 'dropzone-vue';
import store from './store';
import Vue3TouchEvents from "vue3-touch-events";
import PrimeVue from 'primevue/config';
import VuePlyr from 'vue-plyr'
import 'vue-plyr/dist/vue-plyr.css';

import 'primeicons/primeicons.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const handleResize = () => {
    const isLarge = window.innerWidth > 1024;
    store.commit('setIsLargeScreen', isLarge);
  };

window.addEventListener('resize', handleResize);
handleResize();

const app = createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(DropZone)
            .use(store)
            .use(PrimeVue)
            .use(VuePlyr)
            .use(Vue3TouchEvents)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
