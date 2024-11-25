<template>
    <div>
      <button
        @click="openModal"
        type="button"
        class="text-gray-600 ring-1 font-medium flex items-center ring-gray-300 hover:bg-gray-100 hover:text-gray-800 hover:ring-gray-400 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-2 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 group"
      >
        <svg
          class="w-5 h-5"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
        >
          <path
            fill="currentColor"
            d="M11 8.018a.75.75 0 0 1-.75.732c-.75 0-.75-.751-.75-.751V7.99a1 1 0 0 1 .008-.134a2.2 2.2 0 0 1 .42-1.067c.454-.613 1.27-1.062 2.585-1.039c.95.017 1.793.415 2.321 1.07c.537.667.718 1.57.362 2.459c-.362.905-1.181 1.265-1.652 1.471l-.05.023c-.28.123-.413.187-.493.251l-.001.001v.724a.75.75 0 0 1-1.5.001V11c0-.523.252-.897.563-1.147c.25-.2.565-.338.786-.436l.038-.017c.542-.239.8-.387.917-.679a.92.92 0 0 0-.138-.96c-.222-.275-.629-.502-1.179-.511c-.935-.016-1.245.285-1.353.432a.7.7 0 0 0-.134.33zm1.25 7.482a1 1 0 1 0 0-2a1 1 0 0 0 0 2M4 4.5A2.5 2.5 0 0 1 6.5 2H18a2.5 2.5 0 0 1 2.5 2.5v14.25a.75.75 0 0 1-.75.75H5.5a1 1 0 0 0 1 1h13.25a.75.75 0 0 1 0 1.5H6.5A2.5 2.5 0 0 1 4 19.5zm1.5 0V18H19V4.5a1 1 0 0 0-1-1H6.5a1 1 0 0 0-1 1"
          />
        </svg>
        <span class="max-w-0 overflow-hidden group-hover:ml-1.5 group-hover:max-w-[200px] whitespace-nowrap transition-all duration-500 ease-in-out">
          How to Start
        </span>
      </button>
  
      <Teleport to="body">
        <Transition name="modal">
          <div v-if="isOpen" class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
              <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-40"></div>
              </div>
  
              <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
  
              <div class="inline-block  align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                  <div class="sm:flex sm:items-start">
                    <div class="text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="h-72">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                How to Start
                            </h3>
                            <div class="mt-4 relative overflow-hidden">
                                <Transition name="slide" mode="out-in">
                                <div :key="currentStep" class="mb-4">
                                    <h4 class="font-semibold text-gray-700">{{ steps[currentStep].title }}</h4>
                                    <p class="text-sm text-gray-600">{{ steps[currentStep].description }}</p>
                                </div>
                                </Transition>
                            </div>
                        </div>
                      <div class="flex justify-between mt-4">
                        <button 
                          @click="prevStep" 
                          :disabled="currentStep === 0"
                          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                        >
                          Previous
                        </button>
                        <button 
                          @click="nextStep" 
                          :disabled="currentStep === steps.length - 1"
                          class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                        >
                          Next
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                  <button 
                    type="button" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                    @click="closeModal"
                  >
                    Got it, thanks!
                  </button>
                </div>
              </div>
            </div>
          </div>
        </Transition>
      </Teleport>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const isOpen = ref(false);
  const currentStep = ref(0);
  
  const steps = [
    {
      title: "1. Create a Palakasan Event",
      description: "Start by creating a new Palakasan event with a theme, date range, and description."
    },
    {
      title: "2. Add Teams",
      description: "Invite and add participating teams to the event."
    },
    {
      title: "3. Select Sports",
      description: "Choose the sports that will be part of the Palakasan event."
    },
    {
      title: "4. Assign Facilitators",
      description: "Assign facilitators to manage each sport or event."
    },
    {
      title: "5. Start the Event",
      description: "Once everything is set up, start the Palakasan event to begin tracking scores and rankings."
    }
  ];
  
  const openModal = () => {
    isOpen.value = true;
    currentStep.value = 0;
  };
  
  const closeModal = () => {
    isOpen.value = false;
  };
  
  const nextStep = () => {
    if (currentStep.value < steps.length - 1) {
      currentStep.value++;
    }
  };
  
  const prevStep = () => {
    if (currentStep.value > 0) {
      currentStep.value--;
    }
  };
  </script>
  
  <style scoped>
  .modal-enter-active,
  .modal-leave-active {
    transition: opacity 0.2s ease;
  }
  
  .modal-enter-from,
  .modal-leave-to {
    opacity: 0;
  }
  
  .slide-enter-active,
  .slide-leave-active {
    transition: all 0.2s ease;
  }
  
  .slide-enter-from {
    transform: translateX(100%);
    opacity: 0;
  }
  
  .slide-leave-to {
    transform: translateX(-100%);
    opacity: 0;
  }
  </style>
  
  