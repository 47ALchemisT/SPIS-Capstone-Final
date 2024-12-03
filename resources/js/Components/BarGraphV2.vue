<template>
  <div class="flex flex-col md:flex-row gap-6">
    <!-- Left side: Top 3 Teams -->
    <div class="md:w-1/3 bg-white p-6">
      <h3 class="text-md font-semibold text-gray-900 mb-4">Top Teams</h3>
      <div class="space-y-3">
        <template v-if="topThreeTeams.length">
          <div v-for="(team, index) in topThreeTeams" :key="team.id" 
              class="flex p-3 bg-blue-50 rounded-lg">
            <div :class="{
              'w-8 h-8 flex items-center justify-center rounded-full text-white font-bold mr-3': true,
              'bg-yellow-400': index === 0,
              'bg-gray-400': index === 1,
              'bg-amber-600': index === 2
            }">
              {{ index + 1 }}
            </div>
            <div class="flex-1">
              <h4 class="font-medium text-blue-900">{{ team.assigned_team_name }}</h4>
              <p class="text-xs text-blue-500/80">{{ team.college?.name }}</p>
              <p class="text-sm mt-2 font-medium text-blue-600">{{ team.totalPoints }} points</p>
            </div>
          </div>
        </template>
        <p v-else class="text-gray-500 text-sm">No team rankings available</p>
      </div>
    </div>

    <!-- Right side: Bar Graph -->
    <div class="md:w-2/3">
      <div class="rounded-xl">
        <div class="h-96 w-full">
          <Bar
            :data="chartData"
            :options="chartOptions"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);

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
  const rankings = props.teams.map(team => {
    const teamOverallResults = props.overallResult.filter(
      result => result.assigned_team_id === team.id
    );
    const overallPoints = teamOverallResults.reduce((sum, result) => sum + (result.points || 0), 0);
    
    const teamVariationResults = props.variationResult.filter(
      result => result.sport_variation_team_id === team.id
    );
    const variationPoints = teamVariationResults.reduce((sum, result) => sum + (result.points || 0), 0);
    
    const totalPoints = overallPoints + variationPoints;
    
    return {
      ...team,
      totalPoints
    };
  });
  
  return rankings.sort((a, b) => b.totalPoints - a.totalPoints);
});

const generateGradient = (ctx, colorStart, colorEnd) => {
  if (!ctx) return null;
  const gradient = ctx.createLinearGradient(0, 0, 0, 400);
  gradient.addColorStop(0, colorStart);
  gradient.addColorStop(1, colorEnd);
  return gradient;
};

const chartData = computed(() => ({
  labels: teamRankings.value.map(team => team.assigned_team_name),
  datasets: [
    {
      label: 'Overall Points',
      data: teamRankings.value.map(team => team.totalPoints),
      backgroundColor: 'rgba(29, 78, 216, 0.9)',
      borderRadius: 8,
      borderSkipped: false,
      barThickness: 60,
      maxBarThickness: 64
    }
  ]
}));

const topThreeTeams = computed(() => {
  return teamRankings.value.slice(0, 3);
});

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  layout: {
    padding: {
      top: 20,
      right: 20,
      bottom: 20,
      left: 20
    }
  },
  plugins: {
    legend: {
      display: true,
      position: 'top',
      labels: {
        font: {
          family: "'Inter', sans-serif",
          size: 12
        },
        color: '#6b7280'
      }
    },
    tooltip: {
      backgroundColor: 'white',
      titleColor: '#1f2937',
      bodyColor: '#4b5563',
      titleFont: {
        size: 14,
        weight: '600',
        family: "'Inter', sans-serif"
      },
      bodyFont: {
        size: 13,
        family: "'Inter', sans-serif"
      },
      padding: 12,
      borderColor: 'rgb(229, 231, 235)',
      borderWidth: 1,
      displayColors: false,
      callbacks: {
        title: (items) => items[0].label,
        label: (context) => `Total Points: ${context.raw.toLocaleString()}`
      }
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        display: true,
        color: 'rgba(0, 0, 0, 0.05)',
        drawBorder: false
      },
      border: {
        display: false
      },
      ticks: {
        font: {
          family: "'Inter', sans-serif",
          size: 12
        },
        color: '#6b7280',
        padding: 8
      }
    },
    x: {
      grid: {
        display: false
      },
      border: {
        display: false
      },
      ticks: {
        font: {
          family: "'Inter', sans-serif",
          size: 12
        },
        color: '#6b7280',
        padding: 8,
      }
    }
  },
  animation: {
    duration: 750,
    easing: 'easeInOutQuart'
  },
  barPercentage: 0.9,
  categoryPercentage: 0.9
};
</script>