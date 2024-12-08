<template>
  <div class="game-schedule">
    <div v-for="(roundMatches, round) in matchesByRound" :key="round" 
      :class="[
        'mb-8 p-5 rounded-lg transition-all duration-200',
        canUpdateRound(round) ? 'bg-gray-50' : 'bg-gray-100 opacity-75'
      ]">
      <div class="flex items-center justify-between mb-4">
        <div v-if="!canUpdateRound(round)" class="flex items-center text-sm text-gray-600">
          <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path fill="currentColor" d="M12 17a2 2 0 0 0 2-2a2 2 0 0 0-2-2a2 2 0 0 0-2 2a2 2 0 0 0 2 2m6-9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h1V6a5 5 0 0 1 5-5a5 5 0 0 1 5 5v2h1m-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3Z"/>
          </svg>
          Locked: Complete previous round first
        </div>
      </div>
      <h3 class="text-md font-semibold mb-4">Round {{ round }}</h3>
      <div class="flex justify-center gap-4 ">
        <div v-for="match in roundMatches" 
            :key="match.id" 
            class="bg-white shadow-sm rounded-lg p-4 border border-gray-200 hover:shadow transition-all duration-200">
          <div class="flex justify-between items-center mb-3">
            <div class="flex flex-col">
              <span class="font-semibold text-md">{{ match.game }}</span>
            </div>
            <div class="flex">
              <button 
                @click="openWinnerModal(match)"
                :disabled="!canUpdateRound(round) || match.status === 'completed'"
                type="button" 
                class="bg-blue-700 hover:bg-blue-600 text-white text-xs font-medium py-2 px-4 rounded-md disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-700 transition duration-200"
                >
                <i class="fa-solid fa-flag mr-2"></i>
                Score
              </button>
            </div>
          </div>

          <div class="grid grid-cols-4 gap-3 items-center">
            <div class="col-span-2 flex justify-center bg-gray-100 p-2 rounded-md items-center">
              <span class="font-medium mr-3">{{ getTeamName(match.teamA_id) }}</span>
              <span :class="getResultClass(match, 'teamA')">{{ getResult(match, 'teamA') }}</span>
            </div>
            <div class="col-span-2 flex justify-center bg-gray-100 p-2 rounded-md items-center">
              <span :class="getResultClass(match, 'teamB')">{{ getResult(match, 'teamB') }}</span>
              <span class="font-medium ml-3">{{ getTeamName(match.teamB_id) }}</span>
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
            <p class="text-xs text-gray-400">|</p>
            <div class="text-xs flex justify-between gap-1.5 items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0m5 0v.01m4-.01v.01m4-.01v.01"/></svg>            
              <span :class="getStatusClass(match.status)">Status: {{ match.status }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Winner Modal -->
    <div v-if="isWinnerModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
          <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              Declare Match Result
            </h3>
            <button @click="closeWinnerModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <div class="p-4 md:p-5">
            <form class="space-y-4" @submit.prevent="handleWinnerSelection">
              <div>
                <label class="block mb-3 text-sm font-medium text-gray-900">Select Result</label>
                <div class="grid grid-cols-1 gap-3">
                  <div>
                    <input type="radio" name="result" id="teamA" value="teamA" v-model="winnerFormData.result" class="hidden peer">
                    <label for="teamA" class="inline-flex items-center justify-between w-full p-4 text-gray-900 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-gray-50">
                      <div class="block">
                        <div class="w-full text-sm font-semibold">{{ getTeamName(selectedMatch?.teamA_id) }} Wins</div>
                      </div>
                      <i class="fa-solid fa-trophy text-yellow-500 ml-3"></i>
                    </label>
                  </div>
                  <div>
                    <input type="radio" name="result" id="teamB" value="teamB" v-model="winnerFormData.result" class="hidden peer">
                    <label for="teamB" class="inline-flex items-center justify-between w-full p-4 text-gray-900 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-gray-50">
                      <div class="block">
                        <div class="w-full text-sm font-semibold">{{ getTeamName(selectedMatch?.teamB_id) }} Wins</div>
                      </div>
                      <i class="fa-solid fa-trophy text-yellow-500 ml-3"></i>
                    </label>
                  </div>
                  <div>
                    <input type="radio" name="result" id="tie" value="tie" v-model="winnerFormData.result" class="hidden peer">
                    <label for="tie" class="inline-flex items-center justify-between w-full p-4 text-gray-900 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-gray-50">
                      <div class="block">
                        <div class="w-full text-sm font-semibold">Draw</div>
                      </div>
                      <i class="fa-solid fa-handshake text-blue-500 ml-3"></i>
                    </label>
                  </div>
                </div>
              </div>
              <div v-if="form.errors.length" class="mt-2 text-sm text-red-600">
                <div v-for="(error, index) in form.errors" :key="index">{{ error }}</div>
              </div>
              <div class="flex items-center justify-end">
                <button 
                  type="submit" 
                  :disabled="!winnerFormData.result"
                  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Validate
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Signature Modal -->
    <div v-if="showSignatureModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg relative w-[500px]">
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
                {{ winnerFormData.result === 'teamA' ? getTeamName(selectedMatch?.teamA_id) + ' Wins' :
                  winnerFormData.result === 'teamB' ? getTeamName(selectedMatch?.teamB_id) + ' Wins' :
                  'Draw' }}
              </span>
            </div>
          </div>
          <!-- Add Official Name Field -->
          <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900">
              Official Name
              <input 
                type="text"
                v-model="scoreFormData.official_name"
                class="mt-1 block w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter your official name"
                required
              />
            </label>
          </div>

          <div class="mb-4">
            <label class="block mb-2 font-medium text-sm">
              Signature
              <div class="mt-2 rounded-lg bg-white">
                <canvas 
                  ref="signatureCanvas"
                  @mousedown="startDrawing"
                  @mousemove="draw"
                  @mouseup="stopDrawing"
                  @mouseleave="stopDrawing"
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
              @click="backToWinnerModal" 
              type="button" 
              class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
              Back
            </button>

            <button 
              @click="submitResult"
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
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
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
  }
});

