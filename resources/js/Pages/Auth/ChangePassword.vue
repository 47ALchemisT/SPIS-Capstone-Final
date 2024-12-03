<template>
    <Head title="Change Password" />
    <section class="min-h-screen dark:bg-gray-900 py-8">
        <div class="max-w-lg mx-auto px-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
                <div class="mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Change Password</h1>
                    <p class="text-gray-600 dark:text-gray-400">Update your account password</p>
                </div>

                <!-- User Information -->
                <div class="mb-6">
                    <div class="bg-gray-100/80 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-800 mb-2">Account Information</h3>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">Name:</span> {{ account.name }}
                            </p>
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">Username:</span> {{ account.username }}
                            </p>
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">ID Number:</span> {{ account.id_number }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Change Password Form -->
                <form @submit.prevent="changePassword" class="space-y-6">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Current Password
                        </label>
                        <div class="relative">
                            <input
                                v-model="form.current_password"
                                :type="showCurrentPassword ? 'text' : 'password'"
                                id="current_password"
                                class="w-full p-2.5 ring-1 text-sm ring-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:text-white pr-10"
                                required
                            />
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                @click="showCurrentPassword = !showCurrentPassword"
                            >
                                <i class="fas" :class="showCurrentPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <p v-if="form.errors.current_password" class="mt-1 text-sm text-red-600">
                            {{ form.errors.current_password }}
                        </p>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New Password
                        </label>
                        <div class="relative">
                            <input
                                v-model="form.new_password"
                                :type="showNewPassword ? 'text' : 'password'"
                                id="new_password"
                                class="w-full p-2.5 ring-1 text-sm ring-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:text-white pr-10"
                                required
                                minlength="8"
                                maxlength="16"
                            />
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                @click="showNewPassword = !showNewPassword"
                            >
                                <i class="fas" :class="showNewPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <p v-if="form.errors.new_password" class="mt-1 text-sm text-red-600">
                            {{ form.errors.new_password }}
                        </p>
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <input
                                v-model="form.new_password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'"
                                id="new_password_confirmation"
                                class="w-full p-2.5 ring-1 text-sm ring-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:text-white pr-10"
                                required
                            />
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                @click="showConfirmPassword = !showConfirmPassword"
                            >
                                <i class="fas" :class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <p v-if="form.errors.new_password_confirmation" class="mt-1 text-sm text-red-600">
                            {{ form.errors.new_password_confirmation }}
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <Link
                            @click="redirectToDashboard"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                        >
                            <span v-if="form.processing">Changing...</span>
                            <span v-else>Change Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success Modal -->
        <div v-if="showSuccessModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <!-- Modal -->
                <div class="relative bg-white rounded-lg max-w-md w-full p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Password Changed Successfully</h3>
                        <p class="text-sm text-gray-500 mb-6">Your password has been changed successfully. Please use your new password for future logins.</p>
                        <div class="flex justify-center">
                            <button
                                @click="redirectToDashboard"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Back to Dashboard
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    account: Object,
    errors: Object,
});

const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);
const showSuccessModal = ref(false);

const form = useForm({
    id: props.account.id,
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});

const changePassword = () => {
    form.post(route('password.change'), {
        preserveScroll: true,
        onSuccess: () => {
            showSuccessModal.value = true;
            form.reset();
        },
    });
};

const redirectToDashboard = () => {
    window.history.back();
};
</script>