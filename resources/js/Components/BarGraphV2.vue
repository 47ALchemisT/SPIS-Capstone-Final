<template>
  <div class="flex flex-col gap-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
      <h2 class="text-xl font-semibold text-gray-900">Team Rankings</h2>
      <div class="flex items-center space-x-4">
        <button 
          @click="toggleView"
          class="px-3 py-1.5 text-sm font-medium rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
        >
          {{ isCompact ? 'Show All' : 'Compact View' }}
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col lg:flex-row gap-6">
      <!-- Left side: Top Teams -->
      <div class="lg:w-1/3 space-y-4">
        <div class="bg-white ">
          <h3 class="text-md font-semibold text-gray-900 mb-4">Top Performers</h3>
          <div class="space-y-3">
            <template v-if="topThreeTeams.length">
              <div 
                v-for="(team, index) in topThreeTeams" 
                :key="team.id"
                class="flex  p-3 rounded-lg bg-gradient-to-r"
                :class="[
                  index === 0 ? 'from-blue-50 to-blue-100' : 
                  index === 1 ? 'from-blue-50 to-blue-100/80' : 
                  'from-blue-50 to-blue-100/60'
                ]"
              >
                <div 
                  class="w-10 h-10 flex items-center justify-center rounded-full text-white font-bold mr-4 bg-gradient-to-br"
                  :class="[
                    index === 0 ? 'from-blue-500 to-blue-600' : 
                    index === 1 ? 'from-blue-400 to-blue-500' : 
                    'from-blue-300 to-blue-400'
                  ]"
                >
                  {{ index + 1 }}
                </div>
                <div class="flex-1">
                  <h4 class="font-medium text-gray-900">{{ team.assigned_team_name }}</h4>
                  <p class="text-sm text-gray-500">{{ team.college?.name }}</p>
                  <div class="flex items-center mt-2">
                    <span class="text-sm font-semibold text-blue-600">
                      {{ team.totalPoints.toLocaleString() }} points
                    </span>
                  </div>
                </div>
              </div>
            </template>
            <p v-else class="text-gray-500 text-sm">No team rankings available</p>
          </div>
        </div>
      </div>

      <!-- Right side: Enhanced Bar Graph -->
      <div class="lg:w-2/3">
        <div class="bg-white">
          <div :class="['h-[400px] w-full', { 'h-[300px]': isCompact }]">
            <Bar
              :data="chartData"
              :options="chartOptions"
              ref="chart"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
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

});

const chart = ref(null);
const isCompact = ref(false);
const toggleView = () => {
  isCompact.value = !isCompact.value;
};

const teamRankings = computed(() => {
  const rankings = props.teams.map(team => {
    const overallPoints = props.overallResult
      .filter(result => result.assigned_team_id === team.id)
      .reduce((sum, result) => sum + (result.points || 0), 0);
    

    return {
      ...team,
      totalPoints: overallPoints 
    };
  });
  
  return rankings.sort((a, b) => b.totalPoints - a.totalPoints);
});

const topThreeTeams = computed(() => teamRankings.value.slice(0, 3));

const getGradient = (context) => {
  const chart = context.chart;
  const {ctx, chartArea} = chart;
  
  if (!chartArea) {
    return null;
  }
  
  const gradientFill = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
  gradientFill.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
  gradientFill.addColorStop(1, 'rgba(37, 99, 235, 0.8)');
  
  return gradientFill;
};

const chartData = computed(() => ({
  labels: teamRankings.value
    .slice(0, isCompact.value ? 5 : undefined)
    .map(team => team.assigned_team_name),
  datasets: [{
    label: 'Total Points',
    data: teamRankings.value
      .slice(0, isCompact.value ? 5 : undefined)
      .map(team => team.totalPoints),
    backgroundColor: function(context) {
      return getGradient(context);
    },
    borderRadius: 8,
    borderSkipped: false,
    barThickness: isCompact.value ? 80 : 60,
    maxBarThickness: 64
  }]
}));

const chartOptions = computed(() => ({
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
      display: false
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
        label: (context) => {
          const value = context.raw;
          return `${value.toLocaleString()} points`;
        }
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
        padding: 8,
        callback: function(value) {
          return value.toLocaleString();
        }
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
}));
</script>