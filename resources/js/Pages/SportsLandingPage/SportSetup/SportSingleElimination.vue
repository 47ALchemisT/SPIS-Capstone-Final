<template>
    <Head :title="sport.sport.name"/>
    <MainLayout>
        <template v-slot:default>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-12">
                <div>
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

                <div v-if="tournamentWinner" class="mt-6 p-8 bg-blue-100 flex justify-center items-center flex-col rounded-lg">
                        <h2 class="text-sm font-semibold mb-2 text-blue-700">Game Winner</h2>
                        <div class="flex gap-4">
                            <div class="relative">
                                <div class="flex items-center justify-center flex-col">
                                    <p class="text-2xl font-bold text-blue-700 bg-blue-100 rounded-md text-center break-words max-w-full mb-2">
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

                <div v-if="sport.status === 'pending'" class="mt-4 p-6 rounded-lg bg-blue-50 text-sm text-center text-blue-700">
                        <i class="fa-solid fa-circle-info mr-2"></i>
                        <span class="font-medium">Important:</span> All matches must be scheduled with date and time before the sport can be marked as scheduled, this is required to start the Palakasan.
                </div>

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
                                        class=" w-56 rounded-lg  space-y-2"
                                        :disabled="!canUpdateMatch(match)"
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
            </div>
        </template>
    </MainLayout>
</template>
 
<script setup>
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import MainLayout from '@/Layouts/MainLayout.vue';
import Standing from '@/Components/Standing.vue';
import GameSchedule from '@/Components/LandingPage/SportGameSchedule.vue';
import Toast from '@/Components/Toast.vue';  // Adjust the import path as needed
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
    players: Array
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
const selectedVenue = ref('');
const error = ref('');
const showScoreModal = ref(false);
const scoreLoading = ref(false);
const scoreError = ref('');
const selectedMatch = ref(null);
const showCreateMatchesModal = ref(false);
const shuffleTeams = ref(false);
const createMatchesLoading = ref(false);
const createMatchesError = ref('');

const returnToPalakasan = () => {
    router.get(route('sports.sports'));
};

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

const canUpdateMatch = (match) => {
    return match.teamA_id && match.teamB_id && 
           !match.teamA_id.toString().startsWith('winner:') && 
           !match.teamB_id.toString().startsWith('winner:');
};

const scoreFormData = ref({
    sport_match_id: '',
    teamA_score: '',
    teamB_score: '',
    winning_team_id: '',
    losing_team_id: ''
});

const getMatchResult = (matchId) => {
    return props.results.find(result => result.sport_match_id === matchId);
};

const openScoreModal = (match) => {
    if (!canUpdateMatch(match)) return;
    
    selectedMatch.value = match;
    const existingResult = getMatchResult(match.id);
    
    scoreFormData.value = {
        sport_match_id: match.id,
        teamA_score: existingResult ? existingResult.teamA_score : '',
        teamB_score: existingResult ? existingResult.teamB_score : '',
        winning_team_id: '',
        losing_team_id: ''
    };
    
    showScoreModal.value = true;
};

const closeScoreModal = () => {
    showScoreModal.value = false;
    selectedMatch.value = null;
    scoreFormData.value = {
        sport_match_id: '',
        teamA_score: '',
        teamB_score: '',
        winning_team_id: '',
        losing_team_id: ''
    };
    scoreError.value = '';
};

const submitScore = async () => {
    scoreLoading.value = true;
    scoreError.value = '';

    const { teamA_score, teamB_score } = scoreFormData.value;
    const teamAId = selectedMatch.value.teamA_id;
    const teamBId = selectedMatch.value.teamB_id;

    if (teamA_score === teamB_score) {
        scoreError.value = 'Scores cannot be tied in this elimination bracket.';
        scoreLoading.value = false;
        return;
    }

    scoreFormData.value.winning_team_id = teamA_score > teamB_score ? teamAId : teamBId;
    scoreFormData.value.losing_team_id = teamA_score > teamB_score ? teamBId : teamAId;

    try {
        await router.post(route('results.store'), scoreFormData.value, {
            onSuccess: () => {
                console.log('Score updated successfully');
                closeScoreModal();
                scoreLoading.value = false;
            },
            onError: (errors) => {
                console.error('Error updating score:', errors);
                scoreError.value = 'Failed to update score';
                scoreLoading.value = false;
            }
        });
    } catch (error) {
        scoreError.value = 'Failed to update score';
        console.error('Error updating score:', error);
        scoreLoading.value = false;
    }
};

const getTeamBackgroundColor = (matchId, teamId) => {
    const result = getMatchResult(matchId);
    if (!result || !teamId) return 'bg-gray-200';

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

const allMatchesScheduled = computed(() => {
    const sportMatches = props.matches.filter(match => match.assigned_sport_id === props.sport.id);
    return sportMatches.length > 0 && sportMatches.every(match => match.date && match.time);
});

const handleScheduleClick = () => {
    router.patch(route('assigned-sports.update-status', props.sport.id), {
        status: 'scheduled'
    }, {
        onSuccess: () => {
            if (toastRef.value) {
                toastRef.value.addToast('Sport status updated to scheduled', 'success');
            }
        },
        onError: (errors) => {
            if (toastRef.value) {
                toastRef.value.addToast('Failed to update sport status', 'error');
            }
        }
    });
};

//
const showRankingModal = ref(false);
const isSubmittingRankings = ref(false);
const rankingTeams = ref([]);
const rankingsSubmitted = ref(false);

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

const sortTeamsByRecord = (teams, records) => {
  return [...teams].sort((a, b) => {
    const teamA = records[a.id] || { wins: 0, losses: 0, points: 0 };
    const teamB = records[b.id] || { wins: 0, losses: 0, points: 0 };

    // Sort by wins first, then by points, then by losses
    if (teamA.wins !== teamB.wins) return teamB.wins - teamA.wins;
    if (teamA.points !== teamB.points) return teamB.points - teamA.points;
    return teamA.losses - teamB.losses;
  });
};

const openRankingModal = () => {
  if (rankingsSubmitted.value) return;

  const records = calculateTeamRecords();
  const sortedTeams = sortTeamsByRecord(props.teams, records);

  rankingTeams.value = sortedTeams.map((team, index) => ({
    ...team,
    rank: index + 1,
    points: records[team.id].points || 0,
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