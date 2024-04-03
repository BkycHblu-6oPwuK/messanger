const MyDesignTextarea = {
    textarea: {
        root: ({ context }) => ({
            class: [
                'm-0',
                'text-base text-gray-800 dark:text-gray-300 bg-white dark:bg-gray-100 p-3 border border-gray-300 dark:border-blue-900/40 transition-colors duration-200 appearance-none ',
                'hover:border-blue-500 focus:outline-none focus:outline-offset-0 focus:shadow-[0_0_0_0.2rem_rgba(191,219,254,1)] dark:focus:shadow-[0_0_0_0.2rem_rgba(147,197,253,0.5)]',
                'h-14 overflow-auto resize-none w-full', // Добавленные классы
                { 'opacity-60 select-none pointer-events-none cursor-default': context.disabled }
            ],
        })
    }
}



export default MyDesignTextarea