const showSuccessModal = ref(false);
const successMessage = ref('');


// Refs for signature modal
const isWinnerModalOpen = ref(false);
const showSignatureModal = ref(false);
const selectedMatch = ref(null);
const winnerFormData = ref({
  result: '',
});
const signatureError = ref('');
const scoreLoading = ref(false);
const isDrawing = ref(false);
const lastX = ref(0);
const lastY = ref(0);
const signatureCanvas = ref(null);
const scoreFormData = ref({
  official_name: '',
});

// Form data for scores
const scoreError = ref('');
const handleWinnerSelection = (e) => {
  e.preventDefault();
  
  if (!winnerFormData.value.result) {
    form.errors.push('Please select a result');
    return;
  }

  // Close winner modal and open signature modal
  isWinnerModalOpen.value = false;
  showSignatureModal.value = true;

  // Initialize canvas after modal is shown
  nextTick(() => {
    if (signatureCanvas.value) {
      const ctx = signatureCanvas.value.getContext('2d');
      ctx.strokeStyle = 'black';
      ctx.lineWidth = 2;
      clearSignature();
    }
  });
};

const backToWinnerModal = () => {
  showSignatureModal.value = false;
  isWinnerModalOpen.value = true;
};

// Signature canvas functions
const startDrawing = (e) => {
  isDrawing.value = true;
  [lastX.value, lastY.value] = [e.offsetX, e.offsetY];
};

const draw = (e) => {
  if (!isDrawing.value) return;
  
  const canvas = signatureCanvas.value;
  const ctx = canvas.getContext('2d');
  
  ctx.beginPath();
  ctx.moveTo(lastX.value, lastY.value);
  ctx.lineTo(e.offsetX, e.offsetY);
  ctx.stroke();
  
  [lastX.value, lastY.value] = [e.offsetX, e.offsetY];
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
  
  // Validate scores
  const { teamA_score, teamB_score } = scoreFormData.value;
  
  if (!teamA_score || !teamB_score) {
    scoreError.value = 'Please enter scores for both teams';
    return;
  }
  
  if (teamA_score === teamB_score) {
    scoreError.value = 'Scores cannot be tied in this elimination bracket.';
    return;
  }
  
  // Close score modal and open signature modal
  showScoreModal.value = false;
  showSignatureModal.value = true;
  signatureError.value = '';
  
  // Reset canvas for a fresh signature
  nextTick(() => {
    clearSignature();
  });
};

const closeSignatureModal = () => {
  showSignatureModal.value = false;
  signatureError.value = '';
};

// Form and match related functions
const form = useForm({
  matchId: '',
  date: '',
  time: '',
});

const getGameNumber = (game) => {
  if (!game) return 0;
  const matches = game.match(/\d+/);
  return matches ? parseInt(matches[0]) : 0;
};

