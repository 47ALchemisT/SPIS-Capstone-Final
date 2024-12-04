<template>
    <div>
        <!-- Navbar -->
        <nav class="bg-blue-700 fixed top-0 left-0 right-0 z-50">
            <div class="px-4 sm:px-6">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex space-x-2 sm:space-x-3 items-center">
                        <img class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover cursor-pointer" src='/resources/assets/ssclogo.jpg' alt="User dropdown">
                        <span class="text-lg sm:text-xl font-semibold text-white">SPSIS</span>
                    </div>
                    <div class="ml-2 sm:ml-3 relative flex items-center gap-2 sm:gap-3">
                        <div class="hidden sm:block">
                            <p class="text-white text-sm font-semibold">{{ fullName }}</p>
                            <p class="text-white text-xs text-right">Facilitator</p>
                        </div>
                        <Menu as="div" class="relative inline-block text-left">
                                    <MenuButton class="mt-1.5 inline-flex items-center justify-center rounded-lg text-sm font-medium text-gray-700 ">
                                        <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover ring-2 ring-blue-400 cursor-pointer" src='/resources/assets/user.png' alt="User dropdown">

                                    </MenuButton>
                                    <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                    >
                                    <MenuItems class="origin-top-right absolute right-0 z-10 mt-2 w-44 rounded-md shadow bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <div class="py-1">
                                        <MenuItem v-slot="{ active }">
                                            <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 border-b text-sm hover:bg-white font-medium']">
                                                {{ facilitator.student.first_name }} {{ facilitator.student.last_name }}
                                                <p class="text-xs text-gray-500 font-normal">{{ facilitator.student.univ_email }}</p>
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
