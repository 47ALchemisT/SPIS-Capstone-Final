<template>
    <Head title="Login" />
    <section class="bg-gray-50 dark:bg-gray-900">
      <div class="flex items-center max-w-screen-xl px-4 mx-auto pt-4">
        <img src="../../assets/logoMSU.png" class="w-12 h-12 rounded-full mr-3" alt="Logo" />
        <img src="../../assets/Logo.png" class="w-12 h-12 rounded-full mr-3" alt="Logo" />
        <h1 class="text-2xl font-bold">MSUN Supreme Student Council</h1>
      </div>
      <div class="grid lg:grid-cols-2 gap-8 max-w-screen-xl mx-auto py-4 px-4">
        <img src="../../assets/torches.png" class="object-cover h-30 rounded-md" alt="Description" />
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Log in to your account:</h1>
          <h3 class="text-sm font-medium text-gray-600">Welcome back! Please enter your account details.</h3>
  
          <form @submit.prevent="submit" class="mt-3 space-y-4">
            <!-- Account Type Dropdown -->
            <label for="role" class="text-sm font-medium text-gray-900 dark:text-white">Select account type:</label>
            <select v-model="form.role" id="role" class="bg-gray-50 border-gray-300 text-sm text-gray-900 rounded-lg p-2.5 w-full dark:bg-gray-700 dark:text-white" required>
              <option disabled value="">Select account type</option>
              <option value="Admin">Admin</option>
              <option value="Facilitator">Facilitator</option>
              <option value="College Sport Head">College Sports Committee Head</option>
            </select>
  
            <!-- Username Input -->
            <label for="username" class="block text-sm font-medium text-gray-900 dark:text-white">Username</label>
            <input v-model="form.username" type="text" id="username" class="w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" placeholder="username" required />
  
            <!-- Password Input -->
            <label for="password" class="block text-sm font-medium text-gray-900 dark:text-white">Password</label>
            <input v-model="form.password" type="password" id="password" class="w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" placeholder="••••••••••" required />
  
            <!-- Error Message -->
            <span v-if="errorMessage" class="text-sm text-red-600">{{ errorMessage }}</span>
  
            <!-- Remember Me Checkbox -->
            <div class="flex justify-between items-center">
              <div class="flex items-center">
                <input v-model="form.remember" type="checkbox" id="remember" class="w-4 h-4 border-gray-300 rounded bg-gray-50 dark:bg-gray-700" />
                <label for="remember" class="text-sm font-medium text-gray-500 dark:text-gray-400 ml-2">Remember this device</label>
              </div>
            </div>
  
            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">Sign in</button>
          </form>
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
  