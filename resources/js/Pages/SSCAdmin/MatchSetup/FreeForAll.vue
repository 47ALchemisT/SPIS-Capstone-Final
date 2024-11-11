<template>
  <div>
    <Head :title="sport.sport.name" />
    <AppLayout>
      <template v-slot:default>
        <!-- Header Section -->
        <div class="flex items-center gap-2">
          <h1 class="text-4xl font-semibold">{{ sport.sport.name }}</h1>
          <h1 class="text-4xl font-semibold">{{ sportVariationMatches.id }}</h1>
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
        <div class="flex gap-2 mt-2">
          <p class="py-1 px-4 rounded-full bg-blue-100 text-blue-700 text-sm">{{ sport.setup }}</p>
          <p class="py-1 px-4 rounded-full bg-blue-100 text-blue-700 text-sm">{{ sport.categories }}</p>
        </div>

        <!-- Sport Variations List -->
        <div class=" py-6 mb-6">
          <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold mb-4">Sport Variations</h2>
            <div class="">
              <button
                @click="showModal = true"
                type="button"
                class="text-white bg-blue-700 flex items-center gap-2 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
              >
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M15 12v6m-3-3h6"/><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></g></svg>
                Variation
              </button>
            </div>
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
              <div v-if="sportVariationMatches.length > 0" class="mt-4">
                <table class="w-full text-sm">
                  <thead>
                    <tr>
                      <th class="px-4 py-2 bg-gray-100 text-left">Team</th>
                      <th class="px-4 py-2 bg-gray-100 text-center">Rank</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="match in getSortedMatches(sportVariationMatches.filter(m => m.sport_variation_id === variation.id))" 
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

        <!-- Sport Variation Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold">Add Sport Variation</h2>
              <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-times"></i>
              </button>
            </div>

            <form @submit.prevent="submitForm" class="space-y-4">
              <!-- Sport Name (Readonly) -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Sport</label>
                <input 
                  type="text" 
                  :value="sport.sport.name" 
                  readonly
                  class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md"
                >
              </div>

              <!-- Variation Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Variation Name</label>
                <input 
                  v-model="form.sport_variation_name"
                  type="text" 
                  required
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': form.errors.sport_variation_name }"
                >
                <p v-if="form.errors.sport_variation_name" class="mt-1 text-sm text-red-600">
                  {{ form.errors.sport_variation_name }}
                </p>
              </div>

              <!-- Venue Selection -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Venue</label>
                <select 
                  v-model="form.sport_variation_venue_id"
                  required
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': form.errors.venue_id }"
                >
                  <option value="" disabled>Select a venue</option>
                  <option v-for="venue in venues" :key="venue.id" :value="venue.id">
                    {{ venue.name }}
                  </option>
                </select>
                <p v-if="form.errors.venue_id" class="mt-1 text-sm text-red-600">
                  {{ form.errors.venue_id }}
                </p>
              </div>

              <!-- Modal Buttons -->
              <div class="flex justify-end gap-2">
                <button 
                  type="button"
                  @click="closeModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                >
                  Cancel
                </button>
                <button 
                  type="submit"
                  :disabled="form.processing"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50"
                >
                  {{ form.processing ? 'Saving...' : 'Save' }}
                </button>
              </div>
            </form>
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
                    :disabled="updateTimeForm.processing"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                  >
                    {{ updateTimeForm.processing ? 'Saving...' : 'Save' }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Rank Update Modal -->
        <div v-if="showRankModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="relative p-4 w-full max-w-[24rem] max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
              <!-- Modal Header -->
              <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Set Rankings
                </h3>
                <button @click="closeRankModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                  </svg>
                  <span class="sr-only">Close modal</span>
                </button>
              </div>
              <!-- Modal Body -->
              <div class="p-4 pt-0">
                <form @submit.prevent="updateRanks">
                  <div class="mt-4">
                    <table class="w-full text-sm">
                      <thead>
                        <tr>
                          <th class="px-4 py-2 bg-gray-100 text-left">Team</th>
                          <th class="px-4 py-2 bg-gray-100 text-center">Rank</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="match in selectedVariationMatches" :key="match.id">
                          <td class="px-4 py-2">  {{ getTeamName(match.sport_variation_team_id) }}</td>
                          <td class="px-4 py-2">
                            <select 
                              v-model="rankUpdates[match.id]"
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
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div v-if="rankUpdateError" class="mt-4 text-red-500 text-sm">{{ rankUpdateError }}</div>

                  <!-- Modal Buttons -->
                  <div class="grid grid-cols-2 gap-2 mt-4">
                    <button 
                      type="button"
                      @click="closeRankModal"
                      class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                    >
                      Cancel
                    </button>
                    <button 
                      type="submit"
                      :disabled="!isValidRankSelection"
                      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 disabled:opacity-50"
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
import { ref, onMounted, computed } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayout.vue';

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
  }
});

