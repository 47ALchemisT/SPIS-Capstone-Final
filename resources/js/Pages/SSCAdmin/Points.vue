<template>
    <Head title="Points"/>
    <AppLayout>
      <template v-slot:default>
        <!--Head-->
        <div class="mb-6">
          <h1 class="text-2xl font-semibold text-gray-800">Points System</h1>
          <p class="text-sm text-gray-600">Manage scoring points for major and minor events</p>
        </div>
        
        <!-- Add this before the modals -->
        <div class="tooltip invisible opacity-0 fixed z-50 bg-gray-900 text-white text-sm py-1.5 px-3 rounded transition-all duration-150">
            <div class="tooltip-content"></div>
            <div class="tooltip-arrow"></div>
        </div>
        <!--Content-->
        <div class="mt-3 space-y-4">
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
                    class="w-72 pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                  >
                  <button
                    v-if="searchQuery"
                    @click="clearSearch"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                  >
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <div class="flex items-center gap-3">
                  <div class="h-6 w-px bg-gray-200"></div>
                  <div class="flex items-center gap-2">
                    <i class="fas fa-star text-blue-600"></i>
                    <span class="text-sm font-medium text-gray-700">
                      {{ filteredMajorPoints.length + filteredMinorPoints.length }} Points
                    </span>
                  </div>
                </div>
              </div>
              <!--Buttons-->
              <button
                @click="openModal(false)"
                :disabled="latestPalakasan?.status !== 'pending'"
                type="button"
                class="inline-flex items-center gap-2 text-white text-sm bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 font-medium rounded-lg px-4 py-2.5 transition-all duration-200 shadow-sm disabled:bg-gray-400 disabled:cursor-not-allowed disabled:opacity-70"
              >
                Add Points
              </button>

            </div>
          </div>
  
          <!-- Main Content, Two Cards -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-5">
            <!-- Major Points Card -->
            <div class="bg-white rounded-xl ring-1 ring-gray-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
              <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                  <div class="flex items-center gap-3">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600">
                      <i class="fas fa-trophy text-lg"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Major Points</h2>
                  </div>
                  <span class="bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-lg">
                    {{ filteredMajorPoints.length }} Items
                  </span>
                </div>
                <div v-if="filteredMajorPoints.length > 0" class="divide-y divide-gray-100">
                  <div v-for="point in filteredMajorPoints" :key="point.id" class="py-4 first:pt-0 last:pb-0">
                    <div class="flex items-center justify-between mb-2">
                      <h3 class="text-base font-medium text-gray-900">{{ point.type }}</h3>
                      <div class="flex items-center gap-2">
                        <button 
                        :disabled="latestPalakasan?.status !== 'pending'"
                        @click="openModal(true, point)" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button 
                        :disabled="latestPalakasan?.status !== 'pending'"
                        @click="confirmDelete(point.id)" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">
                          <i class="fa-regular fa-trash-can"></i>
                        </button>
                      </div>
                    </div>
                    <div class="flex items-center gap-4">
                      <span class="inline-flex items-center gap-1.5 text-sm text-gray-600">
                        <i class="fas fa-medal text-yellow-500"></i>
                        Rank {{ point.rank }}
                      </span>
                      <span class="inline-flex items-center gap-1.5 text-sm text-gray-600">
                        <i class="fas fa-star text-blue-500"></i>
                        {{ point.points }} Points
                      </span>
                    </div>
                  </div>
                </div>
                <div v-else class="flex flex-col items-center justify-center py-8">
                  <div class="text-gray-400 mb-3">
                    <i class="fas fa-trophy text-5xl"></i>
                  </div>
                  <h3 class="text-base font-medium text-gray-900 mb-1">No major points found</h3>
                  <p class="text-sm text-gray-500">Add some major points to get started</p>
                </div>
              </div>
            </div>

            <!-- Minor Points Card -->
            <div class="bg-white rounded-xl ring-1 ring-gray-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
              <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                  <div class="flex items-center gap-3">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600">
                      <i class="fas fa-medal text-lg"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Minor Points</h2>
                  </div>
                  <span class="bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-lg">
                    {{ filteredMinorPoints.length }} Items
                  </span>
                </div>
                <div v-if="filteredMinorPoints.length > 0" class="divide-y divide-gray-100">
                  <div v-for="point in filteredMinorPoints" :key="point.id" class="py-4 first:pt-0 last:pb-0">
                    <div class="flex items-center justify-between mb-2">
                      <h3 class="text-base font-medium text-gray-900">{{ point.type }}</h3>
                      <div class="flex items-center gap-2">
                        <button @click="openModal(true, point)" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button @click="confirmDelete(point.id)" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">
                          <i class="fa-regular fa-trash-can"></i>
                        </button>
                      </div>
                    </div>
                    <div class="flex items-center gap-4">
                      <span class="inline-flex items-center gap-1.5 text-sm text-gray-600">
                        <i class="fas fa-medal text-yellow-500"></i>
                        Rank {{ point.rank }}
                      </span>
                      <span class="inline-flex items-center gap-1.5 text-sm text-gray-600">
                        <i class="fas fa-star text-blue-500"></i>
                        {{ point.points }} Points
                      </span>
                    </div>
                  </div>
                </div>
                <div v-else class="flex flex-col items-center justify-center py-8">
                  <div class="text-gray-400 mb-3">
                    <i class="fas fa-medal text-5xl"></i>
                  </div>
                  <h3 class="text-base font-medium text-gray-900 mb-1">No minor points found</h3>
                  <p class="text-sm text-gray-500">Add some minor points to get started</p>
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Modal for Add/Edit -->
        <TransitionRoot appear :show="isModalOpen" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-50">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md transform transition-all">
                                <DialogTitle class="text-xl font-bold text-gray-900 mb-4">{{ isEditing ? 'Edit Point' : 'Add Point' }}</DialogTitle>
                                <form @submit.prevent="submitPoint" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                        <select
                                            v-model="form.type"
                                            required
                                            class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                        >
                                            <option value="" disabled>Select a type</option>
                                            <option value="Major">Major</option>
                                            <option value="Minor">Minor</option>
                                        </select>
                                    </div>

                                    <div v-for="(item, index) in form.rankPoints" :key="index" class="space-y-4">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-1">
                                                <label :for="'rank-' + index" class="block text-sm font-medium text-gray-700 mb-2">Rank</label>
                                                <input 
                                                    v-model="item.rank" 
                                                    :id="'rank-' + index" 
                                                    type="text" 
                                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" 
                                                    placeholder="Enter rank..."
                                                >
                                            </div>
                                            <div class="flex-1">
                                                <label :for="'points-' + index" class="block text-sm font-medium text-gray-700 mb-2">Points</label>
                                                <input 
                                                    v-model="item.points" 
                                                    :id="'points-' + index" 
                                                    type="number" 
                                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" 
                                                    placeholder="Enter points..."
                                                >
                                            </div>
                                            <button 
                                                type="button" 
                                                @click="removeRankPoints(index)" 
                                                class="mt-7 py-2 px-3 ring-1 ring-gray-200 hover:ring-red-400 text-gray-500 hover:text-red-800 rounded-lg hover:bg-red-50 transition-all"
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
                                            class="px-4 py-2.5 text-sm font-medium text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50 rounded-lg transition"
                                        >
                                            Cancel
                                        </button>
                                        <button 
                                            type="submit" 
                                            class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition"
                                        >
                                            {{ isEditing ? 'Update Point' : 'Add Point' }}
                                        </button>
                                    </div>
                                </form>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Modal for Delete Confirmation -->
        <TransitionRoot appear :show="isDeleteModalOpen" as="template">
            <Dialog as="div" @close="closeDeleteModal" class="relative z-50">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md transform transition-all">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-red-100">
                                        <i class="fa-regular fa-trash-can text-red-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <DialogTitle class="text-xl font-bold text-gray-900">Delete Point</DialogTitle>
                                        <p class="text-sm text-gray-600">This action cannot be undone</p>
                                    </div>
                                </div>

                                <p class="text-gray-600 mb-6">Are you sure you want to remove this point from the system?</p>

                                <div class="flex justify-end gap-3">
                                    <button 
                                        type="button" 
                                        @click="closeDeleteModal" 
                                        class="px-4 py-2.5 text-sm font-medium text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50 rounded-lg transition"
                                    >
                                        Cancel
                                    </button>
                                    <button 
                                        @click="deletePoint" 
                                        class="px-4 py-2.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition"
                                    >
                                        Delete Point
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
      </template>
    </AppLayout>
    <SuccessModal
        :show="showSuccessModal"
        :message="successMessage"
        @close="showSuccessModal = false"
     />
  </template>
  
  <script setup>
  import { Head, useForm, router } from '@inertiajs/vue3';
  import { ref, onMounted, computed } from 'vue';
  import AppLayout from '@/Layout/DashboardLayout.vue';
  import { route } from 'ziggy-js';
  import SuccessModal from '@/Components/SuccessModal.vue';
  import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';

  
  const props = defineProps({
    points: Array,
    errors: Object,
    latestPalakasan: Object
  })

  const showSuccessModal = ref(false);
  const successMessage = ref('');
  
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
        preserveScroll: true,
        onSuccess: () => {
          closeModal();
          showSuccessModal.value = true;
          successMessage.value = 'Points updated successfully!';
        },
      });
    } else {
      form.post(route('points.store'), {
        preserveScroll: true,
        onSuccess: () => {
          closeModal();
          showSuccessModal.value = true;
          successMessage.value = 'Points created successfully!';
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
      preserveScroll: true,
      onSuccess: () => {
        closeDeleteModal();
        showSuccessModal.value = true;
        successMessage.value = 'Points deleted successfully!';
      }
    });
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