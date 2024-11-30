<template>
    <div>
        <!-- Emergency Actions Section -->
        <div class="bg-white rounded-lg mt-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Emergency Actions</h2>
            
            <!-- Emergency Cancel Section -->
            <div class="bg-red-50 p-4 mb-6 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <!-- Warning Icon -->
                        <svg class="h-6 w-6 text-red-600 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-red-800">Emergency Palakasan Cancellation</h3>
                        <div class="mt-2 text-red-700">
                            <p class="text-sm">This action should only be used in emergency situations such as:</p>
                            <ul class="list-disc ml-5 mt-2 text-sm">
                                <li>Severe weather conditions or natural disasters</li>
                                <li>Security threats or safety concerns</li>
                                <li>Medical emergencies affecting multiple participants</li>
                                <li>Facility-related emergencies (power outage, structural issues)</li>
                                <li>Other unforeseen critical circumstances</li>
                            </ul>
                        </div>
                        <div class="mt-4">
                            <button 
                                @click="showModal = true"
                                :disabled="form.processing || latestPalakasan?.status === 'cancelled' || latestPalakasan?.status === 'completed'"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Force Cancel Palakasan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-black bg-opacity-25 transition-opacity" aria-hidden="true"></div>
                <!-- Modal panel -->
                <div class="inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full relative">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex flex-col items-center">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                <!-- Warning icon -->
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class=" w-full">
                                <h3 class="text-lg leading-6 font-medium text-center text-gray-900 mb-4" id="modal-title">
                                    Emergency Cancellation Confirmation
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-center text-gray-500 mb-4">
                                        WARNING: This will immediately cancel the ongoing Palakasan. This action cannot be undone.
                                        Please enter your admin credentials to proceed with the emergency cancellation.
                                    </p>
                                    
                                    <!-- Admin Authentication Form -->
                                    <div class="space-y-4 max-w-md mx-auto pt-4">
                                        <div>
                                            <label for="username" class="block text-sm font-medium text-gray-700">Admin Username</label>
                                            <input 
                                                type="text" 
                                                id="username" 
                                                v-model="form.username"
                                                class="mt-2 block w-full rounded-md border-gray-300 p-2.5 ring-1 ring-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                                placeholder="Enter admin username"
                                            >
                                        </div>
                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700">Admin Password</label>
                                            <input 
                                                type="password" 
                                                id="password" 
                                                v-model="form.password"
                                                class="mt-2 block w-full p-2.5 ring-1 ring-gray-200 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                                placeholder="Enter admin password"
                                            >
                                        </div>
                                        <!-- Error Message -->
                                        <div v-if="showError" class="text-sm text-red-600">
                                            {{ errorMessage }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full px-4 py-6 sm:px-6 flex justify-end space-x-3">
                        <button 
                            @click="showModal = false"
                            class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm"
                        >
                            Cancel
                        </button>
                        <button 
                            @click="confirmEmergencyCancel"
                            :disabled="form.processing || !form.username || !form.password"
                            class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Force Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    latestPalakasan: {
        type: Object,
        required: true
    },
    admin: {
        type: Array,
        required: true,
        default: () => []
    }
});

const showModal = ref(false);
const showError = ref(false);
const errorMessage = ref('');

const form = useForm({
    username: '',
    password: '',
});

const confirmEmergencyCancel = async () => {
    try {
        // Send credentials to backend for validation
        await form.post(route('palakasan.emergency-cancel', props.latestPalakasan.id));
        showModal.value = false;
    } catch (error) {
        showError.value = true;
        errorMessage.value = error.message || 'An error occurred during validation';
        console.error('Emergency cancel error:', error);
    }
};
</script>