<template>
    <Head :title="sport.sport.name"/>
    <AppLayout :facilitator="facilitator">
        <template v-slot:default>
            <div class="flex flex-col">
                <div class="flex items-center justify-between gap-2 pt-4">
                    <h1 class="text-2xl font-semibold">{{ sport.sport.name }} {{ sport.categories }}</h1>
                    <div class="hidden sm:flex justify-end mb-2">
                        <button 
                            @click="returnToFacilitator" 
                            type="button" 
                            class="text-white bg-blue-700 font-medium hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-md text-sm px-3 py-2.5 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
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
                    <div class=" sm:w-1/3 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" :style="{ width: `${progressPercentage}%` }"></div>
                    </div>
                    <div class="flex items-center mt-2">
                        <h2 class="text-xs text-gray-600">Sport Progress  </h2>
                        <h2 class="text-xs text-gray-600 px-2">:</h2>
                        <span class="text-xs font-medium">{{ progressPercentage.toFixed(0) }}% completed</span>
                    </div>
                </div>

                <!-- Tournament Winner Display -->
                <div v-if="tournamentWinner" class="mt-6 p-6 bg-green-50 flex justify-center flex-col items-center border border-green-200 rounded-lg">
                    <h2 class="text-md font-medium text-green-800">Game Winner</h2>
                    <p class="text-3xl font-bold text-green-700 mt-2">{{ getTeamName(tournamentWinner) }}</p>
                    <button
                        v-if="sport.status !== 'completed'"
                        @click="openRankingModal"
                        type="button"
                        class="text-white mt-4 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                        :disabled="isSubmittingRankings || rankingsSubmitted"
                        >
                        {{ rankingsSubmitted ? 'Rankings Submitted' : 'Submit Ranking' }}
                    </button>
                </div>

                <!-- Tabs Navigation -->
                <nav class="mt-4">
                    <div class="grid grid-cols-2 sm:grid-cols-none sm:flex sm:gap-2 gap-2 rounded-lg">
                        <button 
                            v-for="tab in ['matches', 'standing', 'brackets', 'players']"
                            :key="tab"
                            @click="activeTab = tab"
                            :class="[
                                'px-5 py-2.5 font-medium sm:py-2 text-sm w-full sm:w-auto',
                                activeTab === tab
                                    ? 'text-gray-800 bg-blue-700 text-white font-medium rounded-md'
                                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 border-transparent'
                            ]"
                        >
                            {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                        </button>
                    </div>
                </nav>

                <div class="mt-4 mb-8">
                    <div v-if="activeTab === 'matches'" >
                        <GameSchedule :matches="matches" :teams="teams" :results="results" :venues="venues"  :allMatches="allMatches" :venueRecords="venueRecords"/>                
                    </div>
                    <div v-if="activeTab === 'standing'">
                        <div class="w-full">
                            <Standing :teams="teams" :results="results" />
                        </div>
                    </div>
                    <div v-if="activeTab === 'brackets'">
                        <div class="overflow-hidden">
                            <div class="overflow-hidden max-w-full">
                                <div v-if="Object.keys(groupedBrackets).length > 0" class="overflow-x-auto overflow-y-hidden">
                                    <div class="space-y-8">
                                    <!-- Winners Bracket -->
                                    <div>
                                        <h3 class="text-md font-semibold text-green-700 bg-white  py-2">Winners Bracket</h3>
                                        <div class="flex gap-8 pb-4">
                                            <div 
                                                v-for="(matches, round) in groupedBrackets.winners" 
                                                :key="`winners-${round}`"
                                                class="space-y-6"
                                            >
                                                <h4 class="text-center text-sm font-bold bg-white py-2">Round {{ round }}</h4>
                                                <div 
                                                v-for="match in matches"
                                                :key="match.id"
                                                class=" w-56  space-y-2"
                                                >
                                                    <div class="flex justify-between items-center">
                                                        <p class="font-medium text-xs flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1-7 0a5 5 0 0 0-7 0zm0 16v-7"/></svg>
                                                            {{ match.game }}
                                                        </p>
                                                    </div>
                                                    <div 
                                                    class="shadow border border-gray-300 p-2 rounded-lg"
                                                    :class="{ 'border-2 border-green-500 bg-green-50': isChampionshipMatch(match) }"
                                                    >
                                                        <span class="mb-1 bg-gray-100 text-xs flex rounded-md justify-between"
                                                        :class="getTeamBackgroundColor(match.id, match.teamA_id)"
                                                        >
                                                            <p class="p-2">{{ getTeamName(match.teamA_id) }}</p>
                                                            <div v-if="getMatchResult(match.id)" class="mt-2 mr-2">
                                                                <p>{{ getMatchResult(match.id).teamA_score }}</p>
                                                            </div>
                                                        </span>
                                                        <span class=" bg-gray-100 text-xs flex rounded-md justify-between"
                                                        :class="getTeamBackgroundColor(match.id, match.teamB_id)">
                                                            <p class="p-2">{{ getTeamName(match.teamB_id) }}</p>
                                                            <div v-if="getMatchResult(match.id)" class="mt-2 mr-2">
                                                                <p>{{ getMatchResult(match.id).teamB_score }}</p>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Deciding Match -->
                                            <div v-if="decidingMatch" class="space-y-6">
                                                <h4 class="text-center text-sm font-bold bg-white py-2">
                                                    Deciding Match
                                                </h4>
                                                <div 
                                                    :key="decidingMatch.id"
                                                    class=" w-56 space-y-2"
                                                >
                                                <div class="flex justify-between items-center">
                                                        <p class="font-medium text-xs flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1-7 0a5 5 0 0 0-7 0zm0 16v-7"/></svg>
                                                            {{ decidingMatch.game }}
                                                        </p>
                                                    </div>
                                                    <div  class="shadow ring-2 ring-yellow-400 bg-yellow-50 p-2 rounded-lg">
                                                        <span class="mb-1 bg-yellow-200 text-xs rounded-md flex justify-between"
                                                            :class="getTeamBackgroundColor(decidingMatch.id, decidingMatch.teamA_id)">
                                                            <p class="p-2">{{ getTeamName(decidingMatch.teamA_id) }}</p>
                                                            <div v-if="getMatchResult(decidingMatch.id)" class="mt-2 mr-2">
                                                                <p>{{ getMatchResult(decidingMatch.id).teamA_score }}</p>
                                                            </div>
                                                        </span>
                                                        <span class=" bg-yellow-200 text-xs rounded-md flex justify-between"
                                                            :class="getTeamBackgroundColor(decidingMatch.id, decidingMatch.teamB_id)">
                                                            <p class="p-2">{{ getTeamName(decidingMatch.teamB_id) }}</p>
                                                            <div v-if="getMatchResult(decidingMatch.id)" class="mt-2 mr-2">
                                                                <p>{{ getMatchResult(decidingMatch.id).teamB_score }}</p>
                                                            </div>  
                                                        </span>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Losers Bracket -->
                                    <div>
                                        <h3 class="text-md font-semibold text-orange-700 sticky top-0 bg-white z-10 py-2">Losers Bracket</h3>
                                        <div class="flex gap-8 pb-4">
                                        <div 
                                            v-for="(matches, round) in groupedBrackets.losers"
                                            :key="`losers-${round}`"
                                            class="space-y-6"
                                        >
                                            <h4 class="text-center text-sm font-bold py-2">Round {{ round }}</h4>
                                            <div 
                                                v-for="match in matches"
                                                :key="match.id"
                                                class=" w-56 space-y-2"
                                                >
                                                <div class="flex justify-between items-center">
                                                    <p class="font-medium text-xs flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1-7 0a5 5 0 0 0-7 0zm0 16v-7"/></svg>
                                                        {{ match.game }}
                                                    </p>
                                                </div>    
                                                
                                                <div class="shadow border border-gray-300 p-2 rounded-lg">
                                                    <span class="mb-1 text-xs rounded-md bg-gray-100 flex justify-between"
                                                    :class="getTeamBackgroundColor(match.id, match.teamA_id)"
                                                    >
                                                        <p class="p-2">{{ getTeamName(match.teamA_id) }}</p>
                                                        <div v-if="getMatchResult(match.id)" class="mt-2 mr-2">
                                                        <p>{{ getMatchResult(match.id).teamA_score }}</p>
                                                        </div>
                                                    </span>
                                                    <span class=" text-xs rounded-md bg-gray-100 flex justify-between"
                                                    :class="getTeamBackgroundColor(match.id, match.teamB_id)">
                                                        <p class="p-2">{{ getTeamName(match.teamB_id) }}</p>
                                                        <div v-if="getMatchResult(match.id)" class="mt-2 mr-2">
                                                        <p>{{ getMatchResult(match.id).teamB_score }}</p>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <p v-else class="text-center py-4">No matches have been created yet. Click 'Start Tournament' to begin.</p>
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
                                <span class="font-medium">Rank</span>
                                <input
                                v-model="team.rank"
                                :max="rankingTeams.length"
                                class="w-8 border text-center rounded px-2 py-1"
                                required
                                disabled
                                />
                                <span class="font-medium">Points</span>
                                <input
                                v-model="team.points"
                                class="w-10 border text-center rounded px-2 py-1"
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
                            class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700"
                            :disabled="isSubmittingRankings"
                            >
                            {{ isSubmittingRankings ? 'Submitting...' : 'Submit Rankings' }}
                            </button>
                        </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
    <SuccessModal
        :show="showSuccessModal"
        :message="successMessage"
        @close="showSuccessModal = false"
     />
 </template>
 
 <script setup>
    import { Head, useForm, router, usePage } from '@inertiajs/vue3';
    import { ref, onMounted, computed, watch } from 'vue';
    import { route } from 'ziggy-js';
    import AppLayout from '@/Layout/DashboardLayoutF.vue';
    import Standing from '@/Components/Standing.vue'
    import GameSchedule from '@/Components/Facilitator/FGameSchedule.vue';
    import PlayersDisplay from '@/Components/PlayersDisplay.vue';
    import SuccessModal from '@/Components/SuccessModal.vue';


const props = defineProps({
    sport: [Array, Object],
    tournaments: Array,
    teams: Array,
    matches: Array,
    results: Array,
    venues: Array,
    allMatches: Array,
    venueRecords: Array,
    players: Array,
    facilitator: {
        type: Object,
        required: true
    },
    majorPoints: {
      type: Array,
      default: () => []
    },
    minorPoints: {
      type: Array,
      default: () => []
    },
});


const showSuccessModal = ref(false);
const successMessage = ref('');

const activeTab = ref('matches');  // Default to "matches" 

const returnToFacilitator = () => {
  const currentPath = window.location.pathname;
  const matches = currentPath.match(/sportview\/\d+\/([^/]+)$/);
  if (matches && matches[1]) {
    const encryptedId = matches[1];
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

const groupedBrackets = computed(() => {
    const sportMatches = props.matches.filter(match => match.assigned_sport_id === props.sport.id);
    
    const brackets = {
        winners: {},
        losers: {},
        deciding: {}
    };
    
    sportMatches.forEach(match => {
        const bracket = match.bracket_type === 'deciding match' ? 'deciding' : match.bracket_type;
        const round = match.round || 1;
        
        if (!brackets[bracket]) {
            brackets[bracket] = {};
        }
        
        if (!brackets[bracket][round]) {
            brackets[bracket][round] = [];
        }
        
        brackets[bracket][round].push(match);
    });
    
    // Sort matches within each round by game number
    Object.values(brackets).forEach(bracketRounds => {
        Object.values(bracketRounds).forEach(round => {
            round.sort((a, b) => {
                const gameA = parseInt(a.game.replace('Game ', ''));
                const gameB = parseInt(b.game.replace('Game ', ''));
                return gameA - gameB;
            });
        });
    });
    
    return brackets;
});


// Get match result if it exists
const getMatchResult = (matchId) => {
    return props.results.find(result => result.sport_match_id === matchId);
};

// Add this computed property to get the deciding match
const decidingMatch = computed(() => {
  const decidingMatches = props.matches.filter(match => match.bracket_type === 'deciding match');
  return decidingMatches.length > 0 ? decidingMatches[0] : null;
});

const tournamentWinner = computed(() => {
  const winnersBracket = groupedBrackets.value.winners;
  const lastRound = winnersBracket[Math.max(...Object.keys(winnersBracket).map(Number))];
  if (!lastRound || lastRound.length === 0) return null;
  
  const championshipMatches = lastRound.filter(match => isChampionshipMatch(match));
  if (championshipMatches.length === 0) return null;

  const lastChampionshipMatch = championshipMatches[championshipMatches.length - 1];
  const result = getMatchResult(lastChampionshipMatch.id);
  
  // Check if there's a deciding match
  if (decidingMatch.value) {
    const decidingResult = getMatchResult(decidingMatch.value.id);
    // If the deciding match is completed, use its result
    if (decidingResult && decidingMatch.value.status === 'completed') {
      return decidingResult.winning_team_id;
    }
    // If there's a deciding match but it's not completed, don't declare a winner yet
    return null;
  }
  
  // If there's no deciding match, use the result of the last championship match
  return result && lastChampionshipMatch.status === 'completed' ? result.winning_team_id : null;
});

// Modify the isChampionshipMatch function to include the deciding match
const isChampionshipMatch = (match) => {
  const winnersBracket = groupedBrackets.value.winners;
  const lastRound = winnersBracket[Math.max(...Object.keys(winnersBracket).map(Number))];
  return lastRound && (lastRound[0]?.id === match.id || match.bracket_type === 'deciding match');
};

// New computed properties for progress bar
const totalMatches = computed(() => props.matches.length);

const completedMatches = computed(() => {
  return props.matches.filter(match => match.status === 'completed').length;
});

const progressPercentage = computed(() => {
  return (completedMatches.value / totalMatches.value) * 100;
});


const getTeamBackgroundColor = (matchId, teamId) => {
    const result = getMatchResult(matchId);
    if (!result || !teamId) return 'bg-gray-200';

    if (result.winning_team_id === teamId) {
        return 'bg-green-200 text-green-600';
    } else if (result.losing_team_id === teamId) {
        return 'bg-red-200 text-red-600';
    }
    return 'bg-gray-200 ';
};

//
// Ranking Modal
const rankingsSubmitted = ref(false);

const showRankingModal = ref(false)
const isSubmittingRankings = ref(false)
const rankingTeams = ref([])


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
  showRankingModal.value = false
}

const form = useForm({
  rankings: [],
})

const submitRankings = () => {
  isSubmittingRankings.value = true
  form.rankings = rankingTeams.value.map(team => ({
    assigned_sport_id: props.sport.id,
    assigned_team_id: team.id,
    rank: parseInt(team.rank),
    points: parseInt(team.points),
  }))

  form.post(route('overall-results.store'), {
    preserveScroll: true,
    onSuccess: () => {
      closeRankingModal()
      isSubmittingRankings.value = false
      rankingsSubmitted.value = true
      showSuccessModal.value = true;
      successMessage.value = 'Rankings submitted successfully!';
    },
    onError: () => {
      isSubmittingRankings.value = false
      showToast('Failed to submit rankings', 'error');
    },
  })
}
 </script>
 
 <style scoped>
    /* Home page specific styles */
 </style>