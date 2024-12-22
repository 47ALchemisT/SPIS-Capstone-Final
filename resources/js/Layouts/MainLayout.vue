<template>
    <div class="min-h-screen">
        <!-- Top Header (non-sticky) -->
        <div class="bg-blue-700 text-white">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-10">
                    <div class="flex items-center ">
                        <div class="flex items-center gap-2 relative sm:flex hidden">
                            <img src="/resources/assets/logoMSU.png" alt="MSU Logo" class="h-12 w-auto relative">
                            <img src="/resources/assets/ssclogo.jpg" alt="SSC Logo" class="h-12 w-12 rounded-full">
                        </div>
                        <div class="ml-0 sm:ml-2">
                            <h1 class="text-md sm:text-lg font-bold">Supreme Student Council</h1>
                            <p class="text-xs text-gray-200">Mindanao State University at Naawan</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="ml-6 flex items-center">
                            <button @click="$inertia.visit(route('login'))" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Login</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Bar -->
        <nav class="sticky top-0 w-full z-50 bg-white bg-opacity-70 backdrop-blur-md shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-14">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-2xl font-bold text-blue-600">Palakasan {{ latestPalakasan.year }}</span>
                    </div>
                    <div class="flex items-center space-x-8">
                        <div class="flex items-center">
                            <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:hidden">
                                <span class="sr-only">Open main menu</span>
                                <i class="fas fa-bars" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen}"></i>
                                <i class="fas fa-times" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}"></i>
                            </button>
                        </div>
                        <div class="hidden sm:flex sm:space-x-8">
                            <Link :href="route('sports.landing')" :class="{'border-blue-500 text-blue-700': $page.component === 'SportsLandingPage/LandingPage', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': $page.component !== 'SportsLandingPage/LandingPage'}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Home</Link>
                            <Link :href="route('sports.schedules')" :class="{'border-blue-500  text-blue-700': $page.component === 'SportsLandingPage/SportsSchedule', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': $page.component !== 'SportsLandingPage/SportsSchedule'}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Schedules</Link>
                            <Link :href="route('sports.sports')" :class="{'border-blue-500 text-blue-700': $page.component === 'SportsLandingPage/Sports', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': $page.component !== 'SportsLandingPage/Sports'}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Sports</Link>
                            <Link :href="route('sports.rankings')" :class="{'border-blue-500 text-blue-700': $page.component === 'SportsLandingPage/Rankings', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': $page.component !== 'SportsLandingPage/Rankings'}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Rankings</Link>
                            <Link :href="route('sports.history')" :class="{'border-blue-500 text-blue-700': $page.component === 'SportsLandingPage/History', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': $page.component !== 'SportsLandingPage/History'}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">History</Link>
                        </div>
                    </div>
                </div>
                <div class="sm:hidden" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}">
                    <div class="pt-2 pb-3 space-y-1">
                        <Link :href="route('sports.landing')" :class="{'bg-blue-50 border-blue-500 text-blue-700': $page.component === 'SportsLandingPage/LandingPage', 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700': $page.component !== 'SportsLandingPage/LandingPage'}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Home</Link>
                        <Link :href="route('sports.schedules')" :class="{'bg-blue-50 border-blue-500 text-blue-700': $page.component === 'SportsLandingPage/SportsSchedule', 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700': $page.component !== 'SportsLandingPage/SportsSchedule'}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Schedules</Link>
                        <Link :href="route('sports.sports')" :class="{'bg-blue-50 border-blue-500 text-blue-700': $page.component === 'SportsLandingPage/Sports', 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700': $page.component !== 'SportsLandingPage/Sports'}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Sports</Link>
                        <Link :href="route('sports.rankings')" :class="{'bg-blue-50 border-blue-500 text-blue-700': $page.component === 'SportsLandingPage/Rankings', 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700': $page.component !== 'SportsLandingPage/Rankings'}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Rankings</Link>
                        <Link :href="route('sports.history')" :class="{'bg-blue-50 border-blue-500 text-blue-700': $page.component === 'SportsLandingPage/History', 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700': $page.component !== 'SportsLandingPage/History'}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium">History</Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <slot></slot>
        </main>

        <footer class="bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-12">
            <div class="">
                <!-- Desktop Grid View - hidden on mobile -->
                <div class="hidden md:grid md:grid-cols-4 md:gap-8">
                <!-- About Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">About Palakasan</h3>
                    <div class="text-gray-600 text-sm leading-relaxed">
                    <p>A comprehensive sports tournament management platform.</p>
                    </div>
                </div>

                <!-- Features Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Features</h3>
                    <ul class="space-y-2">
                    <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-chart-line text-blue-600 w-5"></i>
                        <span class="text-sm">Real-time Updates</span>
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-users text-blue-600 w-5"></i>
                        <span class="text-sm">Team Management</span>
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-trophy text-blue-600 w-5"></i>
                        <span class="text-sm">Tournament Brackets</span>
                    </li>
                    </ul>
                </div>

                <!-- Team Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Web Development Team</h3>
                    <ul class="space-y-2">
                    <li class="text-gray-600 hover:text-blue-600 text-sm transition-colors duration-200">
                        Jolander C. Guiral
                    </li>
                    <li class="text-gray-600 hover:text-blue-600 text-sm transition-colors duration-200">
                        Kent Remar L. Cuñado
                    </li>
                    <li class="text-gray-600 hover:text-blue-600 text-sm transition-colors duration-200">
                        James Bryan V. Balambao
                    </li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Us</h3>
                    <ul class="space-y-2">
                    <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-map-marker-alt text-blue-600 w-5"></i>
                        <span class="text-sm">MSU Naawan</span>
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-envelope text-blue-600 w-5"></i>
                        <span class="text-sm">palakasan@tup.edu.ph</span>
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-phone text-blue-600 w-5"></i>
                        <span class="text-sm">(02) 8301-3001</span>
                    </li>
                    </ul>
                </div>
                </div>

                <!-- Mobile Accordion View - hidden on desktop -->
                <nav class="md:hidden space-y-2">
                <!-- About Section -->
                <div>
                    <div @click="toggleSection('about')" class="cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900">About Palakasan</h3>
                    </div>
                    <div v-if="openSections.about" class="text-gray-600 text-sm leading-relaxed mt-2">
                    <p>A comprehensive sports tournament management platform.</p>
                    </div>
                </div>

                <!-- Features Section -->
                <div>
                    <div @click="toggleSection('features')" class="cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900">Features</h3>
                    </div>
                    <div v-if="openSections.features" class="mt-2">
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-chart-line text-blue-600 w-5"></i>
                        <span class="text-sm">Real-time Updates</span>
                        </li>
                        <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-users text-blue-600 w-5"></i>
                        <span class="text-sm">Team Management</span>
                        </li>
                        <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-trophy text-blue-600 w-5"></i>
                        <span class="text-sm">Tournament Brackets</span>
                        </li>
                    </ul>
                    </div>
                </div>

                <!-- Team Section -->
                <div>
                    <div @click="toggleSection('team')" class="cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900">Web Development Team</h3>
                    </div>
                    <div v-if="openSections.team" class="mt-2">
                    <ul class="space-y-2">
                        <li class="text-gray-600 hover:text-blue-600 text-sm transition-colors duration-200">
                        Jolander C. Guiral
                        </li>
                        <li class="text-gray-600 hover:text-blue-600 text-sm transition-colors duration-200">
                        Kent Remar L. Cuñado
                        </li>
                        <li class="text-gray-600 hover:text-blue-600 text-sm transition-colors duration-200">
                        James Bryan V. Balambao
                        </li>
                    </ul>
                    </div>
                </div>

                <!-- Contact Section -->
                <div>
                    <div @click="toggleSection('contact')" class="cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900">Contact Us</h3>
                    </div>
                    <div v-if="openSections.contact" class="mt-2">
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-map-marker-alt text-blue-600 w-5"></i>
                        <span class="text-sm">MSU Naawan</span>
                        </li>
                        <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-envelope text-blue-600 w-5"></i>
                        <span class="text-sm">palakasan@tup.edu.ph</span>
                        </li>
                        <li class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-phone text-blue-600 w-5"></i>
                        <span class="text-sm">(02) 8301-3001</span>
                        </li>
                    </ul>
                    </div>
                </div>
                </nav>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-center text-gray-600 text-sm">
                {{ currentYear }} Supreme Student Council - Mindanao State University at Naawan
                </p>
            </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    latestPalakasan: {
        type: Object,
        required: true
    }
});

const mobileMenuOpen = ref(false);

const openSections = ref({
    about: false,
    features: false,
    team: false,
    contact: false,
});

const toggleSection = (section) => {
    openSections.value[section] = !openSections.value[section];
};
</script>

<style>
.backdrop-blur-md {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
</style>