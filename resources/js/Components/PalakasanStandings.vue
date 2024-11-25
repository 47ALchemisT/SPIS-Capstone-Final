<template>
  <div class="bg-white rounded-xl shadow-sm space-y-4">
    <div class="flex items-center justify-between">
      <h2 class="text-lg font-semibold text-gray-700">Tournament Standings</h2>
      <div class="text-sm text-gray-500">Total Teams: {{ teamRankings.length }}</div>
    </div>
    
    <div class="relative overflow-hidden rounded-lg border border-gray-300">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-gray-50 ">
            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Rank</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Team</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">College</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Points</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="(team, index) in teamRankings" 
              :key="team.id"
              class="hover:bg-blue-50/50 transition-colors duration-150 ease-in-out">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <span :class="[
                  'flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold',
                  index < 3 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'
                ]">
                  {{ index + 1 }}
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="font-medium text-gray-900">{{ team.assigned_team_name }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
              {{ team.college?.name }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center space-x-2">
                <span class="font-semibold text-blue-700">{{ team.totalPoints }}</span>
                <span class="text-gray-500 text-sm">pts</span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  teams: {
    type: Array,
    required: true,
    default: () => []
  },
  overallResult: {
    type: Array,
    required: true,
    default: () => []
  },
  variationResult: {
    type: Array,
    required: true,
    default: () => []
  }
});

const teamRankings = computed(() => {
  if (!props.teams || !props.overallResult || !props.variationResult) {
    return [];
  }

  return props.teams.map(team => {
    const overallPoints = props.overallResult
      .filter(result => result?.assigned_team_id === team?.id)
      .reduce((sum, result) => sum + (result?.points || 0), 0);

    const variationPoints = props.variationResult
      .filter(result => result?.sport_variation_team_id === team?.id)
      .reduce((sum, result) => sum + (result?.points || 0), 0);

    const totalPoints = overallPoints + variationPoints;

    return {
      ...team,
      overallPoints,
      variationPoints,
      totalPoints
    };
  }).sort((a, b) => b.totalPoints - a.totalPoints);
});
</script>