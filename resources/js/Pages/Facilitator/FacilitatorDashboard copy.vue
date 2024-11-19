<template>
    <AppLayout :facilitator="facilitator">
      <Head title="Facilitator Dashboard" />
      <div class="">
        <h1 class="text-xl font-bold">Facilitator Dashboard</h1>
        <p class="mb-4"> Welcome, Facilitator <strong>{{ facilitator.student.first_name }} {{ facilitator.student.last_name }}</strong></p>
        
        <!--COntent-->
        <div>
          
        </div>


        <h2 class="text-lg font-semibold mb-3">Assigned Sports</h2>
        <div v-if="sports.length > 0">
          <ul class="space-y-4">
            <li v-for="sport in sports" :key="sport.id" class="bg-white ring-1 ring-gray-300 rounded-lg p-6">
              <h3 class="text-xl font-medium mb-2">{{ sport.sport.name }}</h3>
              <div class="grid grid-cols-2 gap-2 mb-4">
                <p><strong>Category:</strong> {{ sport.categories }}</p>
                <p><strong>Setup:</strong> {{ sport.setup }}</p>
                <p><strong>Type:</strong> {{ sport.type }}</p>
                <p><strong>Status:</strong> {{ sport.status }}</p>
              </div>
              <button 
                type="button" 
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition duration-300 ease-in-out"
                @click="navigateToSportView(sport.id)"
              >
                View Sport Details
              </button>
            </li>
          </ul>
        </div>
        <p v-else class="text-gray-500">No sports assigned yet.</p>
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { Head } from '@inertiajs/vue3';
  import { router } from '@inertiajs/vue3';
  import AppLayout from '@/Layout/DashboardLayoutF.vue';
  
  const props = defineProps({
    facilitator: {
      type: Object,
      required: true
    },
    sports: {
      type: Array,
      default: () => []
    }
  });
  
  const navigateToSportView = (sportId) => {
    router.visit(route('facilitator-sportview.index', { 
      sport: sportId, 
      facilitator: props.facilitator.id 
    }));
  };
  </script>