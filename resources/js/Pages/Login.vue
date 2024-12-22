<template>
    <Head title="Login" />
    <section class="grid grid-cols-1 sm:grid-cols-2  dark:bg-gray-900 min-h-full flex items-center justify-center relative">
      <div class="h-screen sm:block hidden">
        <img class="h-screen w-full object-cover hidden-mobile" src="/resources/assets/U.png" alt="">
      </div>
      <div class="flex items-center justify-center">
        <div class="w-full max-w-xl p-4 relative z-10">
          <div class="p-6 bg-white dark:bg-gray-800">
            <div class="flex sm:mb-2 mb-2 gap-2 relative">
              <img src="/resources/assets/logoMSU.png" alt="MSU Logo" class="h-12 w-auto relative">
              <img src="/resources/assets/ssclogo.jpg" alt="MSU Logo" class="h-12 w-auto relative rounded-full">
            </div>
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
                <div class="relative">
                    <input 
                        v-model="form.password" 
                        :type="showPassword ? 'text' : 'password'" 
                        id="password" 
                        class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" 
                        placeholder="••••••••••" 
                        required 
                    />
                    <button 
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600"
                    >
                        <svg 
                            v-if="!showPassword"
                            class="w-5 h-5" 
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg 
                            v-else
                            class="w-5 h-5" 
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                <div v-if="errorMessage" class="text-sm p-3 bg-red-100/70 text-red-600 mt-2 rounded-lg">
                  <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                  {{ errorMessage }}
                </div>
              </div>
              <!-- Error Message -->
    
              <!-- Remember Me Checkbox -->
              <div class="flex justify-between items-center pb-6">
                <div class="flex justify-between items-center">
                  <div class="flex items-center">
                    <input v-model="form.remember" type="checkbox" id="remember" class="w-4 h-4 border-gray-300 rounded bg-gray-50 dark:bg-gray-700" />
                    <label for="remember" class="text-sm font-medium text-gray-500 dark:text-gray-400 ml-2">Remember this device</label>
                  </div>
                </div><!-- Create Admin Account Section -->
                <Link 
                  v-if="hasAdmin.length === 0"
                  href="/create-admin"
                  class="font-medium text-sm text-blue-600 rounded-lg hover:underline inline-block text-center"
                >
                  Create Admin Account
                </Link>
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
  import { router, Head, Link } from '@inertiajs/vue3';
  
  const props = defineProps({
    hasAdmin: {
      type: Object,
      required: true,
      default: () => ({})
    },
    errors: Object,
    flash: Object,
    sharedPalakasans: Array
  });

  // Create the form using reactive to ensure reactivity
  const form = reactive({
    username: '',
    password: '',
    role: '',
    remember: false
  });
  
  // Reactive property to store error messages
  const errorMessage = ref('');
  
  const showPassword = ref(false);
  
  // Handle form submission
  const submit = () => {
  
    // Reset error message before submission
    errorMessage.value = '';

    // Submit form via Inertia.js
    router.post('/login', form, {
        onSuccess: () => {
            // Clear any existing error messages on success
            errorMessage.value = '';
        },
        onError: (errors) => {
            if (errors.message) {
                errorMessage.value = errors.message;
            } else if (errors.username || errors.password || errors.role) {
                errorMessage.value = errors.username || errors.password || errors.role;
            } else {
                errorMessage.value = 'An unexpected error occurred. Please try again.';
            }
        }
    });
  };
  </script>
  
  <style scoped>
</style>