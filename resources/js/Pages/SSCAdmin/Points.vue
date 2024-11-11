<template>
    <Head title="Points"/>
    <AppLayout>
      <template v-slot:default>
        <!--Head-->
        <div>
          <h1 class="text-3xl font-semibold text-gray-800">Points</h1>
          <h1 class="text-sm font-normal text-gray-700">List of Points</h1>
        </div>
        <!--Content-->
        <div class="mt-3 space-y-3">
          <!--Utility-->
          <div class="utility">
            <div class="flex justify-between items-center">
              <div class="search flex space-x-2 items-center">
                <!-- Search Input -->
                <div class="relative">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                  </span>
                  <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search points..."
                    class="w-64 pl-10 pr-10 py-2 bg-white shadow-sm border border-gray-300 focus:border-blue-500 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
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
              <div class="flex items-center space-x-2.5">
                <button @click="openModal(false)" class="bg-blue-700 text-white py-2 px-3 rounded-lg text-sm font-medium shadow hover:bg-blue-800/90 transition-colors">
                  <i class="fa-solid fa-square-plus mr-1"></i>   
                  Points                             
                </button>
              </div>
            </div>
          </div>
  
        <!-- Main Content, Two Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Major Points Card -->
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition-all ring-1 ring-gray-300">
              <div class="p-4">
                <h2 class="text-xl font-bold mb-3">Major Points</h2>
                <div v-if="filteredMajorPoints.length > 0">
                  <div v-for="point in filteredMajorPoints" :key="point.id" class="mb-3 pb-3 border-b last:border-b-0">
                    <div class="flex items-center justify-between mb-2">
                      <h3 class="text-lg font-semibold">{{ point.type }}</h3>
                      <div class="flex space-x-2">
                        <button @click="openModal(true, point)" class="text-blue-600 hover:text-blue-800">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button @click="confirmDelete(point.id)" class="text-red-600 hover:text-red-800">
                          <i class="fa-regular fa-trash-can"></i>
                        </button>
                      </div>
                    </div>
                    <p class="text-gray-600 text-sm">Rank: {{ point.rank }}</p>
                    <p class="text-gray-600 text-sm">Points: {{ point.points }}</p>
                  </div>
                </div>
                <div v-else class="text-center text-gray-600 mt-4">
                  No major points found.
                </div>
              </div>
            </div>

            <!-- Minor Points Card -->
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition-all ring-1 ring-gray-300">
              <div class="p-4">
                <h2 class="text-xl font-bold mb-3">Minor Points</h2>
                <div v-if="filteredMinorPoints.length > 0">
                  <div v-for="point in filteredMinorPoints" :key="point.id" class="mb-3 pb-3 border-b last:border-b-0">
                    <div class="flex items-center justify-between mb-2">
                      <h3 class="text-lg font-semibold">{{ point.type }}</h3>
                      <div class="flex space-x-2">
                        <button @click="openModal(true, point)" class="text-blue-600 hover:text-blue-800">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button @click="confirmDelete(point.id)" class="text-red-600 hover:text-red-800">
                          <i class="fa-regular fa-trash-can"></i>
                        </button>
                      </div>
                    </div>
                    <p class="text-gray-600 text-sm">Rank: {{ point.rank }}</p>
                    <p class="text-gray-600 text-sm">Points: {{ point.points }}</p>
                  </div>
                </div>
                <div v-else class="text-center text-gray-600 mt-4">
                  No minor points found.
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Modal for Add/Edit -->
        <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
          <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal"></div>
          <div class="bg-white p-6 rounded-xl shadow-lg z-50 max-w-md w-full">
            <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit Point' : 'Add Point' }}</h2>
            <form @submit.prevent="submitPoint">
              <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-1.5 font-medium">Type</label>
                <select
                  v-model="form.type"
                  required
                  class="border transition border-gray-300 rounded-lg bg-gray-50 text-sm w-full p-2"
                >
                  <option value="" disabled>Select a type</option>
                  <option value="Major">Major</option>
                  <option value="Minor">Minor</option>
                </select>
              </div>
  
              <div v-for="(item, index) in form.rankPoints" :key="index" class="mb-4">
                <div class="flex items-center gap-3">
                  <div>
                    <label :for="'rank-' + index" class="block mb-1.5 text-sm font-medium text-gray-700">Rank</label>
                    <input v-model="item.rank" :id="'rank-' + index" type="text" class="block p-2.5 transition w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter rank...">
                  </div>
                  <div>
                    <label :for="'points-' + index" class="block mb-1.5 text-sm font-medium text-gray-700">Points</label>
                    <input v-model="item.points" :id="'points-' + index" type="number" class="block p-2.5 transition w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter points...">
                  </div>
                  <button type="button" @click="removeRankPoints(index)" class="py-2 px-3 rounded-md bg-red-100 mt-6 text-red-500 hover:text-red-700">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </div>
  
              <button type="button" @click="addRankPoints" class="mb-4 text-blue-600 hover:text-blue-800 text-sm font-medium">
                <i class="fas fa-plus-circle mr-1"></i> Add Another Rank and Points
              </button>
  
              <div class="flex justify-end">
                <button type="button" @click="closeModal" class="mr-2 bg-gray-100 hover:bg-gray-200 px-4 py-2 text-sm rounded-lg transition">Cancel</button>
                <button type="submit" class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition">{{ isEditing ? 'Update Point' : 'Add Point' }}</button>
              </div>
            </form>
          </div>
        </div>
  
        <!-- Modal for Delete Confirmation -->
        <div v-if="isDeleteModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
          <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeDeleteModal"></div>
          <div class="bg-white p-6 rounded-xl shadow-lg z-50 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-bold">Confirm Delete</h2>
              <i class="fa-regular fa-trash-can text-xl"></i>
            </div>
            <p>Are you sure you want to remove this Point from the list?</p>
            <div class="flex justify-end mt-4">
              <button type="button" @click="closeDeleteModal" class="mr-2 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm transition">No, keep it</button>
              <button @click="deletePoint" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition">Yes, delete it</button>
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