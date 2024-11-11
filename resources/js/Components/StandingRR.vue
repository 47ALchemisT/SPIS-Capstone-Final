<template>
  <div class="w-full overflow-x-auto">
    <table class="min-w-full bg-white">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Played</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Won</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Drawn</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Lost</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-for="(standing, index) in sortedStandings" :key="standing.id">
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ index + 1 }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ getTeamName(standing.team_id) }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ standing.played }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ standing.won }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ standing.drawn }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ standing.lost }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ standing.points }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  standings: {
    type: Array,
    required: true
  },
  teams: {
    type: Array,
    required: true
  }
});

const sortedStandings = computed(() => {
  return [...props.standings].sort((a, b) => {
    if (b.won !== a.won) return b.won - a.won;
    if (b.drawn !== a.drawn) return b.drawn - a.drawn;
    return b.played - a.played;
  });
});

const getTeamName = (teamId) => {
  const team = props.teams.find(t => t.id === teamId);
  return team ? team.assigned_team_name : 'Unknown Team';
};
</script>