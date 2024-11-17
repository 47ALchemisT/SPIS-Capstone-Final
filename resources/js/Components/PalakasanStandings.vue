<template>
  <div class="space-y-6">
    <div class="rounded-lg">
      <h1 class="font-medium text-blue-700">Standings</h1>
      <div class="relative overflow-x-auto mt-3">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-blue-700 uppercase bg-blue-100">
            <tr>
              <th scope="col" class="px-6 py-3">Rank</th>
              <th scope="col" class="px-6 py-3">Team</th>
              <th scope="col" class="px-6 py-3">College</th>
              <th scope="col" class="px-6 py-3">Total Points</th>

            </tr>
          </thead>
          <tbody>
            <tr v-for="(team, index) in teamRankings" :key="team.id">
              <th scope="row" class="px-6 py-4 text-blue-700 whitespace-nowrap dark:text-white">
                {{ index + 1 }}
              </th>
              <td class="px-6 py-4 font-medium text-blue-700">
                {{ team.assigned_team_name }}
              </td>
              <td class="px-6 py-4">
                {{ team.college?.name }}
              </td>
              <td class="px-6 py-4">
                {{ team.totalPoints }} Points
              </td>
            </tr>
          </tbody>
        </table>
      </div>
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