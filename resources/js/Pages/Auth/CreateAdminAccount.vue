<template>
    <Head title="Create Admin Account - Step 2" />
    <section class="min-h-screen flex items-center justify-center dark:bg-gray-900">
        <div class="w-full max-w-xl p-4">
            <div class="p-6 bg-white dark:bg-gray-800">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-0.5">Create Admin Account</h1>
                <h3 class="text-sm text-gray-600 mb-6">Set up administrator credentials</h3>

                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Student Information</h4>
                        <div class="mt-2 space-y-1">
                            <p class="text-sm text-gray-600 dark:text-gray-300">Name: {{ student.first_name }} {{ student.last_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">ID Number: {{ student.id_number }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">College: {{ student.college }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Email: {{ student.univ_email }}</p>
                        </div>
                    </div>

                    <form @submit.prevent="createAdmin" class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-900 dark:text-white mb-2">Username</label>
                            <input 
                                v-model="form.username" 
                                type="text" 
                                class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" 
                                required 
                            />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-900 dark:text-white mb-2">Password</label>
                            <input 
                                v-model="form.password" 
                                type="password" 
                                class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" 
                                required 
                            />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-900 dark:text-white mb-2">Confirm Password</label>
                            <input 
                                v-model="form.password_confirmation" 
                                type="password" 
                                class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" 
                                required 
                            />
                        </div>

                        <div class="mt-2">
                            <span v-if="errorMessage" class="text-sm text-red-600">{{ errorMessage }}</span>
                        </div>

                        <div class="flex justify-end space-x-3 font-medium mt-6">
                            <button 
                                type="button"
                                @click="goBack"
                                class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800"
                            >
                                Back
                            </button>
                            <button 
                                type="submit" 
                                class="px-6 py-2 text-sm bg-blue-700 text-white rounded-lg hover:bg-blue-800"
                            >
                                Create Admin Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl max-w-sm w-full mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Account Created Successfully!</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">Your admin account has been created. You can now log in to access your dashboard.</p>
                <button 
                    @click="goToLogin"
                    class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800"
                >
                    Go to Login
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { router, Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    student: {
        type: Object,
        required: true
    }
});

const showSuccessModal = ref(false);

const goToLogin = () => {
    router.visit('/login');
};

const errorMessage = ref('');

const form = reactive({
    username: '',
    password: '',
    password_confirmation: '',
    student_id: props.student.id,
});

const goBack = () => {
    router.delete(`/register-admin/cancel-student/${props.student.id}`, {
        preserveScroll: true
    });
};

const createAdmin = () => {
    if (form.password !== form.password_confirmation) {
        errorMessage.value = 'Passwords do not match';
        return;
    }

    console.log('Student ID:', props.student.id);
    console.log('Form data:', {
        username: form.username,
        student_id: form.student_id,
        password: 'REDACTED'
    });

    router.post('/register-admin/create-account', form, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccessModal.value = true;
        },
        onError: (errors) => {
            console.error('Admin creation error:', errors);
            if (errors.username) {
                errorMessage.value = errors.username;
            } else if (errors.password) {
                errorMessage.value = errors.password;
            } else if (errors.message) {
                errorMessage.value = errors.message;
            } else if (errors.student_id) {
                errorMessage.value = errors.student_id;
            } else {
                errorMessage.value = 'Error creating admin account';
            }
        }
    });
};
</script>
