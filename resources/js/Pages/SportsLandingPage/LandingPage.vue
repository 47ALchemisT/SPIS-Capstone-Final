<template>
    <Head title="Home" />
    <MainLayout :latestPalakasan="latestPalakasan">
        <!-- Main Content -->
        <div class="">
            <!-- Hero Section -->
            <div class="relative isolate">
                <!-- Background image -->
                <div class="absolute inset-0 -z-10">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-800/90 to-blue-800/70"></div>
                    <img src="/resources/assets/Ball animation.gif" alt="Background" class="w-full h-full object-cover">
                </div>
                
                <main class="grid grid-cols-1 md:grid-cols-2 mx-auto max-w-7xl h-full overflow-hidden px-4 sm:px-6 lg:px-8">
                    <div class="relative px-2 lg:px-8">
                        <div class="w-full py-16 sm:py-44  ">
                            <!-- Hero content -->
                            <div class="text-left w-full">
                                <h1 class="text-xl tracking-tight text-white font-medium sm:text-3xl md:text-5xl">
                                    <span class="">{{ latestPalakasan.theme }}</span>
                                </h1>
                                <h1 class="text-2xl tracking-tight text-white mt-2  sm:text-3xl md:text-2xl">
                                    <span class="">{{ latestPalakasan.tagline }}</span>
                                </h1>
                                <p class="mt-3 text-base text-white sm:mt-5 sm:text-lg md:mt-5 md:text-md">
                                    {{ latestPalakasan.description }}
                                </p>
                                <div class="mt-5 sm:mt-8 flex flex-col gap-4 sm:flex-row sm:space-y-0 sm:space-x-3">
                                    <div class=" gap-4 w-full  grid grid-cols-1 md:grid-cols-2">
                                        <a @click="scrollToFeatured" class="flex items-center justify-center px-8 py-3 border border-gray-200 text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 md:py-2 md:text-md md:px-6 cursor-pointer">
                                            Get Started
                                        </a>
                                        <a href="/sports/schedules" class="group flex items-center justify-center px-8 py-3 border-2 border-white text-base font-medium rounded-md text-white md:py-2 md:text-md md:px-6 cursor-pointer hover:bg-white/10 transition-all duration-300">
                                            View Schedule
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative hidden md:flex items-center justify-center gap-8">
                        <div class="flex items-center justify-center h-full overflow-hidden">
                            <div class="flex gap-2 items-center w-full h-full justify-center">
                                <template v-for="(image, index) in getVisibleImages()" :key="currentIndex + index">
                                    <div class="carousel-slide relative" :style="{ 
                                        width: `${400 - (index * 50)}px`,
                                        height: `${525 - (index * 40)}px`
                                    }">
                                        <img 
                                            :src="image" 
                                            alt="Carousel Image" 
                                            :class="[
                                                'carousel-image h-full w-full object-cover transition-opacity duration-1000 ease-in-out',
                                                index === 0 ? '' : ''
                                            ]"
                                        >
                                        <div 
                                            class="absolute inset-0"
                                            :style="{
                                                backgroundColor: index === 0 ? 'rgba(0, 0, 0, 0)' : 'rgba(0, 0, 0, ' + (index * 0.1) + ')'
                                            }"
                                        ></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </main>
            </div>

            <!--add a section here where it displays how many active sports assigned teams and players there are-->
            <div class="py-4 sm:py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="flex flex-col items-center justify-center p-6 bg-white">
                                <div class="text-4xl font-bold text-gray-900 mb-2">{{ activeSports.length }}</div>
                                <div class="text-xs sm:text-lg text-gray-600">Active Sports</div>
                                <div class="mt-4 p-2 bg-blue-50 rounded-full">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="flex flex-col items-center justify-center p-6 bg-white">
                                <div class="text-4xl font-bold text-gray-900 mb-2">{{ assignedTeams.length }}</div>
                                <div class="text-xs sm:text-lg text-gray-600">Teams Participating</div>
                                <div class="mt-4 p-2 bg-green-50 rounded-full">
                                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="flex flex-col items-center justify-center p-6 bg-white">
                                <div class="text-4xl font-bold text-gray-900 mb-2">{{ studentPlayers.length }}</div>
                                <div class="text-xs sm:text-lg text-gray-600">Active Players</div>
                                <div class="mt-4 p-2 bg-purple-50 rounded-full">
                                    <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="featured-sports" class="py-16 bg-blue-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Sports Info Section -->
                    <div class="lg:col-span-1 order-first lg:order-last">
                        <div class="sticky top-24 space-y-6">
                        <div class="bg-white p-6 rounded-lg sm:rounded-2xl shadow-md hover:shadow-lg transition-shadow duration-300">
                            <h2 class="text-2xl font-bold tracking-tight text-gray-900 mb-3">
                            Upcoming Sports Schedules
                            </h2>
                            <p class="text-gray-600 mb-6">
                            Stay updated with all upcoming matches and events in Palakasan.
                            </p>
                            <button @click="goToAllSchedules"
                                    class="group w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            See All Schedules
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                class="h-5 w-5 ml-2 transform transition-transform duration-200" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                            </button>
                        </div>
                        </div>
                    </div>

                    <!-- Schedules Section -->
                    <div class="lg:col-span-2">
                        <div class="space-y-6">
                        <div v-if="!sportMatches?.length || latestPalakasan?.status !== 'live'" 
                            class="bg-white p-8 sm:p-12 rounded-2xl shadow-md text-center">
                            <div class="text-gray-400 mb-4">
                            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-2">
                            {{ latestPalakasan?.status !== 'live' ? 'No Live Event' : 'No Matches Scheduled' }}
                            </h3>
                            <p class="text-gray-600">
                            {{ latestPalakasan?.status !== 'live' ? 'Please wait for the event to go live' : 'Check back later for upcoming matches and events' }}
                            </p>
                        </div>

                        <template v-else-if="latestPalakasan?.status === 'live'">
                            <div v-for="(match, index) in sortedMatches" 
                                :key="match?.id" 
                                class="group bg-white rounded-lg sm:rounded-2xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                            <div class="p-6 sm:p-8">
                                <!-- Match Header -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 sm:gap-4 mb-4">
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center gap-2">
                                        <span>{{ match?.assignedSport?.sport?.name || 'Unknown Sport' }}</span>
                                        <span class="text-gray-400 hidden sm:inline-block">•</span>
                                        <span class="text-gray-600">{{ match?.assignedSport?.categories || 'Unknown'}}</span>
                                        <span class="text-gray-400 hidden sm:inline-block">•</span>
                                        <span class="text-gray-600">{{ match?.game || 'Unknown'}}</span>
                                    </h3>
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4 text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ match?.date || 'TBA' }}</span>
                                        </div>
                                            <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ match?.time || 'TBA' }}</span>
                                        </div>
                                        <div class=" flex items-center text-sm text-gray-600">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>{{ match?.matchVenue?.name || 'TBA' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Teams Section -->
                                <div class="grid grid-cols-2 sm:grid-cols-6 gap-4 items-center">
                                    <div class="col-span-1 sm:col-span-3 bg-gray-50 group-hover:bg-gray-100 transition-colors duration-200 p-4 rounded-lg text-center">
                                        <div class="font-semibold text-gray-900 mb-1">
                                        {{ match?.teamA?.assigned_team_name || 'Team A' }}
                                        </div>
                                        <div class="sm:block hidden text-sm text-gray-600">
                                        {{ match?.teamA?.college.name || 'Team A College' }}
                                        </div>
                                    </div>
                                    
                                    <div class="col-span-1 sm:col-span-3 bg-gray-50 group-hover:bg-gray-100 transition-colors duration-200 p-4 rounded-lg text-center">
                                        <div class="font-semibold text-gray-900 mb-1">
                                        {{ match?.teamB?.assigned_team_name || 'Team B' }}
                                        </div>
                                        <div class="sm:block hidden text-sm text-gray-600">
                                        {{ match?.teamB?.college.name || 'Team B College' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </template>
                        </div>
                    </div>
                    </div>
                </div>
            </div>


            <!--All sport section-->
            <div class="relative py-16 bg-blue-700 overflow-hidden">
                <div class="absolute inset-0">
                    <img src="/resources/assets/overlay.jpg" alt="" class="w-full h-full">
                    <div class="absolute inset-0 bg-blue-700/80 backdrop-blur-sm"></div>
                </div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row justify-between  mb-8">
                        <div>
                            <h2 class="text-xl sm:text-3xl font-bold text-white">
                                Palakasan Sports
                            </h2>
                            <p class="text-blue-200">
                                Sports that are currently in progress
                            </p>
                        </div>
                        <div>
                            <button @click="goToAllSports" 
                                class="inline-flex justify-center mt-4 sm:mt-0 items-center w-full px-6 py-2.5 border border-transparent text-base font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 focus:ring-4 focus:ring-blue-300 font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                View All Sports
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                    class="h-5 w-5 ml-2" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div v-if="!liveSports.length" 
                         class="text-center py-12 bg-white/10 backdrop-blur-sm rounded-xl">
                        <div class="text-blue-200">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                      d="M19.428 15.428a2 2 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-semibold text-white">No Ongoing Sports</h3>
                        <p class="mt-2 text-blue-200">
                            There are no sports currently in progress
                        </p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="(sport) in liveSports.slice(0, 6)" 
                            @click="handleSportClick(sport.id)"
                             :key="sport.id"
                             :class="['group bg-white/5 backdrop-blur-md rounded-xl cursor-pointer p-6 border border-white/10 hover:bg-white/10 transition-all duration-300 hover:scale-[1.02] hover:border-white/20', { 'cursor-wait': loading }]"
                             :disabled="loading">
                            <div class="flex justify-between items-start mb-6 ">
                                <div>
                                    <h3 class="text-lg font-semibold text-white group-hover:text-white/90">
                                        {{ sport.sport.name }}
                                    </h3>
                                    <p class="text-blue-100/80 mt-1">{{ sport.categories }}</p>
                                </div>
                                <div class="h-12 w-12 bg-white/10 rounded-xl text-white/90 flex items-center justify-center transform group-hover:rotate-12 transition-transform group-hover:bg-white/20">
                                    <i :class="sport.sport.icon" class="text-xl"></i>
                                </div>
                            </div>
                            
                            <div class="flex items-center text-blue-100/70 gap-2 mb-6">
                                <span class="text-sm">{{ sport.setup }}</span>
                                <span class="text-sm opacity-50">•</span>
                                <span class="text-sm">{{ sport.type }}</span>
                            </div>
                            
                            <div>
                                <span :class="{
                                    'px-4 py-1.5 text-sm font-medium rounded-full inline-flex items-center backdrop-blur-sm': true,
                                    'bg-red-500/10 text-red-100 border border-red-500/20': sport.status === 'Ongoing',
                                    'bg-yellow-500/10 text-yellow-100 border border-yellow-500/20': sport.status === 'scheduled',
                                    'bg-green-500/10 text-green-100 border border-green-500/20': sport.status === 'completed',
                                    'bg-gray-500/10 text-gray-100 border border-gray-500/20': sport.status === 'pending'
                                }">
                                    <span class="relative flex h-2 w-2 mr-2">
                                        <span :class="{
                                            'animate-ping absolute inline-flex h-full w-full rounded-full opacity-75': true,
                                            'bg-red-400': sport.status === 'Ongoing',
                                            'bg-yellow-400': sport.status === 'scheduled',
                                            'bg-green-400': sport.status === 'completed',
                                            'bg-gray-400': sport.status === 'pending'
                                        }"></span>
                                        <span :class="{
                                            'relative inline-flex rounded-full h-2 w-2': true,
                                            'bg-red-500': sport.status === 'Ongoing',
                                            'bg-yellow-500': sport.status === 'scheduled',
                                            'bg-green-500': sport.status === 'completed',
                                            'bg-gray-500': sport.status === 'pending'
                                        }"></span>
                                    </span>
                                    {{ sport.status.charAt(0).toUpperCase() + sport.status.slice(1) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Overall ranking section-->
            <div class="py-16 bg-blue-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">
                            Overall Rankings
                        </h2>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                            Current standings of colleges in Palakasan
                        </p>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Left side: Top 3 Colleges -->
                        <div class="md:w-1/3 space-y-6">
                            <div class="flex justify-between items-center">
                                <div class="space-y-1">
                                    <h3 class="text-xl font-semibold text-gray-900">Leading Teams</h3>
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <i class="fas fa-trophy text-yellow-500"></i>
                                        <span>Top performers this season</span>
                                    </div>
                                </div>
                                <button @click="goToAllRankings" 
                                        class="hidden md:inline-flex items-center px-4 py-2 text-sm text-blue-600 hover:text-white hover:bg-blue-600 rounded-lg font-medium transition-all duration-300 border border-blue-600">
                                    View All
                                    <i class="fas fa-arrow-right ms-2 transition-transform group-hover:translate-x-1"></i>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div v-for="(team, index) in teamRankings.slice(0, 3)" :key="team.id" 
                                     class="group relative bg-white rounded-xl hover:shadow-lg transition-all duration-300 overflow-hidden cursor-pointer"
                                     :class="{
                                         'ring-2 ring-yellow-400 shadow-yellow-200/50': index === 0,
                                         'ring-1 ring-gray-300 shadow-gray-200/50': index === 1,
                                         'ring-1 ring-amber-600 shadow-amber-200/50': index === 2
                                     }">
                                    <div class="absolute inset-0 bg-gradient-to-r opacity-0 group-hover:opacity-10 transition-opacity duration-300"
                                         :class="{
                                             'from-yellow-400/50 to-yellow-500/50': index === 0,
                                             'from-gray-300/50 to-gray-400/50': index === 1,
                                             'from-amber-600/50 to-amber-700/50': index === 2
                                         }">
                                    </div>
                                    <div class="relative flex items-start gap-4 p-4">
                                        <div :class="{
                                            'w-12 h-12 flex flex-col items-center justify-center rounded-xl font-bold text-lg shadow-lg transform group-hover:scale-110 transition-all duration-300': true,
                                            'bg-gradient-to-br from-yellow-400 to-yellow-500 text-white': index === 0,
                                            'bg-gradient-to-br from-gray-300 to-gray-400 text-white': index === 1,
                                            'bg-gradient-to-br from-amber-600 to-amber-700 text-white': index === 2
                                        }">
                                            <span class="text-2xl">{{ index + 1 }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div class="space-y-1 group-hover:translate-x-1 transition-transform duration-300">
                                                    <h4 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                                        {{ team.assigned_team_name }}
                                                    </h4>
                                                    <p class="text-sm text-gray-500">{{ team.college?.name }}</p>
                                                </div>
                                            </div>
                                            <div class=" transition-colors">
                                                <span class="text-sm font-medium text-blue-700">{{ team.totalPoints }} Points</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button @click="goToAllRankings" 
                                    class="md:hidden w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                See All Rankings
                                <i class="fas fa-arrow-right ms-2 transition-transform group-hover:translate-x-1"></i>
                            </button>
                        </div>

                        <!-- Right side: Graph -->
                        <div class="md:w-2/3 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <Graph
                                :teams="assignedTeams"
                                :overallResult="overallResult"
                                :variationResult="variationResult"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, onBeforeMount, onBeforeUnmount } from 'vue';
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
    studentPlayers: Array,
});

