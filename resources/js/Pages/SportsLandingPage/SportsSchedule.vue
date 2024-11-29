<template>
    <Head title="Schedule" />
    <MainLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <!-- Header Section -->
                <div class="sm:text-left text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Sports Schedule</h1>
                    <p class="mt-2 text-gray-600">{{ palakasanYear }} Palakasan Games</p>
                </div>

                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="-mb-px flex space-x-8">
                        <a @click="activeTab = 'pending'"
                        :class="getTabClass('pending')">
                            Pending
                            <span class="ml-2 bg-gray-100 text-gray-900 px-2.5 py-0.5 rounded-full text-xs">
                                {{ matchCounts.pending }}
                            </span>
                        </a>
                        <a @click="activeTab = 'completed'"
                        :class="getTabClass('completed')">
                            Completed
                            <span class="ml-2 bg-green-100 text-green-900 px-2.5 py-0.5 rounded-full text-xs">
                                {{ matchCounts.completed }}
                            </span>
                        </a>
                    </nav>
                </div>

                <!-- Filters Section -->
                <div class=" mb-6">
                    <div class="flex gap-4">
                        <!-- Sport Filter -->
                        <div>
                            <select v-model="selectedSport" class="w-full py-2 px-3 text-sm rounded-md ring-1 ring-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Sports</option>
                                <option v-for="sport in activeSports" :key="sport.id" :value="sport?.sport?.name">
                                    {{ sport?.sport?.name }}
                                </option>
                            </select>
                        </div>
                        <!-- Category Filter -->
                        <div>
                            <select v-model="selectedCategory" class="w-full py-2 px-3 text-sm rounded-md ring-1 ring-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Categories</option>
                                <option value="Men">Men</option>
                                <option value="Women">Women</option>
                                <option value="Mixed">Mixed</option>
                            </select>
                        </div>
                        <!-- Team Filter -->
                        <div>
                            <select v-model="selectedTeam" class="w-full py-2 px-3 text-sm ring-1 ring-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Teams</option>
                                <option v-for="team in activeTeams" :key="team.id" :value="team?.college?.name">
                                    {{ team?.college?.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Schedule Cards -->
                <div class="space-y-4">
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
                                <div class="border-b border-gray-100 p-4">
                                    <!-- Regular Match Header -->
                                    <div v-if="match.type === 'regular'" class="flex flex-col sm:flex-row gap-2">
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
                                        
                                        <!-- Sport and Game Info -->
                                        <div class="flex flex-wrap text-sm items-center gap-2">
                                            <span class="font-semibold text-gray-900">{{ match.assignedSport?.sport?.name || match.sport_name }}</span>
                                            <span class="font-semibold text-gray-900">{{ match.assignedSport?.categories || match.category }}</span>
                                            <span class="text-sm font-medium text-gray-600">{{ match.game || 'Game' }}</span>
                                        </div>

                                        <!-- View Details button for completed matches -->
                                        <div v-if="match.status?.toLowerCase() === 'completed'" class="sm:ml-auto mt-2 sm:mt-0">
                                            <button 
                                                @click="showResultModal(match)"
                                                type="button" 
                                                class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                View Result
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Sports Variation Header -->
                                    <div v-else class="flex flex-col sm:flex-row gap-2 sm:items-center">
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
                                        <!-- Sport Name -->
                                        <div class="flex flex-wrap text-sm items-center gap-2">
                                            <span class="font-medium text-gray-600">{{ match.sport_id?.sport?.name }} {{ match.sport_id?.categories }}</span>
                                        </div>
                                        <span class="hidden sm:block text-gray-400">•</span>
                                        <!-- Sport Variation Name -->
                                        <div class="flex flex-wrap text-sm items-center gap-2">
                                            <span class="font-medium text-gray-600">{{ match.sport_variation_name }}</span>
                                        </div>

                                        <!-- View Details button for completed variation matches -->
                                        <div v-if="match.status?.toLowerCase() === 'completed'" class="sm:ml-auto mt-2 sm:mt-0">
                                            <button 
                                                @click="showResultModal(match)"
                                                type="button" 
                                                class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                View Result
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Teams Section -->
                                <div class="p-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-11 gap-4">
                                        <template v-if="match.type === 'regular'">
                                            <!-- Team A -->
                                            <div class="sm:col-span-5 flex flex-col items-center justify-center text-center">
                                                <span class="font-bold text-lg text-gray-900 mb-1">{{ match.teamA?.assigned_team_name || 'TBA' }}</span>
                                                <span class="text-sm text-gray-500">{{ match.teamA?.college?.name }}</span>
                                            </div>

                                            <!-- VS -->
                                            <div class="sm:col-span-1 flex items-center justify-center my-2 sm:my-0">
                                                <span class="font-bold text-xl text-gray-400">VS</span>
                                            </div>

                                            <!-- Team B -->
                                            <div class="sm:col-span-5 flex flex-col items-center justify-center text-center">
                                                <span class="font-bold text-lg text-gray-900 mb-1">{{ match.teamB?.assigned_team_name || 'TBA' }}</span>
                                                <span class="text-sm text-gray-500">{{ match.teamB?.college?.name }}</span>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <!-- Sports Variation Match -->
                                            <div class="col-span-full">
                                                <div v-if="match.sport_variation_i_d && match.sport_variation_i_d.length > 0" 
                                                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                                    <div v-for="team in match.sport_variation_i_d" :key="team.id" 
                                                        class="bg-gray-50 hover:bg-gray-100 rounded-lg p-3 transition-colors">
                                                        <div class="flex flex-col items-center">
                                                            <span class="text-md font-medium text-gray-700">{{ team.assigned_team_variation_i_d?.assigned_team_name || 'TBA' }}</span>
                                                            <span class="text-sm text-center text-gray-500">{{ team.assigned_team_variation_i_d?.college?.name }}</span>
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
                </div>
            </div>
        </div>
    </MainLayout>

    <!-- Result Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 px-4 py-6 sm:px-0">
        <div class="relative h-full flex items-center justify-center">
            <div class="absolute inset-0" @click="closeModal"></div>
            
            <div class="relative w-full max-w-lg mx-auto bg-white rounded-lg shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-50">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900">
                        Match Result
                    </h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="px-6 py-8">
                    <!-- Match Info -->
                    <div class="space-y-1 mb-6">
                        <h4 class="text-base sm:text-lg font-medium text-gray-900">
                            {{ selectedMatch?.assignedSport?.sport?.name || selectedMatch?.sport_id?.sport?.name }}
                            {{ selectedMatch?.assignedSport?.categories || selectedMatch?.sport_id?.categories }}
                        </h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p>{{ formatDate(selectedMatch?.date) }}</p>
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ selectedMatch?.time }}
                            </p>
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ selectedMatch?.matchVenue?.name || selectedMatch?.venue_id?.name }}
                            </p>
                        </div>
                    </div>

                    <!-- Regular Match Result -->
                    <div v-if="selectedMatch?.type === 'regular'" class="py-4">
                        <div class="grid grid-cols-7 gap-4 items-center">
                            <!-- Team A -->
                            <div class="col-span-3 text-center">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="font-semibold text-base sm:text-lg mb-1 line-clamp-2">{{ selectedMatch?.teamA?.assigned_team_name }}</p>
                                    <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ selectedMatch?.teamA?.college?.name }}</p>
                                    <div>
                                        <template v-if="selectedMatch?.assignedSport?.round_robin || selectedMatch?.sport_id?.round_robin">
                                            <div class="flex justify-center space-x-4">
                                                <div>
                                                    <p class="text-2xl sm:text-4xl font-bold text-green-600">{{ selectedMatch?.match_result?.teamA_score }}</p>
                                                    <p class="text-xs text-gray-500">Wins</p>
                                                </div>
                                                <div>
                                                    <p class="text-2xl sm:text-4xl font-bold text-red-600">{{ selectedMatch?.match_result?.teamA_losses || '0' }}</p>
                                                    <p class="text-xs text-gray-500">Losses</p>
                                                </div>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <p class="text-2xl sm:text-4xl font-bold text-gray-900">{{ selectedMatch?.match_result?.teamA_score }}</p>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- VS -->
                            <div class="col-span-1 text-center">
                                <span class="text-lg sm:text-xl font-bold text-gray-400">VS</span>
                            </div>

                            <!-- Team B -->
                            <div class="col-span-3 text-center">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="font-semibold text-base sm:text-lg mb-1 line-clamp-2">{{ selectedMatch?.teamB?.assigned_team_name }}</p>
                                    <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ selectedMatch?.teamB?.college?.name }}</p>
                                    <div>
                                        <template v-if="selectedMatch?.assignedSport?.round_robin || selectedMatch?.sport_id?.round_robin">
                                            <div class="flex justify-center space-x-4">
                                                <div>
                                                    <p class="text-2xl sm:text-4xl font-bold text-green-600">{{ selectedMatch?.match_result?.teamB_score }}</p>
                                                    <p class="text-xs text-gray-500">Wins</p>
                                                </div>
                                                <div>
                                                    <p class="text-2xl sm:text-4xl font-bold text-red-600">{{ selectedMatch?.match_result?.teamB_losses || '0' }}</p>
                                                    <p class="text-xs text-gray-500">Losses</p>
                                                </div>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <p class="text-2xl sm:text-4xl font-bold text-gray-900">{{ selectedMatch?.match_result?.teamB_score }}</p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Winner -->
                        <div v-if="selectedMatch?.match_result" class="mt-6 w-full ">
                            <div class="inline-block bg-green-50 rounded-lg w-full text-center px-6 py-3">
                                <p class="text-sm text-green-600 font-medium">Winner</p>
                                <p class="text-base sm:text-lg font-semibold text-green-700 mt-1">
                                    {{ selectedMatch.match_result.winning_team_id === selectedMatch.teamA?.id 
                                        ? selectedMatch.teamA?.assigned_team_name 
                                        : selectedMatch.teamB?.assigned_team_name }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Sports Variation Result -->
                    <div v-else class="py-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div v-for="team in selectedMatch?.sport_variation_i_d" :key="team.id" 
                                class="bg-gray-50 rounded-lg p-4">
                                <p class="font-semibold text-base sm:text-lg mb-1">
                                    {{ team.assigned_team_variation_i_d?.assigned_team_name }}
                                </p>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ team.assigned_team_variation_i_d?.college?.name }}
                                </p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm font-medium text-gray-600">Rank:</span>
                                    <span class="ml-2 px-3 py-1 text-sm font-medium bg-blue-50 text-blue-700 rounded-full">
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

