<template>
  <div class="w-full">
    <div class="rounded-lg my-4 bg-blue-50 overflow-hidden">
      <!-- Header -->
      <div class="px-6 pt-4">
        <div class="flex items-center justify-between gap-2">
          <h3 v-if="progressPercentage < 100" class="text-lg font-semibold text-gray-900">Initial Rankings</h3>
          <h3 v-else class="text-lg font-semibold text-gray-900">Final Rankings</h3>
          <button
            type="button"
            @click="openRankingModal"
            :disabled="progressPercentage < 100"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Submit Rankings
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto p-4">
        <table class="min-w-full">
          <thead class="bg-blue-100">
            <tr>
              <th scope="col" class="px-6 py-3 text-center text-sm font-medium text-blue-700 tracking-wider w-20">
                Rank
              </th>
              <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-blue-700 tracking-wider">
                Team
              </th>
              <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-blue-700 tracking-wider">
                College
              </th>
              <th scope="col" class="px-6 py-3 text-center text-sm font-medium text-blue-700 tracking-wider">
                Accumulated Points
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(entry, index) in sortedTeams"
              :key="entry.teamId"
              class="hover:bg-blue-100 transition-colors duration-150"
            >
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <div
                  v-if="index + 1 <= 3"
                  :class="[
                    'inline-flex items-center justify-center h-6 w-6 rounded-full font-medium',
                    index + 1 === 1 ? 'bg-yellow-100 text-yellow-700' :
                    index + 1 === 2 ? 'bg-gray-100 text-gray-700' :
                    'bg-orange-100 text-orange-700'
                  ]"
                >
                  {{ index + 1 }}
                </div>
                <span v-else>{{ index + 1 }}</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ entry.teamName }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-500">{{ entry.collegeName }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                {{ entry.points }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3'; 

const props = defineProps({
  variationMatches: {
    type: Array,
    required: true
  },
  progressPercentage: {
    type: Array,
    required: true,
  },
  pointsType: {
    type: Array,
    required: true,
  },

});

const showRankingModal = ref(false)
const openRankingModal = () => {
  showRankingModal.value = true;
};
const closeRankingModal = () => {
  showRankingModal.value = false;
};
// Calculate accumulated points with separated team and college information
const accumulatedPoints = computed(() => {
  return props.variationMatches.reduce((acc, match) => {
    const team = match.assigned_team_variation_i_d;
    const teamId = team?.id || `unknown-${Math.random()}`; // Unique identifier for each team
    const teamName = team?.assigned_team_name || 'Unknown Team';
    const collegeName = team?.college?.name || 'Unknown College';
    const points = match.points || 0;

    if (!acc[teamId]) {
      acc[teamId] = {
        teamId,
        teamName,
        collegeName,
        points: 0
      };
    }

    acc[teamId].points += points;
    return acc;
  }, {});
});

// Sort teams by points and convert to array for easier template rendering
const sortedTeams = computed(() => {
  return Object.values(accumulatedPoints.value)
    .sort((a, b) => b.points - a.points);
});
const form = useForm({
  // Add any necessary fields here, e.g.:
  rankings: [] // Assuming you want to send rankings as an array
});
//submit rankings
const isSubmittingRankings = ref(false);
const rankingsSubmitted = ref(false);

//

//
const submitRankings = () => {
  const rankings = sortedTeams.value.map((team, index) => ({
    assigned_sport_id: team.assignedSportId,
    assigned_team_id: team.teamId,
    rank: index + 1,
    points: props.pointsType[index]?.points || 0, // Fallback to 0 if points are undefined
  }));

  // Check if rankings is empty
  if (rankings.length === 0) {
    console.error('No rankings to submit');
    return; // Prevent submission if there are no rankings
  }

  console.log('Rankings to be submitted:', rankings); // Log for debugging

  // Post the rankings data
  form.post(route('overall-results.store'), {
    preserveScroll: true,
    data: {
      rankings: rankings, // Include the rankings array
    },
    onSuccess: () => {
      closeRankingModal();
    },
    onError: () => {
      console.error('Error submitting rankings:', form.errors);
    },
  });
};
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>