import img1 from '../../../assets/Carousel/1.png';
import img2 from '../../../assets/Carousel/2.png';
import img3 from '../../../assets/Carousel/3.png';
import img4 from '../../../assets/Carousel/4.png';
import img5 from '../../../assets/Carousel/5.png';
import img6 from '../../../assets/Carousel/6.png';
import img7 from '../../../assets/Carousel/7.png';
import img8 from '../../../assets/Carousel/8.png';
import img9 from '../../../assets/Carousel/9.png';
import img10 from '../../../assets/Carousel/10.png';


const images = ref([
    img1,
    img2,
    img3,
    img4,
    img5,
    img6,
    img7,
    img8,
    img9,
    img10
]);

const loading = ref(false);

const handleSportClick = (sportId) => {
    if (loading.value) return; // Prevent further clicks if already loading
    loading.value = true; // Set loading state

    // Simulate loading (replace with your actual logic)
    viewSport(sportId);
    
    // Reset loading state after some time or after the action is complete
    setTimeout(() => {
        loading.value = false; // Reset loading state
    }, 2000); // Adjust time as needed
};

const currentIndex = ref(0);
let intervalId = null;

const getVisibleImages = () => {
    const totalImages = images.value.length;
    let visibleImages = [];
    
    for (let i = -1; i <= 2; i++) {
        let index = ((currentIndex.value + i) + totalImages) % totalImages;
        visibleImages.push(images.value[index]);
    }
    
    return visibleImages;
};

