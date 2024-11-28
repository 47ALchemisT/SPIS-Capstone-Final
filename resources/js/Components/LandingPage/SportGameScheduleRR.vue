<template>
  <div class="game-schedule w-full">
    <div v-for="(roundMatches, round) in matchesByRound" :key="round" class="mb-8 p-3 sm:p-5 rounded-lg bg-gray-50">
      <h3 class="text-md font-semibold mb-4 px-2">Round {{ round }}</h3>
      <div class="flex flex-col sm:flex-row sm:flex-wrap justify-start gap-4">
        <div v-for="match in roundMatches" 
            :key="match.id" 
            class="flex-grow sm:flex-grow-0 sm:basis-[calc(50%-1rem)] lg:basis-[calc(33.33%-1rem)] xl:basis-[calc(25%-1rem)] bg-white shadow rounded-lg p-3 sm:p-4 border border-gray-200 hover:shadow-md transition-all duration-200">
          <div class="flex justify-between items-center mb-3">
            <div class="flex flex-col">
              <span class="font-semibold text-sm sm:text-md">{{ match.game }}</span>
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3 items-center">
            <div class="col-span-1 sm:col-span-2 flex justify-center bg-gray-100 p-2 rounded-md items-center">
              <span class="font-medium text-sm mr-3 truncate max-w-[120px]">{{ getTeamName(match.teamA_id) }}</span>
              <span :class="getScoreClass(match, 'teamA')">{{ getScore(match, 'teamA') }}</span>
            </div>
            <div class="col-span-1 sm:col-span-2 flex justify-center bg-gray-100 p-2 rounded-md items-center">
              <span :class="getScoreClass(match, 'teamB')">{{ getScore(match, 'teamB') }}</span>
              <span class="font-medium text-sm ml-3 truncate max-w-[120px]">{{ getTeamName(match.teamB_id) }}</span>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row items-start sm:items-center mt-4 gap-2 sm:gap-2.5 text-xs">
            <div class="flex items-center gap-1.5 text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span class="truncate max-w-[150px]">{{ getVenueName(match.match_venue_id) }}</span>
            </div>
            <p class="hidden sm:block text-gray-400">|</p>
            <div class="flex items-center gap-1.5 text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span :class="{'text-red-500': !match.date || !match.time}">
                {{ formatDateTime(match.date, match.time) }}
              </span>
            </div>
            <p class="hidden sm:block text-gray-400">|</p>
            <div class="flex items-center gap-1.5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0m5 0v.01m4-.01v.01m4-.01v.01"/></svg>            
              <span :class="getStatusClass(match.status)">Status: {{ match.status }}</span>
            </div>
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
</script>