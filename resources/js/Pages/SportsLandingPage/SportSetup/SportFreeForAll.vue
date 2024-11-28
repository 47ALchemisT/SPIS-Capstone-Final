<template>
  <div>
    <Head :title="sport.sport.name" />
    <MainLayout>
      <template v-slot:default>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-12">
          <!-- Header Section -->
          <div class="flex flex-col">
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
          </div>

          <nav class="flex relative justify-between mt-4  items-center">
            <div class="flex gap-2 rounded-lg ">
                <div class=" flex gap-2 rounded-lg">
                    <button 
                        v-for="tab in ['sports', 'players']"
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
            <div v-if="activeTab === 'sports'">
              <!-- Sport Variations List -->
              <div class=" mb-6">
                <div v-if="sportVariations.length === 0" class="text-gray-500">
                  No variations added yet.
                </div>
                <ul v-else class="space-y-4">
                  <li v-for="variation in sportVariations" :key="variation.id" 
                    class="pb-4 p-4 rounded-lg"
                    :class="{ 
                      'bg-blue-50 border border-blue-400': variation.status?.toLowerCase() === 'completed',
                      'border border-gray-300': variation.status?.toLowerCase() !== 'completed'
                    }">
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
                      </div>
                    </div>
                    
                    <!-- Variation Matches -->
                    <div v-if="getVariationMatches(variation.id).length > 0" class="mt-4">
                      <table class="w-full text-sm">
                        <thead>
                          <tr>
                            <th class="px-4 py-2 text-left" :class="{ 'bg-blue-100': variation.status?.toLowerCase() === 'completed' }">Team</th>
                            <th class="px-4 py-2 text-center" :class="{ 'bg-blue-100': variation.status?.toLowerCase() === 'completed' }">Rank</th>
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
            </div>
            <div v-if="activeTab === 'players'">
              <PlayersDisplay class="mt-1" :players="players" :teams="teams" />
            </div>
          </div>
        </div>
      </template>
    </MainLayout>
  </div>
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import MainLayout from '@/Layouts/MainLayout.vue';
import AppLayout from '@/Layout/DashboardLayout.vue';
import PlayersDisplay from '@/Components/PlayersDisplay.vue';

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
  players: Array,
  latestPalakasan: {
    type: Object,
    default: () => ({})
  },

});

const rankUpdates = ref({});
const activeTab = ref('sports');

const totalMatches = computed(() => props.sportVariations.length);
const completedMatches = computed(() => props.sportVariations.filter(variation => variation.status === 'completed').length);
const progressPercentage = computed(() => totalMatches.value > 0 ? (completedMatches.value / totalMatches.value) * 100 : 0);


const returnToPalakasan = () => {
  router.get(route('sports.sports'));
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


watch(
  () => rankUpdates.value,
  () => {
    // The computed property will automatically update the sort
  },
  { deep: true }
);


</script>