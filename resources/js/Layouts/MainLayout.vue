<template>
    <div class="min-h-screen">
        <!-- Navigation Bar -->
        <nav class="fixed top-0 w-full z-50 bg-white bg-opacity-70 backdrop-blur-md shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-2xl font-bold text-blue-600">Palakasan</span>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <Link :href="route('sports.landing')" :class="{'border-blue-500 text-gray-900': $page.url === '/', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': $page.url !== '/'}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Home</Link>
                            <Link :href="route('sports.sports')" :class="{'border-blue-500 text-gray-900': $page.url === '/', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': $page.url !== '/'}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Sports</Link>
                            <Link :href="route('sports.schedules')" :class="{'border-blue-500 text-gray-900': $page.url === '/', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': $page.url !== '/'}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Schedules</Link>
                            <Link :href="route('sports.rankings')" :class="{'border-blue-500 text-gray-900': $page.url === '/', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': $page.url !== '/'}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Rankings</Link>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Login</button>
                    </div>
                    <div class="flex items-center sm:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                            <span class="sr-only">Open main menu</span>
                            <i class="fas fa-bars" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen}"></i>
                            <i class="fas fa-times" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}"></i>
                        </button>
                    </div>
                </div>
                <div class="sm:hidden" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}">
                    <div class="pt-2 pb-3 space-y-1">
                        <Link :href="route('sports.landing')" :class="{'bg-blue-50 border-blue-500 text-blue-700': $page.component === 'SportsLandingPage/LandingPage', 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700': $page.component !== 'SportsLandingPage/LandingPage'}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Home</Link>
                        <Link :href="route('sports.sports')" :class="{'border-blue-500 text-gray-900': $page.url === '/', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': $page.url !== '/'}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Sports</Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content with padding for navbar -->
        <div class="pt-16 sm:pt-8">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const mobileMenuOpen = ref(false);
</script>

<style>
.backdrop-blur-md {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
</style>
