<template>
    <div class="game-schedule">
      <Toast ref="toastRef" />
      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div v-for="match in sortedMatches" 
          :key="match.id" 
          class="bg-white rounded-lg p-4 border border-gray-300 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
          <div class="flex justify-between items-center mb-3">
            <div class="flex flex-col">
              <span class="font-semibold text-md group-hover:text-blue-600">{{ match.game }}</span>
              <div class=" text-xs flex justify-between gap-1.5 items-center">
                <span :class="getStatusClass(match.status)">Status : {{ formatStatus(match.status) }}</span>
              </div>          
            </div>
            <div>
                <button 
                    @click="openScoreModal(match)"
                    type="button" 
                    class="p-2 text-sm font-medium text-gray-600 focus:outline-none hover:text-blue-800 rounded-lg focus:z-10 focus:ring-4 focus:ring-gray-100 transition duration-200"
                    :disabled="match.status === 'completed'"
                    >
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20"><path fill="currentColor" d="M3.005 14.176A3 3 0 0 0 6 17h2.003q.01-.171.054-.347L8.22 16H6l-.15-.005A2 2 0 0 1 4 14v-4h3l.176-.005A3 3 0 0 0 10 7V4h4l.15.005A2 2 0 0 1 16 6v2.003a2.9 2.9 0 0 1 1 .13V6l-.005-.176A3 3 0 0 0 14 3H9.621l-.176.008a2 2 0 0 0-1.238.578L3.586 8.207l-.12.13A2 2 0 0 0 3 9.62V14zM7 9l-2.782-.001q.034-.045.075-.085l4.621-4.621L9 4.219V7l-.005.15A2 2 0 0 1 7 9m2.98 5.377l4.83-4.83a1.87 1.87 0 1 1 2.644 2.646l-4.83 4.829a2.2 2.2 0 0 1-1.02.578l-1.498.374a.89.89 0 0 1-1.079-1.078l.375-1.498a2.2 2.2 0 0 1 .578-1.02"/></svg>
                </button>
            </div>
          </div>
  
          <div class="grid grid-cols-4 gap-3 items-center">
            <div class=" col-span-2 flex justify-center bg-gray-100 p-2 rounded-md items-center group-hover:bg-blue-200 transition ">
              <span class="font-medium mr-3">{{ getTeamName(match.teamA_id) }}</span>
              <span :class="getScoreClass(match, 'teamA')">{{ getScore(match, 'teamA') }}</span>
            </div>
            <div class=" col-span-2 flex justify-center bg-gray-100 p-2 rounded-md items-center group-hover:bg-blue-200 transition ">
              <span :class="getScoreClass(match, 'teamB')">{{ getScore(match, 'teamB') }}</span>
              <span class="font-medium ml-3">{{ getTeamName(match.teamB_id) }}</span>
            </div>
          </div>
  
          <div class="flex items-center mt-4 gap-2.5">
            <div class="text-xs text-gray-600 ">
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
    </div>

    <div v-if="showScoreModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg relative w-96">
        <!-- Modal header -->
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
                {{ getTeamName(selectedMatch.teamA_id) }}
                <input 
                  v-model="scoreFormData.teamA_score"
                  type="number"
                  min="0"
                  required
                  class="border px-3 py-2 mt-2 rounded-lg border-gray-300 w-full"
                />
              </label>
            </div>

            <div class="mb-4">
              <label class="block mb-3 font-medium text-sm">
                {{ getTeamName(selectedMatch.teamB_id) }}
                <input 
                  v-model="scoreFormData.teamB_score"
                  type="number"
                  min="0"
                  required
                  class="border mt-2 rounded-lg border-gray-300 px-3 py-2 w-full"
                />
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
                Next: Signature
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

     <!-- Signature Modal -->
     <div v-if="showSignatureModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg relative w-[500px]">
        <!-- Modal header -->
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
          <!-- Official Name Input -->
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
  </template>
  
  <script setup>
  import { ref, computed, watch, nextTick, onMounted } from 'vue';
  import { useForm, router } from '@inertiajs/vue3';
  import Toast from '@/Components/Toast.vue';  // Adjust the import path as needed

    
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
    }
  
  });

  const showScoreModal = ref(false);
  const scoreLoading = ref(false);
    const scoreError = ref('');
    const selectedMatch = ref(null);
    const signatureCanvas = ref(null);

    // State for modals
const showSignatureModal = ref(false);
const signatureError = ref('');
const isDrawing = ref(false);
const lastX = ref(0);
const lastY = ref(0);

const toastRef = ref(null);


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
  // Optionally reopen score modal if needed
  // showScoreModal.value = true;
  signatureError.value = '';
};


