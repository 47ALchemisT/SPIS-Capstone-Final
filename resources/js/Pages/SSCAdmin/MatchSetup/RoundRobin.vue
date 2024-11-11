<template>
  <div>
    <Head title="College"/>
    <AppLayout>
      <template v-slot:default>
        <div class="flex items-center gap-2">
          <h1 class="text-4xl font-semibold">{{ sport.sport.name }}</h1>
          <div>
            <button 
              @click="returnToPalakasan" 
              type="button" 
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-2 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
            >
              <i class="fa-solid fa-right-to-bracket mr-2"></i>
              Return
            </button>
          </div>
        </div>

        <!-- Progress Bar -->
        <div class="flex mt-2 flex-col">
          <div class="flex items-center mb-2">
            <h2 class="text-xs text-gray-600">Sport Progress</h2>
            <h2 class="text-xs text-gray-600 px-2">:</h2>
            <span class="text-xs font-medium">{{ progressPercentage.toFixed(0) }}% completed</span>
          </div>
          <div class="w-1/3 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" :style="{ width: `${progressPercentage}%` }"></div>
          </div>
        </div>

        <div class="flex gap-2 mt-2">
          <p class="py-1 px-4 rounded-full bg-blue-100 text-blue-700 text-sm">{{ sport.setup }}</p>
          <p class="py-1 px-4 rounded-full bg-blue-100 text-blue-700 text-sm">{{ sport.categories }}</p>
        </div>

        <!-- Tournament Winner Display -->
        <div v-if="tournamentWinner" class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
          <h2 class="text-lg font-semibold text-green-800">Tournament Winner</h2>
          <p class="text-xl font-bold text-green-700 mt-2">🏆 {{ getTeamName(tournamentWinner) }}</p>
        </div>

        <!-- Tabs Navigation -->
        <nav class="flex relative justify-between mt-4 items-center">
          <div class="bg-gray-100 flex gap-2 rounded-lg p-1.5">
            <div class="bg-gray-100 flex gap-2 rounded-lg">
              <button 
                v-for="tab in ['matches', 'standing', 'players']"
                :key="tab"
                @click="activeTab = tab"
                :class="[
                  'px-5 py-1 text-sm',
                  activeTab === tab
                    ? 'text-gray-800 ring-1 ring-gray-300 bg-white rounded-md'
                    : 'text-gray-500 hover:text-gray-700 border-transparent'
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
              class="flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-700/90 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
              :disabled="hasExistingMatches"
            >
              <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M14.384 14.38a23 23 0 0 1 1.056 4.863l.064.644l.126 1.431a10 10 0 0 1-9.15-.98l2.08-2.087l.246-.24c1.793-1.728 3.41-2.875 5.387-3.566zm6.09-.783l.414.003l.981.014a10 10 0 0 1-4.319 6.704l-.054-.605c-.18-2.057-.55-3.958-1.163-5.814c1.044-.182 2.203-.278 3.529-.298zm-7.869-3.181a25 25 0 0 1 1.052 2.098c-2.276.77-4.142 2.053-6.144 3.967l-.355.344l-2.236 2.24a10 10 0 0 1-2.917-6.741L2 12l.004-.25H3.1l.467-.002c3.547-.026 6.356-.367 8.938-1.295zm9.388 1.202l-1.515-.02c-1.86-.003-3.45.124-4.865.402a26 26 0 0 0-1.163-2.38c1.393-.695 2.757-1.597 4.179-2.75l.428-.354l.816-.682a10 10 0 0 1 2.098 5.409zM7.33 3.158L8.596 4.68c1.145 1.398 2.121 2.713 2.949 3.985c-2.26.766-4.739 1.052-7.883 1.081L3.1 9.75h-.844A10 10 0 0 1 7.33 3.157zM17 3.34q.796.46 1.483 1.046l-1.025.857c-1.379 1.128-2.688 1.993-4.034 2.649c-.89-1.398-1.943-2.836-3.182-4.358l-.474-.574l-.485-.584A10 10 0 0 1 17 3.34"/></svg>
              {{ hasExistingMatches ? 'Tournament Already Started' : 'Start Tournament' }}
            </button>
          </div>
        </nav>

        <div class="mt-4">
          <div v-if="activeTab === 'matches'">
            <GameSchedule :matches="matches" :teams="teams" :results="results" :venues="venues" :allMatches="allMatches"/>
          </div>
          <div v-if="activeTab === 'standing'">
            <div class="w-full">
              <Standing :standings="standings" :teams="teams" />
            </div>
          </div>
          <div v-if="activeTab === 'players'">
            <!-- To be implemented -->
          </div>
        </div>

        <!-- Create Matches Modal -->
        <div v-if="showCreateMatchesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white p-6 rounded-lg shadow-lg relative w-96">
            <button 
              @click="closeCreateMatchesModal" 
              class="absolute top-2 right-2 text-gray-500 hover:text-gray-800"
            >
              &times;
            </button>
            <h2 class="text-lg font-bold mb-4">Create Round-Robin Matches</h2>
            <form @submit.prevent="createRoundRobinMatches">
              <div class="mb-4">
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
              </div>
              <button 
                type="submit"
                :disabled="createMatchesLoading || !selectedVenue"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ createMatchesLoading ? 'Creating...' : 'Create Round-Robin Matches' }}
              </button>
              <p v-if="createMatchesError" class="text-red-600 mt-2">{{ createMatchesError }}</p>
            </form>
          </div>
        </div>

      </template>
    </AppLayout>
  </div>
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayout.vue';
import Standing from '@/Components/StandingRR.vue';
import GameSchedule from '@/Components/GameScheduleRR.vue';

const props = defineProps({
  sport: [Array, Object],
  tournaments: Array,
  teams: Array,
  matches: Array,
  results: Array,
  venues: Array,
  allMatches: Array,
  standings: Array
});

const activeTab = ref('matches');
const selectedVenue = ref('');
const error = ref('');
const showScoreModal = ref(false);
const scoreLoading = ref(false);
const scoreError = ref('');
const selectedMatch = ref(null);
const showCreateMatchesModal = ref(false);
const createMatchesLoading = ref(false);
const createMatchesError = ref('');

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
        console.log('Success response:', page);
        createMatchesLoading.value = false;
        closeCreateMatchesModal();
        router.visit(window.location.pathname);
      },
      onError: (errors) => {
        console.error('Error creating matches:', errors);
        createMatchesError.value = errors.message || 'Failed to create matches';
      }
    });
  } catch (err) {
    console.error('Error in createRoundRobinMatches:', err);
    createMatchesLoading.value = false;
    createMatchesError.value = err.message || 'Failed to create matches';
  }
};

const getTeamName = (teamId) => {
  if (!teamId) return 'TBD';
  const team = props.teams.find(t => t.id === teamId);
  return team ? team.assigned_team_name : 'Unknown';
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

const tournamentWinner = computed(() => {
  // Implement logic to determine the winner based on round-robin results
  // This could be the team with the most wins or highest point total
  return null; // Placeholder
});

const totalMatches = computed(() => props.matches.length);

const completedMatches = computed(() => {
  return props.matches.filter(match => match.status === 'completed').length;
});

const progressPercentage = computed(() => {
  return (completedMatches.value / totalMatches.value) * 100;
});
</script>

<style scoped>
/* Add any scoped styles here */
</style>