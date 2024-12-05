<template>
    <Head title="Forgot Password" />
    <section class="min-h-screen dark:bg-gray-900 flex items-center justify-center py-12">
        <div class="w-full max-w-lg px-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg  p-8">
                <div class=" mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Forgot Password</h1>
                    <p class="text-gray-600 dark:text-gray-400">Enter your Student ID number to recover your account</p>
                </div>

                <!-- ID Number Form -->
                <form @submit.prevent="searchUser" v-if="!foundUser" class="space-y-4">
                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student ID Number</label>
                        <input
                            v-model="form.id_number"
                            type="number"
                            id="id_number"
                            class="w-full p-2.5 ring-1 text-sm ring-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:text-white"
                            placeholder="Enter Student ID number"
                            required
                        />
                    </div>
                    
                    <!-- Error Message -->
                    <div v-if="error" class="p-2.5 text-sm text-red-600 bg-red-100 rounded-lg">
                        {{ error }}
                    </div>

                    <button
                        type="submit"
                        class="w-full py-2.5 text-sm px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium"
                        :disabled="processing"
                    >
                        {{ processing ? 'Searching...' : 'Search Account' }}
                    </button>
                </form>

                <!-- User Information Display -->
                <div v-if="foundUser" class="space-y-6 max-w-lg mx-auto">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-green-700 font-medium">Account Found!</span>
                        </div>
                    </div>

                    <div class="flex p-4 ring-1 mt-6 ring-gray-300 rounded-lg">
                        <div class="flex-shrink-0">
                            <img 
                                src="/resources/assets/user23.gif" 
                                alt="Profile Animation"
                                class="w-16 h-16 rounded-xl object-cover"
                            />
                        </div>
                        <div class="ml-4 mt-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ foundUser.name }}</h3>
                            <div>
                                <span class="text-sm rounded-full text-gray-600">
                                    {{ foundUser.role }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <button
                            @click="proceedToReset"
                            :disabled="foundUser.status === 0"
                            class="w-full py-2.5 text-sm px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium"
                        >
                            Reset Password
                        </button>
                        
                        <button
                            @click="searchAgain"
                            class="w-full py-2.5 text-sm px-4 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium border border-gray-300"
                        >
                            Search Again
                        </button>
                    </div>
                </div>

                <!-- Back to Login Link -->
                <div class="mt-6 text-center">
                    <Link
                        href="/account-settings"
                        class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400"
                    >
                        Back to Account Settings
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    error: String,
    foundUser: Object
});

const form = reactive({
    id_number: ''
});

const processing = ref(false);
const error = ref('');
const foundUser = ref(null);

const searchUser = () => {
    console.log('Submitting search with ID:', form.id_number);
    processing.value = true;
    
    router.post('/search-user', form, {
        preserveScroll: true,
        onSuccess: (page) => {
            console.log('Search success response:', page.props);
            if (page.props.error) {
                console.log('Error received:', page.props.error);
                error.value = page.props.error;
            }
            if (page.props.foundUser) {
                console.log('User found:', page.props.foundUser);
                // Check if the found user's account is deactivated
                if (page.props.foundUser.student_account && page.props.foundUser.student_account.status === false) {
                    error.value = 'This account has been deactivated.';
                    foundUser.value = null;
                } else {
                    foundUser.value = page.props.foundUser;
                }
            }
        },
        onError: (errors) => {
            console.error('Search failed:', errors);
            error.value = 'Failed to find account.';
        },
        onFinish: () => {
            console.log('Search request finished');
            processing.value = false;
        }
    });
};

const proceedToReset = () => {
    if (props.foundUser) {
        console.log('Proceeding to reset for user:', props.foundUser.id);
        router.get(`/reset-password/${props.foundUser.id}`);
    } else {
        console.error('No user found to reset password');
    }
};

const searchAgain = () => {
    form.id_number = '';
    router.get('/forgot-password');
};

// Debug mounted
console.log('Component mounted with props:', props);
</script>
