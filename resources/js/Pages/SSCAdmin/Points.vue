<template>
    <Head title="Points"/>
    <AppLayout>
      <template v-slot:default>
        <!--Head-->
        <div class="mb-6">
          <h1 class="text-2xl font-semibold text-gray-800">Points System</h1>
          <p class="text-sm text-gray-600">Manage scoring points for major and minor events</p>
        </div>

        <!--Content-->
        <div class="space-y-6">
          <!--Utility-->
          <div class="">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
              <div class="search flex space-x-2 items-center w-full sm:w-auto">
                <!-- Search Input -->
                <div class="relative w-full sm:w-64">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                  </span>
                  <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search points..."
                    class="w-full pl-10 pr-10 py-2 bg-white border border-gray-300 focus:border-blue-500 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                  >
                  <button
                    v-if="searchQuery"
                    @click="clearSearch"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                  >
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!--Buttons-->
              <div class="flex items-center w-full sm:w-auto">
                <button @click="openModal(false)" class="w-full sm:w-auto bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 transition-all duration-200 flex items-center justify-center gap-2">
                  <i class="fa-solid fa-square-plus"></i>   
                  Add Points                             
                </button>
              </div>
            </div>
          </div>
  
          <!-- Main Content, Two Cards -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Major Points Card -->
            <div class="bg-white rounded-lg shadow-sm hover:shadow transition-all duration-200 ring-1 ring-gray-300">
              <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                  <h2 class="text-lg font-semibold text-gray-800">Major Points</h2>
                  <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ filteredMajorPoints.length }} Items
                  </span>
                </div>
                <div v-if="filteredMajorPoints.length > 0" class="divide-y divide-gray-200">
                  <div v-for="point in filteredMajorPoints" :key="point.id" class="py-4 first:pt-0 last:pb-0">
                    <div class="flex items-center justify-between mb-2">
                      <h3 class="text-base font-medium text-gray-800">{{ point.type }}</h3>
                      <div class="flex items-center space-x-2">
                        <button @click="openModal(true, point)" class="text-gray-600 hover:text-blue-600 transition-colors p-1">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button @click="confirmDelete(point.id)" class="text-gray-600 hover:text-red-600 transition-colors p-1">
                          <i class="fa-regular fa-trash-can"></i>
                        </button>
                      </div>
                    </div>
                    <div class="flex items-center space-x-4">
                      <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                        <i class="fas fa-trophy text-yellow-500"></i>
                        Rank {{ point.rank }}
                      </span>
                      <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                        <i class="fas fa-star text-blue-500"></i>
                        {{ point.points }} Points
                      </span>
                    </div>
                  </div>
                </div>
                <div v-else class="text-center py-8 text-gray-500">
                  <i class="fas fa-trophy text-gray-400 text-4xl mb-3"></i>
                  <p>No major points found</p>
                </div>
              </div>
            </div>

            <!-- Minor Points Card -->
            <div class="bg-white rounded-lg shadow-sm hover:shadow transition-all duration-200 ring-1 ring-gray-300">
              <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                  <h2 class="text-lg font-semibold text-gray-800">Minor Points</h2>
                  <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ filteredMinorPoints.length }} Items
                  </span>
                </div>
                <div v-if="filteredMinorPoints.length > 0" class="divide-y divide-gray-200">
                  <div v-for="point in filteredMinorPoints" :key="point.id" class="py-4 first:pt-0 last:pb-0">
                    <div class="flex items-center justify-between mb-2">
                      <h3 class="text-base font-medium text-gray-800">{{ point.type }}</h3>
                      <div class="flex items-center space-x-2">
                        <button @click="openModal(true, point)" class="text-gray-600 hover:text-blue-600 transition-colors p-1">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button @click="confirmDelete(point.id)" class="text-gray-600 hover:text-red-600 transition-colors p-1">
                          <i class="fa-regular fa-trash-can"></i>
                        </button>
                      </div>
                    </div>
                    <div class="flex items-center space-x-4">
                      <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                        <i class="fas fa-trophy text-yellow-500"></i>
                        Rank {{ point.rank }}
                      </span>
                      <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                        <i class="fas fa-star text-blue-500"></i>
                        {{ point.points }} Points
                      </span>
                    </div>
                  </div>
                </div>
                <div v-else class="text-center py-8 text-gray-500">
                  <i class="fas fa-trophy text-gray-400 text-4xl mb-3"></i>
                  <p>No minor points found</p>
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Modal for Add/Edit -->
        <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
          <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="closeModal"></div>
          <div class="bg-white p-6 rounded-xl shadow-lg z-50 max-w-md w-full mx-4">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-semibold text-gray-800">{{ isEditing ? 'Edit Point' : 'Add Point' }}</h2>
              <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <form @submit.prevent="submitPoint" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select
                  v-model="form.type"
                  required
                  class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="" disabled>Select a type</option>
                  <option value="Major">Major</option>
                  <option value="Minor">Minor</option>
                </select>
              </div>
  
              <div v-for="(item, index) in form.rankPoints" :key="index" class="space-y-4">
                <div class="flex items-center gap-4">
                  <div class="flex-1">
                    <label :for="'rank-' + index" class="block text-sm font-medium text-gray-700 mb-1">Rank</label>
                    <input 
                      v-model="item.rank" 
                      :id="'rank-' + index" 
                      type="text" 
                      class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" 
                      placeholder="Enter rank..."
                    >
                  </div>
                  <div class="flex-1">
                    <label :for="'points-' + index" class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                    <input 
                      v-model="item.points" 
                      :id="'points-' + index" 
                      type="number" 
                      class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" 
                      placeholder="Enter points..."
                    >
                  </div>
                  <button 
                    type="button" 
                    @click="removeRankPoints(index)" 
                    class="mt-6 p-2 text-red-600 hover:text-red-800 rounded-lg hover:bg-red-50 transition-colors"
                  >
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </div>
  
              <button 
                type="button" 
                @click="addRankPoints" 
                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm font-medium"
              >
                <i class="fas fa-plus-circle"></i>
                Add Another Rank and Points
              </button>
  
              <div class="flex justify-end gap-3 pt-4">
                <button 
                  type="button" 
                  @click="closeModal" 
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                >
                  Cancel
                </button>
                <button 
                  type="submit" 
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-lg transition-colors focus:ring-4 focus:ring-blue-300"
                >
                  {{ isEditing ? 'Update Point' : 'Add Point' }}
                </button>
              </div>
            </form>
          </div>
        </div>
  
        <!-- Modal for Delete Confirmation -->
        <div v-if="isDeleteModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
          <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="closeDeleteModal"></div>
          <div class="bg-white p-6 rounded-xl shadow-lg z-50 max-w-md w-full mx-4">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-12 h-12 flex items-center justify-center rounded-full bg-red-100">
                <i class="fa-regular fa-trash-can text-red-600 text-xl"></i>
              </div>
              <div>
                <h2 class="text-xl font-semibold text-gray-800">Delete Point</h2>
                <p class="text-sm text-gray-600">This action cannot be undone</p>
              </div>
            </div>
            <p class="text-gray-600 mb-6">Are you sure you want to remove this point from the system?</p>
            <div class="flex justify-end gap-3">
              <button 
                type="button" 
                @click="closeDeleteModal" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
              >
                Cancel
              </button>
              <button 
                @click="deletePoint" 
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors focus:ring-4 focus:ring-red-300"
              >
                Delete Point
              </button>
            </div>
          </div>
        </div>
  
        <!--Tooltip-->
        <div class="tooltip absolute z-10 invisible py-2 px-3 text-xs text-white bg-blue-800 rounded-lg opacity-0 transition-opacity duration-300">
          <div class="tooltip-content">Hello</div>
        </div>
  
      </template>
    </AppLayout>
  </template>
  
  <script setup>
  import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
  import { ChevronDownIcon } from '@heroicons/vue/20/solid'
  import { Head, useForm, router } from '@inertiajs/vue3';
  import { ref, onMounted, computed } from 'vue';
  import AppLayout from '@/Layout/DashboardLayout.vue';
  import Modal from '@/Components/Modal.vue';
  import { route } from 'ziggy-js';
  
  const props = defineProps({
    points: Array,
    errors: Object
  })
  
  const isModalOpen = ref(false);
  const isDeleteModalOpen = ref(false);
  const isEditing = ref(false);
  const form = useForm({
    id: null,
    type: '',
    rankPoints: [{ rank: '', points: '' }],
  });
  
  const pointIdToDelete = ref(null);
  const searchQuery = ref('');
  
  const filteredMajorPoints = computed(() => {
  return props.points.filter(point => 
    point.type === 'Major' &&
    (point.type.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
     point.rank.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
     point.points.toString().includes(searchQuery.value))
  );
});

