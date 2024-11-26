<template>
  <div>
    <Head :title="sport.sport.name" />
    <AppLayout :facilitator="facilitator">
      <template v-slot:default>
        <!-- Header Section -->
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


        <!-- Tags Section -->
        <div class="flex items-center text-gray-600 gap-2">
            <p class="text-sm"> {{ sport.setup }}</p>
            <span class="text-sm">•</span>
            <p class=" text-sm">{{ sport.type }}</p>
            <span class="text-sm">•</span>
            <p class=" text-sm">{{ sport.status }}</p>
        </div>

        <!-- Progress Bar -->
        <div class="mt-3 space-y-2">
          <div class="w-1/3 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div 
              class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" 
              :style="{ width: `${progressPercentage}%` }"
            ></div>
          </div>
          <div class="flex items-center text-xs">
            <h2 class="text-gray-600">Sport Progress :</h2>
            <span class="font-medium">{{ progressPercentage.toFixed(0) }}% completed</span>
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
            <!-- Sport Variations List -->
            <div class=" mb-6">
              <div v-if="sportVariations.length === 0" class="text-gray-500">
                No variations added yet.
              </div>
              <ul v-else class="space-y-4">
                <li v-for="variation in sportVariations" 
                  :key="variation.id" 
                  class="pb-4 border p-4 border-gray-300 rounded-lg"
                  :class="{
                    'bg-gray-50': latestPalakasan?.status?.toLowerCase() === 'completed',
                    'bg-green-50 border-green-400': variation.status?.toLowerCase() === 'completed'
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
                      <div>
                        <button 
                          type="button"
                          @click="openRankModal(variation)" 
                          :disabled="variation.status === 'completed' || sport.status === 'completed'"                      
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
                          <th class="px-4 py-2 text-left" :class="{ 'bg-green-100': variation.status?.toLowerCase() === 'completed' }">Team</th>
                          <th class="px-4 py-2 text-center" :class="{ 'bg-green-100': variation.status?.toLowerCase() === 'completed' }">Rank</th>
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

        <!-- Ranking Modal -->
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
                <div class="mt-4 p-2 bg-gray-100 rounded-md border border-gray-300">
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
                            class="w-full px-2 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          >
                            <option value="">Select</option>                              
                            <option value="10">Desqualified</option>
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
                            class="px-2 py-2 border w-20 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Points"
                            readonly
                          />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-4">
                  <button 
                    type="button"
                    @click="closeRankModal"
                    class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100"
                  >
                    Cancel
                  </button>
                  <button 
                    @click="openValidationModal"
                    :disabled="!isValidRankSelection"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 disabled:opacity-50"
                  >
                    Next: Validate
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Validation Modal -->
        <div v-if="showValidationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="relative p-4 w-full max-w-[32rem] max-h-full">
            <div class="relative bg-white rounded-lg shadow">
              <div class="flex items-center justify-between p-4 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">
                  Validate Rankings
                </h3>
                <button @click="closeValidationModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center">
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                  </svg>
                </button>
              </div>
              
              <div class="p-4">
                <!-- Official Name Input -->
                <div class="mt-4">
                  <label for="official_name" class="block text-sm font-medium text-gray-700">Official Name</label>
                  <input 
                    type="text"
                    id="official_name"
                    v-model="rankUpdateForm.official_name"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="Enter official name"
                    required
                  />
                </div>

                <!-- Signature Pad -->
                <div class="mt-4">
                  <label class="block text-sm font-medium text-gray-700">Signature</label>
                  <div ref="signaturePadContainer" class="mt-2 border-2 border-gray-300 rounded-md bg-white"></div>
                  <div class="flex justify-between mt-2">
                    <button 
                      type="button"
                      @click="clearSignature" 
                      class="text-sm text-red-600 hover:bg-red-50 px-3 py-1 rounded"
                    >
                      Clear Signature
                    </button>
                    <span class="text-xs text-gray-500">Sign within the box above</span>
                  </div>
                </div>

                <div v-if="rankUpdateError" class="mt-4 text-red-500 text-sm">{{ rankUpdateError }}</div>

                <div class="grid grid-cols-2 gap-2 mt-4">
                  <button 
                    type="button"
                    @click="backToRankModal"
                    class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100"
                  >
                    Back
                  </button>
                  <button 
                    @click="updateRanks"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2"
                  >
                    {{ rankUpdateForm.processing ? 'Saving...' : 'Save' }}
                  </button>
                </div>
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
import { ref, onMounted, computed, watch, nextTick } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayoutF.vue';
import PlayersDisplay from '@/Components/PlayersDisplay.vue';

const props = defineProps({
  players: {
    type: Array,
    default: () => []
  },
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
  facilitator: Object,
  majorPoints: {
    type: Array,
    default: () => []
  },
  minorPoints: {
    type: Array,
    default: () => []
  },
  latestPalakasan: {
    type: Array
  }
});

const showRankModal = ref(false);
const selectedVariationMatches = ref([]);
const rankUpdateError = ref('');
const rankUpdates = ref({});
const selectedVariation = ref(null);
const activeTab = ref('sports');

