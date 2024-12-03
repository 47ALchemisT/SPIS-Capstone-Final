<template>
    <Head title="Account Settings"/>
    <DashboardLayout>
        <div class="container mx-auto pb-8">
            <div class="bg-white rounded-lg ">
                <!-- Profile Header -->
                <div class=" text-gray-800 rounded-t-lg">
                    <h1 class="text-2xl font-bold">Account Settings</h1>
                    <p class="text-gray-600">Manage your account information and security settings</p>
                </div>

                <div class="flex  p-6 ring-1 mt-6 ring-gray-300 rounded-lg">
                    <div class="flex-shrink-0">
                        <img 
                            src="/resources/assets/user23.gif" 
                            alt="Profile Animation"
                            class="w-32 h-32 rounded-xl object-cover"
                        />
                    </div>
                    <div class="ml-8 mt-2">
                        <h3 class="text-2xl font-medium text-gray-900">{{ user.name }}</h3>
                        <div class="mt-2">
                            <span class="inline-flex items-center rounded-full font-medium ">
                                {{ user.role }}
                            </span>
                        </div>
                        <h3 class="text-sm text-gray-700">Supreme Student Council</h3>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="p-6 ring-1 ring-gray-300 rounded-lg mt-6">
                    <h2 class="text-xl font-semibold mb-6">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div class="rounded-lg p-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Name</div>
                            <div class="text-md text-gray-900">{{ user.name }}</div>
                        </div>

                        <!-- Email -->
                        <div class="rounded-lg p-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Email</div>
                            <div class="text-md text-gray-900">{{ user.email }}</div>
                        </div>

                        <!-- ID Number -->
                        <div class="rounded-lg p-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">ID Number</div>
                            <div class="text-md text-gray-900">{{ user.id_number }}</div>
                        </div>

                        <!-- College -->
                        <div class="rounded-lg p-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">College</div>
                            <div class="text-md text-gray-900">{{ user.college }}</div>
                        </div>

                        <!-- Role -->
                        <div class="rounded-lg p-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Role</div>
                            <div class="text-md text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ user.role }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 ring-1 ring-gray-300 rounded-lg mt-6 gap-6">
                    <!-- Left Column: Account Information -->
                    <div class="p-6 rounded-lg">
                        <h2 class="text-xl font-semibold mb-6">Account Information</h2>
                        <div class="space-y-4">
                            <!-- Username -->
                            <div class="p-4 rounded-lg border border-gray-200">
                                <div class="text-sm font-medium text-gray-500">Username</div>
                                <div class="text-md font-medium text-gray-900">{{ user.username }}</div>
                            </div>
                            <!-- Password -->
                            <div class="p-4 rounded-lg border border-gray-200">
                                <div class="text-sm font-medium text-gray-500">Password</div>
                                <div class="flex items-center space-x-2">
                                    <input 
                                        :type="showPassword ? 'text' : 'password'" 
                                        :value="'********'"
                                        readonly
                                        class="bg-transparent border-none text-md font-medium text-gray-900 focus:ring-0"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Security Information -->
                    <div class="p-6  rounded-lg">
                        <h2 class="text-xl font-semibold mb-6">Security Information</h2>
                        <div class="space-y-6">
                            <!-- Account Status -->
                            <div class="p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-500 mb-2">Account Status</div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-900">Active and Secure</span>
                                </div>
                            </div>

                            <!-- Account Created -->
                            <div class="p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-500 mb-2">Account Created</div>
                                <div class="text-sm text-gray-900">{{ formatDate(user.created_at) }}</div>
                            </div>

                            <!-- Security Tips -->
                            <div class="p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-500 mb-3">Security Tips</div>
                                <ul class="space-y-2">
                                    <li class="flex items-start text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Use a strong, unique password
                                    </li>
                                    <li class="flex items-start text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Change password regularly
                                    </li>
                                    <li class="flex items-start text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Never share your credentials
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" mt-6">
                    <div class="">
                        <!-- Account Settings Section -->
                        <div class="bg-white rounded-lg border border-gray-300 p-6 mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Account Settings</h2>
                                                
                            <!-- Change Password Card -->
                            <div class="bg-blue-50 rounded-lg p-6">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="text-md font-medium text-blue-700 mb-1">Password & Security</h3>
                                        <p class="text-sm text-blue-500">Update your password to keep your account secure</p>
                                    </div>
                                    <div>
                                        <Link
                                            :href="route('password.change.form', user.id)"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                            >
                                            Change Password
                                        </Link>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 ring-1 ring-gray-300 mt-6 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Forgot Password</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        If you're having trouble accessing your account or can't remember your password, use the forgot password feature. 
                        This will allow you to search for a student account using their ID number and reset their password, this can be used 
                        in all accounts.
                    </p>
                    <button 
                        type="button" 
                        @click="router.get('/forgot-password')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        Reset Student Password
                    </button>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { useForm, Head, router, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import DashboardLayout from '@/Layout/DashboardLayout.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const showPassword = ref(false);

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

</script>
