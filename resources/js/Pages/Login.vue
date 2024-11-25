<template>
    <Head title="Login" />
    <section class="grid grid-cols-2  dark:bg-gray-900 min-h-screen flex items-center justify-center relative">
      <div class="h-full">
        <img class="h-full w-full object-cover" src="/resources/assets/torches.png" alt="">
      </div>
      <div class="flex items-center justify-center">
        <div class="w-full max-w-xl p-4 relative z-10">
          <div class="p-6 bg-white dark:bg-gray-800">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-0.5">Log in to your account</h1>
            <h3 class="text-sm  text-gray-600 mb-6">Welcome back! Please enter your account details.</h3>
    
            <form @submit.prevent="submit" class="mt-3 space-y-4">
              <div>
                <!-- Account Type Dropdown -->
                <label for="role" class="text-sm text-gray-900 dark:text-white ">Log in as</label>
                <div class="relative pt-2">
                  <select 
                    v-model="form.role"
                    id="role"
                    class="bg-gray-50 ring-1 ring-gray-200 text-gray-900 text-sm rounded-lg block w-full p-2.5 pr-8 appearance-none dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    required
                  >
                    <option disabled value="">Select account type</option>
                    <option value="Admin">Admin</option>
                    <option value="Sub Admin">Sub Admin</option>
                    <option value="Facilitator">Facilitator</option>
                    <option value="College Sport Head">College Sports Committee Head</option>
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 top-2 right-0 flex items-center px-2.5">
                    <svg 
                      class="h-4 w-4 text-gray-500"
                      xmlns="http://www.w3.org/2000/svg" 
                      viewBox="0 0 24 24" 
                      fill="none" 
                      stroke="currentColor" 
                      stroke-width="2" 
                      stroke-linecap="round" 
                      stroke-linejoin="round"
                    >
                      <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                  </div>
                </div>
              </div>
              
              <div>
                <!-- Username Input -->
                <label for="username" class="block text-sm text-gray-900 dark:text-white mb-2">Username</label>
                <input v-model="form.username" type="text" id="username" class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" placeholder="username" required />
              </div>

              <div>
                <!-- Password Input -->
                <label for="password" class="block text-sm text-gray-900 dark:text-white mb-2">Password</label>
                <input v-model="form.password" type="password" id="password" class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" placeholder="••••••••••" required />
                <span v-if="errorMessage" class="text-sm text-red-600">{{ errorMessage }}</span>
              </div>
              <!-- Error Message -->
    
              <!-- Remember Me Checkbox -->
              <div class="flex justify-between items-center pb-6">
                <div class="flex items-center">
                  <input v-model="form.remember" type="checkbox" id="remember" class="w-4 h-4 border-gray-300 rounded bg-gray-50 dark:bg-gray-700" />
                  <label for="remember" class="text-sm font-medium text-gray-500 dark:text-gray-400 ml-2">Remember this device</label>
                </div>
              </div>
              <!-- Submit Button -->
              <button type="submit" class="w-full py-3 font-medium text-sm bg-blue-700 text-white rounded-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">Sign in</button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { reactive, ref } from 'vue';
  import { router, Head } from '@inertiajs/vue3';  // Assuming you're using Inertia.js
  
  // Create the form using reactive to ensure reactivity
  const form = reactive({
    username: '',
    password: '',
    role: '',
    remember: false
  });
  
  // Reactive property to store error messages
  const errorMessage = ref('');
  
  // Handle form submission
  const submit = () => {
  
    // Reset error message before submission
    errorMessage.value = '';
  
    // Submit form via Inertia.js
    router.post('/login', form, {
      onError: (errors) => {
        console.log(errors); // Log errors if form submission fails
        
        // Set the error message if there's a login error
        if (errors.username) {
          errorMessage.value = 'These Username do not match our records.';
        }
        if (errors.password) {
          errorMessage.value = 'Incorrect password. Please try again.';
        }
      }
    });
  };
  </script>
  
  <style scoped>
  /* Add any component-specific styles here */
  </style>
  