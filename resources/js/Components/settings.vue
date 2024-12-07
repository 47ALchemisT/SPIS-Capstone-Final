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
                                :disabled="form.processing || latestPalakasan?.status === 'cancelled' || latestPalakasan?.status === 'completed' || latestPalakasan?.status === 'pending'"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Force Cancel Palakasan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Continue Cancelled Palakasan Section -->
            <div v-if="latestPalakasan?.status === 'cancelled'" class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <!-- Info Icon -->
                        <svg class="h-6 w-6 text-blue-600 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-blue-800">Continue Cancelled Palakasan</h3>
                        <div class="mt-2 text-blue-700">
                            <p class="text-sm">You can resume this Palakasan if the emergency situation has been resolved. Please note:</p>
                            <ul class="list-disc ml-5 mt-2 text-sm">
                                <li>All previous scores and progress will be maintained</li>
                                <li>All scheduled matches will be preserved</li>
                                <li>You should communicate the continuation to all participants</li>
                            </ul>
                        </div>
                        <div class="mt-4">
                            <button 
                                @click="showContinueModal = true"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Resume Palakasan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <TransitionRoot appear :show="showModal" as="template">
            <Dialog as="div" @close="showModal = false" class="relative z-50">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <div class="flex flex-col items-center">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 mb-4">
                                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>

                                    <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 mb-2 text-center">
                                        Emergency Cancellation Confirmation
                                    </DialogTitle>

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
                                                    class="mt-2 block w-full rounded-lg border-gray-300 p-2.5 ring-1 ring-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                                    placeholder="Enter admin username"
                                                >
                                            </div>
                                            <div>
                                                <label for="password" class="block text-sm font-medium text-gray-700">Admin Password</label>
                                                <input 
                                                    type="password" 
                                                    id="password" 
                                                    v-model="form.password"
                                                    class="mt-2 block w-full p-2.5 ring-1 ring-gray-200 rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
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

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button 
                                        @click="showModal = false"
                                        class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                                    >
                                        Cancel
                                    </button>
                                    <button 
                                        @click="showFinalConfirmationModal = true; showModal = false"
                                        :disabled="form.processing || !form.username || !form.password"
                                        class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                                    >
                                        Confirm Cancel
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Final Confirmation Modal -->
        <TransitionRoot appear :show="showFinalConfirmationModal" as="template">
            <Dialog as="div" @close="closeFinalConfirmation" class="relative z-[60]">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <div class="flex flex-col items-center">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 mb-4">
                                        <svg class="h-6 w-6 text-red-600 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>

                                    <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 mb-2 text-center">
                                        Final Warning
                                    </DialogTitle>

                                    <div class="mt-2">
                                        <p class="text-sm text-center text-gray-500">
                                            Are you absolutely sure you want to cancel the Palakasan? This action is irreversible and will immediately end all ongoing activities.
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button 
                                        @click="closeFinalConfirmation"
                                        class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                                    >
                                        Go Back
                                    </button>
                                    <button 
                                        @click="confirmEmergencyCancel"
                                        class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                    >
                                        Yes, Cancel Palakasan
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Continue Modal -->
        <TransitionRoot appear :show="showContinueModal" as="template">
            <Dialog as="div" @close="showContinueModal = false" class="relative z-50">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <div class="flex flex-col items-center">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 mb-4">
                                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>

                                    <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 mb-2 text-center">
                                        Continue Palakasan Confirmation
                                    </DialogTitle>

                                    <div class="mt-2">
                                        <p class="text-sm text-center text-gray-500 mb-4">
                                            Please enter your admin credentials to continue the Palakasan.
                                        </p>

                                        <!-- Admin Authentication Form -->
                                        <div class="space-y-4 max-w-md mx-auto pt-4">
                                            <div>
                                                <label for="continue-username" class="block text-sm font-medium text-gray-700">Admin Username</label>
                                                <input 
                                                    type="text" 
                                                    id="continue-username" 
                                                    v-model="continueForm.username"
                                                    class="mt-2 block w-full rounded-lg border-gray-300 p-2.5 ring-1 ring-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                    placeholder="Enter admin username"
                                                >
                                            </div>
                                            <div>
                                                <label for="continue-password" class="block text-sm font-medium text-gray-700">Admin Password</label>
                                                <input 
                                                    type="password" 
                                                    id="continue-password" 
                                                    v-model="continueForm.password"
                                                    class="mt-2 block w-full p-2.5 ring-1 ring-gray-200 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                    placeholder="Enter admin password"
                                                >
                                            </div>
                                            <!-- Error Message -->
                                            <div v-if="showContinueError" class="text-sm text-red-600">
                                                {{ continueErrorMessage }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button 
                                        @click="showContinueModal = false"
                                        class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                                    >
                                        Cancel
                                    </button>
                                    <button 
                                        @click="confirmContinuePalakasan"
                                        :disabled="continueForm.processing || !continueForm.username || !continueForm.password"
                                        class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                                    >
                                        Continue Palakasan
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Success Modal with improved design -->
        <TransitionRoot appear :show="showSuccessModal" as="template">
            <Dialog as="div" @close="closeSuccessModal" class="relative z-50">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <div class="flex flex-col items-center">
                                    <!-- Success Icon with Animation -->
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 mb-4 animate-[bounce_1s_ease-in-out_1]">
                                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    
                                    <!-- Title and Message -->
                                    <div class="text-center">
                                        <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 mb-2">
                                            Success!
                                        </DialogTitle>
                                        <p class="text-sm text-gray-500">
                                            {{ successMessage }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="mt-6 flex justify-center">
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition-colors duration-200"
                                        @click="closeSuccessModal"
                                    >
                                        Got it
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Success Modal for Continue Palakasan -->
        <TransitionRoot appear :show="showContinueSuccessModal" as="template">
            <Dialog as="div" @close="closeContinueSuccessModal" class="relative z-50">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/25 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <div class="flex flex-col items-center">
                                    <!-- Success Icon with Animation -->
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 mb-4 animate-[bounce_1s_ease-in-out_1]">
                                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    
                                    <!-- Title and Message -->
                                    <div class="text-center">
                                        <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 mb-2">
                                            Success!
                                        </DialogTitle>
                                        <p class="text-sm text-gray-500">
                                            {{ continueSuccessMessage }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="mt-6 flex justify-center">
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition-colors duration-200"
                                        @click="closeContinueSuccessModal"
                                    >
                                        Got it
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    latestPalakasan: {
        type: Object,
        default: () => ({})
    }
});

const showModal = ref(false);
const showError = ref(false);
const errorMessage = ref('');
const form = useForm({
    username: '',
    password: ''
});

// New success modal refs
const showSuccessModal = ref(false);
const successMessage = ref('');
const showContinueSuccessModal = ref(false);
const continueSuccessMessage = ref('');

const showFinalConfirmationModal = ref(false);

const confirmEmergencyCancel = async () => {
    showError.value = false;
    errorMessage.value = '';
    
    try {
        await form.post(route('palakasan.emergency-cancel', props.latestPalakasan.id), {
            onSuccess: () => {
                showFinalConfirmationModal.value = false;
                showModal.value = false;
                showSuccessModal.value = true;
                successMessage.value = 'Palakasan has been successfully cancelled.';
                form.reset();
            },
            onError: (errors) => {
                showError.value = true;
                errorMessage.value = errors.message || 'Invalid credentials. Please try again.';
                showFinalConfirmationModal.value = false;
            }
        });
    } catch (error) {
        showError.value = true;
        errorMessage.value = 'An unexpected error occurred. Please try again.';
        console.error('Emergency cancel error:', error);
        showFinalConfirmationModal.value = false;
    }
};

const showContinueModal = ref(false);
const showContinueError = ref(false);
const continueErrorMessage = ref('');
const continueForm = useForm({
    username: '',
    password: ''
});

const confirmContinuePalakasan = async () => {
    showContinueError.value = false;
    continueErrorMessage.value = '';
    
    try {
        await continueForm.post(route('palakasan.continue', props.latestPalakasan.id), {
            onSuccess: () => {
                showContinueModal.value = false;
                showContinueSuccessModal.value = true;
                continueSuccessMessage.value = 'Palakasan has been successfully resumed.';
                continueForm.reset();
            },
            onError: (errors) => {
                showContinueError.value = true;
                continueErrorMessage.value = errors.message || 'Invalid credentials. Please try again.';
            }
        });
    } catch (error) {
        showContinueError.value = true;
        continueErrorMessage.value = 'An unexpected error occurred. Please try again.';
        console.error('Continue Palakasan error:', error);
    }
};

const closeSuccessModal = () => {
    showSuccessModal.value = false;
    successMessage.value = '';
};

const closeContinueSuccessModal = () => {
    showContinueSuccessModal.value = false;
    continueSuccessMessage.value = '';
};

const closeFinalConfirmation = () => {
    showFinalConfirmationModal.value = false;
};
</script>
