import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        "./index.html",
        "./src/**/*.{vue,js,ts,jsx,tsx}",
        "./node_modules/primevue/**/*.{vue,js,ts,jsx,tsx}"
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                body: [
                    'Inter',
                    'ui-sans-serif',
                    'system-ui',
                    '-apple-system',
                    'system-ui',
                    'Segoe UI',
                    'Roboto',
                    'Helvetica Neue',
                    'Arial',
                    'Noto Sans',
                    'sans-serif',
                    'Apple Color Emoji',
                    'Segoe UI Emoji',
                    'Segoe UI Symbol',
                    'Noto Color Emoji'
                ],
            },
            colors: {
                primary: {
                    "50":"#eff6ff","100":"#dbeafe","200":"#bfdbfe","300":"#93c5fd","400":"#60a5fa",
                    "500":"#3b82f6","600":"#2563eb","700":"#1d4ed8","800":"#1e40af","900":"#1e3a8a",
                    "950":"#172554"
                }
            },
            maxHeight: {
                '128': '32rem',
                '156': '40rem',
            },
                galleria: {
        root: 'flex flex-col',
        content: 'flex flex-col',
        itemwrapper: 'flex flex-col relative',
        itemcontainer: 'relative flex h-full',
        item: 'flex justify-center items-center h-full w-full',
        thumbnailwrapper: 'flex flex-col overflow-auto shrink-0',
        thumbnailcontainer: {
            class: ['flex flex-row', 'bg-black/90 p-4']
        },
        previousthumbnailbutton: {
            class: [
                'self-center flex shrink-0 justify-center items-center overflow-hidden relative',
                'm-2 bg-transparent text-white w-8 h-8 transition duration-200 ease-in-out rounded-full',
                'hover:bg-white/10 hover:text-white',
                'focus:outline-none focus:outline-offset-0 focus:shadow-[0_0_0_0.2rem_rgba(191,219,254,1)]'
            ]
        },
        thumbnailitemscontainer: 'overflow-hidden w-full',
        thumbnailitems: 'flex',
        thumbnailitem: {
            class: ['overflow-auto flex items-center justify-center cursor-pointer opacity-50', 'flex-1 grow-0 shrink-0 w-20', 'hover:opacity-100 hover:transition-opacity hover:duration-300']
        },
        nextthumbnailbutton: {
            class: [
                'self-center flex shrink-0 justify-center items-center overflow-hidden relative',
                'm-2 bg-transparent text-white w-8 h-8 transition duration-200 ease-in-out rounded-full',
                'hover:bg-white/10 hover:text-white',
                'focus:outline-none focus:outline-offset-0 focus:shadow-[0_0_0_0.2rem_rgba(191,219,254,1)]'
            ]
        },
        indicators: {
            class: ['flex items-center justify-center', 'p-4']
        },
        indicator: 'mr-2',
        indicatorbutton: ({ context }) => ({
            class: [
                'w-4 h-4 transition duration-200 rounded-full',
                'focus:outline-none focus:outline-offset-0 focus:shadow-[0_0_0_0.2rem_rgba(191,219,254,1)] dark:focus:shadow-[0_0_0_0.2rem_rgba(147,197,253,0.5)]',
                {
                    'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600': !context.highlighted,
                    'bg-blue-500 hover:bg-blue-600': context.highlighted
                }
            ]
        }),
        mask: {
            class: ['fixed top-0 left-0 w-full h-full', 'flex items-center justify-center', 'bg-black bg-opacity-90']
        },
        closebutton: {
            class: [
                'absolute top-0 right-0 flex justify-center items-center overflow-hidden m-2',
                'text-white bg-transparent w-12 h-12 rounded-full transition duration-200 ease-in-out',
                'hover:text-white hover:bg-white/10',
                'focus:outline-none focus:outline-offset-0 focus:shadow-[0_0_0_0.2rem_rgba(191,219,254,1)]'
            ]
        },
        closeicon: 'w-6 h-6',
        previousitembutton: {
            class: [
                'inline-flex justify-center items-center overflow-hidden',
                'bg-transparent text-white w-16 h-16 transition duration-200 ease-in-out rounded-md mx-2',
                'fixed top-1/2 mt-[-0.5rem]',
                'left-0',
                'hover:bg-white/10 hover:text-white',
                'focus:outline-none focus:outline-offset-0 focus:shadow-[0_0_0_0.2rem_rgba(191,219,254,1)]'
            ]
        },
        nextitembutton: {
            class: [
                'inline-flex justify-center items-center overflow-hidden',
                'bg-transparent text-white w-16 h-16 transition duration-200 ease-in-out rounded-md mx-2',
                'fixed top-1/2 mt-[-0.5rem]',
                'right-0',
                'hover:bg-white/10 hover:text-white',
                'focus:outline-none focus:outline-offset-0 focus:shadow-[0_0_0_0.2rem_rgba(191,219,254,1)]'
            ]
        },
        caption: {
            class: ['absolute bottom-0 left-0 w-full', 'bg-black/50 text-white p-4']
        },
        transition: {
            enterFromClass: 'opacity-0 scale-75',
            enterActiveClass: 'transition-all duration-150 ease-in-out',
            leaveActiveClass: 'transition-all duration-150 ease-in',
            leaveToClass: 'opacity-0 scale-75'
        }
    }
        },
    },
    plugins: [forms],
};
