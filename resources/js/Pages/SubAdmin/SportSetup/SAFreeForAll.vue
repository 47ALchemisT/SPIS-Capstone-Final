<template>
  <div>
    <Head :title="sport.sport.name" />
    <AppLayout :user="user">
      <template v-slot:default>
        <!-- Header Section -->
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
              <h2 class="text-md font-semibold mb-4">Sport Variations</h2>
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
                          @click="openUpdateTimeModal(variation)"
                          type="button" 
                          class="p-2 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                        >
                          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M21 12a9 9 0 1 0-9.972 8.948q.48.051.972.052"/><path d="M12 7v5l2 2m4.42 1.61a2.1 2.1 0 0 1 2.97 2.97L18 22h-3v-3z"/></g></svg>
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
          </div>
          <div v-if="activeTab === 'players'">
            <PlayersDisplay class="mt-1" :players="players" :teams="teams" />
          </div>
        </div>
        <!-- Update Time Modal -->
        <div v-if="showUpdateTimeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="relative p-4 w-full max-w-[30rem] max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
              <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Update Schedule
                </h3>
                <button @click="closeUpdateTimeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                  </svg>
                  <span class="sr-only">Close modal</span>
                </button>
              </div>
              <div class="p-4 pt-0">
                <!-- Venue Selection -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mt-5 mb-2">Venue</label>
                  <select 
                    v-model="updateTimeForm.sport_variation_venue_id"
                    @change="updateAvailableTimeSlots"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <option value="" disabled>Select a venue</option>
                    <option v-for="venue in venues" :key="venue.id" :value="venue.id">
                      {{ venue.name }}
                    </option>
                  </select>
                </div>

                <!-- Date Selection -->
                <label class="text-sm font-medium text-gray-900 mt-5 dark:text-white mb-2 block">
                  Pick your date
                </label>
                <div class="mx-auto sm:mx-0 flex justify-center mb-5">
                  <input
                    type="date"
                    v-model="updateTimeForm.date"
                    :min="getCurrentDate()"
                    @change="updateAvailableTimeSlots"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>

                <!-- Time Slots -->
                <label class="text-sm font-medium text-gray-900 dark:text-white mb-2 block">
                  Pick your time
                </label>
                <ul class="grid w-full grid-cols-4 gap-2 mb-5">
                  <li v-for="slot in availableTimeSlots" :key="slot.value">
                    <input 
                      type="radio" 
                      :id="slot.value" 
                      :value="slot.value" 
                      v-model="updateTimeForm.time" 
                      class="hidden peer" 
                      name="timetable"
                      :disabled="slot.disabled"
                    >
                    <label :for="slot.value"
                      :class="[
                        'inline-flex items-center justify-center w-full px-2 py-1.5 text-sm font-medium text-center',
                        slot.disabled ? 'cursor-not-allowed bg-gray-100 text-gray-400' : 'hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border rounded-lg cursor-pointer text-gray-500 border-gray-300 dark:border-gray-700 dark:peer-checked:border-blue-500 peer-checked:border-blue-700 dark:hover:border-gray-600 dark:peer-checked:text-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-600 dark:peer-checked:bg-blue-900'
                      ]"
                    >
                      {{ slot.label }}
                    </label>
                  </li>
                </ul>

                <div v-if="errorMessage" class="mb-4 text-red-500 text-sm">{{ errorMessage }}</div>

                <!-- Modal Buttons -->
                <div class="grid grid-cols-2 gap-2">
                  <button 
                    type="button"
                    @click="closeUpdateTimeModal"
                    class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                  >
                    Discard
                  </button>
                  <button 
                    @click="updateTime"
                    :disabled="updateTimeForm.processing || !isValidTimeSelection"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {{ updateTimeForm.processing ? 'Saving...' : 'Save' }}
                  </button>
                </div>
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
  </div>
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayoutSA.vue';
import PlayersDisplay from '@/Components/PlayersDisplay.vue';
import SuccessModal from '@/Components/SuccessModal.vue';


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
  user: Object

});
const showSuccessModal = ref(false);
const successMessage = ref('');
const showModal = ref(false);
const errorMessage = ref('');
const showRankModal = ref(false);
const selectedVariationMatches = ref([]);
const rankUpdateError = ref('');
const rankUpdates = ref({});
const showUpdateTimeModal = ref(false);
const selectedVariation = ref(null);
const activeTab = ref('sports');

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

const form = useForm({
  assigned_sport_id: props.sport.id,
  sport_variation_name: '',
  sport_variation_venue_id: '',
  date: '',
  time: '',
  status: 'pending',
});

onMounted(() => {
  form.assigned_sport_id = props.sport.id;
});


