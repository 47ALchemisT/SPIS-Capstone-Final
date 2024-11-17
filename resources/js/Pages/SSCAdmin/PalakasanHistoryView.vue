<template>
    <Head :title="palakasan ? palakasan.theme : 'Palakasan History'" />
    <AppLayout>
      <template v-slot:default>
        <div v-if="palakasan" class="container mx-auto space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl dark:ring-gray-700">
                <!-- Header Section with Theme and Return Button -->
                <div class="">
                    <div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
                        <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ palakasan.theme }}
                        </h1>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">{{ palakasan.tagline }}</p>
                        </div>
                        <div>
                            <button
                                @click="returnToPalakasan"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                >
                                <i class="fa-solid fa-arrow-left mr-2"></i>
                                Return
                            </button>
                        </div>
                    </div>
                </div>
                <div class="space-y-4 mt-5">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Description</h2>
                    <p class="text-gray-700 dark:text-gray-300">{{ palakasan.description }}</p>
                </div>
            </div>
  
  
  
          <!-- Assigned Teams Section -->
          <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Teams</h2>
            <div v-if="assignedTeams && assignedTeams.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
              <div v-for="team in assignedTeams" :key="team.id" class="bg-white dark:bg-gray-800 rounded-lg ring-1 ring-gray-300 p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ team.assigned_team_name }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">College: {{ team.college.name || 'N/A' }}</p>
              </div>
            </div>
            <div v-else class="text-gray-600 dark:text-gray-400">
              No teams have been assigned to this Palakasan event.
            </div>
          </div>
  
          <!-- Assigned Sports Section -->
          <div v-if="assignedSports && assignedSports.length > 0" class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Sports</h2>
            <div class="bg-white dark:bg-gray-800 rounded-lg ring-1 ring-gray-300">
              <!-- Search Bar -->
              <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="relative">
                  <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Search sports..."
                    class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition duration-300 ease-in-out"
                  />
                  <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </div>
                </div>
              </div>
  
              <!-- Table -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sport Name</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Setup</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                  </thead>
                  <tbody class=" dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="sport in filteredSports" :key="sport.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 ease-in-out">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ sport.sport.name }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ sport.categories }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ sport.setup }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ sport.type }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span :class="[
                          'px-2 py-1 text-xs font-semibold rounded-full inline-flex items-center',
                          {
                            'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100': sport.status === 'completed',
                            'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100': sport.status === 'ongoing',
                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100': sport.status === 'pending'
                          }
                        ]">
                          <span class="h-2 w-2 rounded-full mr-1.5" 
                                :class="{
                                  'bg-green-500': sport.status === 'completed',
                                  'bg-blue-500': sport.status === 'ongoing',
                                  'bg-yellow-500': sport.status === 'pending'
                                }">
                          </span>
                          {{ sport.status }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
  
              <!-- Empty State -->
              <div v-if="filteredSports.length === 0" class="p-8 text-center">
                <div class="text-gray-500 dark:text-gray-400">No sports found matching your search.</div>
              </div>
            </div>
          </div>
          <div v-else class="p-8 text-center bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="text-gray-500 dark:text-gray-400">No sports have been assigned to this Palakasan event.</div>
          </div>
        </div>
      </template>
    </AppLayout>
  </template>
  
  <script setup>
  import { Head, router } from '@inertiajs/vue3';
  import { ref, computed } from 'vue';
  import AppLayout from '@/Layout/DashboardLayout.vue';
  
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
    }
  });

  const returnToPalakasan = () => {
    router.get(route('palakasan.details', { tab: 'details' }));
    };

  
  const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
  };
  
  const searchQuery = ref('');
  
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
  /* Add any component-specific styles here */
  </style>