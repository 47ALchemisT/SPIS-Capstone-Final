<template>
    <Head title="Rankings" />
    <MainLayout>
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
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Leading Teams</h3>
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
                                            <p class="text-sm font-medium text-gray-900">{{ team.total_points }} total points</p>
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

                <!-- Sport-specific Rankings -->
                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Sport Rankings</h2>
                    
                    <div v-for="sport in completedSports.filter(s => 
                            !s.categories.toLowerCase().includes('free for all') && 
                            s.setup !== 'Free for All')" 
                         :key="'sport-'+sport.id" 
                         class="mb-8 bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                {{ sport.sport.name }} {{ sport.categories }}
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wins</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Losses</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Win %</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template v-if="getSportRankings(sport.id).length">
                                            <tr v-for="(team, index) in getSportRankings(sport.id)" :key="team.team_id">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8">
                                                            <img v-if="team.logo" :src="team.logo" class="h-8 w-8 rounded-full" :alt="team.name">
                                                            <div v-else class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                                <span class="text-xs font-medium text-gray-500">{{ team.acronym }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ team.name }}</div>
                                                            <div class="text-sm text-gray-500">{{ team.acronym }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">{{ team.wins }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">{{ team.losses }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ team.win_percentage }}%</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium">{{ team.regular_points + team.variation_points }}</td>
                                            </tr>
                                        </template>
                                        <tr v-else>
                                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No rankings available for this sport yet
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Sport Variations -->
                    <div v-for="variation in sportsVariationMatches" :key="'variation-'+variation.id" class="mb-8 bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <i :class="variation.sport_id.sport.icon" class="mr-2"></i>
                                {{ variation.sport_id.sport.name }} {{ variation.sport_id.categories }} - {{ variation.sport_variation_name }}
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template v-if="variation.sport_variation_i_d && variation.sport_variation_i_d.length">
                                            <tr v-for="team in variation.sport_variation_i_d" :key="team.id">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ team.rank || '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8">
                                                            <img v-if="team.assigned_team_variation_i_d?.college?.logo" 
                                                                 :src="team.assigned_team_variation_i_d.college.logo" 
                                                                 class="h-8 w-8 rounded-full" 
                                                                 :alt="team.assigned_team_variation_i_d?.college?.name">
                                                            <div v-else class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                                <span class="text-xs font-medium text-gray-500">
                                                                    {{ team.assigned_team_variation_i_d?.college?.acronym }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ team.assigned_team_variation_i_d?.assigned_team_name }}
                                                            </div>
                                                            <div class="text-sm text-gray-500">
                                                                {{ team.assigned_team_variation_i_d?.college?.name }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium">{{ team.points || 0 }}</td>
                                            </tr>
                                        </template>
                                        <tr v-else>
                                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No teams registered for this variation
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import Graph from '@/Components/BarGraph.vue';

const props = defineProps({
    latestPalakasan: {
        type: Object,
        required: true
    },
    sportMatches: {
        type: Array,
        default() {
            return [];
        }
    },
    overallResult: {
        type: Array,
        default() {
            return [];
        }
    },
    sportsVariationMatches: {
        type: Array,
        required: true
    },
    sportsVariations: {
        type: Array,
        default() {
            return [];
        }
    },
    activeSports: {
        type: Array,
        required: true
    },
    activeTeams: {
        type: Array,
        required: true
    },
    assignedTeams: {
        type: Array,
        required: true
    },
    assignedSports: {
        type: Array,
        required: true
    }
});

const teamRankings = computed(() => {
    return props.assignedTeams.map(team => {
        const teamOverallResults = props.overallResult.filter(
            result => result.assigned_team_id === team.id
        );
        const regular_points = teamOverallResults.reduce((sum, result) => sum + (result.points || 0), 0);
        
        const teamVariationResults = props.sportsVariationMatches.filter(
            result => result.sport_variation_team_id === team.id
        );
        const variation_points = teamVariationResults.reduce((sum, result) => sum + (result.points || 0), 0);
        
        const total_points = regular_points + variation_points;
        
        return {
            ...team,
            regular_points,
            variation_points,
            total_points
        };
    }).sort((a, b) => b.total_points - a.total_points);
});

const completedSports = computed(() => {
    return props.activeSports.filter(sport => sport.status === 'completed');
});

const getSportRankings = (sportId) => {
    // Get regular matches for this sport
    const regularMatches = props.sportMatches.filter(match => match.assignedSport?.id === sportId);
    
    // Get variation matches for this sport
    const variationMatches = props.sportsVariationMatches.filter(match => match.sportVariationID?.assigned_sport_id === sportId);
    
    // Create a map to store team statistics
    const teamStats = new Map();
    
    // Initialize stats for all teams
    props.assignedTeams.forEach(team => {
        teamStats.set(team.id, {
            team_id: team.id,
            name: team.college?.name,
            acronym: team.college?.acronym,
            logo: team.college?.logo,
            wins: 0,
            losses: 0,
            total_games: 0,
            regular_points: 0,
            variation_points: 0
        });
    });
    
    // Calculate stats from regular matches
    regularMatches.forEach(match => {
        if (match.match_result) {
            const winner = match.match_result.winning_team_id;
            const loser = match.match_result.losing_team_id;
            
            if (winner && teamStats.has(winner)) {
                const stats = teamStats.get(winner);
                stats.wins++;
                stats.total_games++;
                stats.regular_points += 2; // 2 points per win
                teamStats.set(winner, stats);
            }
            
            if (loser && teamStats.has(loser)) {
                const stats = teamStats.get(loser);
                stats.losses++;
                stats.total_games++;
                teamStats.set(loser, stats);
            }
        }
    });
    
    // Calculate stats from variation matches
    variationMatches.forEach(match => {
        const teamId = match.sport_variation_team_id;
        if (teamStats.has(teamId)) {
            const stats = teamStats.get(teamId);
            if (match.rank === 1) {
                stats.wins++;
                stats.variation_points += 3; // 3 points for first place
            } else if (match.rank === 2) {
                stats.variation_points += 2; // 2 points for second place
            } else if (match.rank === 3) {
                stats.variation_points += 1; // 1 point for third place
            }
            stats.total_games++;
            teamStats.set(teamId, stats);
        }
    });
    
    // Convert to array and calculate percentages
    return Array.from(teamStats.values())
        .map(team => ({
            ...team,
            win_percentage: team.total_games > 0 
                ? Math.round((team.wins / team.total_games) * 100 * 10) / 10 
                : 0
        }))
        .filter(team => team.total_games > 0) // Only show teams that have played
        .sort((a, b) => b.regular_points + b.variation_points - a.regular_points - a.variation_points || b.win_percentage - a.win_percentage);
};

const getTeamInfo = (teamId) => {
    const team = props.assignedTeams.find(team => team.id === teamId);
    return team || { assigned_team_name: 'TBA', college: { name: '-' } };
};
</script>
