<template>
    <div>
        <!-- Navbar -->
        <nav class="bg-blue-700 fixed top-0 left-0 right-0 z-50">
            <div class="px-6">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex space-x-3 items-center">
                        <img class="w-10 h-10 rounded-full object-cover cursor-pointer" src='/resources/assets/logoMSU.png' alt="User dropdown">
                        <span class="text-xl font-semibold text-white">SPIS</span>
                    </div>
                    <div class="ml-3 relative flex items-center gap-3">
                        <div>
                            <p class="text-white text-right text-sm font-semibold ">{{ comHead.student.first_name }} {{ comHead.student.last_name }}</p>
                            <p class="text-white text-xs text-right ">College Committee Head</p>
                        </div>
                        <Menu as="div" class="relative inline-block text-left">
                                    <MenuButton class="mt-1.5 inline-flex items-center justify-center w-full rounded-lg  text-sm font-medium text-gray-700 ">
                                        <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown" class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-400 cursor-pointer" src='/resources/assets/user.png' alt="User dropdown">

                                    </MenuButton>
                                    <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                    >
                                    <MenuItems class="origin-top-right absolute right-0 z-10 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <div class="py-1">
                                            <MenuItem v-slot="{ active }">
                                                <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 border-b text-sm hover:bg-white font-medium']">
                                                    {{ comHead.student.first_name }} {{ comHead.student.last_name }}
                                                    <p class="text-xs text-gray-500 font-normal">{{ comHead.student.univ_email }}</p>
                                                </a>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                                    Settings
                                                </a>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                                    <Link 
                                                        :href="route('logout')" 
                                                        method="post"
                                                        as="button"
                                                        class="flex w-full rounded-lg transition-all"
                                                        :class="{'text-white': currentRoute === route('logout'), 'text-gray-700 ': currentRoute !== route('logout')}"
                                                    >
                                                        <span class="">Sign out</span>
                                                    </Link>
                                                </a>
    
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
        <div class="contatiner flex-1 px-48 mt-16">
            <slot />
        </div>
    </div>

</template>

<script setup>
    import { Link, usePage } from '@inertiajs/vue3';
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { ChevronDownIcon } from '@heroicons/vue/20/solid'

    const props = defineProps({
        comHead: Object,
    });

    const { url: currentRoute } = usePage();
</script>

<style scoped>
</style>
