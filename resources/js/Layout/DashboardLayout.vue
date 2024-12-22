<template>
    <div>
        <!-- Navbar -->
        <nav class="bg-blue-700 fixed top-0 left-0 right-0 z-50 shadow-md">
            <div class="px-4 sm:px-6">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Hamburger menu for both mobile and desktop -->
                        <button 
                            @click="toggleSidebar" 
                            class="text-white p-2 rounded-lg hover:bg-blue-600 transition-colors duration-200"
                        >
                            <i :class="['fa-solid', isSidebarOpen ? 'fa-bars' : 'fa-times', 'w-6 h-6']"></i>
                        </button>
                        
                        <div class="flex-shrink-0 flex space-x-2 sm:space-x-3 items-center ml-2">
                            <img class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover cursor-pointer shadow-md ring-2 ring-blue-400" src='/resources/assets/ssclogo.jpg' alt="SSC Logo">
                            <span class="text-lg sm:text-xl font-semibold text-white tracking-wide">SPSIS</span>
                        </div>
                    </div>
                    
                    <div class="ml-2 sm:ml-3 relative flex items-center gap-2 sm:gap-3">
                        <div class="hidden sm:block">
                            <p class="text-white text-sm font-semibold tracking-wide">Supreme Student Council</p>
                            <p class="text-blue-100 text-xs text-right">Admin</p>
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
                                            <Link 
                                                :href="route('admin.account-settings')"
                                                class="block px-4 py-2.5 text-sm transition-colors duration-150"
                                                :class="[
                                                    active ? 'bg-gray-50 text-gray-900' : 'text-gray-700',
                                                    'hover:bg-gray-50'
                                                ]"
                                            >
                                                <div class="flex items-center">
                                                    <i class="fa-solid fa-gear w-4 h-4 mr-2"></i>
                                                    Account Settings
                                                </div>
                                            </Link>
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
                                                    Logout
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

        <!-- Overlay for mobile only when sidebar is open -->
        <div 
            v-if="isSidebarOpen && isMobile" 
            @click="closeSidebar"
            class="fixed inset-0 bg-black bg-opacity-50 z-30 transition-opacity duration-300 md:hidden"
        ></div>

        <div class="flex pt-16">
            <!-- Sidebar with modified classes for both mobile and desktop -->
            <aside 
                :class="[
                    'fixed top-0 left-0 z-40 h-screen bg-white ring-right ring-1 ring-gray-200 pt-16 transition-all duration-300',
                    isSidebarOpen ? 'w-64' : 'w-0',
                    'overflow-hidden' // Prevent content flash during transition
                ]"
            >
                <!-- Sidebar content with conditional rendering -->
                <div v-if="isSidebarOpen" class="h-full px-4 py-4 overflow-y-auto flex flex-col justify-between space-y-2">
                    <div>
                        <div class="text-xs mb-3 mt-1 text-gray-500 font-medium uppercase tracking-wider">Dashboard</div>
                        <ul class="space-y-1.5" style="margin-bottom: 8px;">
                            <li>
                                <Link 
                                    :href="route('admin.show')" 
                                    class="flex items-center py-2.5 px-3 text-sm rounded-lg transition-all duration-200"
                                    :class="{'bg-blue-600 text-white shadow-sm': currentRoute === '/admin' || currentRoute === '/', 'text-gray-700 hover:bg-blue-50 hover:text-blue-600': currentRoute !== '/admin' && currentRoute !== '/'}"
                                >
                                    <div class="">
                                        <i class="fa-solid fa-chart-pie w-5 h-5"></i>
                                        <span class="ms-3 font-medium">Home</span>
                                    </div>
                                </Link>
                            </li>
                        </ul>
                        <div class="text-xs mb-2 mt-1 text-gray-500 font-medium">Setup</div>
                        <ul class="space-y-1.5">
                            <li>
                                <Link 
                                    href="/sport" 
                                    class="flex items-center py-2.5 px-3 text-sm rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/sport', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/sport'}"
                                >
                                    <div>
                                        <i class="fa-solid fa-medal w-5 h-5"></i>
                                        <span class="ms-3">Sports</span>
                                    </div>
                                </Link>
                                <Link 
                                    href="/points" 
                                    class="flex items-center py-2.5 px-3 text-sm rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/points', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/points'}"
                                >
                                    <div>
                                        <i class="fa-solid fa-trophy w-5 h-5"></i>
                                        <span class="ms-3">Points</span>
                                    </div>
                                </Link>
                                <Link 
                                    href="/venue" 
                                    class="flex items-center py-2.5 px-3 text-sm rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/venue', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/venue'}"
                                >
                                    <div>
                                        <i class="fa-solid fa-location-dot w-5 h-5"></i>
                                        <span class="ms-3">Venues</span>
                                    </div>
                                </Link>
                                <Link 
                                    href="/college" 
                                    class="flex items-center py-2.5 px-3 text-sm rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/college', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/college'}"
                                >
                                    <div>
                                        <i class="fa-solid fa-building-columns w-5 h-5"></i>
                                        <span class="ms-3">Colleges</span>
                                    </div>
                                </Link>
                                <Link 
                                    href="/student" 
                                    class="flex items-center py-2.5 px-3 text-sm rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/student', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/student'}"
                                >
                                    <div>
                                        <i class="fa-solid fa-graduation-cap w-5 h-5"></i>
                                        <span class="ms-3">Students</span>
                                    </div>
                                </Link>
                            </li>
                            <li>
                                <Link 
                                    href="/account" 
                                    class="flex items-center py-2.5 px-3 text-sm rounded-lg transition-all"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/account', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/account'}"
                                >
                                    <div    >
                                        <i class="fa-solid fa-users w-5 h-5"></i>
                                        <span class="ms-3">Accounts</span>
                                    </div>
                                </Link>
                            </li>
                        </ul>
                        <div class="text-xs mb-2 mt-1 text-gray-500 font-medium">Main</div>
                        <ul class="space-y-1.5" style="margin-bottom: 6px;">
                            <li>
                                <Link 
                                    href="/details" 
                                    class="flex items-center py-2.5 px-3  text-sm rounded-lg transition-all"
                                    :class="{
                                    'bg-blue-700 text-white': currentRoute.startsWith('/details'), 
                                    'text-gray-700 hover:bg-blue-100 hover:text-blue-600': !currentRoute.startsWith('/details')
                                    }"
                                >
                                    <div>
                                        <i class="fa-solid fa-flag-checkered w-5 h-5"></i>
                                        <span class="ms-3 font-medium">Palakasan</span>
                                    </div>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>

            <!-- Main content with dynamic margin -->
            <div 
                class="flex-1 transition-all duration-300 p-6"
                :style="{ marginLeft: isSidebarOpen ? '256px' : '0' }"
            >
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';

