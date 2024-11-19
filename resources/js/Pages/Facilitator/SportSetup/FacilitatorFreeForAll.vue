<template>
  <div>
    <Head :title="sport.sport.name" />
    <AppLayout :facilitator="facilitator">
      <template v-slot:default>
        <!-- Header Section -->
        <div class="flex items-center gap-2">
          <h1 class="text-4xl font-semibold">{{ sport.sport.name }} {{ sport.categories }}</h1>
          <div>
            <button
              @click="returnToFacilitator"
              type="button"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-2 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
            >
              <i class="fa-solid fa-right-to-bracket mr-2"></i>
              Return
            </button>
          </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-2 space-y-2">
          <div class="flex items-center text-xs">
            <h2 class="text-gray-600">Sport Progress :</h2>
            <span class="font-medium">{{ progressPercentage.toFixed(0) }}% completed</span>
          </div>
          <div class="w-1/3 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div 
              class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" 
              :style="{ width: `${progressPercentage}%` }"
            ></div>
          </div>
        </div>

        <!-- Tags Section -->
        <div class="flex items-center gap-2 mt-2">
              <p class="text-sm"> {{ sport.setup }}</p>
              <p class="text-xs">|</p>
              <p class=" text-sm">{{ sport.type }}</p>
              <p class="text-xs">|</p>
              <p class=" text-sm">{{ sport.status }}</p>
        </div>


        <!-- Sport Variations List -->
        <div class="py-6 mb-6">
          <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold mb-4">Sport Variations</h2>
          </div>
          <div v-if="sportVariations.length === 0" class="text-gray-500">
            No variations added yet.
          </div>
          <ul v-else class="space-y-4">
            <li v-for="variation in sportVariations" :key="variation.id" class="pb-4 border p-4 border-gray-300 rounded-lg ">
              <div class="flex justify-between items-center">
                <div class="flex justify-between w-full">
                  <div>
                    <h3 class="text-md font-semibold">{{ variation.sport_variation_name }}</h3>
                    <div class="flex items-center mt-1 gap-2.5">
                      <div class="text-xs text-gray-600">
                        <div class="flex items-center gap-1.5">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                          </svg>
                          <span>{{ getVenueName(variation.sport_variation_venue_id) }}</span>
                        </div>
                      </div>
                      <p class="text-xs text-gray-400">|</p>
                      <div class="text-xs text-gray-600">
                        <div class="flex items-center gap-1.5">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                          </svg>
                          <span :class="{'text-red-500': !variation.date || !variation.time}">
                            {{ formatDateTime(variation.date, variation.time) }}
                          </span>
                        </div>
                      </div>
                      <p class="text-xs text-gray-400">|</p>
                      <div class="text-xs flex justify-between gap-1.5 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0m5 0v.01m4-.01v.01m4-.01v.01"/></svg>            
                        <span :class="getStatusClass(variation.status)">
                          {{ formatStatus(variation.status) }}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div>
                    <button 
                      type="button"
                      @click="openRankModal(variation)" 
                      class="p-2 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                    >
                      <svg  class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.5 18c0-1.414 0-2.121.44-2.56C4.378 15 5.085 15 6.5 15H7c.943 0 1.414 0 1.707.293S9 16.057 9 17v5H3.5zM15 19c0-.943 0-1.414.293-1.707S16.057 17 17 17h.5c1.414 0 2.121 0 2.56.44c.44.439.44 1.146.44 2.56v2H15zM2 22h20M9 16c0-1.414 0-2.121.44-2.56C9.878 13 10.585 13 12 13s2.121 0 2.56.44c.44.439.44 1.146.44 2.56v6H9zm3.691-13.422l.704 1.42a.87.87 0 0 0 .568.423l1.276.213c.816.137 1.008.734.42 1.323l-.992 1a.88.88 0 0 0-.208.73l.284 1.238c.224.98-.292 1.359-1.152.847l-1.196-.714a.86.86 0 0 0-.792 0l-1.196.714c-.856.512-1.376.129-1.152-.847l.284-1.238a.88.88 0 0 0-.208-.73l-.991-1c-.584-.589-.396-1.186.42-1.323l1.275-.213a.87.87 0 0 0 .564-.424l.704-1.42c.384-.77 1.008-.77 1.388 0" color="currentColor"/></svg>                    
                    </button>

                  </div>
                </div>
              </div>
              
              <!-- Variation Matches -->
              <div v-if="getVariationMatches(variation.id).length > 0" class="mt-4">
                <table class="w-full text-sm">
                  <thead>
                    <tr>
                      <th class="px-4 py-2 bg-gray-100 text-left">Team</th>
                      <th class="px-4 py-2 bg-gray-100 text-center">Rank</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="match in getSortedMatches(getVariationMatches(variation.id))" 
                        :key="match.id" 
                        class="border-b">
                      <td class="px-4 py-2">{{ getTeamName(match.sport_variation_team_id) }}</td>
                      <td class="px-4 py-2 text-center">{{ match.rank || '--' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </li>
          </ul>
        </div>

        <div v-if="showRankModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="relative p-4 w-full max-w-[32rem] max-h-full">
            <div class="relative bg-white rounded-lg shadow">
              <div class="flex items-center justify-between p-4 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">
                  Set Rankings and Points
                </h3>
                <button @click="closeRankModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center">
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                  </svg>
                </button>
              </div>
              
              <div class="p-4 pt-0">
                <form @submit.prevent="updateRanks">
                  <div class="mt-4">
                    <table class="w-full text-sm">
                      <thead>
                        <tr>
                          <th class="px-4 py-2 font-medium text-left">Team</th>
                          <th class="px-4 py-2 font-medium text-left">Rank</th>
                          <th class="px-4 py-2 font-medium text-left">Points</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="match in sortedMatches" :key="match.id">
                          <td class="px-4 py-2 w-72">{{ getTeamName(match.sport_variation_team_id) }}</td>
                          <td class="px-4 py-2 w-44">
                            <select 
                              v-model="rankUpdates[match.id].rank"
                              class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                              <option value="" disabled>Select rank</option>
                              <option 
                                v-for="rank in availableRanks(match.id)" 
                                :key="rank"
                                :value="rank"
                              >
                                {{ formatRank(rank) }}
                              </option>
                            </select>
                          </td>
                          <td class="px-4 py-2 w-12">
                            <input 
                              type="number"
                              v-model="rankUpdates[match.id].points"
                              min="0"
                              class=" px-2 py-1 border w-20 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Enter points"
                            />
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div v-if="rankUpdateError" class="mt-4 text-red-500 text-sm">{{ rankUpdateError }}</div>

                  <div class="grid grid-cols-2 gap-2 mt-4">
                    <button 
                      type="button"
                      @click="closeRankModal"
                      class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100"
                    >
                      Cancel
                    </button>
                    <button 
                      type="submit"
                      :disabled="!isValidRankSelection"
                      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 disabled:opacity-50"
                    >
                      {{ rankUpdateForm.processing ? 'Saving...' : 'Save' }}
                    </button>
                  </div>
                </form>
              </div>
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
import AppLayout from '@/Layout/DashboardLayoutF.vue';

const props = defineProps({
  sport: {
    type: [Array, Object],
    required: true
  },
  points: {
    type: [Array, Object],
    required: true
  },
  tournaments: {
    type: Array,
    default: () => []
  },
  venues: {
    type: Array,
    required: true
  },
  teams: {
    type: Array,
    default: () => []
  },
  allMatches: {
    type: Array,
    default: () => []
  },
  sportVariations: {
    type: Array,
    default: () => []
  },
  sportVariationMatches: {
    type: Array,
    default: () => []
  },
  venueRecords: {
    type: Array,
    default: () => []
  },
  facilitator: Object
});

const showRankModal = ref(false);
const selectedVariationMatches = ref([]);
const rankUpdateError = ref('');
const rankUpdates = ref({});
const selectedVariation = ref(null);

const totalMatches = computed(() => props.sportVariations.length);
const completedMatches = computed(() => props.sportVariations.filter(variation => variation.status === 'completed').length);
const progressPercentage = computed(() => totalMatches.value > 0 ? (completedMatches.value / totalMatches.value) * 100 : 0);


const updateTimeForm = useForm({
  sport_variation_id: '',
  sport_variation_venue_id: '',
  date: '',
  time: '',
});

const rankUpdateForm = useForm({
  matches: [],
});


onMounted(() => {
  form.assigned_sport_id = props.sport.id;
});

const returnToFacilitator = () => {
  router.visit(route('facilitator.show', { id: props.facilitator.id }));
};

const getVenueName = (venueId) => {
  if (!venueId) return 'No venue assigned';
  const venue = props.venues.find(v => v.id === venueId);
  return venue ? venue.name : 'Unknown venue';
};

const getTeamName = (teamId) => {
  const team = props.teams.find((t) => t.id === teamId && t.palakasan_id === props.sport.palakasan_sport_id);
  return team ? team.assigned_team_name : 'Unknown team';
};

// Add a new computed property to filter teams for the current sport
const currentSportTeams = computed(() => {
  return props.teams.filter(team => team.palakasan_id === props.sport.palakasan_sport_id);
});


const openRankModal = (variation) => {
    selectedVariation.value = variation;
    selectedVariationMatches.value = props.sportVariationMatches
      .filter(m => m.sport_variation_id === variation.id)
      .filter(m => currentSportTeams.value.some(team => team.id === m.sport_variation_team_id));
    
    rankUpdates.value = selectedVariationMatches.value.reduce((acc, match) => ({
      ...acc,
      [match.id]: {
        rank: match.rank || '',
        points: match.points || ''
      }
    }), {});
    showRankModal.value = true;
  };

  const closeRankModal = () => {
  showRankModal.value = false;
  selectedVariation.value = null;
  selectedVariationMatches.value = [];
  rankUpdates.value = {};
  rankUpdateError.value = '';
  rankUpdateForm.reset();
};

  const isValidRankSelection = computed(() => {
    const selectedMatches = Object.values(rankUpdates.value);
    const allFieldsFilled = selectedMatches.every(match => 
      match.rank !== '' && match.points !== ''
    );
    const selectedRanks = new Set(selectedMatches.map(match => match.rank).filter(rank => rank !== ''));
    return allFieldsFilled && selectedRanks.size === selectedVariationMatches.value.length;
  });

  const updateRanks = async () => {
    const selectedRanks = Object.values(rankUpdates.value)
      .filter(match => match.rank !== '')
      .map(match => match.rank.toString());
    
    const uniqueRanks = new Set(selectedRanks);
    
    if (selectedRanks.length !== uniqueRanks.size) {
      rankUpdateError.value = 'Each team must have a unique rank.';
      return;
    }

    if (selectedRanks.length !== selectedVariationMatches.value.length) {
      rankUpdateError.value = 'All teams must have a rank and points assigned.';
      return;
    }

    rankUpdateForm.matches = Object.entries(rankUpdates.value).map(([id, data]) => ({
      id: parseInt(id),
      rank: parseInt(data.rank),
      points: parseInt(data.points)
    }));

    try {
      await rankUpdateForm.patch(route('sport-variations.update-ranks', selectedVariation.value.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          closeRankModal();
        },
        onError: (errors) => {
          console.error('Update ranks errors:', errors);
          rankUpdateError.value = 'Please correct the errors and try again.';
        },
      });
    } catch (error) {
      console.error('Update ranks error:', error);
      rankUpdateError.value = 'An unexpected error occurred. Please try again.';
    }
  };

const formatDateTime = (date, time) => {
  if (!date && !time) return 'Not scheduled';
  if (!date) return `Time: ${time}`;
  if (!time) return `Date: ${formatDate(date)}`;
  return `${formatDate(date)} at ${time}`;
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  });
};