const filteredMinorPoints = computed(() => {
  return props.points.filter(point => 
    point.type === 'Minor' &&
    (point.type.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
     point.rank.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
     point.points.toString().includes(searchQuery.value))
  );
});
  
  const openModal = (editMode = false, point = null) => {
    isModalOpen.value = true;
    isEditing.value = editMode;
  
    if (editMode && point) {
      form.id = point.id;
      form.type = point.type;
      form.rankPoints = [{ rank: point.rank, points: point.points }];
    } else {
      form.reset();
      form.rankPoints = [{ rank: '', points: '' }];
    }
  };
  
  const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
  };
  
  const addRankPoints = () => {
    form.rankPoints.push({ rank: '', points: '' });
  };
  
  const removeRankPoints = (index) => {
    if (form.rankPoints.length > 1) {
      form.rankPoints.splice(index, 1);
    }
  };
  
  const submitPoint = () => {
    if (isEditing.value) {
      form.put(route('points.update', form.id), {
        preserveState: false,
        preserveScroll: true,
        onSuccess: () => {
          closeModal();
        },
      });
    } else {
      form.post(route('points.store'), {
        preserveState: false,
        preserveScroll: true,
        onSuccess: () => {
          closeModal();
        },
      });
    }
  };
  
  const confirmDelete = (pointId) => {
    pointIdToDelete.value = pointId;
    isDeleteModalOpen.value = true;
  };
  
  const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    pointIdToDelete.value = null;
  };
  
  const deletePoint = () => {
    router.delete(route('points.destroy', pointIdToDelete.value), {
      preserveState: false,
      preserveScroll: true,
    });
    closeDeleteModal();
  };
  
  const clearSearch = () => {
    searchQuery.value = '';
  };
  
  onMounted(() => {
    const tooltip = document.querySelector('.tooltip');
    const tooltipContent = tooltip.querySelector('.tooltip-content');
  
    // Select all buttons with the 'tooltip-btn' class
    const buttons = document.querySelectorAll('.tooltip-btn');
  
    buttons.forEach(btn => {
      btn.addEventListener('mouseenter', (e) => {
        const tooltipText = e.target.getAttribute('data-tooltip');
        const btnRect = e.target.getBoundingClientRect();
  
        // Set the tooltip content and position
        tooltipContent.textContent = tooltipText;
        tooltip.classList.remove('invisible', 'opacity-0');
        tooltip.style.top = `${btnRect.top - tooltip.offsetHeight - 6}px`;
        tooltip.style.left = `${btnRect.left + (btnRect.width / 2) - (tooltip.offsetWidth / 2)}px`;
      });
  
      btn.addEventListener('mouseleave', () => {
        tooltip.classList.add('invisible', 'opacity-0');
      });
    });
  });
  </script>
  
  <style scoped>
    /* Home page specific styles */
  </style>