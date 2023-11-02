export const TRANSITIONS = {
    overlay: {
        enterFromClass: 'opacity-0 scale-75',
        enterActiveClass: 'transition-transform transition-opacity duration-150 ease-in',
        leaveActiveClass: 'transition-opacity duration-150 ease-linear',
        leaveToClass: 'opacity-0'
    }
};

const MyDesignMultiselect = {
    multiselect: {
        root: ({ props }) => ({
            class: [
                'inline-flex cursor-pointer select-none',
                'bg-white border border-gray-400 transition-colors duration-200 ease-in-out rounded-md',
                'w-full md:w-80',
                { 'opacity-60 select-none pointer-events-none cursor-default': props?.disabled }
            ]
        }),
        labelContainer: 'overflow-hidden flex flex-auto cursor-pointer',
        label: ({ props }) => ({
            class: [
                'block overflow-hidden whitespace-nowrap cursor-pointer overflow-ellipsis',
                'text-gray-800',
                'p-3 transition duration-200',
                {
                    '!p-3': props.display !== 'chip' && (props?.modelValue == null || props?.modelValue == undefined),
                    '!py-1.5 px-3': props.display == 'chip' && props?.modelValue !== null,
                    '!p-3': props.display == 'chip' && props?.modelValue == null
                }
            ]
        }),
        token: {
            class: ['py-1 px-2 mr-2 bg-gray-300 text-gray-700 rounded-full', 'cursor-default inline-flex items-center']
        },
        removeTokenIcon: 'ml-2',
        trigger: {
            class: ['flex items-center justify-center shrink-0', 'bg-transparent text-gray-600 w-12 rounded-tr-lg rounded-br-lg']
        },
        panel: {
            class: ['bg-white text-gray-700 border-0 rounded-md shadow-lg']
        },
        header: {
            class: ['p-3 border-b border-gray-300 text-gray-700 bg-gray-100 rounded-t-lg', 'flex items-center justify-between']
        },
        headerCheckboxContainer: {
            class: ['inline-flex cursor-pointer select-none align-bottom relative', 'mr-2', 'w-6 h-6']
        },
        headerCheckbox: ({ context }) => ({
            class: [
                'flex items-center justify-center',
                'border-2 w-6 h-6 text-gray-600 rounded-lg transition-colors duration-200',
                'hover:border-blue-500 focus:outline-none focus:shadow-[0_0_0_0.2rem_rgba(191,219,254,1)]',
                {
                    'border-gray-300 bg-white': !context?.selected,
                    'border-blue-500 bg-blue-500': context?.selected
                }
            ]
        }),
        headercheckboxicon: 'w-4 h-4 transition-all duration-200 text-white text-base',
        closeButton: {
            class: [
                'flex items-center justify-center overflow-hidden relative',
                'w-8 h-8 text-gray-500 border-0 bg-transparent rounded-full transition duration-200 ease-in-out mr-2 last:mr-0',
                'hover:text-gray-700 hover:bg-gray-200',
                'focus:outline-none focus:shadow-[0_0_0_0.2rem_rgba(191,219,254,1)]'
            ]
        },
        closeButtonIcon: 'w-4 h-4 inline-block',
        wrapper: {
            class: ['max-h-[200px] overflow-auto', 'bg-white text-gray-700 border-0 rounded-md shadow-lg']
        },
        list: 'py-3 list-none m-0',
        item: ({ context }) => ({
            class: [
                'cursor-pointer font-normal overflow-hidden relative flex items-center whitespace-nowrap',
                'm-0 p-3 border-0 transition-shadow duration-200 rounded-none',
                'hover:text-gray-700 hover:bg-gray-200',
                {
                    'text-gray-700': !context.focused && !context.selected,
                    'bg-gray-300 text-gray-700': context.focused && !context.selected,
                    'bg-blue-400 text-blue-700': context.focused && context.selected,
                    'bg-blue-50 text-blue-700': !context.focused && context.selected
                }
            ]
        }),
        checkboxContainer: {
            class: ['inline-flex cursor-pointer select-none align-bottom relative', 'mr-2', 'w-6 h-6']
        },
        checkbox: ({ context }) => ({
            class: [
                'flex items-center justify-center',
                'border-2 w-6 h-6 text-gray-600 rounded-lg transition-colors duration-200',
                'hover:border-blue-500 focus:outline-none focus:shadow-[0_0_0_0.2rem_rgba(191,219,254,1)]',
                {
                    'border-gray-300 bg-white': !context?.selected,
                    'border-blue-500 bg-blue-500': context?.selected
                }
            ]
        }),
        checkboxicon: 'w-4 h-4 transition-all duration-200 text-white text-base',
        itemgroup: {
            class: ['m-0 p-3 text-gray-800 bg-white font-bold', 'cursor-auto']
        },
        filtercontainer: 'relative',
        filterinput: {
            class: [
                'pr-7 -mr-7',
                'w-full',
                'font-sans text-base text-gray-700 bg-white py-3 px-3 border border-gray-300 transition duration-200 rounded-lg appearance-none',
                'hover:border-blue-500 focus:outline-none focus:shadow-[0_0_0_0.2rem_rgba(191,219,254,1)]'
            ]
        },
        filtericon: '-mt-2 absolute top-1/2',
        clearicon: 'text-gray-500 right-12 -mt-2 absolute top-1/2',
        transition: TRANSITIONS.overlay
    }
}

export default MyDesignMultiselect