const formatStatus = (status) => {
  if (!status) return 'Pending';
  return status.charAt(0).toUpperCase() + status.slice(1);
};

const getStatusClass = (status) => {
  const statusClasses = {
    pending: 'text-yellow-600',
    ongoing: 'text-green-600',
    completed: 'text-blue-600',
    cancelled: 'text-red-600'
  };
  return statusClasses[status?.toLowerCase()] || 'text-gray-600';
};

const formatRank = (rank) => {
  const suffixes = { 1: 'st', 2: 'nd', 3: 'rd' };
  const suffix = suffixes[rank] || 'th';
  return `${rank}${suffix} place`;
};

const getSortedMatches = (matches) => {
  return [...matches].sort((a, b) => {
    if (a.rank && b.rank) return a.rank - b.rank;
    if (a.rank) return -1;
    if (b.rank) return 1;
    return 0;
  });
};

const getVariationMatches = (variationId) => {
  return props.sportVariationMatches
    .filter(match => match.sport_variation_id === variationId)
    .filter(match => currentSportTeams.value.some(team => team.id === match.sport_variation_team_id));
};

// Update the availableRanks function
const availableRanks = (matchId) => {
  const currentRanks = new Set();
  
  // Collect all currently selected ranks except for the current match
  Object.entries(rankUpdates.value).forEach(([id, data]) => {
    if (id !== matchId.toString() && data.rank) {
      currentRanks.add(data.rank.toString());
    }
  });
  
  // Generate all possible ranks
  const allRanks = Array.from(
    { length: selectedVariationMatches.value.length }, 
    (_, i) => (i + 1).toString()
  );
  
  // Return ranks that are either unselected or currently selected by this match
  return allRanks.filter(rank => 
    !currentRanks.has(rank) || 
    rankUpdates.value[matchId]?.rank === rank
  );
};



const sortedMatches = computed(() => {
  return [...selectedVariationMatches.value].sort((a, b) => {
    const rankA = rankUpdates.value[a.id]?.rank || Infinity;
    const rankB = rankUpdates.value[b.id]?.rank || Infinity;
    return rankA - rankB;
  });
});
watch(
  () => rankUpdates.value,
  () => {
    // The computed property will automatically update the sort
  },
  { deep: true }
);

const updateAssignedSportStatus = async () => {
  if (progressPercentage.value === 100) {
    try {
      await router.patch(route('assigned-sports.update-status', props.sport.id), {
        status: 'completed'
      });
      // Optionally, you can update the local state here if needed
      props.sport.status = 'completed';
    } catch (error) {
      console.error('Failed to update assigned sport status:', error);
    }
  }
};

watch(progressPercentage, (newValue) => {
  if (newValue === 100) {
    updateAssignedSportStatus();
  }
});
</script>