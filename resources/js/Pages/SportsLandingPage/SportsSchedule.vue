<template>
    <Head title="Schedule" />
    <MainLayout :latestPalakasan="latestPalakasan">
        <div class="py-2 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <!-- Header Section -->
                <div class="text-left mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Sports Schedule</h1>
                    <p class=" text-gray-600">{{ palakasanYear }} Palakasan Games</p>
                </div>

                <!-- Tabs for larger screens -->
                <div class="hidden md:block border-b border-gray-200 mb-4">
                    <nav class="-mb-px flex space-x-8">
                        <a 
                            v-for="tab in tabs" 
                            :key="tab.id"
                            @click="activeTab = tab.id"
                            :class="getTabClass(tab.id)"
                            class="cursor-pointer"
                        >
                            {{ tab.label }}
                            <span 
                                :class="[
                                    'ml-2 px-2.5 py-0.5 rounded-full text-xs',
                                    tab.id === 'completed' ? 'bg-green-100 text-green-900' : 'bg-gray-100 text-gray-900'
                                ]"
                            >
                                {{ matchCounts[tab.id] }}
                            </span>
                        </a>
                    </nav>
                </div>

                <!-- Select dropdown for mobile -->
                <div class="md:hidden border-b border-gray-200 mb-4">
                    <select 
                        v-model="activeTab"
                        class="w-full py-2.5 px-3 text-sm rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option 
                            v-for="tab in tabs" 
                            :key="tab.id" 
                            :value="tab.id"
                        >
                            {{ tab.label }} ({{ matchCounts[tab.id] }})
                        </option>
                    </select>
                </div>
                <!-- Filters Section -->
                <div class=" mb-6">
                    <div class=" grid grid-cols-2 sm:flex gap-4">
                        <!-- Sport Filter -->
                        <div>
                            <select 
                                v-model="selectedSport" 
                                class="w-full py-2.5 px-3 text-sm rounded-md ring-1 ring-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option :value="{ name: '', categories: '' }">All Sports</option>
                                <option 
                                    v-for="sport in activeSports" 
                                    :key="sport.id" 
                                    :value="{ 
                                        name: sport?.sport?.name, 
                                        categories: formatCategories(sport?.categories)
                                    }"
                                >
                                    {{ sport?.sport?.name }} {{ formatCategories(sport?.categories) }}
                                </option>
                            </select>
                        </div>
                        <!-- Team Filter -->
                        <div>
                            <select v-model="selectedTeam" class="w-full py-2.5 px-3 text-sm ring-1 ring-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Teams</option>
                                <option v-for="team in availableTeams" :key="team" :value="team">
                                    {{ team }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Schedule Cards -->
                <div v-if="latestPalakasan.status === 'live' || latestPalakasan.status === 'completed'" class="space-y-4">
                    <template v-if="Object.keys(groupedMatches).length === 0">
                        <div class="text-center text-gray-500 py-4">
                            There are no scheduled matches yet.
                        </div>
                    </template>
                    <template v-else>
                        <template v-for="(matches, date) in groupedMatches" :key="date">
                            <!-- Date Header -->
                            <div class="sticky top-14 z-10">
                                <h2 class="text-xl font-bold text-gray-800 bg-white py-4 rounded-t-lg">
                                    {{ formatDate(date) }}
                                </h2>
                            </div>
                            
                            <!-- Games for this date -->
                            <div class="grid grid-cols-1 gap-4 px-1">
                                <div v-for="match in matches" :key="match.id" 
                                    class="bg-white rounded-lg ring-1 ring-gray-200 hover:ring-2 hover:ring-blue-300 transition-all duration-200">
                                    <!-- Sport Header -->
                                    <div class="px-4 pt-2">
                                        <!-- Regular Match Header -->
                                        <div v-if="match.type === 'regular'" class="flex flex-col sm:flex-row sm:gap-2 gap-0 ">
                                            <!-- Time and Venue -->
                                            <div class="flex flex-wrap gap-2 text-sm text-gray-600">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="font-medium">{{ match.time }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <span class="font-medium">{{ match.matchVenue?.name || match.venue?.name }}</span>
                                                </div>
                                            </div>
                                            <span class="block text-gray-400 sm:block hidden">•</span>
                                            <!-- Sport and Game Info -->
                                            <div class="flex flex-wrap text-xs sm:text-sm items-center gap-2">
                                                <span class="font-normal  text-gray-700">{{ match.assignedSport?.sport?.name || match.sport_name }}</span>
                                                <span class="font-normal text-gray-700">{{ match.assignedSport?.categories || match.category }}</span>
                                                <span class="block text-gray-400">•</span>
                                                <span class=" font-normal text-gray-700">{{ match.game || 'Game' }}</span>
                                            </div>

                                            <!-- View Details button for completed matches -->
                                            <div v-if="match.status?.toLowerCase() === 'completed'" class="sm:ml-auto mt-2 sm:mt-0">
                                                <button 
                                                    @click="showResultModal(match)"
                                                    type="button" 
                                                    class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                    View Result
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Sports Variation Header -->
                                        <div v-else class="flex flex-col sm:flex-row sm:gap-2 gap-0 sm:items-center">
                                            <!-- Time and Venue -->
                                            <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="font-medium">{{ match.time }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <span class="font-medium">{{ match.venue_id?.name }}</span>
                                                </div>
                                            </div>
                                            <span class="hidden sm:block text-gray-400">•</span>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <!-- Sport Name -->
                                                <div class="flex flex-wrap text-xs sm:text-sm items-center gap-2">
                                                    <span class=" text-gray-600">{{ match.sport_id?.sport?.name }} {{ match.sport_id?.categories }}</span>
                                                </div>
                                                <span class="block text-gray-400">•</span>
                                                <!-- Sport Variation Name -->
                                                <div class="flex flex-wrap text-xs sm:text-sm items-center gap-2">
                                                    <span class="text-gray-600">{{ match.sport_variation_name }}</span>
                                                </div>
                                            </div>
                                            <!-- View Details button for completed variation matches -->
                                            <div v-if="match.status?.toLowerCase() === 'completed'" class="sm:ml-auto mt-2 sm:mt-0">
                                                <button 
                                                    @click="showResultModal(match)"
                                                    type="button" 
                                                    class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                    View Result
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Teams Section -->
                                    <div class="p-4">
                                        <div class="grid grid-cols-2 sm:grid-cols-11 gap-4">
                                            <template v-if="match.type === 'regular'">
                                                <!-- Team A -->
                                                <div class="sm:col-span-5 flex flex-col items-center justify-center text-center bg-gray-50 p-3 rounded-md">
                                                    <span class="font-bold text-lg text-gray-900">{{ match.teamA?.assigned_team_name || 'TBA' }}</span>
                                                    <span class="sm:block hidden text-sm text-gray-500">{{ match.teamA?.college?.name }}</span>
                                                </div>

                                                <!-- VS -->
                                                <div class="sm:block hidden sm:col-span-1 flex items-center justify-center my-2 sm:my-0">
                                                    <span class="font-bold text-xl text-gray-400">VS</span>
                                                </div>

                                                <!-- Team B -->
                                                <div class="sm:col-span-5 flex flex-col items-center justify-center text-center bg-gray-50 p-3 rounded-md">
                                                    <span class="font-bold text-lg text-gray-900">{{ match.teamB?.assigned_team_name || 'TBA' }}</span>
                                                    <span class="sm:block hidden  text-sm text-gray-500">{{ match.teamB?.college?.name }}</span>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <!-- Sports Variation Match -->
                                                <div class="col-span-full">
                                                    <div v-if="match.sport_variation_i_d && match.sport_variation_i_d.length > 0" 
                                                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
                                                        <div v-for="team in match.sport_variation_i_d.sort((a, b) => {
                                                            const rankA = a.rank || Infinity;
                                                            const rankB = b.rank || Infinity;
                                                            const pointsA = a.points || 0;
                                                            const pointsB = b.points || 0;
                                                            // First sort by rank, then by points
                                                            return rankA - rankB || pointsB - pointsA;
                                                        })" :key="team.id" 
                                                            class="bg-gray-50 hover:bg-gray-100 rounded-lg p-3 transition-colors">
                                                            <div class="flex flex-col items-center">
                                                                <span class="text-md font-medium text-gray-700">{{ team.assigned_team_variation_i_d?.assigned_team_name || 'TBA' }}</span>
                                                                <span class="sm:block hidden text-sm text-center text-gray-500">{{ team.assigned_team_variation_i_d?.college?.name }}</span>
                                                                <span v-if="team.assigned_team_variation_i_d?.team_name" class="text-xs text-gray-500 mt-1">{{ team.assigned_team_variation_i_d?.team_name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center text-sm text-gray-500 bg-gray-50 px-4 py-3 rounded-lg">
                                                        No teams registered yet
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
                <div v-else class="text-center text-gray-500 py-4">
                    The Palakasan has not started yet.
                </div>

            </div>
        </div>
    </MainLayout>

    <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 px-4 py-6 sm:px-0">
        <div class="relative min-h-[calc(100vh-4rem)] flex items-center justify-center">
        <div class="absolute inset-0" @click="closeModal"></div>
        
            <!-- Modal Container -->
            <div class="relative w-full max-w-3xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center px-8 py-5 border-b bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-xl font-bold text-gray-900">
                        Match Result
                    </h3>
                    <button @click="closeModal" 
                            class="rounded-full p-2 hover:bg-gray-100 transition-colors duration-200">
                        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="px-8 py-6">
                <!-- Match Info -->
                    <div class="space-y-3 mb-8">
                        <h4 class="text-lg font-semibold text-gray-700">
                        {{ selectedMatch?.assignedSport?.sport?.name || selectedMatch?.sport_id?.sport?.name }}
                        <span class="text-gray-700">
                            {{ selectedMatch?.assignedSport?.categories || selectedMatch?.sport_id?.categories }}
                        </span>
                        </h4>
                        
                        <!-- Match Details -->
                        <div class="inline-flex flex-wrap gap-4 rounded-xl">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-600">{{ formatDate(selectedMatch?.date) }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-gray-600">{{ selectedMatch?.time }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-sm text-gray-600">{{ selectedMatch?.matchVenue?.name || selectedMatch?.venue_id?.name }}</span>
                        </div>
                        </div>
                    </div>

                    <!-- Regular Match Result -->
                    <div v-if="selectedMatch?.type === 'regular'" class="space-y-6">
                        <div class="grid sm:grid-cols-6 grid-cols-2 gap-4 items-stretch">
                            <!-- Team A -->
                            <div class="sm:col-span-3 col-span-1">
                                <div class="h-full bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100 shadow-sm p-6">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                    <p class="font-bold text-lg mb-2 line-clamp-2 text-center">{{ selectedMatch?.teamA?.assigned_team_name }}</p>
                                    <p class="text-sm text-gray-500 line-clamp-2 hidden sm:block text-center">{{ selectedMatch?.teamA?.college?.name }}</p>
                                    </div>
                                    <div class="sm:mt-2">
                                    <template v-if="selectedMatch?.assignedSport?.setup === 'Round Robin' || selectedMatch?.sport_id?.setup === 'Round Robin'">
                                        <div class="flex justify-center">
                                        <p v-if="selectedMatch?.match_result?.teamA_score > selectedMatch?.match_result?.teamB_score"
                                            class="text-3xl font-bold text-green-600">Win</p>
                                        <p v-if="selectedMatch?.match_result?.teamA_score == selectedMatch?.match_result?.teamB_score"
                                            class="text-3xl font-bold text-blue-600">Draw</p>
                                        <p v-if="selectedMatch?.match_result?.teamA_score < selectedMatch?.match_result?.teamB_score"
                                            class="text-3xl font-bold text-red-600">Lose</p>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <p class="text-3xl font-bold text-gray-900 text-center">
                                        {{ selectedMatch?.match_result?.teamA_score }}
                                        </p>
                                    </template>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <!-- Team B -->
                            <div class="sm:col-span-3 col-span-1">
                                <div class="h-full bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100 shadow-sm p-6">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                    <p class="font-bold text-lg mb-2 line-clamp-2 text-center">{{ selectedMatch?.teamB?.assigned_team_name }}</p>
                                    <p class="text-sm text-gray-500 line-clamp-2 hidden sm:block text-center">{{ selectedMatch?.teamB?.college?.name }}</p>
                                    </div>
                                    <div class="sm:mt-2">
                                    <template v-if="selectedMatch?.assignedSport?.setup === 'Round Robin' || selectedMatch?.sport_id?.setup === 'Round Robin'">
                                        <div class="flex justify-center">
                                        <p v-if="selectedMatch?.match_result?.teamB_score > selectedMatch?.match_result?.teamA_score"
                                            class="text-3xl font-bold text-green-600">Win</p>
                                        <p v-if="selectedMatch?.match_result?.teamB_score == selectedMatch?.match_result?.teamA_score"
                                            class="text-3xl font-bold text-blue-600">Draw</p>
                                        <p v-if="selectedMatch?.match_result?.teamB_score < selectedMatch?.match_result?.teamA_score"
                                            class="text-3xl font-bold text-red-600">Lose</p>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <p class="text-3xl font-bold text-gray-900 text-center">
                                        {{ selectedMatch?.match_result?.teamB_score }}
                                        </p>
                                    </template>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Winner -->
                        <div v-if="selectedMatch?.match_result" class="mt-8">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                            <div class="text-center">
                            <span class="inline-block px-4 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium mb-2">
                                Winner
                            </span>
                            <p v-if="(selectedMatch.match_result.teamA_score === 1 && selectedMatch.match_result.teamB_score === 0) || 
                                    (selectedMatch.match_result.teamB_score === 1 && selectedMatch.match_result.teamA_score === 0)" 
                                class="text-sm text-green-600 mt-1">(Win by default)</p>
                            <p class="text-2xl font-bold text-green-800 mt-2">
                                <span v-if="selectedMatch.match_result.winning_team_id === null">Draw</span>
                                <span v-else>{{ selectedMatch.match_result.winning_team_id === selectedMatch.teamA?.id 
                                ? selectedMatch.teamA?.assigned_team_name 
                                : selectedMatch.teamB?.assigned_team_name }}</span>
                            </p>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Sports Variation Result -->
                    <div v-else class="space-y-4">
                        <div v-for="team in selectedMatch?.sport_variation_i_d.sort((a, b) => {
                            const rankA = a.rank || Infinity;
                            const rankB = b.rank || Infinity;
                            const pointsA = a.points || 0;
                            const pointsB = b.points || 0;
                            return rankA - rankB || pointsB - pointsA;
                        })" :key="team.id" 
                        class="bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100 shadow-sm p-6">
                        <div class="flex justify-between items-start">
                            <div>
                            <p class="font-bold text-lg mb-1">{{ team.assigned_team_variation_i_d?.assigned_team_name }}</p>
                            <p class="text-sm text-gray-500 hidden sm:block">{{ team.assigned_team_variation_i_d?.college?.name }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-600">Rank</span>
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-semibold">
                                {{ team.rank || 'N/A' }}
                            </span>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'

const props = defineProps({
    sportMatches: {
        type: Array,
        default: () => []
    },
    activeSports: {
        type: Array,
        default: () => []
    },
    activeTeams: {
        type: Array,
        default: () => []
    },
    palakasanYear: {
        type: [String, Number],
        default: new Date().getFullYear()
    },
    sportVariations: {
        type: Array,
        default: () => []
    },
    latestPalakasan: {
        type: Object,
        default: null
    }
})

const viewSport = (sportId) => {
    if (sportId) {
        console.log('Navigating to sport:', sportId);
        router.get(route('home-sportview.index', { sport: sportId }));
    } else {
        console.log('Sport ID is missing');
    }
};
const emit = defineEmits(['update:activeTab'])

const selectedSport = ref({
    name: '',
    categories: ''
})
const selectedCategory = ref('')
const selectedTeam = ref('')
const activeTab = ref('all') // 'all', 'pending', 'ongoing', 'completed'
const tabs = [
    { id: 'all', label: 'All' },
    { id: 'pending', label: 'Pending' },
    { id: 'completed', label: 'Completed' }
]
const allMatches = computed(() => {
    const regularMatches = props.sportMatches.map(match => ({
        ...match,
        type: 'regular',
        date: match.date,
        time: match.time
    }))

    const variationMatches = props.sportVariations.map(match => ({
        ...match,
        type: 'variation',
        date: match.date,
        time: match.time,
        matchVenue: match.venue,
        assignedSport: {
            sport: { name: match.sport_name },
            categories: match.category
        }
    }))

    return [...regularMatches, ...variationMatches]
})

const availableTeams = computed(() => {
    const teams = new Set();
    
    // Add teams from regular matches
    props.sportMatches.forEach(match => {
        if (match.teamA?.assigned_team_name) teams.add(match.teamA.assigned_team_name);
        if (match.teamB?.assigned_team_name) teams.add(match.teamB.assigned_team_name);
    });
    
    // Add teams from sport variations
    props.sportVariations.forEach(match => {
        match.sport_variation_i_d?.forEach(variation => {
            if (variation.assigned_team_variation_i_d?.assigned_team_name) {
                teams.add(variation.assigned_team_variation_i_d.assigned_team_name);
            }
        });
    });
    
    return Array.from(teams).sort();
})

const formatCategories = (categories) => {
    if (Array.isArray(categories)) {
        return categories.join(', ');
    }
    return categories || '';
}

const filteredMatches = computed(() => {
    return allMatches.value.filter(match => {
        // Sport and category filter
        if (selectedSport.value.name) {
            if (match.type === 'regular') {
                const matchSportName = match.assignedSport?.sport?.name;
                const matchCategories = formatCategories(match.assignedSport?.categories);
                
                if (matchSportName !== selectedSport.value.name || 
                    matchCategories !== selectedSport.value.categories) {
                    return false;
                }
            } else {
                const matchSportName = match.sport_id?.sport?.name || match.sport_name;
                const matchCategories = formatCategories(match.sport_id?.categories || match.category);
                
                if (matchSportName !== selectedSport.value.name || 
                    matchCategories !== selectedSport.value.categories) {
                    return false;
                }
            }
        }

        // Team filter
        if (selectedTeam.value) {
            if (match.type === 'regular') {
                const teamAName = match.teamA?.assigned_team_name;
                const teamBName = match.teamB?.assigned_team_name;
                if (teamAName !== selectedTeam.value && teamBName !== selectedTeam.value) {
                    return false;
                }
            } else {
                const hasTeam = match.sport_variation_i_d?.some(variation => 
                    variation.assigned_team_variation_i_d?.assigned_team_name === selectedTeam.value
                );
                if (!hasTeam) {
                    return false;
                }
            }
        }

        // Status filter (all, pending, completed)
        if (activeTab.value !== 'all') {
            const matchStatus = (match.status || '').toLowerCase();
            if (matchStatus !== activeTab.value) {
                return false;
            }
        }

        return true;
    }).sort((a, b) => {
        // Sort by date
        const dateA = new Date(a.date + ' ' + (a.time || ''));
        const dateB = new Date(b.date + ' ' + (b.time || ''));
        if (dateA - dateB !== 0) return dateA - dateB;

        // Then sort by sport name
        const sportNameA = a.type === 'regular' ? 
            a.assignedSport?.sport?.name : 
            a.sport_id?.sport?.name || a.sport_name;
        const sportNameB = b.type === 'regular' ? 
            b.assignedSport?.sport?.name : 
            b.sport_id?.sport?.name || b.sport_name;
        if (sportNameA !== sportNameB) {
            return sportNameA < sportNameB ? -1 : 1;
        }

        // Finally sort by time
        return a.time < b.time ? -1 : 1;
    });
});
const matchCounts = computed(() => {
    const counts = {
        all: 0,
        pending: 0,
        ongoing: 0,
        completed: 0
    }
    
    ;(props.sportMatches || []).forEach(match => {
        const status = (match.status || '').toLowerCase()
        counts.all++
        if (counts.hasOwnProperty(status)) {
            counts[status]++
        }
    })
    
    return counts
})

const getTabClass = (tab) => {
    const baseClasses = 'inline-flex items-center px-4 py-2 text-sm font-medium border-b-2 cursor-pointer'
    if (activeTab.value === tab) {
        return `${baseClasses} text-blue-600 border-blue-600`
    }
    return `${baseClasses} text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300`
}

const groupedMatches = computed(() => {
    // Separate completed and pending matches
    const completedMatches = filteredMatches.value.filter(match => match.status?.toLowerCase() === 'completed');
    const pendingMatches = filteredMatches.value.filter(match => match.status?.toLowerCase() !== 'completed');
    
    // Sort completed matches (latest date first, highest game number first)
    const sortedCompleted = completedMatches.sort((a, b) => {
        const dateCompare = new Date(b.date) - new Date(a.date);
        if (dateCompare !== 0) return dateCompare;
        
        const aGameNum = parseInt(a.game?.replace('Game ', '') || '0');
        const bGameNum = parseInt(b.game?.replace('Game ', '') || '0');
        return bGameNum - aGameNum;
    });
    
    // Sort pending matches (earliest date first, lowest game number first)
    const sortedPending = pendingMatches.sort((a, b) => {
        const dateCompare = new Date(a.date) - new Date(b.date);
        if (dateCompare !== 0) return dateCompare;
        
        const aGameNum = parseInt(a.game?.replace('Game ', '') || '0');
        const bGameNum = parseInt(b.game?.replace('Game ', '') || '0');
        return aGameNum - bGameNum;
    });
    
    // Combine sorted matches
    const sortedMatches = [...sortedCompleted, ...sortedPending];
    
    // Group by date
    const grouped = {}
    sortedMatches.forEach(match => {
        if (!grouped[match.date]) {
            grouped[match.date] = []
        }
        grouped[match.date].push(match)
    })
    
    // For completed matches dates: latest first
    // For pending matches dates: earliest first
    return Object.keys(grouped)
        .sort((a, b) => {
            const aHasCompleted = grouped[a].some(match => match.status?.toLowerCase() === 'completed');
            const bHasCompleted = grouped[b].some(match => match.status?.toLowerCase() === 'completed');
            
            // If both dates have completed matches or both don't, sort by date
            if (aHasCompleted === bHasCompleted) {
                return aHasCompleted ? 
                    new Date(b) - new Date(a) :  // Latest first for completed
                    new Date(a) - new Date(b);   // Earliest first for pending
            }
            
            // Put dates with completed matches first
            return aHasCompleted ? -1 : 1;
        })
        .reduce((obj, key) => {
            obj[key] = grouped[key]
            return obj
        }, {})
})

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const showModal = ref(false)
const selectedMatch = ref(null)

const showResultModal = (match) => {
    selectedMatch.value = match
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    selectedMatch.value = null
}
</script>