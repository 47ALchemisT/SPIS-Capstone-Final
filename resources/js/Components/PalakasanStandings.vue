<template>
  <div class="bg-white rounded-xl">
    <!-- Header section with improved spacing and styling -->
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-800">Tournament Standings</h2>
      <div class="px-4 py-1.5 bg-blue-50 rounded-full text-sm font-medium text-blue-600">
        {{ teamRankings.length }} Teams
      </div>
    </div>
    
    <!-- Table with improved styling -->
    <div class="relative overflow-hidden rounded-xl border border-gray-200">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rank</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Team</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">College</th>
            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Points</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="(team, index) in teamRankings" 
              :key="team.id"
              class="hover:bg-blue-50/40 transition-colors duration-200 ease-in-out">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <span :class="[
                  'flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold',
                  index === 0 ? 'bg-yellow-100 text-yellow-700' : 
                  index === 1 ? 'bg-gray-100 text-gray-600' :
                  index === 2 ? 'bg-amber-100 text-amber-700' :
                  'bg-gray-50 text-gray-500'
                ]">
                  {{ index + 1 }}
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="font-semibold text-gray-900">{{ team.assigned_team_name }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 inline-block">
                {{ team.college?.name }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center space-x-2">
                <span class="text-lg font-bold text-blue-600">{{ team.totalPoints }}</span>
                <span class="text-gray-400 text-sm">points</span>
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