const viewSport = (sportId) => {
    router.get(route('home-sportview.index', { sport: sportId }));
};

const startCarousel = () => {
    intervalId = setInterval(() => {
        currentIndex.value = (currentIndex.value + 1) % images.value.length;
    }, 10000);
};

onMounted(() => {
    startCarousel();
});

onBeforeUnmount(() => {
    if (intervalId) clearInterval(intervalId);
});

const goToAllSchedules = () => {
  router.get(route('sports.schedules'));
};

const goToAllSports = () => {
  router.get(route('sports.sports'));
};

const goToAllRankings = () => {
  router.get(route('sports.rankings'));
};

const teamRankings = computed(() => {
    return props.assignedTeams.map(team => {
        // Calculate overall points
        const teamOverallResults = props.overallResult.filter(
            result => result.assigned_team_id === team.id
        );
        const overallPoints = teamOverallResults.reduce((sum, result) => sum + (result.points || 0), 0);
        
        // Calculate variation points - using the correct field for filtering
        const teamVariationResults = props.variationResult.filter(
            result => result.sport_variation_team_id === team.id
        );
        const variationPoints = teamVariationResults.reduce((sum, result) => sum + (result.points || 0), 0);
        
        // Calculate total points
        const totalPoints = overallPoints + variationPoints;
        
        return {
            ...team,
            totalPoints,
            overallPoints,
            variationPoints
        };
    }).sort((a, b) => b.totalPoints - a.totalPoints);
});

