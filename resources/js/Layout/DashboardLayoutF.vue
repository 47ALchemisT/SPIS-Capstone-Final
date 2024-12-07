<template>
    <div>
        <!-- Navbar -->
        <nav class="bg-blue-700 fixed top-0 left-0 right-0 z-50 shadow-md">
            <div class="px-4 sm:px-6">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex space-x-2 sm:space-x-3 items-center">
                        <img class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover cursor-pointer shadow-md ring-2 ring-blue-400" src='/resources/assets/ssclogo.jpg' alt="SSC Logo">
                        <span class="text-lg sm:text-xl font-semibold text-white tracking-wide">SPSIS</span>
                    </div>
                    <div class="ml-2 sm:ml-3 relative flex items-center gap-2 sm:gap-3">
                        <div class="hidden sm:block">
                            <p class="text-white text-sm font-semibold tracking-wide">{{ fullName }}</p>
                            <p class="text-blue-100 text-xs text-right">Facilitator</p>
                        </div>
                        <Menu as="div" class="relative inline-block text-left">
                            <MenuButton class="mt-1.5 inline-flex items-center justify-center rounded-lg text-sm font-medium focus:outline-none">
                                <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown" 
                                    class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover ring-2 ring-blue-400 cursor-pointer hover:ring-blue-300 transition-all duration-200 shadow-md" 
                                    src='/resources/assets/user.png' alt="User dropdown">
                            </MenuButton>
                            <transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-150"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1">
                                        <MenuItem v-slot="{ active }">
                                            <a href="#" :class="[
                                                active ? 'bg-gray-50' : '',
                                                'block px-4 py-3 border-b text-sm hover:bg-gray-50 transition-colors duration-150'
                                            ]">
                                                <div class="font-medium text-gray-900">{{ facilitator.student.first_name }} {{ facilitator.student.last_name }}</div>
                                                <div class="text-xs text-gray-500 mt-0.5">{{ facilitator.student.univ_email }}</div>
                                            </a>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                            <a href="#" :class="[
                                                active ? 'bg-gray-50 text-gray-900' : 'text-gray-700',
                                                'block px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors duration-150'
                                            ]">
                                                <div class="flex items-center">
                                                    <i class="fa-solid fa-gear w-4 h-4 mr-2"></i>
                                                    Settings
                                                </div>
                                            </a>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                            <Link 
                                                :href="route('logout')" 
                                                method="post"
                                                as="button"
                                                class="w-full text-left px-4 py-2.5 text-sm transition-colors duration-150"
                                                :class="[
                                                    active ? 'bg-gray-50 text-red-600' : 'text-red-600',
                                                    'hover:bg-gray-50'
                                                ]"
                                            >
                                                <div class="flex items-center">
                                                    <i class="fa-solid fa-right-from-bracket w-4 h-4 mr-2"></i>
                                                    Sign out
                                                </div>
                                            </Link>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <div class="container mx-auto flex-1 px-4 sm:px-6 lg:px-8 xl:px-48 mt-16 main-content">
            <slot />
        </div>
    </div>
</template>

<script setup>
    import { Link, usePage } from '@inertiajs/vue3';
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { ChevronDownIcon } from '@heroicons/vue/20/solid'
    import { computed } from 'vue';

    const { url: currentRoute } = usePage();

    const props = defineProps({
        facilitator: {
            type: Object,
            required: true,
            validator(value) {
                return value && value.student && 
                       typeof value.student.first_name === 'string' && 
                       typeof value.student.last_name === 'string';
            }
        }
    });

    const fullName = computed(() => {
        if (!props.facilitator?.student) return 'Loading...';
        return `${props.facilitator.student.first_name} ${props.facilitator.student.last_name}`;
    });
</script>

<style scoped>
</style>
