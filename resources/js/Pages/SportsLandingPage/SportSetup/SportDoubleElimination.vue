<template>
    <Head :title="sport.sport.name"/>
    <MainLayout>
        <template v-slot:default>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-12">
                <div class=" gap-2">
                        <button 
                            @click="returnToPalakasan" 
                            type="button" 
                            class="text-white mb-3 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-2 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            >
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Return
                        </button>
                        <h1 class="text-2xl font-semibold">{{ sport.sport.name }} {{ sport.categories }}</h1>
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
                    <div class="w-full sm:w-1/3 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" :style="{ width: `${progressPercentage}%` }"></div>
                    </div>
                    <div class="flex items-center mt-2">
                        <h2 class="text-xs text-gray-600">Sport Progress  </h2>
                        <h2 class="text-xs text-gray-600 px-2">:</h2>
                        <span class="text-xs font-medium">{{ progressPercentage.toFixed(0) }}% completed</span>
                    </div>
                </div>

                <div v-if="tournamentWinner" class="mt-6 p-8 bg-blue-100 flex justify-center items-center flex-col rounded-lg">
                    <h2 class="text-sm font-semibold mb-2 text-blue-700">Game Winner</h2>
                    <div class="flex gap-4">
                        <div class="relative">
                            <div class="flex items-center justify-center flex-col">
                                <p class="text-2xl font-bold text-blue-700 bg-blue-100 w-32 rounded-md text-center break-words max-w-full mb-2">
                                    {{ getTeamName(tournamentWinner) }}
                                </p>
                                <div class="text-sm text-blue-700 font-medium">
                                    <span>W: {{ calculateTeamRecords()[tournamentWinner]?.wins || 0 }}</span>
                                    <span class="mx-2">-</span>
                                    <span>L: {{ calculateTeamRecords()[tournamentWinner]?.losses || 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <nav class="mt-4">
                    <div class="grid grid-cols-4 sm:grid-cols-none sm:flex sm:gap-2 gap-2 rounded-lg">
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

                <div v-if="sport.status == 'pending'" class="mt-4 p-6 rounded-lg bg-blue-50 text-sm text-center text-blue-700">
                    <i class="fa-solid fa-circle-info mr-2"></i>
                    <span class="font-medium">Important:</span> All matches must be scheduled with date and time before the sport can be marked as scheduled, this is required to start the Palakasan.
                </div>

                <div class="mt-4">
                    <div v-if="activeTab === 'matches'" >
                        <GameSchedule class="pb-4" :matches="matches" :teams="teams" :results="results" :venues="venues"  :allMatches="allMatches" :venueRecords="venueRecords"/>                
                    </div>
                    <div v-if="activeTab === 'standing'" class="overflow-hidden">
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
                                                :disabled="!canUpdateMatch(match)"
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
                                                    @click="openScoreModal(decidingMatch)"
                                                    :key="decidingMatch.id"
                                                    class=" w-56 space-y-2"
                                                    :disabled="!canUpdateMatch(decidingMatch)"
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
                                                @click="openScoreModal(match)"
                                                :key="match.id"
                                                class=" w-56 space-y-2"
                                                :disabled="!canUpdateMatch(match)"
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
            </div>
        </template>
    </MainLayout>
 </template>
 
 <script setup>
    import { Head, useForm, router } from '@inertiajs/vue3';
    import { ref, onMounted, computed } from 'vue';
    import { route } from 'ziggy-js';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import Standing from '@/Components/Standing.vue'
    import GameSchedule from '@/Components/GameSchedule.vue';
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
    players: Array,
});

const loading = ref(false);
const error = ref('');
const showScoreModal = ref(false);
const scoreLoading = ref(false);
const scoreError = ref('');
const selectedMatch = ref(null);
const selectedVenue = ref('');
const shuffleTeams = ref(false);
const showCreateMatchesModal = ref(false);
const createMatchesLoading = ref(false);
const createMatchesError = ref('');

const openCreateMatchesModal = () => {
    if (hasExistingMatches.value) {
        error.value = 'Tournament has already been started';
        return;
    }
    showCreateMatchesModal.value = true;
};


const closeCreateMatchesModal = () => {
    showCreateMatchesModal.value = false;
    shuffleTeams.value = false;
    selectedVenue.value = '';
    createMatchesError.value = '';
};


const activeTab = ref('matches');  // Default to "Details" 
const returnToPalakasan = () => {
  router.get(route('sports.sports'));
};

// Check if matches already exist for this sport
const hasExistingMatches = computed(() => {
    return props.matches.some(match => match.assigned_sport_id === props.sport.id);
});


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
// Check if a match can be updated (has both teams assigned and is not a placeholder)
const canUpdateMatch = (match) => {
    return match.teamA_id && match.teamB_id && 
           !match.teamA_id.toString().startsWith('winner:') && 
           !match.teamB_id.toString().startsWith('winner:');
};


// Get match result if it exists
const getMatchResult = (matchId) => {
    return props.results.find(result => result.sport_match_id === matchId);
};

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

// Add this computed property to get the deciding match
const decidingMatch = computed(() => {
  const decidingMatches = props.matches.filter(match => match.bracket_type === 'deciding match');
  return decidingMatches.length > 0 ? decidingMatches[0] : null;
});

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


const calculateTeamRecords = () => {
  const records = {};
  props.teams.forEach(team => {
    records[team.id] = { wins: 0, losses: 0, points: 0 };
  });

  props.results.forEach(result => {
    if (result.winning_team_id) {
      records[result.winning_team_id].wins++;
      records[result.winning_team_id].points += result.winning_points || 0;
    }
    if (result.losing_team_id) {
      records[result.losing_team_id].losses++;
      records[result.losing_team_id].points += result.losing_points || 0;
    }
  });

  return records;
    };

 </script>
 
 <style scoped>
    /* Home page specific styles */
 </style>