const totalMatches = computed(() => props.sportVariations.length);
const completedMatches = computed(() => props.sportVariations.filter(variation => variation.status === 'completed').length);
const progressPercentage = computed(() => totalMatches.value > 0 ? (completedMatches.value / totalMatches.value) * 100 : 0);
const hasValidSignature = ref(false);
const signaturePadContainer = ref(null);
const signaturePad = ref(null);

const rankUpdateForm = useForm({
  matches: [],
  official_name: '',
  signature: '',
  facilitator_id: props.facilitator?.id,
});
//new added
const showValidationModal = ref(false);

const openValidationModal = () => {
  showRankModal.value = false;
  showValidationModal.value = true;
  nextTick(() => {
    initSignaturePad();
  });
};

const closeValidationModal = () => {
  showValidationModal.value = false;
  rankUpdateError.value = '';
};

const backToRankModal = () => {
  showValidationModal.value = false;
  showRankModal.value = true;
};

const closeRankModal = () => {
  showRankModal.value = false;
  showValidationModal.value = false;
  selectedVariation.value = null;
  selectedVariationMatches.value = [];
  rankUpdates.value = {};
  rankUpdateError.value = '';
  rankUpdateForm.reset();
};
//


const returnToFacilitator = () => {
  const currentPath = window.location.pathname;
  const matches = currentPath.match(/sportview\/\d+\/(.+)$/);
  if (matches && matches[1]) {
    router.visit(route('facilitator.show', { id: matches[1] }));
  }
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

const currentSportTeams = computed(() => {
  return props.teams.filter(team => team.palakasan_id === props.sport.palakasan_sport_id);
});

const pointsToUse = computed(() => {
  return props.sport.type === 'Major' ? props.majorPoints : props.minorPoints;
});

const sortedMatches = computed(() => {
  return [...selectedVariationMatches.value].sort((a, b) => {
    const rankA = rankUpdates.value[a.id]?.rank || Infinity; // Use Infinity for unassigned ranks
    const rankB = rankUpdates.value[b.id]?.rank || Infinity;
    return rankA - rankB; // Sort by user-defined ranks
  });
});


// SignaturePad class implementation
class SignaturePad {
  constructor(canvas, options = {}) {
    this.canvas = canvas;
    this.ctx = canvas.getContext('2d');
    this.points = [];
    this.isDrawing = false;

    this.canvas.addEventListener('mousedown', this.onMouseDown.bind(this));
    this.canvas.addEventListener('mousemove', this.onMouseMove.bind(this));
    this.canvas.addEventListener('mouseup', this.onMouseUp.bind(this));
    this.canvas.addEventListener('touchstart', this.onTouchStart.bind(this));
    this.canvas.addEventListener('touchmove', this.onTouchMove.bind(this));
    this.canvas.addEventListener('touchend', this.onTouchEnd.bind(this));
  }

  onMouseDown(event) {
    this.isDrawing = true;
    const rect = this.canvas.getBoundingClientRect();
    this.addPoint(event.clientX - rect.left, event.clientY - rect.top);
  }

  onMouseMove(event) {
    if (!this.isDrawing) return;
    const rect = this.canvas.getBoundingClientRect();
    this.addPoint(event.clientX - rect.left, event.clientY - rect.top);
  }

  onMouseUp() {
    this.isDrawing = false;
  }

  onTouchStart(event) {
    event.preventDefault();
    if (event.touches.length === 1) {
      const touch = event.changedTouches[0];
      const rect = this.canvas.getBoundingClientRect();
      this.addPoint(touch.clientX - rect.left, touch.clientY - rect.top);
    }
  }

  onTouchMove(event) {
    event.preventDefault();
    if (event.touches.length === 1) {
      const touch = event.changedTouches[0];
      const rect = this.canvas.getBoundingClientRect();
      this.addPoint(touch.clientX - rect.left, touch.clientY - rect.top);
    }
  }

  onTouchEnd(event) {
    event.preventDefault();
  }

  addPoint(x, y) {
    this.points.push({ x, y });
    this.redraw();
  }

  redraw() {
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    this.ctx.strokeStyle = 'black';
    this.ctx.lineWidth = 2;
    this.ctx.beginPath();
    for (let i = 0; i < this.points.length; i++) {
      const point = this.points[i];
      if (i === 0) {
        this.ctx.moveTo(point.x, point.y);
      } else {
        this.ctx.lineTo(point.x, point.y);
      }
    }
    this.ctx.stroke();
  }

  clear() {
    this.points = [];
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
  }

  isValid() {
    return this.points.length > 10;
  }

  toDataURL() {
    return this.canvas.toDataURL('image/png');
  }
}

onMounted(() => {
  nextTick(() => {
    if (showRankModal.value) {
      initSignaturePad();
    }
  });
});


const initSignaturePad = () => {
  const canvas = document.createElement('canvas');
  canvas.width = 300;
  canvas.height = 200;
  signaturePadContainer.value.appendChild(canvas);
  
  signaturePad.value = new SignaturePad(canvas);
  
  // Add event listeners to update hasValidSignature
  canvas.addEventListener('mouseup', checkSignatureValidity);
  canvas.addEventListener('touchend', checkSignatureValidity);
};

const checkSignatureValidity = () => {
  if (signaturePad.value) {
    hasValidSignature.value = signaturePad.value.isValid();
  }
};

const clearSignature = () => {
  if (signaturePad.value) {
    signaturePad.value.clear();
    hasValidSignature.value = false;
  }
};
//end

const openRankModal = (variation) => {
  selectedVariation.value = variation;
  
  // Get matches for the selected variation
  selectedVariationMatches.value = props.sportVariationMatches
    .filter(m => m.sport_variation_id === variation.id)
    .filter(m => currentSportTeams.value.some(team => team.id === m.sport_variation_team_id));
  
  // Initialize rank updates 
  rankUpdates.value = selectedVariationMatches.value.reduce((acc, match) => ({
    ...acc,
    [match.id]: {
      rank: match.rank || '', 
      points: match.points || ''
    }
  }), {});

  showRankModal.value = true;
  nextTick(() => {
    initSignaturePad();
  });
};

watch(() => rankUpdates.value, (newRankUpdates) => {
  const sortedPoints = [...pointsToUse.value].sort((a, b) => b.points - a.points);
  
  // Get all selected ranks and sort them, excluding Desqualified
  const selectedRanks = Object.values(newRankUpdates)
    .filter(match => match.rank !== '' && match.rank !== '10')  // Exclude Desqualified rank
    .map(match => parseInt(match.rank))
    .sort((a, b) => a - b);

  // Assign points based on rank order
  Object.keys(newRankUpdates).forEach((matchId) => {
    const rank = newRankUpdates[matchId].rank;
    if (rank !== '') {
      // If rank is 10 (Desqualified), set points to 0
      if (rank === '10') {
        newRankUpdates[matchId].points = 0;
      } else {
        const rankIndex = selectedRanks.indexOf(parseInt(rank));
        newRankUpdates[matchId].points = sortedPoints[rankIndex] 
          ? sortedPoints[rankIndex].points 
          : 0;
      }
    }
  });
}, { deep: true });


const isValidRankSelection = computed(() => {
  const selectedMatches = Object.values(rankUpdates.value);
  const allFieldsFilled = selectedMatches.every(match => 
    match.rank !== '' && match.points !== ''
  );
  const selectedRanks = new Set(selectedMatches.map(match => match.rank).filter(rank => rank !== ''));
  return allFieldsFilled && selectedRanks.size === selectedVariationMatches.value.length;
});

const updateRanks = async () => {
  if (!signaturePad.value || !signaturePad.value.isValid()) {
    alert('Please provide a valid signature before submitting.');
    return;
  }

  if (!rankUpdateForm.official_name) {
    alert('Please enter your official name.');
    return;
  }

  try {
    // Log the data being sent
    console.log('Form data:', {
      matches: Object.entries(rankUpdates.value).map(([id, data]) => ({
        id: parseInt(id),
        rank: parseInt(data.rank),
        points: parseInt(data.points)
      })),
      official_name: rankUpdateForm.official_name,
      facilitator_id: props.facilitator?.id,
      signature: signaturePad.value.toDataURL('image/png')
    });

    // Prepare the form data
    rankUpdateForm.signature = signaturePad.value.toDataURL('image/png');
    rankUpdateForm.matches = Object.entries(rankUpdates.value).map(([id, data]) => ({
      id: parseInt(id),
      rank: parseInt(data.rank),
      points: parseInt(data.points)
    }));

    await rankUpdateForm.patch(route('sport-variations.update-ranks', selectedVariation.value.id), {
      preserveScroll: true,
      onSuccess: () => {
        closeRankModal();
        // Refresh the page to show updated data
        window.location.reload();
      },
      onError: (errors) => {
        console.error('Update ranks errors:', errors);
        if (errors.signature) {
          rankUpdateError.value = 'Please provide a valid signature.';
        } else if (errors.official_name) {
          rankUpdateError.value = 'Please provide your official name.';
        } else if (errors.matches) {
          rankUpdateError.value = 'Please ensure all ranks and points are properly assigned.';
        } else {
          rankUpdateError.value = errors.error || 'An error occurred while updating ranks. Please try again.';
        }
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
    completed: 'text-blue-600',
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

const availableRanks = (matchId) => {
  const currentRanks = new Set();
  
  Object.entries(rankUpdates.value).forEach(([id, data]) => {
    if (id !== matchId.toString() && data.rank) {
      currentRanks.add(data.rank.toString());
    }
  });
  
  const allRanks = Array.from(
    { length: selectedVariationMatches.value.length }, 
    (_, i) => (i + 1).toString()
  );
  
  return allRanks.filter(rank => 
    !currentRanks.has(rank) || 
    rankUpdates.value[matchId]?.rank === rank
  );
};


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

<style scoped>
/* Add any component-specific styles here */
</style>