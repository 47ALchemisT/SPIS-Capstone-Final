<template>
  <div class="game-schedule">
    <h2 class="text-md font-medium mt-1 mb-4">Match Schedule</h2>
    <div v-for="(roundMatches, round) in matchesByRound" :key="round" class="mb-8 p-5 border rounded-lg border-gray-200 bg-gray-50">
      <h3 class="text-md font-semibold mb-4">Round {{ round }}</h3>
      <div class="flex justify-center gap-4 ">
        <div v-for="match in roundMatches" 
            :key="match.id" 
            class="bg-white shadow rounded-lg p-4 border border-gray-200 hover:shadow-md transition-all duration-200">
          <div class="flex justify-between items-center mb-3">
            <div class="flex flex-col">
              <span class="font-semibold text-md">{{ match.game }}</span>
            </div>
            <div class="flex">
              <button 
                @click="openWinnerModal(match)"
                type="button" 
                class="p-2 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
              >
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2m0 16H3V5h18M5 7h4c.6 0 1 .4 1 1v8c0 .6-.4 1-1 1H5c-.6 0-1-.4-1-1V8c0-.6.4-1 1-1m1 2v6h2V9m7-2h4c.6 0 1 .4 1 1v8c0 .6-.4 1-1 1h-4c-.6 0-1-.4-1-1V8c0-.6.4-1 1-1m1 2v6h2V9m-6 2c.6 0 1-.4 1-1s-.4-1-1-1s-1 .4-1 1s.4 1 1 1m0 4c.6 0 1-.4 1-1s-.4-1-1-1s-1 .4-1 1s.4 1 1 1"/></svg>                
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
            <form class="space-y-4" @submit.prevent="declareWinner">
              <div>
                <label for="result" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Result</label>
                <select v-model="winnerFormData.result" id="result" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                  <option value="">Choose a result</option>
                  <option value="teamA">{{ getTeamName(selectedMatch?.teamA_id) }} Wins</option>
                  <option value="teamB">{{ getTeamName(selectedMatch?.teamB_id) }} Wins</option>
                  <option value="tie">Tie</option>
                </select>
              </div>
              <div v-if="form.errors.length" class="mt-2 text-sm text-red-600">
                <div v-for="(error, index) in form.errors" :key="index">{{ error }}</div>
              </div>
              <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit Result</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
  
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

const isWinnerModalOpen = ref(false);
const selectedMatch = ref(null);
const winnerFormData = ref({
  result: '',
});
  
const isTimeModalOpen = ref(false);
const selectedDate = ref('');
const selectedTime = ref('');
const formError = ref('');
const availableTimeSlots = ref([]);

const form = useForm({
  matchId: '',
  date: '',
  time: '',
});

