<template>
  <div class="space-y-6">
    <div class="rounded-xl">
      <div class="h-[50vh] min-h-[300px] w-full">
        <Bar
          :data="chartData"
          :options="chartOptions"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
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

const isSmallScreen = ref(false);

onMounted(() => {
  checkScreenSize();
  window.addEventListener('resize', checkScreenSize);
});

const checkScreenSize = () => {
  isSmallScreen.value = window.innerWidth < 768;
};

const teamRankings = computed(() => {
  const rankings = props.teams.map(team => {
    const teamOverallResults = props.overallResult.filter(
      result => result.assigned_team_id === team.id
    );
    const overallPoints = teamOverallResults.reduce((sum, result) => sum + (result.points || 0), 0);
    

    const totalPoints = overallPoints;
    
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
  labels: teamRankings.value.map(team => {
    const name = team.assigned_team_name;
    return isSmallScreen.value && name.length > 10 
      ? `${name.slice(0, 10)}...` 
      : name;
  }),
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
      barThickness: isSmallScreen.value ? 30 : 60,
      maxBarThickness: isSmallScreen.value ? 32 : 64
    }
  ]
}));

const chartOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  layout: {
    padding: {
      top: isSmallScreen.value ? 10 : 20,
      right: isSmallScreen.value ? 10 : 20,
      bottom: isSmallScreen.value ? 10 : 20,
      left: isSmallScreen.value ? 10 : 20
    }
  },
  plugins: {
    legend: {
      display: true,
      position: 'top',
      labels: {
        font: {
          family: "'Inter', sans-serif",
          size: isSmallScreen.value ? 10 : 12
        },
        color: '#6b7280',
        padding: isSmallScreen.value ? 4 : 8
      }
    },
    tooltip: {
      backgroundColor: 'white',
      titleColor: '#1f2937',
      bodyColor: '#4b5563',
      titleFont: {
        size: isSmallScreen.value ? 12 : 14,
        weight: '600',
        family: "'Inter', sans-serif"
      },
      bodyFont: {
        size: isSmallScreen.value ? 11 : 13,
        family: "'Inter', sans-serif"
      },
      padding: isSmallScreen.value ? 8 : 12,
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
          size: isSmallScreen.value ? 10 : 12
        },
        color: '#6b7280',
        padding: isSmallScreen.value ? 4 : 8
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
          size: isSmallScreen.value ? 10 : 12
        },
        color: '#6b7280',
        padding: isSmallScreen.value ? 4 : 8,
        maxRotation: isSmallScreen.value ? 45 : 0,
        minRotation: isSmallScreen.value ? 45 : 0
      }
    }
  },
  animation: {
    duration: 750,
    easing: 'easeInOutQuart'
  },
  barPercentage: isSmallScreen.value ? 0.8 : 0.9,
  categoryPercentage: isSmallScreen.value ? 0.8 : 0.9
}));
</script>