const sortedMatches = computed(() => {
    if (!props.sportMatches) return [];
    
    const currentTime = new Date('2024-12-10T15:47:17+08:00');
    const MAX_CARDS = 7;
    
    return [...props.sportMatches]
        .filter(match => match.status !== 'completed') 
        .sort((a, b) => {
            const timeA = new Date(`${a.date} ${a.time}`);
            const timeB = new Date(`${b.date} ${b.time}`);
            
            // If the match time has passed, move it to the bottom
            if (timeA < currentTime && timeB >= currentTime) return 1;
            if (timeB < currentTime && timeA >= currentTime) return -1;
            
            // Sort by date and time
            return timeA - timeB;
        })
        .slice(0, MAX_CARDS); 
});

const liveSports = computed(() => {
    return props.activeSports.filter(sport => sport.status === 'Ongoing' || sport.status === 'completed');
});

const currentOffset = ref(0);
const activeIndex = ref(0);
const displayedSports = computed(() => {
    // Create an array that repeats the sports for infinite scrolling
    const repeatedSports = [...props.activeSports, ...props.activeSports, ...props.activeSports];
    const startIndex = activeIndex.value % props.activeSports.length;
    return repeatedSports.slice(startIndex, startIndex + 3);
});

const autoScrollInterval = ref(null);

const startAutoScroll = () => {
    autoScrollInterval.value = setInterval(() => {
        nextSlide();
    }, 3000); // Scroll every 3 seconds
};

const stopAutoScroll = () => {
    if (autoScrollInterval.value) {
        clearInterval(autoScrollInterval.value);
        autoScrollInterval.value = null;
    }
};


onMounted(() => {
    if (props.activeSports.length > 0) {
        startAutoScroll();
    }
});

onBeforeUnmount(() => {
    stopAutoScroll();
});

const scrollToFeatured = () => {
    const featuredSection = document.getElementById('featured-sports');
    featuredSection.scrollIntoView({ behavior: 'smooth' });
}
</script>

<style scoped>
.carousel-slide {
    position: relative;
}

.carousel-image {
    display: block;
}
</style>