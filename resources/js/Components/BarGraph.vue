<template>
  <div class="space-y-6">
    <div class="rounded-xl">
      <div class="h-96 w-full">
        <Bar
          :data="chartData"
          :options="chartOptions"
        />
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
      backgroundColor: function(context) {
        const chart = context.chart;
        const {ctx} = chart;
        return generateGradient(ctx, 'rgba(29, 78, 216, 0.9)', 'rgba(29, 78, 216, 0.2)');
      },
      borderRadius: 8,
      borderSkipped: false,
      barThickness: 60,
      maxBarThickness: 64
    }
  ]
}));

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