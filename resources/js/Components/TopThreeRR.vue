<template>
    <div class="bg-green-50 border border-green-400 rounded-lg p-6 mt-4">
      <h2 class="text-2xl font-bold text-center text-gray-800">Game Winner</h2>
      <h2 class="text-sm mb-4 text-center text-gray-600">Top 3 Teams</h2>
      <div class="space-y-4">
        <div v-for="(team, index) in topThree" :key="team.id" class="flex items-center justify-between">
          <div class="flex items-center">
            <div :class="[
              'w-8 h-8 rounded-full flex items-center justify-center mr-4 text-white font-bold',
              index === 0 ? 'bg-yellow-400' : index === 1 ? 'bg-gray-400' : 'bg-orange-400'
            ]">
              {{ index + 1 }}
            </div>
            <span class="text-lg font-semibold text-gray-700">{{ getTeamName(team.team_id) }}</span>
          </div>
          <div class="text-sm text-gray-600">
            <span class="font-medium">W: {{ team.won }}</span>
            <span class="mx-2">|</span>
            <span class="font-medium">D: {{ team.drawn }}</span>
            <span class="mx-2">|</span>
            <span class="font-medium">L: {{ team.lost }}</span>
          </div>
        </div>
      </div>
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
  
  const topThree = computed(() => {
    return [...props.standings]
      .sort((a, b) => {
        if (b.won !== a.won) return b.won - a.won;
        if (b.drawn !== a.drawn) return b.drawn - a.drawn;
        return a.lost - b.lost;
      })
      .slice(0, 3);
  });
  
  const getTeamName = (teamId) => {
    const team = props.teams.find(t => t.id === teamId);
    return team ? team.assigned_team_name : 'Unknown Team';
  };
  </script>