<template>
  <div>
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
            <h2 class="text-xs text-gray-600">Sport Progress</h2>
            <h2 class="text-xs text-gray-600 px-2">:</h2>
            <span class="text-xs font-medium">{{ progressPercentage.toFixed(0) }}% completed</span>
          </div>
        </div>


        <!-- Winner Display -->
        <div v-if="displayWinner" class="mt-6 p-6 bg-green-50 border border-green-200 rounded-lg flex flex-col items-center justify-center">
          <h2 class="text-md font-semibold text-green-800">Tournament Winner</h2>
          <p class="text-2xl font-bold text-green-700 mt-2"> {{ getTeamName(displayWinner) }}</p>
          <button
            @click="openRankingModal"
            type="button"
            class="text-white mt-6 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
            :disabled="matches.status === 'completed'"
          >
            {{ rankingsSubmitted ? 'Rankings Submitted' : 'Submit Ranking' }}
          </button>
        </div>

        <Toast ref="toastRef" />

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

        <div class="mt-5">
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
              <form @submit.prevent="submitRankings" :class="{ 'opacity-50': form.processing }">
                <div v-for="team in rankingTeams" :key="team.id" class="mb-4">
                  <div class="flex items-center justify-between">
                    <span class="font-medium">{{ team.assigned_team_name }}</span>
                    <div class="flex items-center space-x-2">
                      <span class="font-medium">Rank</span>
                      <input
                        v-model="team.rank"
                        type="text"
                        disabled
                        class="w-8 border text-center rounded px-2 py-1"
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
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="form.processing || rankingsSubmitted"
                  >
                    {{ form.processing ? 'Submitting...' : (rankingsSubmitted ? 'Rankings Submitted' : 'Submit Rankings') }}
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
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayoutF.vue';
import Standing from '@/Components/StandingRR.vue';
import GameSchedule from '@/Components/Facilitator/FGameScheduleRR.vue';
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
  standings: Array,
  winner: Number,
  venueRecords: Array,
  facilitator: Object,
  players: Array,
  errors: Object,
  majorPoints: {
    type: Array,
    default: () => [],
  },
  minorPoints: {
    type: Array,
    default: () => [],
  }
});

const activeTab = ref('matches');

const tournamentWinner = ref(null);
const showRankingModal = ref(false);
const isSubmittingRankings = ref(false);
const rankingTeams = ref([]);
const rankingsSubmitted = ref(false);

const displayWinner = computed(() => {
  return tournamentWinner.value || props.winner;
});

const toastRef = ref(null);
const page = usePage();

const form = useForm({
  rankings: []
});

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

const returnToFacilitator = () => {
  const currentPath = window.location.pathname;
  const matches = currentPath.match(/sportview\/\d+\/(.+)$/);
  if (matches && matches[1]) {
    router.visit(route('facilitator.show', { id: matches[1] }));
  }
};
const hasExistingMatches = computed(() => {
  return props.matches.some(match => match.assigned_sport_id === props.sport.id);
});

const getTeamName = (teamId) => {
  if (!teamId) return 'TBD';
  const team = props.teams.find(t => t.id === teamId);
  return team ? team.assigned_team_name : 'Unknown';
};

const sortedStandings = computed(() => {
  return [...props.standings].sort((a, b) => {
    if (b.won !== a.won) return b.won - a.won;
    if (b.drawn !== a.drawn) return b.drawn - a.drawn;
    return b.played - a.played;
  });
});

const determineWinner = () => {
  if (sortedStandings.value.length > 0) {
    if (sortedStandings.value[0].won > sortedStandings.value[1].won) {
      tournamentWinner.value = sortedStandings.value[0].team_id;
    } else {
      // If there's a tie for first place, you might want to implement a tie-breaker logic here
      console.log('There is a tie for first place. Implement tie-breaker logic if needed.');
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

const pointsToUse = computed(() => {
  return props.sport.type === 'Major' ? props.majorPoints : props.minorPoints;
});

const openRankingModal = () => {
  if (rankingsSubmitted.value) return;

  const sortedTeams = sortedStandings.value.map(standing => {
    const team = props.teams.find(t => t.id === standing.team_id);
    return {
      ...team,
      ...standing
    };
  });

  const sortedPoints = [...pointsToUse.value].sort((a, b) => b.points - a.points);

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

const submitRankings = () => {
  if (rankingsSubmitted.value || form.processing) return;

  // Set the rankings directly to form data
  form.clearErrors();
  form.rankings = rankingTeams.value.map(team => ({
    assigned_sport_id: props.sport.id,
    assigned_team_id: team.team_id, // Use team_id from standings instead of id
    rank: parseInt(team.rank),
    points: parseInt(team.points),
  }));

  // Submit the form
  form.post(route('overall-results.store'), {
    onSuccess: () => {
      rankingsSubmitted.value = true;
      closeRankingModal();
      if (toastRef.value) {
        toastRef.value.addToast('Rankings submitted successfully', 'success');
      }
    },
    onError: (errors) => {
      console.error('Submission errors:', errors);
      if (toastRef.value) {
        toastRef.value.addToast('Failed to submit rankings', 'error');
      }
    }
  });
};
</script>

<style scoped>
/* Add any scoped styles here */
</style>