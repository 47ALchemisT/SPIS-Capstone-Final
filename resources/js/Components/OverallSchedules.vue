<template>
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="sm:text-left text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Overall Schedules</h1>
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
            <div class="mb-6">
                <div class="flex gap-4">
                    <!-- Sport Filter -->
                    <div>
                        <select v-model="selectedSport" class="w-full py-2 px-3 text-sm rounded-md ring-1 ring-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Sports</option>
                            <option v-for="sport in activeSports" :key="sport.id" :value="sport.sport.name">
                                {{ sport.sport.name }}
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
                            <option v-for="team in activeTeams" :key="team.id" :value="team.name">
                                {{ team.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Schedule Cards -->
            <div class="space-y-8">
                <template v-for="(sportGroup, sportName) in groupedBySport" :key="sportName">
                    <!-- Sport and Category Header -->
                    <div class="mb-4">
                        <template v-for="(categoryGroup, category) in sportGroup" :key="category">
                            <h3 class="text-lg font-semibold text-gray-700 mt-4 mb-2">{{ sportName }} {{ category }} Schedules</h3>
                            
                            <!-- Regular Matches -->
                            <div v-if="categoryGroup.matches.length" class="space-y-2 mb-4">
                                <div v-for="(match, index) in categoryGroup.matches" :key="match.id" 
                                    class="bg-white p-4 rounded-lg ring-1 ring-gray-200 hover:ring-2 hover:ring-blue-300 transition-all duration-200">
                                    <div class="flex flex-col space-y-2">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-gray-900">Game {{ index + 1 }}</span>
                                            <div class="text-sm text-gray-600">
                                                {{ formatDate(match.date) }} / {{ formatTime(match.time) }} / {{ match.matchVenue?.name || 'TBA' }}
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-600">
                                                {{ match.teamA?.college?.name }} vs {{ match.teamB?.college?.name }}
                                            </span>
                                            <span v-if="match.match_result" class="text-green-600 font-medium">
                                                Winner: {{ match.match_result.winning_team?.college?.name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sport Variations -->
                            <div v-if="categoryGroup.variations.length" class="space-y-2">
                                <div v-for="variation in categoryGroup.variations" :key="'variation-' + variation.id" 
                                    class="bg-white p-4 rounded-lg ring-1 ring-gray-200 hover:ring-2 hover:ring-blue-300 transition-all duration-200">
                                    <div class="flex flex-col space-y-2">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium text-gray-900">{{ variation.sport_variation_name }}</span>
                                            <div class="text-sm text-gray-600">
                                                {{ formatDate(variation.date) }} / {{ formatTime(variation.time) }} / {{ variation.venue_id?.name || 'TBA' }}
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-600">
                                                Teams: {{ variation.sport_variation_i_d?.map(team => team.assignedTeamVariationID?.college?.name).join(', ') || 'TBA' }}
                                            </span>
                                            <span v-if="variation.status === 'completed'" class="text-green-600 font-medium">
                                                Completed
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- No matches message -->
                <div v-if="!Object.keys(groupedBySport).length" class="text-center py-12">
                    <div class="text-gray-400">
                        <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No Matches Found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Try adjusting your filters or check back later
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    latestPalakasan: {
        type: Object,
        default: () => ({
            sportMatches: []
        })
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
    admin: {
        type: Array,
        default: () => []
    }
});

const selectedSport = ref('')
const selectedCategory = ref('')
const selectedTeam = ref('')
const activeTab = ref('pending')

// Computed property for match counts
const matchCounts = computed(() => {
    const matches = props.latestPalakasan?.sportMatches || []
    const variations = props.latestPalakasan?.sportVariations || []
    
    return {
        pending: matches.filter(m => !m.match_result).length + 
                variations.filter(v => v.status !== 'completed').length || 0,
        completed: matches.filter(m => m.match_result).length + 
                  variations.filter(v => v.status === 'completed').length || 0
    }
})

// Computed property for available teams
const availableTeams = computed(() => {
    const teams = new Set()
    const matches = props.latestPalakasan?.sportMatches || []
    matches.forEach(match => {
        if (match.teamA?.college?.name) teams.add(match.teamA.college.name)
        if (match.teamB?.college?.name) teams.add(match.teamB.college.name)
    })
    return Array.from(teams)
})

// Computed property for filtered matches
const filteredMatches = computed(() => {
    let matches = props.latestPalakasan?.sportMatches || []

    // Filter by status (pending/completed)
    matches = matches.filter(match => {
        if (activeTab.value === 'pending') return !match.match_result
        if (activeTab.value === 'completed') return match.match_result
        return true
    })

    // Apply sport filter
    if (selectedSport.value) {
        matches = matches.filter(match => 
            match.assignedSport?.sport?.name === selectedSport.value
        )
    }

    // Apply category filter
    if (selectedCategory.value) {
        matches = matches.filter(match =>
            match.category === selectedCategory.value
        )
    }

    // Apply team filter
    if (selectedTeam.value) {
        matches = matches.filter(match =>
            match.teamA?.college?.name === selectedTeam.value ||
            match.teamB?.college?.name === selectedTeam.value
        )
    }

    return matches
})

// Computed property for filtered variations
const filteredVariations = computed(() => {
    let variations = props.latestPalakasan?.sportVariations || []

    // Filter by status (pending/completed)
    variations = variations.filter(variation => {
        if (activeTab.value === 'pending') return variation.status !== 'completed'
        if (activeTab.value === 'completed') return variation.status === 'completed'
        return true
    })

    // Apply sport filter
    if (selectedSport.value) {
        variations = variations.filter(variation => 
            variation.sport_id?.sport?.name === selectedSport.value
        )
    }

    // Apply category filter
    if (selectedCategory.value) {
        variations = variations.filter(variation =>
            variation.sport_id?.categories === selectedCategory.value
        )
    }

    // Apply team filter
    if (selectedTeam.value) {
        variations = variations.filter(variation => {
            const teams = variation.sport_variation_i_d?.map(team => 
                team.assignedTeamVariationID?.college?.name
            ) || []
            return teams.includes(selectedTeam.value)
        })
    }

    return variations
})

// Computed property for grouped matches by sport and category
const groupedBySport = computed(() => {
    const grouped = {}
    
    // Group regular matches
    filteredMatches.value.forEach(match => {
        const sportName = match.assignedSport?.sport?.name || 'Uncategorized'
        const category = match.category || 'Uncategorized'
        
        if (!grouped[sportName]) {
            grouped[sportName] = {}
        }
        if (!grouped[sportName][category]) {
            grouped[sportName][category] = {
                matches: [],
                variations: []
            }
        }
        
        grouped[sportName][category].matches.push(match)
    })

    // Group variations
    filteredVariations.value.forEach(variation => {
        const sportName = variation.sport_id?.sport?.name || 'Uncategorized'
        const category = variation.sport_id?.categories || 'Uncategorized'
        
        if (!grouped[sportName]) {
            grouped[sportName] = {}
        }
        if (!grouped[sportName][category]) {
            grouped[sportName][category] = {
                matches: [],
                variations: []
            }
        }
        
        grouped[sportName][category].variations.push(variation)
    })
    
    // Sort matches and variations within each category by date
    Object.keys(grouped).forEach(sport => {
        Object.keys(grouped[sport]).forEach(category => {
            grouped[sport][category].matches.sort((a, b) => {
                if (!a.date) return 1
                if (!b.date) return -1
                return new Date(a.date) - new Date(b.date)
            })
            grouped[sport][category].variations.sort((a, b) => {
                if (!a.date) return 1
                if (!b.date) return -1
                return new Date(a.date) - new Date(b.date)
            })
        })
    })
    
    return grouped
})

// Helper function for tab classes
const getTabClass = (tab) => {
    const isActive = activeTab.value === tab
    return {
        'cursor-pointer px-1 py-4 text-sm font-medium': true,
        'text-blue-600 border-b-2 border-blue-600': isActive,
        'text-gray-500 hover:text-gray-700 hover:border-gray-300': !isActive
    }
}

// Helper function to format dates
const formatDate = (dateString) => {
    if (!dateString) return 'TBA'
    try {
        const date = new Date(dateString)
        if (isNaN(date.getTime())) return 'TBA'
        return new Intl.DateTimeFormat('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        }).format(date)
    } catch (error) {
        return 'TBA'
    }
}

// Helper function to format times
const formatTime = (timeString) => {
    if (!timeString) return ''
    try {
        const [hours, minutes] = timeString.split(':')
        const date = new Date()
        date.setHours(parseInt(hours), parseInt(minutes))
        return new Intl.DateTimeFormat('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        }).format(date)
    } catch (error) {
        return ''
    }
}
</script>