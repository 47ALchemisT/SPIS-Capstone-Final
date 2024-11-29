<template>
    <Head title="College"/>
    <AppLayout :facilitator="facilitator">
        <template v-slot:default>
            <div class="flex items-center justify-between gap-2 pt-4">
                    <h1 class="text-2xl font-semibold">{{ sport.sport.name }} {{ sport.categories }}</h1>
                    <div>
                        <button 
                            @click="returnToFacilitator" 
                            type="button" 
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-2 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            >
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Return
                        </button>
                    </div>
                </div>

                <div class="flex items-center text-gray-600 gap-2 ">
                    <p class="text-sm"> {{ sport.setup }}</p>
                    <span class="text-sm">•</span>
                    <p class=" text-sm">{{ sport.type }}</p>
                    <span class="text-sm">•</span>
                    <p class=" text-sm">{{ sport.status }}</p>
                </div>
                
                <!-- Progress Bar -->
                <div class="flex mt-3 flex-col">
                    <div class="w-1/3 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" :style="{ width: `${progressPercentage}%` }"></div>
                    </div>
                    <div class="flex items-center mt-2">
                        <h2 class="text-xs text-gray-600">Sport Progress  </h2>
                        <h2 class="text-xs text-gray-600 px-2">:</h2>
                        <span class="text-xs font-medium">{{ progressPercentage.toFixed(0) }}% completed</span>
                    </div>
                </div>

            <Toast ref="toastRef" />
                <!-- Tournament Winner Display -->
                <div v-if="tournamentWinner" class="mt-6 p-6 bg-green-50 flex justify-center flex-col items-center border border-green-200 rounded-lg">
                    <h2 class="text-md font-medium text-green-800">Game Winner</h2>
                    <p class="text-3xl font-bold text-green-700 mt-2">{{ getTeamName(tournamentWinner) }}</p>
                    <button
                        @click="openRankingModal"
                        type="button"
                        class="text-white mt-4 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                        :disabled="isSubmittingRankings || rankingsSubmitted"
                        >
                        {{ rankingsSubmitted ? 'Rankings Submitted' : 'Submit Ranking' }}
                    </button>
                </div>

            <!--tab navigation-->
            <nav class="flex relative justify-between mt-4  items-center">
                    <div class="flex gap-2 rounded-lg ">
                        <div class=" flex gap-2 rounded-lg">
                            <button 
                                v-for="tab in ['matches', 'standing', 'brackets', 'players']"
                                :key="tab"
                                @click="activeTab = tab"
                                :class="[
                                'px-5 py-2 text-sm',
                                activeTab === tab
                                    ? 'text-gray-800  bg-blue-700 text-white font-medium rounded-md'
                                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 border-transparent'
                                ]"
                            >
                                {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                            </button>
                        </div>
                    </div>
            </nav>

            <div class="mt-4 pb-6">
                <div v-if="activeTab === 'matches'">
                    <GameSchedule :matches="matches" :teams="teams" :results="results" :venues="venues"  :allMatches="allMatches" :venueRecords="venueRecords"/>                
                </div>
                <div v-if="activeTab === 'standing'">
                    <div class="w-full">
                        <Standing :teams="teams" :results="results" />
                    </div>
                </div>

                <div v-if="activeTab === 'brackets'">
                    <!-- Display Bracket -->
                    <div class="mt-2">
                        <div class="flex justify-between">
                            <h2 class="text-md font-semibold mb-4">Tournament Bracket</h2>
                        </div>
                        <div v-if="groupedMatches.length > 0" class="flex gap-8">
                            <div 
                                v-for="(round, index) in groupedMatches" 
                                :key="index" 
                                class="space-y-6"
                            >
 
                                <h3 class="text-center text-sm font-bold">
                                    {{ index + 1 === groupedMatches.length ? 'Championship' : `Round ${index + 1}` }}
                                </h3>
                                <div 
                                    v-for="match in round" 
                                    :key="match.id" 
                                    class=" w-56 rounded-lg space-y-2"
                                >
                                    <div class="flex justify-between items-center">
                                        <p class="font-medium text-xs flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1-7 0a5 5 0 0 0-7 0zm0 16v-7"/></svg>
                                            {{ match.game }}
                                        </p>
                                    </div>
                                    <div class="shadow border border-gray-300 p-2 rounded-lg" :class="{'ring-1 bg-green-50 ring-green-500': isChampionshipMatch(match)}"
                                    >
                                        <span 
                                            class="mb-1 px-2 text-xs rounded-md flex items-center justify-between"
                                            :class="getTeamBackgroundColor(match.id, match.teamA_id)"
                                        >
                                            <p class="p-2">{{ getTeamName(match.teamA_id) }}</p>
                                            <div v-if="getMatchResult(match.id)" class="p-2 text-center">
                                                <p>{{ getMatchResult(match.id).teamA_score }}</p>
                                            </div>
                                        </span>
                                        <span 
                                            class=" px-2 text-xs rounded-md flex items-center justify-between"
                                            :class="getTeamBackgroundColor(match.id, match.teamB_id)"
                                        >
                                            <p class="p-2">{{ getTeamName(match.teamB_id) }}</p>
                                            <div v-if="getMatchResult(match.id)" class=" p-2 text-center">
                                                <p>{{ getMatchResult(match.id).teamB_score }}</p>
                                            </div>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div v-else>
                            <p>No matches have been created yet. Click 'Start Tournament' to begin.</p>
                        </div>
                    </div>
                </div>
                <div v-if="activeTab === 'players'">
                    <PlayersDisplay class="mt-1" :players="players" :teams="teams" />
                </div>
            </div>

            <!-- Ranking Modal -->
            <div v-if="showRankingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg relative w-96 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Submit Rankings</h3>
                    <button @click="closeRankingModal" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    </button>
                </div>
                <div class="p-4">
                    <form @submit.prevent="submitRankings">
                    <div v-for="team in rankingTeams" :key="team.id" class="mb-4">
                        <div class="flex items-center justify-between">
                        <span class="font-medium">{{ team.assigned_team_name }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="">Rank</span>
                            <input
                            v-model="team.rank"
                            type="text"
                            min="1"
                            disabled
                            :max="teams.length"
                            class="w-8 border text-center rounded px-2 py-1"
                            placeholder="Rank"
                            />
                            <span class="">Points</span>
                            <input
                            v-model="team.points"
                            disabled
                            class="w-10 border rounded text-center px-2 py-1"
                            placeholder="Points"
                            required
                            readonly
                            />
                        </div>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-sm font-medium text-white rounded-md hover:bg-blue-700"
                        :disabled="isSubmittingRankings || rankingsSubmitted"
                        >
                        {{ isSubmittingRankings ? 'Submitting...' : (rankingsSubmitted ? 'Rankings Submitted' : 'Submit Rankings') }}
                        </button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>
 
<script setup>
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayoutF.vue';
import Standing from '@/Components/Standing.vue';
import GameSchedule from '@/Components/Facilitator/FGameSchedule.vue';
import Toast from '@/Components/Toast.vue';  // Ad/just the import path as needed
import PlayersDisplay from '@/Components/PlayersDisplay.vue';


const props = defineProps({
    sport: [Array, Object],
    tournaments: Array,
    teams: Array,
    matches: Array,
    results: Array,
    venues: Array,
    allMatches: Array,
    venueRecords: Array,
    facilitator: Object,
    players: Array,
    majorPoints: {
      type: Array,
      default: () => []
    },
    minorPoints: {
      type: Array,
      default: () => []
    },

});


const toastRef = ref(null);
const page = usePage();

// Watch for flash messages
watch(
    () => page.props.flash,
    (flash) => {
        if (flash.message) {
            toastRef.value.addToast(flash.message, 'success');
        }
        if (flash.error) {
            toastRef.value.addToast(flash.error, 'error');
        }
    },
    { deep: true }
);

const activeTab = ref('matches');

const returnToFacilitator = () => {
  const currentPath = window.location.pathname;
  // Extract everything after "sportview/NUMBER/"
  const matches = currentPath.match(/sportview\/\d+\/([^/]+)$/);
  if (matches && matches[1]) {
    const encryptedId = matches[1];
    // Use the encrypted ID directly without any modification
    router.visit(route('facilitator.show', { id: encryptedId }));
  }
};


const getTeamName = (teamId) => {
    if (!teamId) return 'TBD';
    if (typeof teamId === 'string' && teamId.startsWith('winner:')) {
        const [, round, game] = teamId.split(':');
        return `Winner of R${round} G${game}`;
    }
    const team = props.teams.find(t => t.id === teamId);
    return team ? team.assigned_team_name : 'Unknown';
};

const groupedMatches = computed(() => {
    const rounds = {};
    const sportMatches = props.matches.filter(match => match.assigned_sport_id === props.sport.id);
    
    sportMatches.forEach(match => {
        const round = match.round || 1;
        if (!rounds[round]) {
            rounds[round] = [];
        }
        rounds[round].push(match);
    });
    
    Object.values(rounds).forEach(round => {
        round.sort((a, b) => {
            const gameA = parseInt(a.game.replace('Game ', ''));
            const gameB = parseInt(b.game.replace('Game ', ''));
            return gameA - gameB;
        });
    });
    
    return Object.values(rounds);
});


const getMatchResult = (matchId) => {
    return props.results.find(result => result.sport_match_id === matchId);
};

const getTeamBackgroundColor = (matchId, teamId) => {
    const result = getMatchResult(matchId);
    if (!result || !teamId) return 'bg-gray-200'

    if (result.winning_team_id === teamId) {
        return 'bg-green-200 text-green-600';
    } else if (result.losing_team_id === teamId) {
        return 'bg-red-200 text-red-600';
    }
    return 'bg-gray-200';
};

const tournamentWinner = computed(() => {
    const lastRound = groupedMatches.value[groupedMatches.value.length - 1];
    if (!lastRound || lastRound.length === 0) return null;
    
    const championshipMatch = lastRound[0];
    const result = getMatchResult(championshipMatch.id);
    
    return result ? result.winning_team_id : null;
});

const isChampionshipMatch = (match) => {
    const lastRound = groupedMatches.value[groupedMatches.value.length - 1];
    return lastRound && lastRound[0]?.id === match.id;
};

const totalMatches = computed(() => props.matches.length);

const completedMatches = computed(() => {
    return props.matches.filter(match => match.status === 'completed').length;
});

const progressPercentage = computed(() => {
    return (completedMatches.value / totalMatches.value) * 100;
});


//
const showRankingModal = ref(false);
const isSubmittingRankings = ref(false);
const rankingTeams = ref([]);
const rankingsSubmitted = ref(false);

const pointsToUse = computed(() => {
  return props.sport.type === 'Major' ? props.majorPoints : props.minorPoints;
});

const openRankingModal = () => {
  if (rankingsSubmitted.value) return;

  // Create a map to track team performance
  const teamPerformance = new Map(props.teams.map(team => [team.id, {
    id: team.id,
    assigned_team_name: team.assigned_team_name,
    wins: 0,
    losses: 0,
  }]));

  // Count wins and losses from results
  props.results.forEach(result => {
    if (result.winning_team_id) {
      const winningTeam = teamPerformance.get(result.winning_team_id);
      if (winningTeam) {
        winningTeam.wins += 1;
      }
    }
    if (result.losing_team_id) {
      const losingTeam = teamPerformance.get(result.losing_team_id);
      if (losingTeam) {
        losingTeam.losses += 1;
      }
    }
  });

  const sortedTeams = Array.from(teamPerformance.values()).sort((a, b) => {
    if (b.wins !== a.wins) return b.wins - a.wins;
    
    if (a.losses !== b.losses) return a.losses - b.losses;
    
    return 0;
  });

  const sortedPoints = [...pointsToUse.value].sort((a, b) => b.points - a.points);

  // Assign ranks based on sorted standings
  rankingTeams.value = sortedTeams.map((team, index) => ({
    ...team,
    rank: index + 1,
    points: sortedPoints[index] ? sortedPoints[index].points : 0
  }));

  showRankingModal.value = true;
};

const closeRankingModal = () => {
  showRankingModal.value = false;
};

const submitRankings = async () => {
  if (rankingsSubmitted.value) return;

  isSubmittingRankings.value = true;

  const rankings = rankingTeams.value.map(team => ({
    assigned_sport_id: props.sport.id,
    assigned_team_id: team.id,
    rank: parseInt(team.rank),
    points: team.points,
  }));

  try {
    await router.post(route('overall-results.store'), { rankings }, {
      preserveState: true,
      preserveScroll: true,
    });
    rankingsSubmitted.value = true;
    closeRankingModal();
  } catch (error) {
    console.error('Error submitting rankings:', error);
    // Handle error (e.g., show error message to user)
  } finally {
    isSubmittingRankings.value = false;
  }
};
</script>
 
<style scoped>
/* Home page specific styles */
</style>