const { url: currentRoute } = usePage();
const isSidebarOpen = ref(true);
const isMobile = ref(false);

// Toggle sidebar for both mobile and desktop
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

// Close sidebar (for mobile only)
const closeSidebar = () => {
    if (isMobile.value) {
        isSidebarOpen.value = false;
    }
};

// Handle screen resize
const handleResize = () => {
    isMobile.value = window.innerWidth < 768;
    // Don't automatically open sidebar on desktop resize
    // This allows the sidebar to maintain its state across breakpoints
};

onMounted(() => {
    handleResize();
    window.addEventListener('resize', handleResize);
    
    if (currentRoute === '/') {
        router.visit(route('admin.show'));
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<style scoped>
/* Transition for sidebar and main content */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Smooth hover effects */
a, button {
    transition: all 0.2s ease-in-out;
}

/* Interactive element hover effect */
a:hover, button:hover {
    transform: translateY(-1px);
}

/* Shadow transition */
.shadow-transition {
    transition: box-shadow 0.2s ease-in-out;
}

/* Custom scrollbar styles */
.overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #e2e8f0 #ffffff;
}

.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #ffffff;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: #e2e8f0;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background-color: #cbd5e1;
}

/* Prevent body scroll when mobile menu is open */
body.sidebar-open {
    overflow: hidden;
}
</style>