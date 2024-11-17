<template>
    <TransitionGroup
      tag="div"
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform translate-y-2 opacity-0"
      enter-to-class="transform translate-y-0 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="transform translate-y-0 opacity-100"
      leave-to-class="transform translate-y-2 opacity-0"
      class="fixed top-4 right-4 z-50 space-y-2"
    >
      <div
        v-for="toast in toasts"
        :key="toast.id"
        class="max-w-lg p-4 rounded-lg shadow-lg flex items-center gap-2"
        :class="[toast.type === 'success' ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200']"
      >
        <span>{{ toast.message }}</span>
        <button @click="removeToast(toast.id)" class="ml-5 hover:opacity-70">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path
              fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"
            />
          </svg>
        </button>
      </div>
    </TransitionGroup>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const toasts = ref([]);
  let toastCounter = 0;
  
  const addToast = (message, type = 'success', duration = 5000) => {
    const id = toastCounter++;
    toasts.value.push({ id, message, type });
  
    if (duration > 0) {
      setTimeout(() => {
        removeToast(id);
      }, duration);
    }
  };
  
  const removeToast = (id) => {
    toasts.value = toasts.value.filter((toast) => toast.id !== id);
  };
  
  defineExpose({ addToast });
  </script>