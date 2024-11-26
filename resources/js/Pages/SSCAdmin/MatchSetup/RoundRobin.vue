<template>
  <div>
    <Head title="College"/>
    <AppLayout>
      <template v-slot:default>

        <div class="flex flex-col">
                <div class="flex items-center justify-between gap-2">
                    <h1 class="text-2xl font-semibold">{{ sport.sport.name }} {{ sport.categories }}</h1>
                    <div>
                        <button 
                            @click="returnToPalakasan" 
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
              </div>

          <div v-if="displayWinner" class="mt-6 p-8 bg-blue-100 flex justify-center items-center flex-col rounded-lg">
            <h2 class="text-sm mb-2 font-semibold text-blue-700">Game Winner</h2>
            <div class="flex gap-4">
                <div class="relative">
                    <div class="flex items-center justify-center flex-col">
                        <p class="text-2xl font-bold text-blue-700 bg-blue-100 w-32 rounded-md text-center break-words max-w-full mb-2">
                            {{ getTeamName(displayWinner) }}
                        </p>
                        <div class="text-sm text-blue-700 font-medium">
                            <span>W: {{ calculateTeamRecords()[displayWinner]?.wins || 0 }}</span>
                            <span class="mx-2">-</span>
                            <span>D: {{ calculateTeamRecords()[displayWinner]?.draw || 0 }}</span>
                            <span class="mx-2">-</span>
                            <span>L: {{ calculateTeamRecords()[displayWinner]?.losses || 0 }}</span>
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
                                v-for="tab in ['matches', 'standing', 'players']"
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
                    <div class="flex items-center gap-2">
                    <button
                        @click="openCreateMatchesModal"
                        type="button"
                        class="flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-700/90 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 disabled:bg-blue-100 disabled:text-blue-700 "
                        :disabled="hasExistingMatches"
                    >
                        {{ hasExistingMatches ? 'Game Already Started' : 'Start Game' }}
                    </button>
                </div>
            </nav>

        <div class="mt-4">
          <div v-if="activeTab === 'matches'">
            <GameSchedule :matches="matches" :teams="teams" :results="results" :venues="venues" :allMatches="allMatches" :venueRecords="venueRecords"/>
          </div>
          <div v-if="activeTab === 'standing'">
            <div class="w-full">
              <Standing :standings="standings" :teams="teams" />
            </div>
          </div>
          <div v-if="activeTab === 'players'">
              <PlayersDisplay class="mt-1" :players="players" :teams="teams" />
            </div>
        </div>

        <!-- Create Matches Modal -->
        <div v-if="showCreateMatchesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg shadow-lg relative w-96">
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Create Round-Robin Matches
              </h3>
              <button               
                @click="closeCreateMatchesModal" 
                type="button" 
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
              </button>
            </div>
            <form @submit.prevent="createRoundRobinMatches">
              <div class="p-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Select Venue
                </label>
                <select 
                  v-model="selectedVenue"
                  class="w-full border rounded-md p-2 mb-4 bg-white"
                  required
                >
                  <option value="" disabled>Choose a venue</option>
                  <option 
                    v-for="venue in venues" 
                    :key="venue.id" 
                    :value="venue.id"
                  >
                    {{ venue.name }}
                  </option>
                </select>
                <button 
                  type="submit"
                  :disabled="createMatchesLoading || !selectedVenue"
                  class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-600 w-full disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {{ createMatchesLoading ? 'Creating...' : 'Create Round-Robin Matches' }}
                </button>
                <p v-if="createMatchesError" class="text-red-600 mt-2">{{ createMatchesError }}</p>
              </div>

            </form>
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
                        type="text"
                        min="1"
                        disabled
                        :max="teams.length"
                        class="w-8 border text-center rounded px-2 py-1"
                        placeholder="Rank"
                      />
                      <span class="font-medium">Points</span>
                      <input
                        v-model="team.points"
                        type="number"
                        min="0"
                        class="w-16 border rounded px-2 py-1"
                        placeholder="Points"
                        required
                      />
                    </div>
                  </div>
                </div>
                <div class="mt-4 flex justify-end">
                  <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
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
  </div>
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayout.vue';
import Standing from '@/Components/StandingRR.vue';
import GameSchedule from '@/Components/GameScheduleRR.vue';
import PlayersDisplay from '@/Components/PlayersDisplay.vue';

const props = defineProps({
  sport: [Array, Object],
  tournaments: Array,
  teams: Array,
  matches: Array,
  results: Array,
  venues: Array,
  allMatches: Array,
  standings: Array,
  winner: Number,
  venueRecords: Array,
  players: Array,
});

