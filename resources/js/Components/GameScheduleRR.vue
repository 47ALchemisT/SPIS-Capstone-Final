<template>
  <div class="game-schedule">
    <div v-for="(roundMatches, round) in matchesByRound" :key="round" 
      :class="[
        'mb-8 p-5 rounded-lg',
        isRoundCompleted(roundMatches) ? 'bg-blue-50' : 'bg-gray-50'
      ]">
      <h3 class="text-md font-semibold mb-4">Round {{ round }}</h3>
      <!--the button for setting schedule should be here not in each match-->
      <div class="flex justify-center gap-4 ">
        <div v-for="match in roundMatches" 
          :key="match.id" 
          :class="[
            'shadow rounded-lg p-4 border transition-all duration-200',
            match.status === 'completed' ? 'bg-blue-100 border-blue-300' : 'bg-white border-gray-200 hover:shadow-md'
          ]">
          <div class="flex justify-between items-center mb-3">
            <div class="flex flex-col">
              <span class="font-semibold text-md">{{ match.game }}</span>
            </div>
            <div class="flex">
              <button 
                @click="openTimeModal(round)" 
                type="button" 
                class="p-2 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                :disabled="isRoundCompleted(roundMatches)"
              >
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M21 12a9 9 0 1 0-9.972 8.948q.48.051.972.052"/><path d="M12 7v5l2 2m4.42 1.61a2.1 2.1 0 0 1 2.97 2.97L18 22h-3v-3z"/></g></svg>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-4 gap-3 items-center">
            <div class="col-span-2 flex justify-center p-2 rounded-md items-center"
              :class="[ 
                match.status === 'completed' ? {
                  'bg-green-100 text-green-700': getScore(match, 'teamA') > getScore(match, 'teamB'),
                  'bg-red-100 text-red-700': getScore(match, 'teamA') < getScore(match, 'teamB')
                } : 'bg-gray-100'
              ]">
              <span class="font-medium mr-3">{{ getTeamName(match.teamA_id) }}</span>
              <span v-if="match.status === 'completed'" class="text-sm font-medium">
                {{ getScore(match, 'teamA') > getScore(match, 'teamB') ? 'Win' : 'Lose' }}
              </span>
            </div>
            <div class="col-span-2 flex justify-center p-2 rounded-md items-center"
              :class="[
                match.status === 'completed' ? {
                  'bg-green-100 text-green-700': getScore(match, 'teamB') > getScore(match, 'teamA'),
                  'bg-red-100 text-red-700': getScore(match, 'teamB') < getScore(match, 'teamA')
                } : 'bg-gray-100'
              ]">
              <span v-if="match.status === 'completed'" class="text-sm font-medium">
                {{ getScore(match, 'teamB') > getScore(match, 'teamA') ? 'Win' : 'Lose' }}
              </span>
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
                <span>{{ getVenueName(roundMatches[0].match_venue_id) }}</span>
              </div>
            </div>
            <p class="text-xs text-gray-400">|</p>
            <div class="text-xs text-gray-600">
              <div class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span :class="{'text-red-500': !roundMatches[0].date || !roundMatches[0].time}">
                  {{ formatDateTime(roundMatches[0].date, roundMatches[0].time) }}
                </span>
              </div>
            </div>
            <p class="text-xs text-gray-400">|</p>
            <div class="text-xs flex justify-between gap-1.5 items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0m5 0v.01m4-.01v.01m4-.01v.01"/></svg>            
              <span :class="getStatusClass(roundMatches[0].status)">Status: {{ roundMatches[0].status }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Winner Modal -->
    <TransitionRoot as="template" :show="isWinnerModalOpen">
      <Dialog as="div" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @close="closeWinnerModal">
        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
          <DialogPanel class="relative p-4 w-full max-w-md max-h-full">
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
                  <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit Result</button>
                </form>
              </div>
            </div>
          </DialogPanel>
        </TransitionChild>
      </Dialog>
    </TransitionRoot>

    <!-- Time Modal -->
    <TransitionRoot appear :show="isTimeModalOpen" as="template">
      <Dialog as="div" @close="closeTimeModal" class="relative z-50">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="relative w-full max-w-[30rem] transform overflow-hidden rounded-lg bg-white text-left align-middle shadow-xl transition-all">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b">
                  <DialogTitle as="h3" class="text-lg font-semibold text-gray-900">
                    Schedule round
                  </DialogTitle>
                  <button @click="closeTimeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                  </button>
                </div>

                <!-- Modal body -->
                <div class="p-4 pt-0">
                  <div class="text-xs p-3 bg-blue-50 text-blue-700 rounded-lg border mb-3 border-blue-400">
                    <h1 class="font-semibold mb-1">Note</h1>
                    <p>All available times can be selected. Times that are not available will be grayed out. This is first come first serve, so make sure to set the time and venue correctly.</p>
                  </div>

                  <!-- Venue Selection -->
                  <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                      Select Venue
                    </label>
                    <select
                      v-model="selectedVenue"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      @change="updateAvailableTimeSlots"
                    >
                      <option value="">Select a venue</option>
                      <option v-for="venue in props.venues" :key="venue.id" :value="venue.id">
                        {{ venue.name }}
                      </option>
                    </select>
                  </div>

                  <!-- Date Selection -->
                  <label class="block text-sm font-medium text-gray-900 mb-2">
                    Pick the Date and Time
                  </label>
                  <div class="mx-auto sm:mx-0 flex justify-center mb-5">
                    <input
                      type="date"
                      v-model="selectedDate"
                      :min="getCurrentDate()"
                      @change="updateAvailableTimeSlots"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>

                  <!-- Time Selection -->
                  <ul id="timetable" class="grid w-full grid-cols-4 gap-2 mb-5">
                    <li v-for="slot in availableTimeSlots" :key="slot.value">
                      <input 
                        type="radio" 
                        :id="slot.value" 
                        :value="slot.value" 
                        v-model="selectedTime" 
                        class="hidden peer" 
                        name="timetable"
                        :disabled="slot.disabled"
                      >
                      <label :for="slot.value"
                        :class="[
                          'inline-flex items-center justify-center w-full px-2 py-1.5 text-sm font-medium text-center',
                          slot.disabled ? 'cursor-not-allowed bg-gray-100 text-gray-400 rounded-md' : 'hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border rounded-lg cursor-pointer text-gray-500 border-gray-300 dark:border-gray-700 dark:peer-checked:border-blue-500 peer-checked:border-blue-700 dark:hover:border-gray-600 dark:peer-checked:text-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-600 dark:peer-checked:bg-blue-900'
                        ]"
                      >
                        {{ slot.label }}
                      </label>
                    </li>
                  </ul>

                  <div v-if="formError" class="mb-4 text-red-500 text-sm">{{ formError }}</div>

                  <!-- Action Buttons -->
                  <div class="grid grid-cols-2 gap-2">
                    <button 
                      @click="closeTimeModal" 
                      type="button" 
                      :disabled="isProcessing"
                      class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      Discard
                    </button>
                    <button 
                      @click="saveDateTime" 
                      type="button"
                      :disabled="isProcessing"
                      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                      <svg v-if="isProcessing" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <span>Save</span>
                    </button>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
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
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue';
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

