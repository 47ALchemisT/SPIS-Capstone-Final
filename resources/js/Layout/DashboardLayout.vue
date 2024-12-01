<template>
    <div>
        <!-- Navbar -->
        <nav class="bg-blue-700 fixed top-0 left-0 right-0 z-50">
            <div class="px-6">
                <div class="flex justify-between h-16">
                    <div class="flex-shrink-0 flex space-x-3 items-center">
                        <img class="w-10 h-10 rounded-full object-cover cursor-pointer" src='/resources/assets/ssclogo.jpg' alt="User dropdown">
                        <span class="text-xl font-semibold text-white">SPSIS</span>
                    </div>
                    <div class="ml-3 relative flex items-center gap-3">
                        <div>
                            <p class="text-white text-sm font-semibold ">Supreme Student Council</p>
                            <p class="text-white text-xs text-right ">Admin</p>
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
                                            <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                            Option 1
                                            </a>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                            <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                            Option 2
                                            </a>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                            <a href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                            Option 3
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

        <div class="flex pt-16">
            <!-- Sidebar -->
            <aside class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-e border-gray-300 pt-16">
                <div class="h-full px-5 py-4 overflow-y-auto flex flex-col justify-between space-y-2">
                    <div>
                        <div class="text-xs mb-2 mt-1 text-gray-500 font-medium">Dashboard</div>
                        <ul class="space-y-2" style="margin-bottom: 6px;">
                            <li>
                                <Link 
                                    :href="route('admin.show')" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg transition-all"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/admin' || currentRoute === '/', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/admin' && currentRoute !== '/'}"
                                >
                                <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 6c0-1.886 0-2.828.586-3.414S4.114 2 6 2s2.828 0 3.414.586S10 4.114 10 6v2c0 1.886 0 2.828-.586 3.414S7.886 12 6 12s-2.828 0-3.414-.586S2 9.886 2 8zm0 13c0-.932 0-1.398.152-1.765a2 2 0 0 1 1.083-1.083C3.602 16 4.068 16 5 16h2c.932 0 1.398 0 1.765.152a2 2 0 0 1 1.083 1.083C10 17.602 10 18.068 10 19s0 1.398-.152 1.765a2 2 0 0 1-1.083 1.083C8.398 22 7.932 22 7 22H5c-.932 0-1.398 0-1.765-.152a2 2 0 0 1-1.083-1.083C2 20.398 2 19.932 2 19m12-3c0-1.886 0-2.828.586-3.414S16.114 12 18 12s2.828 0 3.414.586S22 14.114 22 16v2c0 1.886 0 2.828-.586 3.414S19.886 22 18 22s-2.828 0-3.414-.586S14 19.886 14 18zm0-11c0-.932 0-1.398.152-1.765a2 2 0 0 1 1.083-1.083C15.602 2 16.068 2 17 2h2c.932 0 1.398 0 1.765.152a2 2 0 0 1 1.083 1.083C22 3.602 22 4.068 22 5s0 1.398-.152 1.765a2 2 0 0 1-1.083 1.083C20.398 8 19.932 8 19 8h-2c-.932 0-1.398 0-1.765-.152a2 2 0 0 1-1.083-1.083C14 6.398 14 5.932 14 5" color="currentColor"/></svg>                                    
                                <span class="ms-3">Dashboard</span>
                                </Link>
                            </li>
                        </ul>
                        <div class="text-xs mb-2 mt-1 text-gray-500 font-medium">Setup</div>
                        <ul class="space-y-2">
                            <li>
                                <Link 
                                    href="/sport" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/sport', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/sport'}"
                                >
                                <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 2048 2048"><path fill="currentColor" d="m621 1171l256 256l-90 90l-256-256zM1216 0q115 0 221 29t199 84t168 130t130 168t84 199t30 222q0 146-48 279t-134 241t-206 184t-261 108q9 74 9 148q0 53-20 99t-55 82t-81 55t-100 20q-159 0-306-41t-276-116t-233-180t-180-233t-116-275T0 896q0-53 20-99t55-82t81-55t100-20q74 0 148 9q32-142 108-261t184-205T937 48t279-48m354 569q48 35 105 53t117 18q50 0 98-12q-20-68-54-130t-79-116zm96-278q-54-45-116-79t-130-54q-12 48-12 98q0 60 18 117t53 105zm-371-158q-20-3-39-4t-40-1q-124 0-239 42T766 292l450 450l172-172q-53-67-80-147t-28-167q0-63 15-123M675 382q-53 63-90 136t-55 155q137 32 263 99t231 161l102-101zm477 1538q27 0 50-10t40-27t28-41t10-50q0-141-36-272t-104-244t-160-207t-207-161t-245-103t-272-37q-27 0-50 10t-40 27t-28 41t-10 50q0 141 36 272t104 244t160 207t207 161t245 103t272 37m-37-896q94 105 161 231t99 263q81-18 154-55t137-90l-450-451zm641 258q79-95 121-210t43-240q0-20-1-39t-4-40q-60 15-123 15q-86 0-166-27t-148-81l-172 172z"/></svg>                                    
                                <span class="ms-3">Sports</span>
                                </Link>
                                <Link 
                                    href="/points" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/points', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/points'}"
                                >
                                <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"><path d="M15.08 2.752H8.92a1.97 1.97 0 0 0-2.062 1.85v6.077a5.49 5.49 0 0 0 3.024 4.8a4.52 4.52 0 0 0 4.236 0a5.49 5.49 0 0 0 3.024-4.8V4.602a1.97 1.97 0 0 0-2.062-1.85Z"/><path d="M17.142 4.602h2.054a1.97 1.97 0 0 1 2.053 1.85a6.8 6.8 0 0 1-.87 3.311a7.74 7.74 0 0 1-2.423 2.608l-.814.555l-.795.481M6.858 4.602H4.804a1.97 1.97 0 0 0-2.053 1.85c.003 1.16.302 2.3.87 3.311a7.74 7.74 0 0 0 2.423 2.608l.814.555l.795.481m2.294 5.068v-2.969m4.106 2.969v-2.969M8.81 18.475h6.38a1.95 1.95 0 0 1 1.952 1.952v.333a.49.49 0 0 1-.49.49H7.348a.49.49 0 0 1-.49-.49v-.333a1.95 1.95 0 0 1 1.951-1.952Z"/><path stroke-linejoin="round" d="m12.143 10.308l1.18.62a.302.302 0 0 0 .437-.319l-.226-1.313a.3.3 0 0 1 .087-.268l.955-.93a.3.3 0 0 0-.166-.515l-1.319-.19a.3.3 0 0 1-.225-.165l-.603-1.205a.302.302 0 0 0-.542 0l-.602 1.205a.3.3 0 0 1-.226.165l-1.3.19a.301.301 0 0 0-.17.515l.955.93a.3.3 0 0 1 .088.268l-.226 1.313a.3.3 0 0 0 .436.32l1.18-.62a.3.3 0 0 1 .287 0"/></g></svg>                                
                                <span class="ms-3">Points</span>
                                </Link>
                                <Link 
                                    href="/venue" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/venue', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/venue'}"
                                >
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18.5L9 17l-6 3V7l6-3l6 3l6-3v7.5M9 4v13m6-10v5.5m6.121 7.621a3 3 0 1 0-4.242 0Q17.506 20.749 19 22q1.577-1.335 2.121-1.879M19 18v.01"/></svg>
                                    <span class="ms-3">Venues</span>
                                </Link>
                                <Link 
                                    href="/college" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/college', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/college'}"
                                >
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M4.75 22a.75.75 0 0 1-.75-.75v-17A2.25 2.25 0 0 1 6.25 2h8a2.25 2.25 0 0 1 2.25 2.25V9.5h1.25A2.25 2.25 0 0 1 20 11.75v1.3a2.5 2.5 0 0 0-1.5.95v-2.25a.75.75 0 0 0-.75-.75h-2a.75.75 0 0 1-.75-.75v-6a.75.75 0 0 0-.75-.75h-8a.75.75 0 0 0-.75.75V20.5h5.528a4 4 0 0 0 .478 1.5zM7.5 6.5a1 1 0 1 1 2 0a1 1 0 0 1-2 0m0 7a1 1 0 1 1 2 0a1 1 0 0 1-2 0M12 9a1 1 0 1 0 0 2a1 1 0 0 0 0-2m-1-2.5a1 1 0 1 1 2 0a1 1 0 0 1-2 0M8.5 9a1 1 0 1 0 0 2a1 1 0 0 0 0-2m7 8a2 2 0 1 0 0-4a2 2 0 0 0 0 4m0 6c2.567 0 3.5-1.52 3.5-3a2 2 0 0 0-2-2h-3a2 2 0 0 0-2 2c0 1.48.933 3 3.5 3m4.007-1.022q.234.021.493.022c2.2 0 3-1.216 3-2.4a1.6 1.6 0 0 0-1.6-1.6h-2.164c.475.53.764 1.232.764 2c0 .656-.144 1.35-.493 1.978M22 15.5a1.5 1.5 0 1 1-3 0a1.5 1.5 0 0 1 3 0"/></svg>
                                    <span class="ms-3">Colleges</span>
                                </Link>
                                <Link 
                                    href="/student" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg mb-2"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/student', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/student'}"
                                >
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256"><path fill="currentColor" d="m227.79 52.62l-96-32a11.85 11.85 0 0 0-7.58 0l-96 32A12 12 0 0 0 20 63.37a6 6 0 0 0 0 .63v80a12 12 0 0 0 24 0V80.65l23.71 7.9a67.92 67.92 0 0 0 18.42 85A100.36 100.36 0 0 0 46 209.44a12 12 0 1 0 20.1 13.11C80.37 200.59 103 188 128 188s47.63 12.59 61.95 34.55a12 12 0 1 0 20.1-13.11a100.36 100.36 0 0 0-40.18-35.92a67.92 67.92 0 0 0 18.42-85l39.5-13.17a12 12 0 0 0 0-22.76Zm-99.79-8L186.05 64L128 83.35L70 64ZM172 120a44 44 0 1 1-81.06-23.71l33.27 11.09a11.9 11.9 0 0 0 7.58 0l33.27-11.09A43.85 43.85 0 0 1 172 120"/></svg>
                                    <span class="ms-3">Students</span>
                                </Link>
                            </li>
                        </ul>
                        <div class="text-xs mb-2 mt-1 text-gray-500 font-medium">Main</div>
                        <ul class="space-y-2" style="margin-bottom: 6px;">
                            <li>
                                <Link 
                                    href="/details" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg transition-all"
                                    :class="{
                                    'bg-blue-700 text-white': currentRoute.startsWith('/details'), 
                                    'text-gray-700 hover:bg-blue-100 hover:text-blue-600': !currentRoute.startsWith('/details')
                                    }"
                                >
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256"><path fill="currentColor" d="M242.09 41.5A12 12 0 0 0 232 36h-80a12 12 0 0 0-10.92 7l-7.72 17H28a12 12 0 0 0-8.88 20.07L51.78 116l-32.66 35.93A12 12 0 0 0 28 172h73.09a12 12 0 0 0 10.91-7l7.71-17h53.63l-32.28 71a12 12 0 1 0 21.86 10l80-176a12 12 0 0 0-.83-11.5M55.13 148l21.75-23.93a12 12 0 0 0 0-16.14L55.13 84h67.32l-29.09 64Zm129.14-24h-53.63l29.09-64h53.63Z"/></svg>                               
                                    <span class="ms-3">Palakasan</span>
                                </Link>
                            </li>
                            <li>
                                <Link 
                                    href="/account" 
                                    class="flex items-center py-2 px-3 text-md rounded-lg transition-all"
                                    :class="{'bg-blue-700 text-white': currentRoute === '/account', 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== '/account'}"
                                >
                                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></g></svg>
                                    <span class="ms-3">Accounts</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul class="space-y-2" style="margin-bottom: 6px;">
                            <li>
                                <Link 
                                    :href="route('logout')" 
                                    method="post"
                                    as="button"
                                    class="flex items-center w-full py-2 px-3 text-md rounded-lg transition-all"
                                    :class="{'bg-blue-700 text-white': currentRoute === route('logout'), 'text-gray-700 hover:bg-blue-100 hover:text-blue-600': currentRoute !== route('logout')}"
                                >
                                    <i class="fa-solid fa-right-from-bracket"></i>                                    
                                    <span class="ms-3">Log out</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>

            </aside>

            <!-- Main content -->
            <div class="flex-1 p-6 main-content">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
    import { Link, usePage } from '@inertiajs/vue3';
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';

    const { url: currentRoute } = usePage();

    onMounted(() => {
        // Redirect to dashboard if on root path
        if (currentRoute === '/') {
            router.visit(route('admin.show'));
        }
    });
</script>

<style scoped>
.main-content {
    margin-left: 256px;
}

</style>
