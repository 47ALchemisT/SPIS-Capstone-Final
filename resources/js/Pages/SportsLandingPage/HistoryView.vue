<template>
    <Head :title="palakasan ? palakasan.theme : 'Palakasan History'" />
    <AppLayout :latestPalakasan="latestPalakasan">
      <template v-slot:default>
        <div v-if="palakasan" class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 lg:px-10 xl:px-12 py-8 space-y-6">
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl overflow-hidden">
                <div class="px-6 py-8 relative">
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <pattern id="pattern-circles" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                <circle cx="10" cy="10" r="2" fill="currentColor"/>
                            </pattern>
                            <rect x="0" y="0" width="100%" height="100%" fill="url(#pattern-circles)"/>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
                            <div class="flex-1">
                                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-2">
                                    {{ palakasan.theme }}
                                </h1>
                                <p class="text-blue-100 text-lg">{{ palakasan.tagline }}</p>
                            </div>
                            <div class="flex items-start">
                                <button
                                    @click="returnToPalakasan"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-white rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-200"
                                    >
                                    <i class="fa-solid fa-arrow-left mr-2"></i>
                                    Return
                                </button>
                            </div>
                        </div>
                        <div class="mt-6">
                            <p class="text-blue-100">{{ palakasan.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Teams Rankings Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                    <i class="fas fa-trophy text-yellow-400 mr-3"></i>
                    Team Rankings
                </h2>
                <div class="space-y-4">
                    <template v-if="sortedTeams.length > 0">
                        <div v-for="(team, index) in sortedTeams" :key="team.id" 
                             class="flex items-center sm:p-4 rounded-lg transition-all duration-200  hover:transform hover:scale-[1.01]">
                            <div class="flex items-center justify-center w-8 h-8 sm:w-12 sm:h-12 rounded-full mr-4 shadow-md"
                                 :class="{
                                     'bg-gradient-to-br from-yellow-300 to-yellow-500 text-white': index === 0,
                                     'bg-gradient-to-br from-gray-300 to-gray-400 text-white': index === 1,
                                     'bg-gradient-to-br from-orange-300 to-orange-400 text-white': index === 2,
                                     'bg-gradient-to-br from-blue-100 to-blue-200 text-blue-600': index > 2
                                 }">
                                <span class="text-lg font-bold">{{ index + 1 }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ team.assigned_team_name }}
                                </h3>
                                <p class="sm:block hidden text-sm text-gray-600 dark:text-gray-400">
                                    {{ team.college.name }}
                                </p>
                            </div>
                            <div class="text-right bg-blue-50 dark:bg-blue-900/30 px-4 py-2 rounded-lg">
                                <p class="text-md sm:text-lg font-medium text-blue-600 dark:text-blue-400">
                                    {{ team.totalPoints }} pts
                                </p>
                            </div>
                        </div>
                    </template>
                    <div v-else class="text-gray-600 dark:text-gray-400 text-center py-8">
                        No team data available
                    </div>
                </div>
            </div>
            <div v-if="assignedSports && assignedSports.length > 0" class="bg-white dark:bg-gray-800 rounded-xl sm:border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Header Section -->
                <div class="mb-1 sm:p-6  dark:border-gray-700">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                        Sports Events
                    </h2>
                    <div class="relative max-w-md">
                        <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Search sports events..."
                        class="w-full pl-10 pr-4 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition duration-300 ease-in-out shadow-sm"
                        />
                        <div class="absolute left-3 top-3 text-gray-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table View (hidden on mobile) -->
                <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sport Name</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Setup</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="sport in filteredSports" :key="sport.id" 
                        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ sport.sport.name }} {{ sport.categories }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ sport.setup }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ sport.type }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                        <button 
                            @click="viewSport(sport.id)"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                        >
                            View Details
                        </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>

                <!-- Mobile Card View (hidden on desktop) -->
                <div class="md:hidden">
                    <div v-for="sport in filteredSports" :key="sport.id" 
                        class="py-2">
                        <div class="space-y-4 bg-blue-50 p-4 rounded-md">
                            <div class="flex justify-between items-start">
                                <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ sport.sport.name }} {{ sport.categories }}
                                </h3>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                <p class="text-gray-500 dark:text-gray-400">Setup</p>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ sport.setup }}</p>
                                </div>
                                <div>
                                <p class="text-gray-500 dark:text-gray-400">Type</p>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ sport.type }}</p>
                                </div>
                            </div>

                            <button 
                                @click="viewSport(sport.id)"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                View Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- No Results Message -->
                <div v-if="filteredSports.length === 0" class="p-12 text-center">
                <div class="inline-block p-4 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-gray-500 dark:text-gray-400 text-lg">No sports found matching your search.</div>
                <button @click="searchQuery = ''" class="mt-4 text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300">
                    Clear search
                </button>
                </div>
            </div>

            <!-- No Sports Assigned Message -->
            <div v-else class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="inline-block p-4 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M12 20V4" />
                </svg>
                </div>
                <div class="text-gray-500 dark:text-gray-400 text-lg">No sports have been assigned to this Palakasan event.</div>
            </div>
        </div>
      </template>
    </AppLayout>
  </template>
  
  <script setup>
  import { Head, router } from '@inertiajs/vue3';
  import { ref, computed } from 'vue';
  import AppLayout from '@/Layouts/MainLayout.vue';
  
  const props = defineProps({
    palakasan: {
      type: Object,
      default: null
    },
    assignedSports: {
      type: Array,
      default: () => []
    },
    assignedTeams: {
      type: Array,
      default: () => [] 
    },
    latestPalakasan: {
      type: Object,
      default: null
    },
    overallWinner: {
      type: Object,
      default: null
    },
    overallResult: {
      type: Array,
      default: () => []
    },
    variationResult: {
      type: Array,
      default: () => []
    }
  });

  const returnToPalakasan = () => {
    router.visit(route('sports.history'));
    };
    
    const viewSport = (sportId) => {
        router.get(route('home-sportview.index', { sport: sportId }));
    };

    const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
  };
  
  const searchQuery = ref('');
  
  const calculateTeamPoints = (teamId) => {
    let total = 0;
    
    // Add points from overall results
    props.overallResult.forEach(result => {
        if (result.assigned_team_id === teamId) {
            total += parseInt(result.points) || 0;
        }
    });
    
    // Add points from variation results
    props.variationResult.forEach(result => {
        if (result.sport_variation_team_id === teamId) {
            total += parseInt(result.points) || 0;
        }
    });
    
    return total;
  };

  const sortedTeams = computed(() => {
    if (!props.assignedTeams) return [];
    
    const teamsWithPoints = props.assignedTeams.map(team => ({
        ...team,
        totalPoints: calculateTeamPoints(team.id)
    }));
    
    return teamsWithPoints.sort((a, b) => b.totalPoints - a.totalPoints);
  });

  const filteredSports = computed(() => {
    if (!searchQuery.value) return props.assignedSports;
    
    const query = searchQuery.value.toLowerCase();
    return props.assignedSports.filter(sport => 
      sport.sport.name.toLowerCase().includes(query) ||
      sport.categories.toLowerCase().includes(query) ||
      sport.setup.toLowerCase().includes(query) ||
      sport.type.toLowerCase().includes(query) ||
      sport.status.toLowerCase().includes(query)
    );
  });
  </script>
  
  <style scoped>
  .hover\:transform {
    transition: transform 0.2s ease-in-out;
  }
  
  .hover\:scale-\[1\.01\]:hover {
    transform: scale(1.01);
  }
  
  /* Fade in animation for cards */
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .bg-white {
    animation: fadeIn 0.3s ease-out;
  }
  
  /* Smooth transition for hover states */
  .transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
  }
  
  /* Subtle shadow transition */
  .shadow-lg {
    transition: box-shadow 0.2s ease-in-out;
  }
  
  .shadow-lg:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }
  </style>