<template>
    <Head title="Home" />
    <MainLayout>
        <!-- Main Content -->
        <div class="pt-16 sm:pt-8">
            <!-- Hero Section -->
            <div class="relative overflow-hidden">
                <div class="max-w-7xl mx-auto">
                    <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:pb-28 xl:pb-32">
                        <main class="mt-2 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                            <div class="flex flex-col lg:flex-row items-center justify-between">
                                <!-- Text Content -->
                                <div class="w-full lg:w-1/2 text-center lg:text-left">
                                    <span class="text-gray-800 text-xl sm:text-3xl md:text-2xl">Palakasan {{ latestPalakasan.year }}</span>
                                    <h1 class="text-2xl tracking-tight font-medium sm:text-3xl md:text-4xl">
                                        <span class="text-blue-600">{{ latestPalakasan.tagline }}</span>
                                    </h1>
                                    <p class="mt-3 text-base text-gray-600 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-md lg:mx-0">
                                        {{ latestPalakasan.description }}
                                    </p>
                                    <div class="mt-5 sm:mt-8 flex flex-col sm:flex-row justify-center lg:justify-start  sm:space-y-0 sm:space-x-3">
                                        <div class="rounded-md">
                                            <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-gray-200 text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 md:py-2 md:text-md md:px-6">
                                                Get Started
                                            </a>
                                        </div>
                                        <div>
                                            <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-500 hover:bg-blue-400  md:py-2 md:text-md md:px-6">
                                                Learn More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Image - Hidden on mobile, shown on lg screens -->
                                <div class="hidden lg:block w-full lg:w-1/2 mt-10 lg:mt-0">
                                    <img src="/resources/assets/home.jpg" alt="Palakasan Torches" class="w-full h-auto">
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
            </div>

            <!-- Featured Sports Section -->
            <div class="py-12 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Schedules Section (Left) -->
                        <div class="lg:col-span-2">
                            <div class="space-y-4">
                                <div v-if="!sportMatches?.length" class="text-center py-12">
                                    <div class="text-gray-400">
                                        <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900">No Matches Scheduled</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Check back later for upcoming matches
                                    </p>
                                </div>
                                <template v-else>
                                    <div v-for="(match, index) in displayedMatches" :key="match?.id" 
                                         class="ring-1 ring-gray-200 rounded-lg p-6 hover:shadow-md transition duration-300 bg-white">
                                        <div class="">
                                            <div class="flex mb-4 justify-between">
                                                <h3 class="text-md font-medium flex gap-1 items-center text-gray-900">
                                                    <span>{{ match?.assignedSport?.sport?.name || 'Unknown Sport' }} {{ match?.assignedSport?.categories || 'Unknown'}}</span>
                                                    <span class="text-sm">•</span>
                                                    <span>{{ match?.game || 'Unknown Game' }}</span>
                                                </h3>
                                                <div class="flex items-centerfont-normal  text-sm text-gray-500 gap-2">                                                    
                                                    <div class="flex items-center gap-1 justify-center">
                                                        <svg  class="h-3 w-3 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16"><path fill="currentColor" d="M9.5 14h-8C.67 14 0 13.33 0 12.5V2.38C0 1.55.67.88 1.5.88h11c.83 0 1.5.67 1.5 1.5v7.25c0 .28-.22.5-.5.5s-.5-.22-.5-.5V2.38c0-.28-.22-.5-.5-.5h-11c-.28 0-.5.22-.5.5V12.5c0 .28.22.5.5.5h8c.28 0 .5.22.5.5s-.22.5-.5.5"/><path fill="currentColor" d="M4 3.62c-.28 0-.5-.22-.5-.5V.5c0-.28.22-.5.5-.5s.5.22.5.5v2.62c0 .28-.22.5-.5.5m6.12 0c-.28 0-.5-.22-.5-.5V.5c0-.28.22-.5.5-.5s.5.22.5.5v2.62c0 .28-.22.5-.5.5M13.5 6H.5C.22 6 0 5.78 0 5.5S.22 5 .5 5h13c.28 0 .5.22.5.5s-.22.5-.5.5m-1 10C10.57 16 9 14.43 9 12.5S10.57 9 12.5 9s3.5 1.57 3.5 3.5s-1.57 3.5-3.5 3.5m0-6a2.5 2.5 0 0 0 0 5a2.5 2.5 0 0 0 0-5"/><path fill="currentColor" d="M13.5 14a.47.47 0 0 1-.35-.15l-1-1a.5.5 0 0 1-.15-.35V11c0-.28.22-.5.5-.5s.5.22.5.5v1.29l.85.85c.2.2.2.51 0 .71c-.1.1-.23.15-.35.15"/></svg>
                                                        <span>{{ match?.date || 'TBA' }}</span>
                                                        <span>at</span>
                                                        <span>{{ match?.time || 'TBA' }}</span>
                                                    </div>

                                                    <div class="flex items-center gap-1 justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        <span>{{ match?.matchVenue?.name || 'TBA' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <div class="grid grid-cols-7 text-md font-medium text-gray-800 space-x-2">
                                                    <div class="col-span-3 text-center bg-gray-50 p-2 rounded-lg">
                                                        {{ match?.teamA?.assigned_team_name || 'Team A' }} <br>
                                                        <span class="text-xs text-gray-500  font-normal">{{ match?.teamA?.college.name || 'Team A' }} </span>
                                                    </div>
                                                    <span class="col-span-1 flex flex-col items-center justify-center text-center text-lg">VS</span>
                                                    <div  class="col-span-3 text-center bg-gray-50 p-2 rounded-lg">
                                                        {{ match?.teamB?.assigned_team_name || 'Team B' }} <br>
                                                        <span class="text-xs text-gray-500 font-normal">{{ match?.teamB?.college.name || 'Team B' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Sports Info Section (Right) -->
                        <div class="lg:col-span-1">
                            <div class="sticky top-24">
                                <div class="rounded-lg">
                                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                                        Sports Schedules
                                    </h2>
                                    <p class="mt-3 text-lg text-gray-500">
                                        Schedules of all the sports in Palakasan
                                    </p>
                                    <Link href="/sports" class="inline-block mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        See All Schedules →
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--All sport section-->
            <div class="py-12 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                            Sports in Palakasan
                        </h2>
                        <p class="mt-4 text-lg text-gray-500">
                            All sports available in this year's Palakasan tournament
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="sport in activeSports" :key="sport.id" 
                             class="bg-white p-6 rounded-lg ring-1 ring-gray-200 hover:shadow-md transition duration-300">
                            <div class="flex justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ sport.sport.name }} {{ sport.categories }}

                                    </h3>
                                    <div class="flex items-center text-gray-600 gap-2 ">
                                        <p class="text-sm"> {{ sport.setup }}</p>
                                        <span class="text-sm">•</span>
                                        <p class=" text-sm">{{ sport.type }}</p>
                                    </div>
                                </div>
                                <div class="h-10 w-10 bg-blue-50 rounded-full text-blue-600 flex items-center justify-center">
                                    <i :class="sport.sport.icon"></i>
                                </div>
                            </div>
                            <div class="mt-4">
                                <span :class="{
                                    'px-3 py-1 text-xs font-medium rounded-full': true,
                                    'bg-red-100 text-red-600': sport.status === 'live',
                                    'bg-yellow-100 text-yellow-800': sport.status === 'scheduled',
                                    'bg-green-100 text-green-800': sport.status === 'completed',
                                    'bg-gray-100 text-gray-800': sport.status === 'pending'
                                }">
                                    {{ sport.status.charAt(0).toUpperCase() + sport.status.slice(1) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Overall ranking section-->
            <div class="py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                            Overall Rankings
                        </h2>
                        <p class="mt-4 text-lg text-gray-500">
                            Current standings of colleges in Palakasan
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Left side: Top 3 Colleges -->
                            <div class="md:w-1/3">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Leading Team</h3>
                                <div class="space-y-4">
                                    <div v-for="(team, index) in teamRankings.slice(0, 3)" :key="team.id" 
                                         class="flex p-3 bg-gray-50 rounded-lg">
                                        <div :class="{
                                            'w-8 h-8 flex items-center justify-center rounded-md text-white font-bold mr-3': true,
                                            'bg-yellow-400': index === 0,
                                            'bg-gray-400': index === 1,
                                            'bg-amber-600': index === 2
                                        }">
                                            {{ index + 1 }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800">{{ team.assigned_team_name }}</p>
                                            <p class="text-xs text-gray-600">{{ team.college?.name }}</p>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm font-medium text-gray-900">{{ team.totalPoints }} total points</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right side: Graph -->
                            <div class="md:w-2/3">
                                <Graph
                                    :teams="assignedTeams"
                                    :overallResult="overallResult"
                                    :variationResult="variationResult"
                                    class="h-[400px]"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div>
                            <h3 class="text-gray-900 text-lg font-semibold mb-3">About Palakasan</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">A comprehensive sports tournament management platform.</p>
                        </div>
                        <div>
                            <h3 class="text-gray-900 text-lg font-semibold mb-3">Features</h3>
                            <ul class="space-y-2">
                                <li class="flex items-center gap-2 text-gray-600"><i class="fas fa-chart-line text-blue-600 w-5"></i><span class="text-sm">Real-time Updates</span></li>
                                <li class="flex items-center gap-2 text-gray-600"><i class="fas fa-users text-blue-600 w-5"></i><span class="text-sm">Team Management</span></li>
                                <li class="flex items-center gap-2 text-gray-600"><i class="fas fa-trophy text-blue-600 w-5"></i><span class="text-sm">Tournament Brackets</span></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-gray-900 text-lg font-semibold mb-3">Quick Links</h3>
                            <ul class="space-y-2">
                                <li><Link href="/sports" class="text-gray-600 hover:text-blue-600 text-sm transition-colors duration-200">Sports Schedule</Link></li>
                                <li><Link href="/teams" class="text-gray-600 hover:text-blue-600 text-sm transition-colors duration-200">Teams</Link></li>
                                <li><Link href="/rankings" class="text-gray-600 hover:text-blue-600 text-sm transition-colors duration-200">Rankings</Link></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-gray-900 text-lg font-semibold mb-3">Contact Us</h3>
                            <ul class="space-y-2">
                                <li class="flex items-center gap-2 text-gray-600"><i class="fas fa-map-marker-alt text-blue-600 w-5"></i><span class="text-sm">TUP Manila</span></li>
                                <li class="flex items-center gap-2 text-gray-600"><i class="fas fa-envelope text-blue-600 w-5"></i><span class="text-sm">palakasan@tup.edu.ph</span></li>
                                <li class="flex items-center gap-2 text-gray-600"><i class="fas fa-phone text-blue-600 w-5"></i><span class="text-sm">(02) 8301-3001</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <p class="text-center text-gray-600 text-sm"> {{ new Date().getFullYear() }} Palakasan. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </MainLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import Graph from '@/Components/BarGraph.vue';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
    latestPalakasan: Object,
    sportMatches: {
        type: Array,
        default: () => []
    },
    activeSports: {
        type: Array,
        default: () => []
    },
    overallResult: {
        type: Array,
        default: () => []
    },
    variationResult: {
        type: Array,
        default: () => []
    },
    assignedTeams: {
        type: Array,
        default: () => []
    },
});

const mobileMenuOpen = ref(false);

const teamRankings = computed(() => {
    return props.assignedTeams.map(team => {
        const teamOverallResults = props.overallResult.filter(
            result => result.assigned_team_id === team.id
        );
        const overallPoints = teamOverallResults.reduce((sum, result) => sum + (result.points || 0), 0);
        
        const teamVariationResults = props.variationResult.filter(
            result => result.sport_variation_team_id === team.id
        );
        const variationPoints = teamVariationResults.reduce((sum, result) => sum + (result.points || 0), 0);
        
        const totalPoints = overallPoints + variationPoints;
        
        return {
            ...team,
            totalPoints,
            overallPoints,
            variationPoints
        };
    }).sort((a, b) => b.totalPoints - a.totalPoints);
});

// Debug log to check data
onMounted(() => {
    console.log('Sport Matches:', props.sportMatches);
});

const maxSlides = computed(() => {
    if (window.innerWidth >= 1024) {
        return Math.max(0, Math.ceil(props.activeSports.length / 3) - 1);
    } else if (window.innerWidth >= 640) {
        return Math.max(0, Math.ceil(props.activeSports.length / 2) - 1);
    }
    return Math.max(0, props.activeSports.length - 1);
});

const displayedMatches = computed(() => {
    return props.sportMatches.slice(0, 5);
});

const hasMoreMatches = computed(() => {
    return props.sportMatches.length > 5;
});
</script>

<style>
.backdrop-blur-md {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
</style>