const showModal = ref(false);
const showUpdateTimeModal = ref(false);
const errorMessage = ref('');
const selectedVariation = ref(null);

const showRankModal = ref(false);
const selectedVariationMatches = ref([]);
const rankUpdateError = ref('');
const rankUpdates = ref({});

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

const generateTeamsForm = useForm({
  sport_variation_id: '',
  teams: [],
});

onMounted(() => {
  form.assigned_sport_id = props.sport.id;
});

const closeModal = () => {
  showModal.value = false;
  errorMessage.value = '';
  form.reset();
  form.clearErrors();
};

const submitForm = async () => {
  try {
    await form.post(route('sport-variations.store'), {
      onSuccess: () => {
        form.reset();
        showModal.value = false;
        errorMessage.value = '';

        // Generate teams automatically after adding a new variation
        const newVariation = props.sportVariations.find(v => v.sport_variation_name === form.sport_variation_name);
        if (newVariation) {
          openGenerateTeamsModal(newVariation);
          generateTeams();
        }
      },
      onError: (errors) => {
        console.error('Validation errors:', errors);
        errorMessage.value = 'Please correct the errors below.';
      },
      onFinish: () => {
        form.processing = false;
      }
    });
  } catch (error) {
    console.error('Submission error:', error);
    errorMessage.value = 'An unexpected error occurred. Please try again.';
    form.processing = false;
  }
};

const returnToPalakasan = () => {
  router.get(route('palakasan.details', { tab: 'leagues' }));
};

const getVenueName = (venueId) => {
  if (!venueId) return 'No venue assigned';
  const venue = props.venues.find(v => v.id === venueId);
  return venue ? venue.name : 'Unknown venue';
};

const getTeamName = (teamId) => {
  const team = props.teams.find((t) => t.id === teamId);
  return team ? team.college.name : 'Unknown team';
};


const openGenerateTeamsModal = (variation) => {
  selectedVariation.value = variation;
  generateTeamsForm.sport_variation_id = variation.id;
  generateTeamsForm.teams = []; // Reset teams selection
  showGenerateTeamsModal.value = true;
};

const generateTeams = () => {
  generateTeamsForm.post(route('generate-teams'), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      closeGenerateTeamsModal();
      alert('Teams generated successfully!');
    },
    onError: (errors) => {
      console.error('Error generating teams:', errors);
      alert('An error occurred while generating teams. Please try again.');
    },
  });
};

const updateTimeForm = useForm({
  sport_variation_id: '',
  sport_variation_venue_id: '',
  date: '',
  time: '',
});

onMounted(() => {
  form.assigned_sport_id = props.sport.id;
});



const closeUpdateTimeModal = () => {
  showUpdateTimeModal.value = false;
  updateTimeForm.reset();
  updateTimeForm.clearErrors();
};

const openUpdateTimeModal = (variation) => {
  selectedVariation.value = variation;
  updateTimeForm.sport_variation_id = variation.id;
  updateTimeForm.sport_variation_venue_id = variation.sport_variation_venue_id;
  updateTimeForm.date = variation.date;
  updateTimeForm.time = variation.time;
  showUpdateTimeModal.value = true;
};

const updateTime = async () => {
  try {
    await updateTimeForm.patch(route('sport-variations.update-time', selectedVariation.value.id), {
      onSuccess: () => {
        closeUpdateTimeModal();
        // Optionally, you can refresh the sportVariations data here
      },
      onError: (errors) => {
        console.error('Validation errors:', errors);
      },
      onFinish: () => {
        updateTimeForm.processing = false;
      }
    });
  } catch (error) {
    console.error('Update time error:', error);
    updateTimeForm.processing = false;
  }
};


const timeSlots = [
  { value: '6:00 AM', label: '6:00 AM' },
  { value: '6:30 AM', label: '6:30 AM' },
  { value: '7:00 AM', label: '7:00 AM' },
  { value: '7:30 AM', label: '7:30 AM' },
  { value: '8:00 AM', label: '8:00 AM' },
  { value: '8:30 AM', label: '8:30 AM' },
  { value: '09:00 AM', label: '9:00 AM' },
  { value: '09:30 AM', label: '9:30 AM' },
  { value: '10:00 AM', label: '10:00 AM' },
  { value: '10:30 AM', label: '10:30 AM' },
  { value: '11:00 AM', label: '11:00 AM' },
  { value: '11:30 AM', label: '11:30 AM' },
  { value: '12:00 PM', label: '12:00 PM' },
  { value: '01:00 PM', label: '1:00 PM' },
  { value: '01:30 PM', label: '1:30 PM' },
  { value: '02:00 PM', label: '2:00 PM' },
  { value: '02:30 PM', label: '2:30 PM' },
  { value: '03:00 PM', label: '3:00 PM' },
  { value: '03:30 PM', label: '3:30 PM' },
  { value: '04:00 PM', label: '4:00 PM' },
  { value: '04:30 PM', label: '4:30 PM' },
  { value: '05:00 PM', label: '5:00 PM' },
  { value: '05:30 PM', label: '5:30 PM' },
  { value: '06:00 PM', label: '6:00 PM' }
];

