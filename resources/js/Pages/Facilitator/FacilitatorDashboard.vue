<template>
    <AppLayout :facilitator="facilitator">
      <Head title="Facilitator Dashboard" />
      <div class="">
        
        <h1 class="text-xl font-bold">Facilitator Dashboard</h1>
        
        <!--COntent-->
        <div class="grid grid-cols-3 gap-4 my-3">
          <div class="ring-1 ring-gray-300 rounded-lg p-6">
            <p class="text-sm text-gray-700">Assigned Sports</p>
            <p class="text-3xl font-medium mt-4">{{ sports.length }}</p>
          </div>
          <div class="ring-1 ring-gray-300 rounded-lg p-6">
            <p class="text-sm text-gray-700">Completed Sports</p>
            <p class="text-3xl font-medium mt-4">{{ completedSports.length }}</p>
          </div>
          <div class="ring-1 ring-gray-300 rounded-lg p-6">
            <p class="text-sm text-gray-700">Incomplete Sports</p>
            <p class="text-3xl font-medium mt-4">{{ incompleteSports.length }}</p>
          </div>
        </div>

        <div class="flex justify-between items-center">
          <!-- Search Input -->
          <div class="mb-4 pt-3 flex gap-3 items-center">
            <div class="relative">
          <!-- Search Icon (Left) -->
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
              <i class="fas fa-search"></i>
            </span>

            <!-- Input Field -->
              <input
              v-model="searchQuery"
              type="text"
              placeholder="Search sport..."
              class="w-64 pl-10 pr-10 py-2 bg-white shadow-sm border border-gray-300 focus:border-blue-500 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
              >

            <!-- Clear Button (Right) -->
              <button
              v-if="searchQuery"
              @click="clearSearch"
              class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
              >
              <i class="fas fa-times"></i>
              </button>
            </div>
            <h2 class="text-md font-meedium">Assigned Sports</h2>
          </div>
          <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
            Sort
          </button>
        </div>



        <div v-if="sports.length > 0">
          <ul class="space-y-4">
            <li v-for="sport in sports" :key="sport.id" class="bg-white ring-1 ring-gray-300 rounded-lg p-6 hover:bg-gray-50 hover:ring-gray-400 transition">
              <div class="flex justify-between">
                <div>
                  <h3 class="text-xl font-medium mb-1">{{ sport.sport.name }} {{ sport.categories }}</h3>
                  <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>{{ sport.setup }}</span>
                    <span class="text-xs">|</span>
                    <span>{{ sport.type }}</span>
                  </div>
                </div>

                <div>
                  <button 
                    type="button" 
                    class="text-white flex items-center gap-2 bg-gray-800 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition duration-300 ease-in-out"
                    @click="navigateToSportView(sport.id)"
                  >
                    View Sport
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76"/></svg>
                  </button>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <p v-else class="text-gray-500">No sports assigned yet.</p>
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { Head, router } from '@inertiajs/vue3';
  import { ref, computed } from 'vue';
  import AppLayout from '@/Layout/DashboardLayoutF.vue';
  
  const props = defineProps({
    facilitator: {
      type: Object,
      required: true
    },
    sports: {
      type: Array,
      default: () => []
    },
  });

  const completedSports = computed(() => 
    props.sports.filter(sport => sport.status === 'Completed')
  );

  const incompleteSports = computed(() => 
    props.sports.filter(sport => sport.status !== 'Completed')
  );
    
  const navigateToSportView = (sportId) => {
    router.visit(route('facilitator-sportview.index', { 
      sport: sportId, 
      facilitator: props.facilitator.id 
    }));
  };
  </script>