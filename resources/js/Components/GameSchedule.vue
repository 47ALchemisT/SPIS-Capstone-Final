<template>
  <div class="game-schedule">
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="match in sortedMatches" 
        :key="match.id" 
        :class="[
          'rounded-lg p-4 border transition-all duration-200',
          match.status === 'completed' ? 'bg-blue-100 border-blue-300' : 'bg-white border-gray-200 hover:shadow-md'
        ]">
        <div class="flex justify-between items-center mb-3">
          <div class="flex flex-col">
            <span class="font-semibold text-md">{{ match.game }}</span>
            <div class=" text-xs flex justify-between gap-1.5 items-center">
              <span :class="getStatusClass(match.status)">Status : {{ formatStatus(match.status) }}</span>
            </div>          
          </div>
          <div class="flex items-center gap-2">

            <button 
              @click="openTimeModal(match)" 
              type="button" 
              class="p-2 text-sm font-medium text-gray-900 focus:outline-none rounded-lg  hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
              :disabled="match.status === 'completed'"
            >
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M21 12a9 9 0 1 0-9.972 8.948q.48.051.972.052"/>
                <path d="M12 7v5l2 2m4.42 1.61a2.1 2.1 0 0 1 2.97 2.97L18 22h-3v-3z"/></g>
              </svg>
            </button>
          </div>
        </div>

        <div class="grid grid-cols-4 gap-3 items-center">
          <div class=" col-span-2 flex justify-center p-2 rounded-md items-center"
            :class="[
              match.status === 'completed' ? {
                'bg-green-100 text-green-700': getScore(match, 'teamA') > getScore(match, 'teamB'),
                'bg-red-100 text-red-700': getScore(match, 'teamA') < getScore(match, 'teamB')
              } : 'bg-gray-100'
            ]">
            <span class="font-medium mr-3">{{ getTeamName(match.teamA_id) }}</span>
            <span :class="getScoreClass(match, 'teamA')">{{ getScore(match, 'teamA') }}</span>
          </div>
          <div class=" col-span-2 flex justify-center p-2 rounded-md items-center"
            :class="[
              match.status === 'completed' ? {
                'bg-green-100 text-green-700': getScore(match, 'teamB') > getScore(match, 'teamA'),
                'bg-red-100 text-red-700': getScore(match, 'teamB') < getScore(match, 'teamA')
              } : 'bg-gray-100'
            ]">
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

    <!-- Updated Modal -->
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
                    Schedule match
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

                  <!-- Time Selection Type -->
                  <div class="mb-4">
                    <div class="grid grid-cols-2 gap-3">
                      <div class="border border-gray-300 p-2 rounded-md">
                        <input type="radio" id="selectTime" value="select" v-model="timeSelectionType" />
                        <label for="selectTime" class="ml-2">Select time</label>
                      </div>
                      <div class="border border-gray-300 p-2 rounded-md">
                        <input type="radio" id="manualTime" value="manual" v-model="timeSelectionType" />
                        <label for="manualTime" class="ml-2">Custom time</label>
                      </div>
                    </div>
                  </div>

                  <!-- Time Selection -->
                  <ul v-if="timeSelectionType === 'select'" id="timetable" class="grid w-full grid-cols-4 gap-2 mb-5">
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

                  <!-- Custom Time Selection -->
                  <div v-if="timeSelectionType === 'manual'" class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                      Custom Time
                    </label>
                    <input
                      type="time"
                      v-model="customTime"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>

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
                      {{ isProcessing ? 'Saving...' : 'Save' }}
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
import { ref, computed, watch } from 'vue';
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
const customTime = ref(''); // New reactive variable for custom time
const isProcessing = ref(false);
const isTimeModalOpen = ref(false);
const selectedMatch = ref(null);
const selectedDate = ref('');
const selectedTime = ref('');
const previousTime = ref(''); // Track previous time selection
const selectedVenue = ref('');
const formError = ref('');
const timeSelectionType = ref('select'); // Default to selecting from available times
const availableTimeSlots = ref([]);

const form = useForm({
  matchId: '',
  date: '',
  time: '',
  venue_id: ''
});

const timeSlots = [
  { value: '6:00 AM', label: '6:00 AM' },
  { value: '6:30 AM', label: '6:30 AM' },
  { value: '7:00 AM', label: '7:00 AM' },
  { value: '7:30 AM', label: '7:30 AM' },
  { value: '8:00 AM', label: '8:00 AM' },
  { value: '8:30 AM', label: '8:30 AM' },
  { value: '9:00 AM', label: '9:00 AM' },
  { value: '9:30 AM', label: '9:30 AM' },
  { value: '10:00 AM', label: '10:00 AM' },
  { value: '10:30 AM', label: '10:30 AM' },
  { value: '11:00 AM', label: '11:00 AM' },
  { value: '11:30 AM', label: '11:30 AM' },
  { value: '12:00 PM', label: '12:00 PM' },
  { value: '1:00 PM', label: '1:00 PM' },
  { value: '1:30 PM', label: '1:30 PM' },
  { value: '2:00 PM', label: '2:00 PM' },
  { value: '2:30 PM', label: '2:30 PM' },
  { value: '3:00 PM', label: '3:00 PM' },
  { value: '3:30 PM', label: '3:30 PM' },
  { value: '4:00 PM', label: '4:00 PM' },
  { value: '4:30 PM', label: '4:30 PM' },
  { value: '5:00 PM', label: '5:00 PM' },
  { value: '5:30 PM', label: '5:30 PM' },
  { value: '6:00 PM', label: '6:00 PM' }
];

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