const selectedSport = ref('')
const selectedCategory = ref('')
const selectedTeam = ref('')
const activeTab = ref('pending') // 'all', 'pending', 'ongoing', 'completed'

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

const filteredMatches = computed(() => {
    return allMatches.value.filter(match => {
        const sportMatch = !selectedSport.value || 
            (match.type === 'regular' ? 
                match.assignedSport?.sport?.name === selectedSport.value :
                match.sport_id?.sport?.name === selectedSport.value)
        const categoryMatch = !selectedCategory.value || 
            (match.type === 'regular' ? 
                match.assignedSport?.categories === selectedCategory.value :
                match.sport_id?.categories === selectedCategory.value)
        const teamMatch = !selectedTeam.value || 
            (match.type === 'regular' ? 
                (match.teamA?.college?.name === selectedTeam.value || 
                match.teamB?.college?.name === selectedTeam.value) :
                (match.sport_variation_i_d || []).some(team => 
                    team.assigned_team_variation_i_d?.college?.name === selectedTeam.value
                ))
        const statusMatch = activeTab.value === 'all' || 
            (match.status || '').toLowerCase() === activeTab.value
        return sportMatch && categoryMatch && teamMatch && statusMatch
    }).sort((a, b) => {
        // First sort by date
        const dateA = new Date(a.date + ' ' + (a.time || ''))
        const dateB = new Date(b.date + ' ' + (b.time || ''))
        if (dateA - dateB !== 0) return dateA - dateB

        // Then sort by sport name
        const sportNameA = a.type === 'regular' ? 
            a.assignedSport?.sport?.name : 
            a.sport_id?.sport?.name
        const sportNameB = b.type === 'regular' ? 
            b.assignedSport?.sport?.name : 
            b.sport_id?.sport?.name
        if (sportNameA !== sportNameB) {
            return sportNameA < sportNameB ? -1 : 1
        }

        // Then sort by category
        const categoryA = a.type === 'regular' ? 
            a.assignedSport?.categories : 
            a.sport_id?.categories
        const categoryB = b.type === 'regular' ? 
            b.assignedSport?.categories : 
            b.sport_id?.categories
        if (categoryA !== categoryB) {
            return categoryA < categoryB ? -1 : 1
        }

        // Finally sort by time
        return a.time < b.time ? -1 : 1
    })
})

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