const activeTab = ref('matches');
const selectedVenue = ref('');
const error = ref('');
const showCreateMatchesModal = ref(false);
const createMatchesLoading = ref(false);
const createMatchesError = ref('');
const tournamentWinner = ref(null);
const showRankingModal = ref(false);
const isSubmittingRankings = ref(false);
const rankingTeams = ref([]);
const rankingsSubmitted = ref(false);

const displayWinner = computed(() => {
  return tournamentWinner.value || props.winner;
});

const returnToPalakasan = () => {
  router.get(route('palakasan.details', { tab: 'leagues' }));
};

const hasExistingMatches = computed(() => {
  return props.matches.some(match => match.assigned_sport_id === props.sport.id);
});

const openCreateMatchesModal = () => {
  if (hasExistingMatches.value) {
    error.value = 'Tournament has already been started';
    return;
  }
  showCreateMatchesModal.value = true;
};

const closeCreateMatchesModal = () => {
  showCreateMatchesModal.value = false;
  selectedVenue.value = '';
  createMatchesError.value = '';
};

const generateRoundRobinMatches = (teams) => {
  const venueId = parseInt(selectedVenue.value, 10);
  if (!venueId) {
    throw new Error('Please select a valid venue');
  }

  const numTeams = teams.length;
  let matches = [];
  let round = 1;

  // If odd number of teams, add a "bye" team
  if (numTeams % 2 !== 0) {
    teams.push({ id: 'bye', name: 'BYE' });
  }

  const totalRounds = teams.length - 1;
  const matchesPerRound = teams.length / 2;

  for (let currentRound = 0; currentRound < totalRounds; currentRound++) {
    for (let match = 0; match < matchesPerRound; match++) {
      const home = teams[match];
      const away = teams[teams.length - 1 - match];

      // Only create a match if neither team is the "bye" team
      if (home.id !== 'bye' && away.id !== 'bye') {
        matches.push({
          round: round,
          game: `Game ${matches.length + 1}`,
          teamA_id: home.id,
          teamB_id: away.id,
          assigned_sport_id: props.sport.id,
          bracket_type: 'round_robin',
          elimination_type: 'round_robin',
          status: 'pending',
          match_venue_id: venueId,
          date: null,
          time: null,
        });
      }
    }

    // Rotate teams for next round, keeping first team fixed
    teams.splice(1, 0, teams.pop());
    round++;
  }

  return matches;
};

const createRoundRobinMatches = async () => {
  createMatchesError.value = '';

  if (!selectedVenue.value) {
    createMatchesError.value = 'Please select a venue';
    return;
  }

  createMatchesLoading.value = true;

  try {
    const matches = generateRoundRobinMatches(props.teams);

    if (!matches.length) {
      throw new Error('No matches were generated');
    }

    console.log("Round-Robin match data being sent:", JSON.stringify(matches, null, 2));

    const response = await router.post(route('round-robin.store'), matches, {
      preserveScroll: true,
      onSuccess: (page) => {
        createMatchesLoading.value = false;
        closeCreateMatchesModal();
        router.visit(window.location.pathname);
      },
      onError: (errors) => {
        createMatchesError.value = errors.message || 'Failed to create matches';
      }
    });
  } catch (err) {
    createMatchesLoading.value = false;
    createMatchesError.value = err.message || 'Failed to create matches';
  }
};

const getTeamName = (teamId) => {
  if (!teamId) return 'TBD';
  const team = props.teams.find(t => t.id === teamId);
  return team ? team.assigned_team_name : 'Unknown';
};

const determineWinner = () => {
  const sortedStandings = [...props.standings].sort((a, b) => b.points - a.points || b.won - a.won);
  
  if (sortedStandings.length > 0) {
    if (sortedStandings[0].points > sortedStandings[1].points) {
      tournamentWinner.value = sortedStandings[0].team_id;
    } else {
      // If there's a tie for first place, you might want to implement a tie-breaker logic here
      console.log('There is a tie for first place. Implement tie-breaker logic if needed.');
      // For now, tournamentWinner.value = sortedStandings[0].won >= sortedStandings[1].won ? sortedStandings[0].team_id : sortedStandings[1].team_id;
    }
  }
};

const totalMatches = computed(() => props.matches.length);

const completedMatches = computed(() => {
  return props.matches.filter(match => match.status === 'completed').length;
});

const progressPercentage = computed(() => {
  return (completedMatches.value / totalMatches.value) * 100;
});

watch(progressPercentage, (newValue) => {
  if (newValue === 100 && !tournamentWinner.value && !props.winner) {
    determineWinner();
  }
});

onMounted(() => {
  if (props.winner) {
    tournamentWinner.value = props.winner;
  } else {
    determineWinner();
  }
});

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
/* Add any scoped styles here */
</style>