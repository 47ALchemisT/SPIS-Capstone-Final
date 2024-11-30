<template>
  <div class="game-schedule">
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="match in sortedMatches" 
        :key="match.id" 
        class="rounded-lg p-4 border hover:shadow-md transition-all duration-200"
        :class="match.status === 'completed' ? 'bg-green-50 border-green-300' : 'bg-white border-gray-300'">
        <div class="flex justify-between items-center mb-3">
          <div class="flex flex-col">
            <span class="font-semibold text-md">{{ match.game }}</span>
            <div class=" text-xs flex justify-between gap-1.5 items-center">
              <span :class="getStatusClass(match.status)">Status : {{ formatStatus(match.status) }}</span>
            </div>          
          </div>
        </div>

        <div class="grid grid-cols-4 gap-3 items-center">
          <div class=" col-span-2 flex justify-center p-2 rounded-md items-center"
            :class="match.status === 'completed' ? {
              'bg-green-100': isWinner(match, 'teamA'),
              'bg-red-100': !isWinner(match, 'teamA')
            } : 'bg-gray-100'">
            <span class="font-medium mr-3"
              :class="{'text-green-700': match.status === 'completed' && isWinner(match, 'teamA'), 'text-red-600': match.status === 'completed' && !isWinner(match, 'teamA')}">
              {{ getTeamName(match.teamA_id) }}
            </span>
            <span :class="getScoreClass(match, 'teamA')">{{ getScore(match, 'teamA') }}</span>
          </div>
          <div class=" col-span-2 flex justify-center p-2 rounded-md items-center"
            :class="match.status === 'completed' ? {
              'bg-green-100': isWinner(match, 'teamB'),
              'bg-red-100': !isWinner(match, 'teamB')
            } : 'bg-gray-100'">
            <span :class="getScoreClass(match, 'teamB')">{{ getScore(match, 'teamB') }}</span>
            <span class="font-medium ml-3"
              :class="{'text-green-700': match.status === 'completed' && isWinner(match, 'teamB'), 'text-red-600': match.status === 'completed' && !isWinner(match, 'teamB')}">
              {{ getTeamName(match.teamB_id) }}
            </span>
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
</template>

<script setup>
import { ref, computed, watch } from 'vue';
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
  
const isTimeModalOpen = ref(false);
const selectedMatch = ref(null);
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
  if (match.status !== 'completed') return 'font-medium text-gray-500';
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return 'font-medium text-gray-500';
  
  const isTeamWinner = team === 'teamA' 
    ? parseInt(result.teamA_score) > parseInt(result.teamB_score)
    : parseInt(result.teamB_score) > parseInt(result.teamA_score);
  
  return isTeamWinner ? 'font-bold text-green-700' : 'font-medium text-red-600';
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
  if (!selectedDate.value || !selectedMatch.value) return;

  const conflictingRecords = props.venueRecords.filter(record => {
    return record.date === selectedDate.value && 
           record.venue_id === selectedMatch.value.match_venue_id &&
           record.match_id !== selectedMatch.value.id;
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
};

const isWinner = (match, team) => {
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return false;
  
  if (team === 'teamA') {
    return parseInt(result.teamA_score) > parseInt(result.teamB_score);
  }
  return parseInt(result.teamB_score) > parseInt(result.teamA_score);
};

const getResult = (match, team) => {
  if (match.status !== 'completed') return '-';
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return '-';
  
  if (team === 'teamA') {
    return parseInt(result.teamA_score) > parseInt(result.teamB_score) ? 'WIN' : 'LOSS';
  }
  return parseInt(result.teamB_score) > parseInt(result.teamA_score) ? 'WIN' : 'LOSS';
};

const getResultClass = (match, team) => {
  if (match.status !== 'completed') return 'font-medium text-gray-500';
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return 'font-medium text-gray-500';
  
  const isTeamWinner = team === 'teamA' 
    ? parseInt(result.teamA_score) > parseInt(result.teamB_score)
    : parseInt(result.teamB_score) > parseInt(result.teamA_score);
  
  return isTeamWinner ? 'font-bold text-green-700' : 'font-medium text-red-600';
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

  const selectedSlot = availableTimeSlots.value.find(slot => slot.value === selectedTime.value);
  if (!selectedSlot || selectedSlot.disabled) {
    formError.value = 'Selected time slot is not available';
    return;
  }

  form.matchId = selectedMatch.value.id;
  form.date = selectedDate.value;
  form.time = selectedTime.value;

  form.post(route('matches.updateDateTime'), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: (response) => {
      selectedMatch.value.date = selectedDate.value;
      selectedMatch.value.time = selectedTime.value;
      closeTimeModal();
    },
    onError: (errors) => {
      formError.value = Object.values(errors)[0];
    }
  });
};

const sortedMatches = computed(() => {
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

watch(selectedDate, updateAvailableTimeSlots);
watch(selectedMatch, updateAvailableTimeSlots);
</script>