const returnToPalakasan = () => {
    router.get(route('subadmin.show'));
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

const openUpdateTimeModal = (variation) => {
  selectedVariation.value = variation;
  updateTimeForm.sport_variation_id = variation.id;
  updateTimeForm.sport_variation_venue_id = variation.sport_variation_venue_id;
  updateTimeForm.date = variation.date;
  updateTimeForm.time = variation.time;
  showUpdateTimeModal.value = true;
  updateAvailableTimeSlots();
};

const closeUpdateTimeModal = () => {
  showUpdateTimeModal.value = false;
  selectedVariation.value = null;
  updateTimeForm.reset();
  updateTimeForm.clearErrors();
  errorMessage.value = '';
};

const updateTime = async () => {
  if (!isValidTimeSelection.value) {
    errorMessage.value = 'Please select an available time slot.';
    return;
  }

  try {
    await updateTimeForm.patch(route('sport-variations.update-time', selectedVariation.value.id), {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        closeUpdateTimeModal();
        showSuccessModal.value = true;
        successMessage.value = 'Time updated successfully!';
      },
      onError: (errors) => {
        console.error('Update time errors:', errors);
        errorMessage.value = 'Please correct the errors and try again.';
      },
    });
  } catch (error) {
    console.error('Update time error:', error);
    errorMessage.value = 'An unexpected error occurred. Please try again.';
  }
};

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

const getCurrentDate = () => {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
};

const isValidTimeSelection = computed(() => {
  return updateTimeForm.sport_variation_venue_id && 
         updateTimeForm.date && 
         updateTimeForm.time && 
         !availableTimeSlots.value.find(slot => slot.value === updateTimeForm.time)?.disabled;
});

const availableTimeSlots = ref([
  { value: '6:00 AM', label: '6:00 AM', disabled: false  },
  { value: '6:30 AM', label: '6:30 AM', disabled: false  },
  { value: '7:00 AM', label: '7:00 AM', disabled: false  },
  { value: '7:30 AM', label: '7:30 AM', disabled: false  },
  { value: '8:00 AM', label: '8:00 AM', disabled: false  },
  { value: '8:30 AM', label: '8:30 AM', disabled: false  },
  { value: '9:00 AM', label: '9:00 AM', disabled: false  },
  { value: '9:30 AM', label: '9:30 AM', disabled: false  },
  { value: '10:00 AM', label: '10:00 AM', disabled: false  },
  { value: '10:30 AM', label: '10:30 AM', disabled: false  },
  { value: '11:00 AM', label: '11:00 AM', disabled: false  },
  { value: '11:30 AM', label: '11:30 AM', disabled: false  },
  { value: '12:00 PM', label: '12:00 PM', disabled: false  },
  { value: '1:00 PM', label: '1:00 PM', disabled: false  },
  { value: '1:30 PM', label: '1:30 PM', disabled: false  },
  { value: '2:00 PM', label: '2:00 PM', disabled: false  },
  { value: '2:30 PM', label: '2:30 PM', disabled: false  },
  { value: '3:00 PM', label: '3:00 PM', disabled: false  },
  { value: '3:30 PM', label: '3:30 PM', disabled: false  },
  { value: '4:00 PM', label: '4:00 PM', disabled: false  },
  { value: '4:30 PM', label: '4:30 PM', disabled: false  },
  { value: '5:00 PM', label: '5:00 PM', disabled: false  },
  { value: '5:30 PM', label: '5:30 PM', disabled: false  },
  { value: '6:00 PM', label: '6:00 PM', disabled: false  }
]);

const updateAvailableTimeSlots = () => {
  if (!updateTimeForm.date || !updateTimeForm.sport_variation_venue_id) return;

  console.log('Updating available time slots');
  console.log('Selected Date:', updateTimeForm.date);
  console.log('Selected Venue:', updateTimeForm.sport_variation_venue_id);

  // Check UsedVenueRecord for conflicts
  const conflictingRecords = props.venueRecords.filter(record => 
    record.venue_id === updateTimeForm.sport_variation_venue_id &&
    record.date === updateTimeForm.date &&
    (!selectedVariation.value || record.id !== selectedVariation.value.id) // Exclude the current variation if editing
  );

  console.log('Conflicting Records:', conflictingRecords);

  // Create a Set of used time slots for quick lookup
  const usedTimeSlots = new Set(conflictingRecords.map(record => record.time));

  console.log('Used Time Slots:', usedTimeSlots);

  // Update available time slots
  availableTimeSlots.value = availableTimeSlots.value.map(slot => ({
    ...slot,
    disabled: usedTimeSlots.has(slot.value)
  }));

  // If editing and the current time is selected, make it available
  if (selectedVariation.value && selectedVariation.value.time && selectedVariation.value.date === updateTimeForm.date) {
    const currentTimeSlot = availableTimeSlots.value.find(slot => 
      slot.value === selectedVariation.value.time
    );
    if (currentTimeSlot) {
      currentTimeSlot.disabled = false;
    }
  }

  console.log('Updated available time slots:', availableTimeSlots.value);

  // Reset the selected time if it's now disabled
  if (updateTimeForm.time && availableTimeSlots.value.find(slot => slot.value === updateTimeForm.time)?.disabled) {
    updateTimeForm.time = '';
  }
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

watch(() => updateTimeForm.date, updateAvailableTimeSlots);
watch(() => updateTimeForm.sport_variation_venue_id, updateAvailableTimeSlots);
watch(progressPercentage, (newValue) => {
  if (newValue === 100) {
    updateAssignedSportStatus();
  }
});


</script>