const timeSlots = [
  { value: '6:00 AM', label: '6:00 AM' },
  { value: '6:30 AM', label: '6:30 AM' },
  { value: '7:00 AM', label: '7:00 AM' },
  { value: '7:30 AM', label: '7:30 AM' },
  { value: '8:00 AM', label: '8:00 AM' },
  { value: '8:30 AM', label: '8:30 AM' },
  { value: '09:00  AM', label: '9:00 AM' },
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

// Replace getScore and getScoreClass with new result-based functions
const getResult = (match, team) => {
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return '-';
  
  if (result.teamA_score === result.teamB_score) return 'TIE';
  
  if (team === 'teamA') {
    return result.teamA_score > result.teamB_score ? 'WIN' : 'LOSE';
  }
  return result.teamB_score > result.teamA_score ? 'WIN' : 'LOSE';
};

const getResultClass = (match, team) => {
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return 'font-medium text-gray-500';
  
  if (result.teamA_score === result.teamB_score) return 'font-medium text-blue-600';
  
  const resultText = getResult(match, team);
  return resultText === 'WIN' ? 'font-bold text-green-600' : 'font-medium text-red-600';
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

const updateAvailableTimeSlots = () => {
  if (!selectedDate.value || !selectedMatch.value) return;

  const conflictingRecords = props.venueRecords.filter(record => {
    return record.date === selectedDate.value && 
           record.venue_id === selectedMatch.value.match_venue_id &&
           record.match_id !== selectedMatch.value.id; // Exclude current match when editing
  });

  const bookedTimes = new Set(conflictingRecords.map(record => record.time));

  availableTimeSlots.value = timeSlots.map(slot => ({
    ...slot,
    disabled: bookedTimes.has(slot.value)
  }));

  // If editing existing match, make its current time slot available
  if (selectedMatch.value.time && selectedMatch.value.date === selectedDate.value) {
    const currentTimeSlot = availableTimeSlots.value.find(slot => 
      slot.value === selectedMatch.value.time
    );
    if (currentTimeSlot) {
      currentTimeSlot.disabled = false;
    }
  }

  console.log('Booked times:', Array.from(bookedTimes));
  console.log('Available slots:', availableTimeSlots.value);
};

const openTimeModal = (match) => {
  selectedMatch.value = match;
  selectedDate.value = match.date || '';
  selectedTime.value = match.time || '';
  formError.value = '';
  isTimeModalOpen.value = true;
  updateAvailableTimeSlots();
};

const closeTimeModal = () => {
  isTimeModalOpen.value = false;
  selectedMatch.value = null;
  selectedDate.value = '';
  selectedTime.value = '';
  formError.value = '';
  availableTimeSlots.value = [];
};

const saveDateTime = () => {
  if (!selectedDate.value || !selectedTime.value) {
    formError.value = 'Please select date and time';
    return;
  }

  const isTimeSlotAvailable = availableTimeSlots.value.find(
    slot => slot.value === selectedTime.value && !slot.disabled
  );

  if (!isTimeSlotAvailable) {
    formError.value = 'Selected time slot is not available';
    return;
  }

  form.matchId = selectedMatch.value.id;
  form.date = selectedDate.value;
  form.time = selectedTime.value;

  form.post(route('matches.updateDateTimeRR'), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      selectedMatch.value.date = selectedDate.value;
      selectedMatch.value.time = selectedTime.value;
      closeTimeModal();
    },
    onError: (errors) => {
      formError.value = Object.values(errors)[0];
    }
  });
};

const openWinnerModal = (match) => {
  selectedMatch.value = match;
  isWinnerModalOpen.value = true;
  winnerFormData.value = { result: '' };
};

const closeWinnerModal = () => {
  isWinnerModalOpen.value = false;
  selectedMatch.value = null;
  winnerFormData.value = { result: '' };
};

const declareWinner = () => {
  if (!selectedMatch.value) return;

  const formData = {
    sport_match_id: selectedMatch.value.id,
    teamA_score: 0,
    teamB_score: 0,
    winning_team_id: null,
    losing_team_id: null,
    is_draw: false,
  };

  if (winnerFormData.value.result === 'teamA') {
    formData.teamA_score = 10;
    formData.teamB_score = 5;
    formData.winning_team_id = selectedMatch.value.teamA_id;
    formData.losing_team_id = selectedMatch.value.teamB_id;
  } else if (winnerFormData.value.result === 'teamB') {
    formData.teamA_score = 5;
    formData.teamB_score = 10;
    formData.winning_team_id = selectedMatch.value.teamB_id;
    formData.losing_team_id = selectedMatch.value.teamA_id;
  } else if (winnerFormData.value.result === 'tie') {
    formData.teamA_score = 5;
    formData.teamB_score = 5;
    formData.is_draw = true;
  } else {
    console.error('Invalid result selected');
    return;
  }

  const form = useForm(formData);
  form.post(route('resultsRR.store'), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      closeWinnerModal();
    },
    onError: (errors) => {
      console.error('Error submitting result:', errors);
    }
  });
};
</script>