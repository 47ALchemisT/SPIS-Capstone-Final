<template>
  <div class="game-schedule">
    <Toast ref="toastRef" />
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="match in sortedMatches" 
        :key="match.id" 
        :class="[
          'p-4 border rounded-lg transition-all duration-200 group',
          match.status === 'completed' ? 'bg-green-50 border-green-300' : 
          !canUpdateMatch(match) ? 'bg-gray-50 border-gray-300' : 
          'bg-white border-gray-300 hover:border-blue-400'
        ]">
        <div class="flex justify-between mb-3">
          <div class="flex flex-col">
            <span class="font-semibold text-md ">{{ match.game }}</span>
            <div class="text-xs flex justify-between gap-1.5 items-center">
              <span :class="getStatusClass(match.status)">Status: {{ formatStatus(match.status) }}</span>
            </div>          
          </div>
          <div>
            <button 
              @click="openScoreModal(match)"
              type="button" 
              class="bg-blue-700 hover:bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-md disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-700 transition duration-200"
              :disabled="!canUpdateMatch(match) || match.status === 'completed'"
            >
              <i class="fa-solid fa-flag mr-2"></i>
              Score
            </button>
          </div>
        </div>

        <div class="grid grid-cols-4 gap-3 items-center">
          <div class="col-span-2 flex justify-center p-2 rounded-md items-center transition"
            :class="[
              match.status === 'completed' ? {
                'bg-green-100': isWinner(match, 'teamA'),
                'bg-red-100': !isWinner(match, 'teamA')
              } : 'bg-gray-100'
            ]">
            <span class="font-medium mr-3" :class="{
              'text-red-600': match.status === 'completed' && !isWinner(match, 'teamA'),
              'text-green-700': match.status === 'completed' && isWinner(match, 'teamA')
            }">{{ getTeamName(match.teamA_id) }}</span>
            <span :class="getScoreClass(match, 'teamA')">{{ getScore(match, 'teamA') }}</span>
          </div>
          <div class="col-span-2 flex justify-center p-2 rounded-md items-center transition"
            :class="[
              match.status === 'completed' ? {
                'bg-green-100': isWinner(match, 'teamB'),
                'bg-red-100': !isWinner(match, 'teamB')
              } : 'bg-gray-100'
            ]">
            <span :class="getScoreClass(match, 'teamB')">{{ getScore(match, 'teamB') }}</span>
            <span class="font-medium ml-3" :class="{
              'text-red-600': match.status === 'completed' && !isWinner(match, 'teamB'),
              'text-green-700': match.status === 'completed' && isWinner(match, 'teamB')
            }">{{ getTeamName(match.teamB_id) }}</span>
          </div>
        </div>

        <div class="flex items-center mt-4 gap-2.5">
          <div class="text-xs text-gray-600">
            <div class="flex items-center gap-1.5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>{{ getVenueName(match.match_venue_id) }}</span>
            </div>
          </div>
          <p class="text-xs text-gray-400">|</p>
          <div class="text-xs text-gray-600">
            <div class="flex items-center gap-1.5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span :class="{'text-red-500': !match.date || !match.time}">
                {{ formatDateTime(match.date, match.time) }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Score Modal -->
    <div v-if="showScoreModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg relative w-full mx-4">
        <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Add Score
          </h3>
          <button @click="closeScoreModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <div class="p-4">
          <form @submit.prevent="openSignatureModal">
            <div class="mb-4">
              <label class="block mb-2 font-medium text-sm">
                <div class="flex justify-between gap-2">
                  <span>{{ getTeamName(selectedMatch.teamA_id) }}</span>
                  <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input 
                      type="checkbox" 
                      v-model="scoreFormData.teamA_default_win"
                      @change="handleDefaultWin('teamA')"
                      :disabled="scoreFormData.teamB_default_win"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    Win by Default
                  </label>
                </div>
                <div class="flex items-center gap-2 mt-2">
                  <input 
                    v-model="scoreFormData.teamA_score"
                    type="number"
                    min="0"
                    required
                    :disabled="scoreFormData.teamA_default_win || scoreFormData.teamB_default_win"
                    class="border px-3 py-2 rounded-lg border-gray-300 w-full"
                  />
                </div>
              </label>
            </div>

            <div class="mb-4">
              <label class="block mb-3 font-medium text-sm">
                <div class="flex justify-between gap-2">
                  <span>{{ getTeamName(selectedMatch.teamB_id) }}</span>
                  <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input 
                      type="checkbox" 
                      v-model="scoreFormData.teamB_default_win"
                      @change="handleDefaultWin('teamB')"
                      :disabled="scoreFormData.teamA_default_win"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    Win by Default
                  </label>
                </div>
                <div class="flex items-center gap-2 mt-2">
                  <input 
                    v-model="scoreFormData.teamB_score"
                    type="number"
                    min="0"
                    required
                    :disabled="scoreFormData.teamA_default_win || scoreFormData.teamB_default_win"
                    class="border rounded-lg border-gray-300 px-3 py-2 w-full"
                  />
                </div>
              </label>
            </div>

            <p v-if="scoreError" class="text-red-600 mb-4">{{ scoreError }}</p>

            <div class="grid grid-cols-2 items-center gap-2">
              <button  
                @click="closeScoreModal" 
                type="button" 
                class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                Cancel
              </button>

              <button 
                type="submit" 
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Validate
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Signature Modal -->
    <div v-if="showSignatureModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg relative sm:w-[500px] w-full mx-4">
        <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Confirm Signature
          </h3>
          <button @click="closeSignatureModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <div class="p-4">
          <!-- Display Selected Winner -->
          <div class="mb-4 p-4 bg-green-50 text-center rounded-lg">
            <h4 class="text-xs text-green-600 mb-1">Match Result</h4>
            <div class="flex items-center justify-center text-green-600 rounded-md">
              <span class="font-medium text-md">
                {{ parseInt(scoreFormData.teamA_score) > parseInt(scoreFormData.teamB_score) ? 
                    getTeamName(selectedMatch.teamA_id):
                   parseInt(scoreFormData.teamB_score) > parseInt(scoreFormData.teamA_score) ? 
                    getTeamName(selectedMatch.teamB_id):
                    'Draw (' + scoreFormData.teamA_score + ' - ' + scoreFormData.teamB_score + ')'
                }}
              </span>
            </div>
          </div>
          <div class="mb-4">
            <label class="block mb-2 font-medium text-sm">
              Official Name
              <input 
                v-model="scoreFormData.official_name"
                type="text"
                required
                placeholder="Enter your official name"
                class="mt-2 border-2 border-gray-300 rounded-lg w-full px-3 py-2"
              />
            </label>
          </div>
          <div class="mb-4">
            <label class="block mb-2 font-medium text-sm">
              Signature
              <div class="mt-2 rounded-lg  bg-white">
                <canvas 
                  ref="signatureCanvas"
                  @mousedown="startDrawing"
                  @mousemove="draw"
                  @mouseup="stopDrawing"
                  @mouseleave="stopDrawing"
                  @touchstart.prevent="startDrawingTouch"
                  @touchmove.prevent="drawTouch"
                  @touchend.prevent="stopDrawing"
                  @touchcancel.prevent="stopDrawing"
                  width="450"
                  height="200"
                  class="border-2 border-gray-300 rounded-lg w-full"
                ></canvas>
              </div>
            </label>
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

          <p v-if="signatureError" class="text-red-600 mb-4">{{ signatureError }}</p>

          <div class="grid grid-cols-2 items-center gap-2">
            <button  
              @click="closeSignatureModal" 
              type="button" 
              class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
              Back
            </button>

            <button 
              @click="submitScore"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
              {{ scoreLoading ? 'Saving...' : 'Confirm' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <SuccessModal
        :show="showSuccessModal"
        :message="successMessage"
        @close="showSuccessModal = false"
     />
</template>

<script setup>
import { ref, computed, watch, nextTick, onMounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import Toast from '@/Components/Toast.vue';
import SuccessModal from '@/Components/SuccessModal.vue';

const props = defineProps({
  matches: {
    type: Array,
    required: true
  },
  teams: {
    type: Array,
    required: true
  },
  results: {
    type: Array,
    required: true
  },
  venues: {
    type: Array,
    required: true
  },
  allMatches: {
    type: Array,
    required: true
  },
  venueRecords: {
    type: Array,
    required: true
  },
  majorPoints: {
    type: Array,
    default: () => []
  },
  minorPoints: {
    type: Array,
    default: () => []
  }
});

const showSuccessModal = ref(false);
const successMessage = ref('');

const showScoreModal = ref(false);
const scoreLoading = ref(false);
const scoreError = ref('');
const selectedMatch = ref(null);
const signatureCanvas = ref(null);
const showSignatureModal = ref(false);
const signatureError = ref('');
const isDrawing = ref(false);
const lastX = ref(0);
const lastY = ref(0);
const toastRef = ref(null);

const startDrawing = (e) => {
  isDrawing.value = true;
  const pos = getPosition(e);
  [lastX.value, lastY.value] = [pos.x, pos.y];
};

const draw = (e) => {
  if (!isDrawing.value) return;
  
  const canvas = signatureCanvas.value;
  const ctx = canvas.getContext('2d');
  const pos = getPosition(e);
  
  ctx.beginPath();
  ctx.moveTo(lastX.value, lastY.value);
  ctx.lineTo(pos.x, pos.y);
  ctx.stroke();
  
  [lastX.value, lastY.value] = [pos.x, pos.y];
};

const drawTouch = (e) => {
  e.preventDefault(); // Prevent scrolling
  draw(e);
};

const startDrawingTouch = (e) => {
  e.preventDefault(); // Prevent scrolling
  startDrawing(e);
};

const getPosition = (e) => {
  const canvas = signatureCanvas.value;
  const rect = canvas.getBoundingClientRect();
  
  // Calculate the scaling factors
  const scaleX = canvas.width / rect.width;
  const scaleY = canvas.height / rect.height;
  
  let x, y;
  
  if (e.touches && e.touches[0]) {
    // Touch event
    x = (e.touches[0].clientX - rect.left) * scaleX;
    y = (e.touches[0].clientY - rect.top) * scaleY;
  } else {
    // Mouse event
    x = e.offsetX * scaleX;
    y = e.offsetY * scaleY;
  }
  
  return { x, y };
};

const stopDrawing = () => {
  isDrawing.value = false;
};

const clearSignature = () => {
  const canvas = signatureCanvas.value;
  const ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, canvas.width, canvas.height);
};

const openSignatureModal = (e) => {
  e.preventDefault();
  
  const { teamA_score, teamB_score } = scoreFormData.value;
  
  if (!teamA_score || !teamB_score) {
    scoreError.value = 'Please enter scores for both teams';
    return;
  }
  
  if (teamA_score === teamB_score) {
    scoreError.value = 'Scores cannot be tied in this elimination bracket.';
    return;
  }
  
  showScoreModal.value = false;
  showSignatureModal.value = true;
  signatureError.value = '';
  
  nextTick(() => {
    clearSignature();
  });
};

const closeSignatureModal = () => {
  showSignatureModal.value = false;
  signatureError.value = '';
};

const getTeamName = (teamId) => {
  if (!teamId) return 'TBD';

  if (typeof teamId === 'string' && teamId.startsWith('winner:')) {
    const [, round, game] = teamId.split(':');
    return `Winner of R${round} G${game}`;
  }

  if (props.teams && Array.isArray(props.teams)) {
    const team = props.teams.find(t => t.id === teamId);
    return team ? team.assigned_team_name : 'Unknown';
  }

  return 'Unknown';
};

const sortedMatches = computed(() => {
  return props.matches
    .filter(match => match && match.id)
    .sort((a, b) => {
      const getGameNumber = (game) => {
        const number = parseInt(game.match(/\d+/));
        return isNaN(number) ? 0 : number;
      };
      
      const gameA = getGameNumber(a.game);
      const gameB = getGameNumber(b.game);
      
      if (gameA !== gameB) {
        return gameA - gameB;
      }
      
      if (a.date && b.date) {
        const dateCompare = new Date(a.date) - new Date(b.date);
        if (dateCompare !== 0) return dateCompare;
      }
      
      return a.round - b.round;
    });
});

const canUpdateMatch = (match) => {
  // First, check if the match time has arrived
  if (match.date && match.time) {
    const matchDateTime = new Date(`${match.date} ${match.time}`);
    const currentDateTime = new Date();
    
    // If the current time is before the match time, return false
    if (currentDateTime < matchDateTime) {
      return false;
    }
  }

  // Extract game number from match.game (e.g., "Game 1" -> 1)
  const currentGameNumber = parseInt(match.game.match(/\d+/)) || 0;
  
  // If it's Game 1, it can be updated if teams are assigned
  if (currentGameNumber === 1) {
    return match.teamA_id && match.teamB_id;
  }

  // For games after Game 1, check if all previous games are completed
  const previousGamesCompleted = sortedMatches.value
    .filter(m => {
      const gameNumber = parseInt(m.game.match(/\d+/)) || 0;
      return gameNumber < currentGameNumber;
    })
    .every(m => m.status === 'completed');

  return previousGamesCompleted && match.teamA_id && match.teamB_id;
};

const scoreFormData = ref({
  sport_match_id: '',
  teamA_score: '',
  teamB_score: '',
  winning_team_id: '',
  losing_team_id: '',
  official_name: '',
  signature: '',
  teamA_default_win: false,
  teamB_default_win: false
});

const handleDefaultWin = (team) => {
  if (team === 'teamA' && scoreFormData.value.teamA_default_win) {
    scoreFormData.value.teamA_score = '1';
    scoreFormData.value.teamB_score = '0';
  } else if (team === 'teamB' && scoreFormData.value.teamB_default_win) {
    scoreFormData.value.teamA_score = '0';
    scoreFormData.value.teamB_score = '1';
  } else {
    scoreFormData.value.teamA_score = '';
    scoreFormData.value.teamB_score = '';
  }
};

const openScoreModal = (match) => {
  if (canUpdateMatch(match)) {
    selectedMatch.value = match;
    const existingResult = getMatchResult(match.id);
    
    scoreFormData.value = {
      sport_match_id: match.id,
      teamA_score: existingResult ? existingResult.teamA_score : '',
      teamB_score: existingResult ? existingResult.teamB_score : '',
      winning_team_id: '',
      losing_team_id: '',
      signature: '',
      teamA_default_win: false,
      teamB_default_win: false
    };
    
    showScoreModal.value = true;
  } else {
    if (toastRef.value) {
      const matchDateTime = new Date(`${match.date} ${match.time}`);
      const currentDateTime = new Date();
      
      const message = currentDateTime < matchDateTime
        ? "This match hasn't started yet."
        : "This game is locked. Complete the previous games first.";
      
      toastRef.value.show({
        type: 'error',
        message: message
      });
    }
  }
};

const closeScoreModal = () => {
  showScoreModal.value = false;
  showSignatureModal.value = false;
  
  selectedMatch.value = null;
  scoreFormData.value = {
    sport_match_id: '',
    teamA_score: '',
    teamB_score: '',
    winning_team_id: '',
    losing_team_id: '',
    official_name: '',
    signature: '',
    teamA_default_win: false,
    teamB_default_win: false,
  };
  scoreError.value = '';
  signatureError.value = '';
  
  if (signatureCanvas.value) {
    const ctx = signatureCanvas.value.getContext('2d');
    ctx.clearRect(0, 0, signatureCanvas.value.width, signatureCanvas.value.height);
  }
};

const submitScore = async () => {
  if (!scoreFormData.value.official_name.trim()) {
    signatureError.value = 'Please enter your official name';
    return;
  }

  const canvas = signatureCanvas.value;
  const signatureData = canvas.toDataURL('image/png');
  
  const blankCanvas = document.createElement('canvas');
  blankCanvas.width = canvas.width;
  blankCanvas.height = canvas.height;
  
  if (signatureData === blankCanvas.toDataURL('image/png')) {
    signatureError.value = 'Please provide a signature';
    return;
  }

  scoreFormData.value.signature = signatureData;

  const { teamA_score, teamB_score } = scoreFormData.value;
  const teamAId = selectedMatch.value.teamA_id;
  const teamBId = selectedMatch.value.teamB_id;
  
  if (scoreFormData.value.teamA_default_win) {
    scoreFormData.value.winning_team_id = teamAId;
    scoreFormData.value.losing_team_id = teamBId;
  } else if (scoreFormData.value.teamB_default_win) {
    scoreFormData.value.winning_team_id = teamBId;
    scoreFormData.value.losing_team_id = teamAId;
  } else {
    scoreFormData.value.winning_team_id = teamA_score > teamB_score ? teamAId : teamBId;
    scoreFormData.value.losing_team_id = teamA_score > teamB_score ? teamBId : teamAId;
  }

  scoreLoading.value = true;
  signatureError.value = '';

  try {
    await router.post(route('results.store'), scoreFormData.value, {
      onSuccess: () => {
        console.log('Score updated successfully');
        showSignatureModal.value = false;
        showScoreModal.value = false;
        closeScoreModal();
        scoreLoading.value = false;
        showSuccessModal.value = true;
        successMessage.value = 'Score submitted successfully!';
      },
      onError: (errors) => {
        console.error('Error updating score:', errors);
        signatureError.value = errors.message || 'Failed to update score';
        scoreLoading.value = false;
      }
    });
  } catch (error) {
    console.error('Error updating score:', error);
    signatureError.value = 'Failed to update score';
    scoreLoading.value = false;
  }
};

const getVenueName = (venueId) => {
  if (!venueId) return 'No venue assigned';
  const venue = props.venues.find(v => v.id === venueId);
  return venue ? venue.name : 'Unknown venue';
};

const getScore = (match, team) => {
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return '-';
  return team === 'teamA' ? result.teamA_score : result.teamB_score;
};

const getScoreClass = (match, team) => {
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return 'font-medium text-gray-500';
  
  const scoreA = result.teamA_score;
  const scoreB = result.teamB_score;
  
  if (team === 'teamA') {
    return scoreA > scoreB ? 'font-bold text-green-600' : 'font-medium text-red-600';
  }
  return scoreB > scoreA ? 'font-bold text-green-600' : 'font-medium text-red-600';
};

const getStatusClass = (status) => {
  const classes = {
    'scheduled': 'text-blue-600',
    'in_progress': 'text-green-600',
    'completed': 'text-gray-600',
    'cancelled': 'text-red-600'
  };
  return `${classes[status?.toLowerCase()] || 'text-gray-600'}`;
};

const formatStatus = (status) => {
  if (!status) return '';
  return status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
};

const formatDateTime = (date, time) => {
  if (!date || !time) return 'Not scheduled';
  return `${new Date(date).toLocaleDateString()} ${time}`;
};

const getMatchResult = (matchId) => {
  return props.results.find(result => result.sport_match_id === matchId);
};

const isWinner = (match, team) => {
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return false;
  
  if (team === 'teamA') {
    return result.teamA_score > result.teamB_score;
  }
  return result.teamB_score > result.teamA_score;
};

watch(signatureCanvas, (canvas) => {
  if (canvas) {
    const ctx = canvas.getContext('2d');
    ctx.strokeStyle = 'black';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    
    // Add touch-action CSS property to prevent default touch behaviors
    canvas.style.touchAction = 'none';
  }
});

onMounted(() => {
  console.log('Matches:', props.matches);
});
</script>