const formatStatus = (status) => {
  if (!status) return '';
  return status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
};

const formatDateTime = (date, time) => {
  if (!date || !time) return 'Not scheduled';
  return `${new Date(date).toLocaleDateString()} ${time}`;
};

const updateAvailableTimeSlots = () => {
  if (!selectedDate.value || !selectedMatch.value || !selectedVenue.value) return;

  // Store current time as previous before potential changes
  if (selectedTime.value && selectedTime.value !== previousTime.value) {
    previousTime.value = selectedTime.value;
  }

  const conflictingRecords = props.venueRecords.filter(record => {
    return record.date === selectedDate.value && 
           record.venue_id === selectedVenue.value &&
           record.match_id !== selectedMatch.value.id;
  });

  const bookedTimes = new Set(conflictingRecords.map(record => record.time));

  // Remove both current and previous selections from booked times
  if (selectedTime.value) {
    bookedTimes.delete(selectedTime.value);
  }
  if (previousTime.value) {
    bookedTimes.delete(previousTime.value);
  }

  availableTimeSlots.value = timeSlots.map(slot => ({
    ...slot,
    disabled: bookedTimes.has(slot.value)
  }));

  // If editing existing match and it's the same date and venue, make its current time slot available
  if (selectedMatch.value.time && 
      selectedMatch.value.date === selectedDate.value && 
      selectedMatch.value.match_venue_id === selectedVenue.value) {
    const currentTimeSlot = availableTimeSlots.value.find(slot => 
      slot.value === selectedMatch.value.time
    );
    if (currentTimeSlot) {
      currentTimeSlot.disabled = false;
    }
  }
};

const openTimeModal = (match) => {
  selectedMatch.value = match;
  selectedDate.value = match.date || '';
  selectedTime.value = match.time || '';
  previousTime.value = match.time || ''; // Initialize previous time
  selectedVenue.value = match.match_venue_id || '';
  isTimeModalOpen.value = true;
  formError.value = '';
  updateAvailableTimeSlots();
};

const closeTimeModal = () => {
  isTimeModalOpen.value = false;
  selectedMatch.value = null;
  selectedDate.value = '';
  selectedTime.value = '';
  previousTime.value = ''; // Reset previous time
  selectedVenue.value = '';
  customTime.value = '';
  formError.value = '';
  availableTimeSlots.value = [];
};

const formatTo12Hour = (time) => {
  const [hours, minutes] = time.split(':');
  const hour = parseInt(hours);
  const period = hour >= 12 ? 'PM' : 'AM';
  const formattedHour = hour % 12 || 12; // Convert 0 to 12 for 12 AM
  return `${formattedHour}:${minutes} ${period}`;
};

const saveDateTime = () => {
  if (!selectedDate.value || (!selectedTime.value && !customTime.value) || !selectedVenue.value) {
    formError.value = 'Please select a date, time, and venue';
    return;
  }

  let finalTime;

  if (timeSelectionType.value === 'manual') {
    if (!customTime.value) {
      formError.value = 'Please enter a valid custom time';
      return;
    }
    finalTime = formatTo12Hour(customTime.value); // Format the custom time
  } else {
    finalTime = selectedTime.value; // Use the selected time
  }

  // Check if the final time is already booked
  const isTimeBooked = props.venueRecords.some(record => 
    record.date === selectedDate.value && 
    record.venue_id === selectedVenue.value && 
    record.time === finalTime
  );

  if (isTimeBooked) {
    formError.value = 'The selected time is already booked. Please choose a different time.';
    return;
  }

  form.matchId = selectedMatch.value.id;
  form.date = selectedDate.value;
  form.time = finalTime; // Use formatted custom time if provided
  form.venue_id = selectedVenue.value;

  isProcessing.value = true;
  form.put(route('matches.updateDateTime'), {
    onSuccess: () => {
      closeTimeModal();
      showSuccessModal.value = true;
      successMessage.value = 'Match time set successfully!';
      isProcessing.value = false;
    },
    onError: (errors) => {
      formError.value = Object.values(errors)[0];
      isProcessing.value = false;
    },
  });
};



const sortedMatches = computed(() => {
  if (!props.matches) return [];
  return [...props.matches].sort((a, b) => {
    const getGameNumber = (game) => {
      const number = parseInt(game.match(/\d+/));
      return isNaN(number) ? 0 : number;
    };
    
    const gameA = getGameNumber(a.game);
    const gameB = getGameNumber(b.game);
    
    if (gameA !== gameB) {return gameA - gameB;
    }
    
    if (a.date && b.date) {
      const dateCompare = new Date(a.date) - new Date(b.date);
      if (dateCompare !== 0) return dateCompare;
    }
    
    return a.round - b.round;
  });
});

watch([selectedDate, selectedMatch, selectedVenue, selectedTime], updateAvailableTimeSlots);
</script>