const getTeamName = (teamId) => {
    // First, check if teamId is null or undefined
    if (!teamId) return 'TBD';

    // Then handle the winner prefix case
    if (typeof teamId === 'string' && teamId.startsWith('winner:')) {
        const [, round, game] = teamId.split(':');
        return `Winner of R${round} G${game}`;
    }

    // Ensure props.teams exists and is an array before finding
    if (props.teams && Array.isArray(props.teams)) {
        const team = props.teams.find(t => t.id === teamId);
        return team ? team.assigned_team_name : 'Unknown';
    }

    return 'Unknown';
};

const groupedMatches = computed(() => {
    const rounds = {};
    const sportMatches = props.matches.filter(match => match.assigned_sport_id === props.sport.id);
    
    sportMatches.forEach(match => {
        const round = match.round || 1;
        if (!rounds[round]) {
            rounds[round] = [];
        }
        rounds[round].push(match);
    });
    
    Object.values(rounds).forEach(round => {
        round.sort((a, b) => {
            const gameA = parseInt(a.game.replace('Game ', ''));
            const gameB = parseInt(b.game.replace('Game ', ''));
            return gameA - gameB;
        });
    });
    
    return Object.values(rounds);
});

const sortedMatches = computed(() => {
    return props.matches
        .filter(match => match && match.id)  // Filter out invalid matches
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
  // Remove the check for winner: prefix, as it might be preventing updates for deciding matches
  return match.teamA_id && match.teamB_id;
};

const scoreFormData = ref({
    sport_match_id: '',
    teamA_score: '',
    teamB_score: '',
    winning_team_id: '',
    losing_team_id: '',
    official_name: '',
    signature: '',
});

const getMatchResult = (matchId) => {
    return props.results.find(result => result.sport_match_id === matchId);
};

const openScoreModal = (match) => {
  console.log('Opening score modal for match:', match); // Add this line for debugging
  if (canUpdateMatch(match)) {
    selectedMatch.value = match;
    const existingResult = getMatchResult(match.id);
    
    scoreFormData.value = {
      sport_match_id: match.id,
      teamA_score: existingResult ? existingResult.teamA_score : '',
      teamB_score: existingResult ? existingResult.teamB_score : '',
      winning_team_id: '',
      losing_team_id: '',
      signature: ''
    };
    
    showScoreModal.value = true;
  } else {
    console.log('Cannot update this match:', match); // Add this line for debugging
  }
};

const closeScoreModal = () => {
  showScoreModal.value = false;
  showSignatureModal.value = false;
  
  // Reset all relevant states
  selectedMatch.value = null;
  scoreFormData.value = {
    sport_match_id: '',
    teamA_score: '',
    teamB_score: '',
    winning_team_id: '',
    losing_team_id: '',
    official_name: '',
    signature: ''
  };
  scoreError.value = '';
  signatureError.value = '';
  
  // Clear signature canvas
  if (signatureCanvas.value) {
    const ctx = signatureCanvas.value.getContext('2d');
    ctx.clearRect(0, 0, signatureCanvas.value.width, signatureCanvas.value.height);
  }
};

const submitScore = async () => {
  // Validate official name
  if (!scoreFormData.value.official_name.trim()) {
    signatureError.value = 'Please enter your official name';
    return;
  }

  // Validate signature
  const canvas = signatureCanvas.value;
  const signatureData = canvas.toDataURL('image/png');
  
  // Check if signature is just a blank canvas
  const blankCanvas = document.createElement('canvas');
  blankCanvas.width = canvas.width;
  blankCanvas.height = canvas.height;
  
  if (signatureData === blankCanvas.toDataURL('image/png')) {
    signatureError.value = 'Please provide a signature';
    return;
  }

  // Set signature in form data
  scoreFormData.value.signature = signatureData;

  // Determine winning and losing teams
  const { teamA_score, teamB_score } = scoreFormData.value;
  const teamAId = selectedMatch.value.teamA_id;
  const teamBId = selectedMatch.value.teamB_id;
  
  scoreFormData.value.winning_team_id = teamA_score > teamB_score ? teamAId : teamBId;
  scoreFormData.value.losing_team_id = teamA_score > teamB_score ? teamBId : teamAId;

  // Submit score
  scoreLoading.value = true;
  signatureError.value = '';

  try {
    await router.post(route('results.store'), scoreFormData.value, {
      onSuccess: () => {
        console.log('Score updated successfully');
        // Ensure both modals are closed
        showSignatureModal.value = false;
        showScoreModal.value = false;
        
        // Reset form and selected match
        closeScoreModal();
        
        scoreLoading.value = false;
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
      return scoreA > scoreB ? 'font-bold text-green-600' : 'font-medium';
    }
    return scoreB > scoreA ? 'font-bold text-green-600' : 'font-medium';
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
  
  
  // Configure canvas for drawing
watch(signatureCanvas, (canvas) => {
  if (canvas) {
    const ctx = canvas.getContext('2d');
    ctx.strokeStyle = 'black';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
  }
});
onMounted(() => {
    console.log('Matches:', props.matches);
});
  </script>