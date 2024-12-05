<template>
  <div>
    <Head title="College"/>
    <AppLayout :user="user">
      <template v-slot:default>
        <div class="flex items-center justify-between gap-2 pt-6">
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

        <div class="flex items-center gap-2 mt-2">
              <p class="text-sm"> {{ sport.setup }}</p>
              <p class="text-xs">|</p>
              <p class=" text-sm">{{ sport.type }}</p>
              <p class="text-xs">|</p>
              <p class=" text-sm">{{ sport.status }}</p>
          </div>

        <div v-if="displayWinner" class="mt-6 p-8 bg-blue-100 flex justify-center items-center flex-col rounded-lg">
                    <h2 class="text-xs font-semibold mb-2 text-blue-700">Game Winner</h2>

                    <div class="flex gap-4">
                        <div class="text-center flex gap-4">
                            <svg class="w-10 h-10 mt-5 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-14 h-14 mt-4 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-16 h-16 mt-4 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-20 h-20 mt-3 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                        </div>
                        <div class="relative">
                            <svg class="w-36 h-36 text-blue-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.987 9.492L5.678 7.514C4.155 6.204 4.15 4.302 3.988 3.02c1.666.08 2.956.23 4.08 1.18l1.176 1.336l1.273 1.428m8.941 11.455l-2.964-3m-2.471 3c.022-.272.2-.978 1.019-1.734c.734-.679 2.327-2.305 3.042-3.01c.406-.4 1.07-.68 1.374-.679m-3.889-.187l1.337 1.479m-3.229.15l1.495 1.313m5.29 2.23c.83.002 1.54.615 1.538 1.445c-.001.83-.707 1.557-1.538 1.556c-.83-.002-1.47-.732-1.469-1.562c.054-.826.665-1.328 1.47-1.439m-15.867.412l2.958-2.892m-2.992-2.514c.273.022 1.032.204 1.712 1.054c.625.78 2.31 2.29 3.017 3.004c.4.404.68 1.044.68 1.348M7.266 14.23l8.239-9.566c1.34-1.496 3.214-1.528 4.5-1.666c-.112 1.664-.288 2.95-1.26 4.055L8.55 15.927m-3.543 3.572a1.503 1.503 0 1 1-3.006 0a1.503 1.503 0 0 1 3.006 0" />
                            </svg>
                            <div class="absolute inset-0 bottom-2 flex items-center justify-center">
                            <p class="text-md font-bold text-blue-700 bg-blue-100 w-32 rounded-md text-center break-words max-w-full">
                                {{ getTeamName(displayWinner) }}
                            </p>
                            </div>
                        </div>
                        <div class="text-center flex gap-4">
                            <svg class="w-20 h-20 mt-3 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-16 h-16 mt-4 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-14 h-14 mt-4 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-10 h-10 mt-5 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>

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

      </template>
    </AppLayout>
  </div>
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayoutSA.vue';
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
  user: Object
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
    router.get(route('subadmin.show'));
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