const matchesByRound = computed(() => {
  const grouped = props.matches.reduce((acc, match) => {
    if (!acc[match.round]) {
      acc[match.round] = [];
    }
    acc[match.round].push(match);
    return acc;
  }, {});

  // Sort matches within each round
  Object.keys(grouped).forEach(round => {
    grouped[round].sort((a, b) => {
      const gameA = getGameNumber(a.game);
      const gameB = getGameNumber(b.game);
      return gameA - gameB;
    });
  });

  return grouped;
});

const getVenueName = (venueId) => {
  if (!venueId) return 'No venue assigned';
  const venue = props.venues.find(v => v.id === venueId);
  return venue ? venue.name : 'Unknown venue';
};

const getTeamName = (teamId) => {
  if (!teamId) return 'TBD';
  if (typeof teamId === 'string' && teamId.startsWith('winner:')) {
    const [, round, game] = teamId.split(':');
    return `Winner of R${round} G${game}`;
  }
  const team = props.teams.find(t => t.id === teamId);
  return team ? team.assigned_team_name : 'Unknown';
};

const getResult = (match, team) => {
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return '-';
  
  // Check for equal scores first
  if (result.teamA_score === result.teamB_score) {
    return 'Draw';
  }
  
  if (team === 'teamA') {
    return result.teamA_score > result.teamB_score ? 'Win' : 'Lose';
  }
  return result.teamB_score > result.teamA_score ? 'Win' : 'Lose';
};

const getResultClass = (match, team) => {
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return 'font-medium text-gray-500';
  
  // Check for equal scores first
  if (result.teamA_score === result.teamB_score) {
    return 'font-medium text-blue-600';
  }
  
  const resultText = getResult(match, team);
  return resultText === 'Win' ? 'font-bold text-green-600' : 'font-medium text-red-600';
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

const getCurrentDate = () => {
  const today = new Date();
  return today.toISOString().split('T')[0];
};

const formatDateTime = (date, time) => {
  if (!date || !time) return 'Not scheduled';
  return `${new Date(date).toLocaleDateString()} ${time}`;
};

// Winner modal functions
const canUpdateRound = (round) => {
  // Check if all matches in previous rounds are completed
  for (let r = 1; r < round; r++) {
    const previousRoundMatches = matchesByRound.value[r] || [];
    const allMatchesCompleted = previousRoundMatches.every(match => match.status === 'completed');
    if (!allMatchesCompleted) {
      return false;
    }
  }
  return true;
};

const openWinnerModal = (match) => {
  if (!canUpdateRound(match.round)) {
    return;
  }
  selectedMatch.value = match;
  isWinnerModalOpen.value = true;
  winnerFormData.value = { result: '' };
};

const closeWinnerModal = () => {
  isWinnerModalOpen.value = false;
  selectedMatch.value = null;
  winnerFormData.value = { result: '' };
};

const submitResult = () => {
  // Validate official name
  if (!scoreFormData.value.official_name.trim()) {
    signatureError.value = 'Please provide your official name';
    return;
  }

  // Validate signature
  const canvas = signatureCanvas.value;
  const ctx = canvas.getContext('2d');
  const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
  const pixels = imageData.data;
  
  // Check if canvas is empty
  const hasSignature = pixels.some(pixel => pixel !== 0);
  
  if (!hasSignature) {
    signatureError.value = 'Please provide a signature';
    return;
  }

  scoreLoading.value = true;

  // Prepare form data
  const formData = {
    sport_match_id: selectedMatch.value.id,
    teamA_score: winnerFormData.value.result === 'tie' ? 5 : winnerFormData.value.result === 'teamA' ? 10 : 5,
    teamB_score: winnerFormData.value.result === 'tie' ? 5 : winnerFormData.value.result === 'teamB' ? 10 : 5,
    is_draw: winnerFormData.value.result === 'tie',
    official_name: scoreFormData.value.official_name,
    signature: canvas.toDataURL('image/png')
  };

  // Only include winning/losing team IDs if it's not a tie
  if (!formData.is_draw) {
    formData.winning_team_id = winnerFormData.value.result === 'teamA' ? selectedMatch.value.teamA_id : selectedMatch.value.teamB_id;
    formData.losing_team_id = winnerFormData.value.result === 'teamA' ? selectedMatch.value.teamB_id : selectedMatch.value.teamA_id;
  }

  const form = useForm(formData);
  form.post(route('resultsRR.store'), {
    preserveScroll: true,
    onSuccess: () => {
      closeSignatureModal();
      scoreLoading.value = false;
      showSuccessModal.value = true;
      successMessage.value = 'Result submitted successfully!';
    },
    onError: (errors) => {
      console.error('Error submitting result:', errors);
      scoreLoading.value = false;
    }
  });
};
</script>