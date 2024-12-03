<template>
    <Head title="Reset Password" />
    <section class="min-h-screen  dark:bg-gray-900 py-8">
        <div class="max-w-lg mx-auto px-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
                <div class="mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reset Password</h1>
                    <p class="text-gray-600 dark:text-gray-400">Create a new password for this account</p>
                </div>

                <!-- User Information -->
                <div class="mb-6">
                    <div class="bg-gray-100/80 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-800 mb-2">Account Information</h3>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">Name :</span> {{ user.name }}
                            </p>
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">Username :</span> {{ user.username }}
                            </p>
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">ID Number :</span> {{ user.id_number }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Reset Password Form -->
                <form @submit.prevent="resetPassword" class="space-y-6">
                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New Password
                        </label>
                        <div class="relative">
                            <input
                                v-model="form.new_password"
                                :type="showPassword ? 'text' : 'password'"
                                id="new_password"
                                class="w-full p-2.5 ring-1 text-sm ring-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:text-white pr-10"
                                required
                                minlength="8"
                                maxlength="16"
                            />
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                @click="showPassword = !showPassword"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    :class="{ 'hidden': showPassword }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    :class="{ 'hidden': !showPassword }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p v-if="errors?.new_password" class="mt-1 text-sm text-red-600">
                            {{ errors.new_password }}
                        </p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <input
                                v-model="form.new_password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'"
                                id="new_password_confirmation"
                                class="w-full p-2.5 text-sm ring-1 ring-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:text-white pr-10"
                                required
                            />
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                @click="showConfirmPassword = !showConfirmPassword"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    :class="{ 'hidden': showConfirmPassword }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    :class="{ 'hidden': !showConfirmPassword }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p v-if="errors?.new_password_confirmation" class="mt-1 text-sm text-red-600">
                            {{ errors.new_password_confirmation }}
                        </p>
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-blue-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-700 dark:text-gray-300 mb-2">Password Requirements:</h4>
                        <ul class="text-sm text-blue-600 dark:text-gray-400 space-y-1">
                            <li>• Between 8 and 16 characters long</li>
                            <li>• Both passwords must match</li>
                        </ul>
                    </div>

                    <div class="space-y-3">
                        <button
                            type="submit"
                            class="w-full py-2.5 text-sm px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium"
                            :disabled="processing"
                        >
                            {{ processing ? 'Resetting Password...' : 'Reset Password' }}
                        </button>

                        <button
                            type="button"
                            @click="backToAccount"
                            class="w-full py-2.5 text-sm px-4 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium border border-gray-300"
                        >
                            Back to Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black opacity-30"></div>

            <!-- Modal -->
            <div class="relative bg-white dark:bg-gray-800 rounded-lg p-8 max-w-md w-full text-center">
                <div class="mb-6">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Password Reset Successful!</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Your password has been successfully reset. You can now log in with your new password.
                    </p>
                </div>
                <button
                    @click="redirectToLogin"
                    class="w-full py-2.5 text-sm px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium"
                >
                    Go Back to Account Settings
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    errors: Object,
    flash: Object
});

const showSuccessModal = ref(false);

// Watch for flash messages
watch(() => props.flash?.success, (success) => {
    if (success) {
        showSuccessModal.value = true;
        // Clear the form
        form.new_password = '';
        form.new_password_confirmation = '';
    }
});

const form = reactive({
    id: props.user.id,
    new_password: '',
    new_password_confirmation: ''
});

const processing = ref(false);
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const resetPassword = () => {
    processing.value = true;
    router.post('/reset-password', form, {
        preserveScroll: true,
        onFinish: () => {
            processing.value = false;
        }
    });
};

const backToAccount = () => {
    router.get('/forgot-password', {
        data: {
            foundUser: {
                id: props.user.id,
                name: props.user.name,
                username: props.user.username,
                id_number: props.user.id_number
            }
        }
    });
};

// Add this new function for the modal's login button
const redirectToLogin = () => {
    router.visit('/account-settings');

};
</script>