const isWinnerModalOpen = ref(false);
const selectedMatch = ref(null);
const winnerFormData = ref({
  result: '',
});

const isTimeModalOpen = ref(false);
const selectedDate = ref('');
const selectedTime = ref('');
const selectedVenue = ref('');
const formError = ref('');
const availableTimeSlots = ref([]);
const isProcessing = ref(false);

const form = useForm({
  matchId: '',
  date: '',
  time: '',
  venue_id: '',
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

const getCurrentDate = () => {
  const today = new Date();
  return today.toISOString().split('T')[0];
};

const formatDateTime = (date, time) => {
  if (!date || !time) return 'Not scheduled';
  return `${new Date(date).toLocaleDateString()} ${time}`;
};

const updateAvailableTimeSlots = () => {
  if (!selectedDate.value || !selectedRound.value || !selectedVenue.value) return;

  const conflictingRecords = props.venueRecords.filter(record => {
    return record.date === selectedDate.value && 
           record.venue_id === selectedVenue.value &&
           record.round === selectedRound.value;
  });

  const bookedTimes = new Set(conflictingRecords.map(record => record.time));

  availableTimeSlots.value = timeSlots.map(slot => ({
    ...slot,
    disabled: bookedTimes.has(slot.value)
  }));

  // If editing existing round and it's the same date and venue, make its current time slot available
  if (selectedRound.value && 
      selectedDate.value === props.matches.find(match => match.round === selectedRound.value).date && 
      selectedVenue.value === props.matches.find(match => match.round === selectedRound.value).match_venue_id) {
    const currentTimeSlot = availableTimeSlots.value.find(slot => 
      slot.value === props.matches.find(match => match.round === selectedRound.value).time
    );
    if (currentTimeSlot) {
      currentTimeSlot.disabled = false;
    }
  }
};

const openTimeModal = (round) => {
  selectedRound.value = round;
  selectedDate.value = props.matches.find(match => match.round === round).date || '';
  selectedTime.value = props.matches.find(match => match.round === round).time || '';
  selectedVenue.value = props.matches.find(match => match.round === round).match_venue_id || '';
  formError.value = '';
  isTimeModalOpen.value = true;
  updateAvailableTimeSlots();
};

const closeTimeModal = () => {
  isTimeModalOpen.value = false;
  selectedRound.value = null;
  selectedDate.value = '';
  selectedTime.value = '';
  selectedVenue.value = '';
  formError.value = '';
  availableTimeSlots.value = [];
};

const saveDateTime = () => {
  if (!selectedDate.value || !selectedTime.value || !selectedVenue.value) {
    formError.value = 'Please select date, time, and venue';
    return;
  }

  const selectedSlot = availableTimeSlots.value.find(slot => slot.value === selectedTime.value);
  if (!selectedSlot || selectedSlot.disabled) {
    formError.value = 'Selected time slot is not available';
    return;
  }

  isProcessing.value = true;
  formError.value = '';

  // Update all matches in the round with the selected date, time, and venue
  const roundMatches = matchesByRound.value[selectedRound.value];
  roundMatches.forEach(match => {
    match.date = selectedDate.value;
    match.time = selectedTime.value;
    match.match_venue_id = selectedVenue.value;
  });

  // Optionally, save to backend if needed
  form.post(route('matches.updateDateTimeRR'), {
    preserveScroll: true,
    onSuccess: () => {
      closeTimeModal();
      showSuccessModal.value = true;
      successMessage.value = 'Round time set successfully!';
      isProcessing.value = false;
    },
    onError: (errors) => {
      formError.value = Object.values(errors)[0];
      isProcessing.value = false;
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

const isRoundCompleted = (matches) => {
  return matches.every(match => match.status === 'completed');
};

const selectedRound = ref(null);
</script>