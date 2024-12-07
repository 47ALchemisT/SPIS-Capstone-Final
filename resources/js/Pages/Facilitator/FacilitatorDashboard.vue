<template>
  <AppLayout :facilitator="facilitator">
    <Head title="Facilitator Dashboard" />
    <div class="container px-4">
      <!-- Tabs Navigation -->
      <div class="sticky top-[4rem] py-2 z-40 bg-white/80 backdrop-blur-sm">
        <nav class="flex flex-col sm:flex-row relative justify-between items-start sm:items-center py-1 space-y-2 sm:space-y-0">
          <div class="">
            <h1 class="text-lg font-medium text-gray-700">Facilitator Dashboard</h1>
          </div>
          <div class="flex gap-2 rounded-lg w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0">
            <div class="flex gap-2 rounded-lg">
              <button 
                v-for="tab in ['home', 'sports', 'Overview', 'settings']"
                :key="tab"
                @click="activeTab = tab"
                :class="[
                  'px-5 py-2 text-sm whitespace-nowrap',
                  activeTab === tab
                    ? 'text-gray-800 bg-blue-700 text-white font-medium rounded-lg'
                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium border-transparent'
                ]"
              >
                {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
              </button>
            </div>
          </div>
        </nav>
      </div>

      <!-- Tabs Content -->
      <div>
        <div v-if="activeTab === 'home'">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-5">
            <div class="bg-white rounded-lg p-6 flex flex-col justify-between">
              <div>
                <p class="text-2xl text-gray-800 mb-1">Welcome, <span class="font-semibold">{{ facilitator.student.first_name }} {{ facilitator.student.last_name }}</span> !</p>
                <p class="text-md text-gray-600">It's great to see you in the Facilitator Dashboard</p>
              </div>

              <div class="hidden md:block w-full h-96">
                <img class="w-full h-full object- rounded-lg" src="/resources/assets/phone.jpg" alt="Phone Animation">
              </div>

            </div>
            <div class="bg-white flex flex-col justify-between rounded-lg h-full">
              <div>
                <h2 class="text-md font-medium text-gray-800">Task List</h2>
                <p class="text-xs text-gray-600 mb-4">You are assigned to manage these sports</p>
                <div class="overflow-y-auto h-64">
                  <div v-for="sport in assignedSports" :key="sport.id" class="flex items-center px-4 py-2 hover:bg-gray-50 rounded-md transition-colors duration-150">
                    <div class="w-2.5 h-2.5 rounded-full mr-3" :class="{
                      'bg-green-500': sport.status === 'completed',
                      'bg-gray-400': sport.status === 'Ongoing'
                    }"></div>
                    <span class="text-sm text-gray-700">{{ sport.sport.name }} {{ sport.categories }}</span>
                  </div>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-4 mb-10">
                <div class=" col-span-2 w-full py-4">
                  <h3 class="text-sm font-medium text-gray-700 mb-2">Task Progress</h3>
                  <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                    <div class="bg-blue-600 h-2.5 rounded-full" :style="{ width: `${progressPercentage}%` }"></div>
                  </div>
                  <p class="text-xs text-gray-600">{{ completedSports.length }} of {{ assignedSports.length }} tasks completed</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-6">
                  <p class="text-sm text-blue-600 mb-3 font-medium">Assigned Sports</p>
                  <p class="text-2xl font-bold text-blue-700">{{ assignedSports.length }}</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-6">
                  <p class="text-sm text-blue-600 mb-3 font-medium">Completed Sports</p>
                  <p class="text-2xl font-bold text-blue-700">{{ completedSports.length }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="activeTab === 'sports'">
          <div class="py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
              <div class="relative w-full sm:w-64">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search sport..."
                  class="w-full pl-10 pr-10 py-2 bg-white border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                >
                <i class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4 fa-solid fa-magnifying-glass"></i>
                <button
                  v-if="searchQuery"
                  @click="clearSearch"
                  class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                >
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <button type="button" class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                Sort
              </button>
            </div>

            <div v-if="filteredSports.length > 0" class="space-y-4">
              <div v-for="sport in filteredSports" :key="sport.id" 
                class="rounded-lg shadow-sm p-6 border transition-all duration-200 group"
                :class="[
                  sport.status?.toLowerCase() === 'completed'
                    ? 'bg-blue-50 border-blue-300 hover:border-blue-400 hover:bg-blue-100' 
                    : 'bg-white border-gray-200 hover:border-blue-300 hover:bg-blue-50'
                ]">
                <div class="flex justify-between ">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-1 group-hover:text-blue-600">{{ sport.sport.name }} {{ sport.categories }}</h3>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                      <span>{{ sport.setup }}</span>
                      <span class="text-xs">•</span>
                      <span>{{ sport.type }}</span>
                      <span class="text-xs">•</span>
                      <span :class="{
                        'text-green-600': sport.status?.toLowerCase() === 'completed',
                        'text-yellow-600': sport.status?.toLowerCase() === 'in progress',
                        'text-gray-600': sport.status?.toLowerCase() === 'pending'
                      }">{{ sport.status }}</span>
                    </div>
                  </div>
                  <div>
                    <button 
                      @click="navigateToSportView(sport.id)"
                      class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex items-center gap-2"
                    >
                      View Sport
                      <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <p v-else class="text-gray-500 text-center py-8">No sports assigned yet.</p>
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
.space-y-2 {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.space-y-2::-webkit-scrollbar {
  width: 6px;
}

.space-y-2::-webkit-scrollbar-track {
  background: transparent;
}

.space-y-2::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
  border: transparent;
}
</style>
