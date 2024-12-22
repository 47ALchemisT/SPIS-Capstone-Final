<template>
  <AppLayout :facilitator="facilitator">
    <Head title="Facilitator Dashboard" />
    <div class="container">
      <!-- Tabs Navigation -->
      <div class="sticky top-[4rem] sm:py-3 z-40 bg-white/95 backdrop-blur-md">
        <nav
          class="flex flex-col sm:flex-row relative justify-between items-start sm:items-center space-y-4 sm:space-y-0"
        >
          <!-- Title and Description -->
          <div class="sm:block hidden px-1">
            <h1 class="text-xl font-semibold text-gray-800">Facilitator Dashboard</h1>
            <p class="text-sm text-gray-500 hidden sm:block">
              Manage your sports and activities
            </p>
          </div>

          <!-- Tabs Navigation for Larger Screens -->
          <div class="hidden sm:flex w-full sm:w-auto">
            <div class="flex gap-1 p-1.5 bg-blue-100/80 rounded-xl flex-wrap">
              <button
                v-for="tab in ['home', 'sports', 'Overview', 'settings']"
                :key="tab"
                @click="activeTab = tab"
                :class="[
                  'px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg',
                  activeTab === tab
                    ? 'text-blue-700 bg-white shadow-sm'
                    : 'text-blue-400 hover:text-blue-600 hover:bg-blue-100',
                ]"
              >
                <div class="flex items-center gap-2">
                  <!-- Icons for each tab -->
                  <i
                    :class="[
                      'fas',
                      {
                        'fa-home': tab === 'home',
                        'fa-running': tab === 'sports',
                        'fa-chart-bar': tab === 'Overview',
                        'fa-cog': tab === 'settings',
                      },
                    ]"
                  ></i>
                  <span>{{ tab.charAt(0).toUpperCase() + tab.slice(1) }}</span>
                </div>
              </button>
            </div>
          </div>

          <!-- Custom Dropdown Navigation for Mobile View -->
          <div class="sm:hidden w-full relative">
            <button
              @click="dropdownOpen = !dropdownOpen"
              class="w-full flex justify-between items-center mb-2 px-4 py-2.5 text-sm bg-white border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:outline-none"
            >
              <div class="flex items-center gap-2">
                <i
                  :class="[
                    'fas',
                    {
                      'fa-home': activeTab === 'home',
                      'fa-running': activeTab === 'sports',
                      'fa-chart-bar': activeTab === 'Overview',
                      'fa-cog': activeTab === 'settings',
                    },
                  ]"
                ></i>
                <span>{{ activeTab.charAt(0).toUpperCase() + activeTab.slice(1) }}</span>
              </div>
              <i class="fas fa-chevron-down text-gray-500"></i>
            </button>

            <ul
              v-if="dropdownOpen"
              class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg"
            >
              <li
                v-for="tab in ['home', 'sports', 'Overview', 'settings']"
                :key="tab"
                @click="activeTab = tab; dropdownOpen = false"
                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-blue-100 cursor-pointer"
              >
                <i
                  :class="[
                    'fas',
                    {
                      'fa-home': tab === 'home',
                      'fa-running': tab === 'sports',
                      'fa-chart-bar': tab === 'Overview',
                      'fa-cog': tab === 'settings',
                    },
                  ]"
                ></i>
                <span>{{ tab.charAt(0).toUpperCase() + tab.slice(1) }}</span>
              </li>
            </ul>
          </div>
        </nav>
      </div>

      <!-- Tabs Content -->
      <div>
        <div v-if="activeTab === 'home'">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-5">
            <!-- Welcome Card -->
            <div class="bg-white rounded-xl transition-all duration-300">
              <div class="flex flex-col h-full justify-between">
                <div class="space-y-4">
                  <div class="space-y-2">
                    <h2 class="text-2xl font-semibold text-gray-800">
                      Welcome back, <span class="text-blue-600">{{ facilitator.student.first_name }}</span>!
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                      Manage your sports activities and track your progress through the Facilitator Dashboard
                    </p>
                  </div>
                  
                  <!-- Quick Stats -->
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 mt-6">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                      <div class="flex gap-3">
                        <div class="">
                          <i class="fas fa-trophy text-blue-600 py-3 px-2.5 bg-blue-100 rounded-lg"></i>
                        </div>
                        <div>
                          <p class="text-sm text-blue-600">Assigned Sports</p>
                          <p class="text-2xl font-bold text-blue-700">{{ assignedSports.length }}</p>
                        </div>
                      </div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-300">
                      <div class="flex gap-3">
                        <div class="">
                          <i class="fas fa-check text-green-600 py-3 px-3 bg-green-100 rounded-lg"></i>
                        </div>
                        <div>
                          <p class="text-sm text-green-600">Completed</p>
                          <p class="text-2xl font-bold text-green-700">{{ completedSports.length }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- Task List Card -->
            <div class="bg-white rounded-xl p-4 sm:p-8 shadow-sm border border-gray-100 hover:border-blue-200 transition-all duration-300">
              <div class="flex flex-col h-full">
                <div class="flex items-center justify-between mb-6">
                  <div>
                    <h2 class="text-xl font-bold text-gray-800">Task List</h2>
                    <p class="text-sm text-gray-600">Track your assigned sports</p>
                  </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-6">
                  <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-700">Overall Progress</h3>
                    <span class="text-sm font-medium text-blue-600">{{ progressPercentage }}%</span>
                  </div>
                  <div class="w-full bg-gray-100 rounded-full h-3">
                    <div 
                      class="bg-blue-600 h-3 rounded-full transition-all duration-500 ease-out"
                      :style="{ width: `${progressPercentage}%` }"
                    ></div>
                  </div>
                  <p class="text-xs text-gray-500 mt-2">
                    {{ completedSports.length }} of {{ assignedSports.length }} tasks completed
                  </p>
                </div>

                <!-- Tasks List -->
                <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-gray-50">
                  <div class="space-y-2">
                    <div 
                      v-for="sport in assignedSports" 
                      :key="sport.id" 
                      class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200 "
                    >
                      <div 
                        class="w-3 h-3 rounded-full mr-4"
                        :class="{
                          'bg-green-500': sport.status === 'completed',
                          'bg-blue-400': sport.status === 'Ongoing'
                        }"
                      ></div>
                      <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">
                          {{ sport.sport.name }} {{ sport.categories }}
                        </p>
                        <p class="text-xs text-gray-500">{{ sport.setup }}</p>
                      </div>
                      <span 
                        class="text-xs px-2 py-1 rounded-full capitalize"
                        :class="{
                          'bg-green-100 text-green-700': sport.status === 'completed',
                          'bg-blue-100 text-blue-700': sport.status === 'Ongoing'
                        }"
                      >
                        {{ sport.status }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-if="activeTab === 'sports'" class="pt-3 pb-6 sm:py-6">
          <!-- Header Section -->
          <div class="mb-4 sm:mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Assigned Sports</h2>
            <p class="text-gray-600">Manage and view your assigned sports activities</p>
          </div>

          <!-- Search and Filter Bar -->
          <div class="bg-white rounded-xl  mb-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 gap-4">
              <!-- Search Input -->
              <div class="relative w-full sm:w-96">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search by sport name, category, or type..."
                  class="w-full pl-12 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                >
                <i class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4 fa-solid fa-magnifying-glass"></i>
                <button
                  v-if="searchQuery"
                  @click="clearSearch"
                  class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                >
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div v-if="filteredSports.length > 0" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            <div 
              v-for="sport in filteredSports" 
              :key="sport.id" 
              class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow border border-gray-200 hover:border-blue-300 overflow-hidden"
              :class="[
                sport.status?.toLowerCase() === 'completed'
                  ? 'border-green-200 hover:border-green-300' 
                  : 'border-gray-200 hover:border-blue-300'
              ]"
            >
              <!-- Header -->
              <div class="p-5 border-b bg-gray-50">
                <div class="flex justify-between ">
                  <h3 class="text-lg font-semibold text-gray-800 truncate">
                    {{ sport.sport.name }} {{ sport.categories }}
                  </h3>
                  <button 
                    @click="navigateToSportView(sport.id)"
                    class="flex justify-center items-center gap-2 px-3 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-full hover:bg-blue-700 focus:ring-2 focus:ring-offset-1 focus:ring-blue-500"
                  >
                    <i class="fas fa-arrow-right"></i>
                  </button>
                </div>
                <div class="flex items-center gap-2 sm:mt-2">
                  <div 
                    class="w-2.5 h-2.5 rounded-full"
                    :class="{
                      'bg-green-500': sport.status?.toLowerCase() === 'completed',
                      'bg-red-500': sport.status?.toLowerCase() === 'ongoing',
                    }"
                  ></div>
                  <span class="text-sm capitalize"
                    :class="{
                      'text-green-600': sport.status?.toLowerCase() === 'completed',
                      'text-red-600': sport.status?.toLowerCase() === 'ongoing',
                    }"
                  >
                    {{ sport.status }}
                  </span>
                </div>
              </div>

              <!-- Body -->
              <div class="p-5 space-y-3">
                <div class="flex flex-wrap gap-3">
                  <!-- Category -->
                  <div class="flex items-center gap-2 px-3 py-1.5 text-blue-500 bg-blue-50 rounded-full text-xs">
                    <i class="fas fa-user"></i>
                    <span>{{ sport.categories }}</span>
                  </div>
                  <!-- Setup -->
                  <div class="flex items-center gap-2 px-3 py-1.5 text-purple-500 bg-purple-50 rounded-full text-xs">
                    <i class="fas fa-bolt"></i>
                    <span>{{ sport.setup }}</span>
                  </div>
                  <!-- Type -->
                  <div class="flex items-center gap-2 px-3 py-1.5 text-amber-500 bg-amber-50 rounded-full text-xs">
                    <i class="fas fa-trophy"></i>
                    <span>{{ sport.type }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <!-- Empty State -->
          <div v-else class="text-center py-12 bg-white rounded-xl border border-gray-200">
            <div class="flex flex-col items-center">
              <i class="fas fa-volleyball-ball text-4xl text-gray-300 mb-4"></i>
              <h3 class="text-lg font-medium text-gray-900 mb-2">No Sports Assigned</h3>
              <p class="text-gray-500 max-w-sm">There are currently no sports assigned to you. Check back later or contact the administrator.</p>
            </div>
          </div>
        </div>

        <div v-if="activeTab === 'Overview'">
          <div class="py-6">
              <div class="max-w-7xl  sm:px-6 lg:px-12 mx-auto space-y-8">
                <!-- Important Dates Section -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-200">
                  <h3 class="text-lg font-medium text-gray-900 mb-4">Important Dates</h3>
                  <div class="space-y-3">
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                      <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-md text-sm font-medium">
                        {{ formatDate(palakasan.start_date) }}
                      </div>
                      <div>
                        <p class="text-gray-900 font-medium">Event Start</p>
                        <p class="text-sm text-gray-600">Opening ceremony and start of competitions</p>
                      </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                      <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-md text-sm font-medium">
                        {{ formatDate(palakasan.end_date) }}
                      </div>
                      <div>
                        <p class="text-gray-900 font-medium">Event End</p>
                        <p class="text-sm text-gray-600">Closing ceremony and awarding</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Facilitator Guidelines Section -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-200">
                  <h3 class="text-lg font-medium text-gray-900 mb-4">Facilitator Guidelines</h3>
                  <div class="space-y-4">
                    <div class="flex items-start gap-3">
                      <div class="flex-shrink-0 w-6 h-6 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z"/>
                        </svg>
                      </div>
                      <div>
                        <h4 class="text-sm font-medium text-gray-900">Game Coordination</h4>
                        <p class="text-sm text-gray-600">Coordinate with referees and officials to ensure smooth game flow. Report any issues or concerns immediately.</p>
                      </div>
                    </div>
                    <div class="flex items-start gap-3">
                      <div class="flex-shrink-0 w-6 h-6 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z"/>
                        </svg>
                      </div>
                      <div>
                        <h4 class="text-sm font-medium text-gray-900">Documentation</h4>
                        <p class="text-sm text-gray-600">Maintain detailed records of game results, incidents, and important events. Submit reports promptly after each game.</p>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
          </div>
        </div>

        <div v-if="activeTab === 'settings'">
          <FacilitatorSettings 
            :facilitator="facilitator"
            :activity-logs="activityLogs"
            :assignedSports="assignedSports"
          />
        </div>

      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/Layout/DashboardLayoutF.vue';
import FacilitatorSettings from '@/Components/FacilitatorSettings.vue';

const props = defineProps({
  facilitator: {
    type: Object,
    required: true
  },
  assignedSports: {
    type: Array,
    default: () => []
  },
  palakasan: {
    type: Object,
    default: () => ({})
  },
  activityLogs: {
    type: Array,
    default: () => []
  }
});
const dropdownOpen = ref(false);
const searchQuery = ref('');
const activeTab = ref('home');  // Default to "Details" 

const filteredSports = computed(() => {
  if (props.palakasan?.status !== 'live') return [];
  
  if (!searchQuery.value) return props.assignedSports;
  
  const query = searchQuery.value.toLowerCase();
  return props.assignedSports.filter(sport => 
    sport.sport.name.toLowerCase().includes(query) ||
    sport.categories.toLowerCase().includes(query) ||
    sport.setup.toLowerCase().includes(query) ||
    sport.type.toLowerCase().includes(query)
  );
});

const completedSports = computed(() => 
  props.assignedSports.filter(sport => sport.status === 'completed')
);

const progressPercentage = computed(() => {
  if (props.assignedSports.length === 0) return 0;
  return Math.round((completedSports.value.length / props.assignedSports.length) * 100);
});

const navigateToSportView = (sportId) => {
  const url = window.location.pathname;
  const encryptedFacilitatorId = url.split('/').pop();
  
  router.visit(`/sportview/${sportId}/${encryptedFacilitatorId}`);
};

const formatDate = (dateString) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

const clearSearch = () => {
  searchQuery.value = '';
};
</script>

<style scoped>
.scrollbar-thin {
  scrollbar-width: thin;
  scrollbar-color: #E5E7EB transparent;
}

.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: #F9FAFB;
  border-radius: 20px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background-color: #E5E7EB;
  border-radius: 20px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background-color: #D1D5DB;
}
</style>