const availableTimeSlots = ref(timeSlots);

const getCurrentDate = () => {
  const today = new Date();
  return today.toISOString().split('T')[0];
};

onMounted(() => {
  form.assigned_sport_id = props.sport.id;
});

//

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
//

// Add these new methods
const formatRank = (rank) => {
  const suffixes = {
    1: 'st',
    2: 'nd',
    3: 'rd'
  };
  const suffix = suffixes[rank] || 'th';
  return `${rank}${suffix} place`;
};


const availableRanks = (currentMatchId) => {
  const maxRank = selectedVariationMatches.value.length;
  // Get currently selected ranks, excluding the current match's rank
  const usedRanks = new Set(
    Object.entries(rankUpdates.value)
      .filter(([matchId]) => matchId !== currentMatchId.toString())
      .map(([_, rank]) => rank?.toString())
      .filter(rank => rank !== '') // Filter out empty ranks
  );

  // Return array of available ranks (those not used by other matches)
  return Array.from({ length: maxRank }, (_, i) => i + 1)
    .filter(rank => !usedRanks.has(rank.toString()));
};


const openRankModal = (variation) => {
  selectedVariation.value = variation;
  selectedVariationMatches.value = props.sportVariationMatches.filter(
    m => m.sport_variation_id === variation.id
  );
  // Initialize rankUpdates with current values
  rankUpdates.value = selectedVariationMatches.value.reduce((acc, match) => ({
    ...acc,
    [match.id]: match.rank || ''
  }), {});
  showRankModal.value = true;
};

const getSortedMatches = (matches) => {
  return [...matches].sort((a, b) => {
    // Sort by rank (if both have ranks)
    if (a.rank && b.rank) {
      return a.rank - b.rank;
    }
    // Put ranked matches before unranked ones
    if (a.rank) return -1;
    if (b.rank) return 1;
    // Keep original order for unranked matches
    return 0;
  });
};

const closeRankModal = () => {
  showRankModal.value = false;
  selectedVariation.value = null;
  selectedVariationMatches.value = [];
  rankUpdates.value = {};
  rankUpdateError.value = '';
};

const isValidRankSelection = computed(() => {
  // Check if rankUpdates exists and has values
  if (!rankUpdates.value || Object.keys(rankUpdates.value).length === 0) {
    return false;
  }

  // Check if all matches have a rank assigned
  return selectedVariationMatches.value.every(match => 
    rankUpdates.value[match.id] && rankUpdates.value[match.id] !== ''
  );
});

const updateRanks = async () => {
  // Validate that all ranks are unique
  const selectedRanks = Object.values(rankUpdates.value)
    .filter(rank => rank !== '')
    .map(rank => rank.toString());
  
  const uniqueRanks = new Set(selectedRanks);
  if (selectedRanks.length !== uniqueRanks.size) {
    rankUpdateError.value = 'Each team must have a unique rank.';
    return;
  }

  // Validate that all teams have ranks assigned
  if (!isValidRankSelection.value) {
    rankUpdateError.value = 'All teams must have a rank assigned.';
    return;
  }

  rankUpdateForm.matches = Object.entries(rankUpdates.value).map(([id, rank]) => ({
    id: parseInt(id),
    rank: parseInt(rank)
  }));

  try {
    await rankUpdateForm.patch(
      route('sport-variations.update-ranks', selectedVariation.value.id),
      {
        onSuccess: () => {
          closeRankModal();
        },
        onError: (errors) => {
          console.error('Validation errors:', errors);
          rankUpdateError.value = 'An error occurred while updating ranks.';
        },
        onFinish: () => {
          rankUpdateForm.processing = false;
        }
      }
    );
  } catch (error) {
    console.error('Update ranks error:', error);
    rankUpdateError.value = 'An unexpected error occurred.';
    rankUpdateForm.processing = false;
  }
};

//

// Replace the existing computed properties with these corrected versions:
const totalMatches = computed(() => props.sportVariations.length);

const completedMatches = computed(() => {
  return props.sportVariations.filter(variation => variation.status === 'completed').length;
});

const progressPercentage = computed(() => {
  return totalMatches.value > 0 ? (completedMatches.value / totalMatches.value) * 100 : 0;
});
</script>

<style scoped>
/* Component specific styles can be added here */
</style>