<template>
    <Head title="Sub-Admin Dashboard"/>
    <AppLayout :user ="user">
        <template v-slot:default>
            <Toast ref="toastRef" />

            <!-- Tabs Navigation -->
            <div class="sticky top-[4rem] py-2 z-40 bg-white/80 backdrop-blur-sm">
                <nav class="flex flex-col sm:flex-row relative justify-between items-start sm:items-center py-1 space-y-2 sm:space-y-0">
                <div class="">
                    <h1 class="text-lg font-medium text-gray-700">Sub Admin Dashboard</h1>
                </div>
                <div class="flex gap-2 rounded-lg w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0">
                    <div class="flex gap-2 rounded-lg">
                    <button 
                        v-for="tab in ['home',  'settings']"
                        :key="tab"
                        @click="activeTab = tab"
                        :class="[
                        'px-5 py-2 text-sm whitespace-nowrap',
                        activeTab === tab
                            ? 'text-gray-800 bg-blue-700 text-white font-medium rounded-lg'
                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium border-transparent'
                        ]"
                    >
                        {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                    </button>
                    </div>
                </div>
                </nav>
            </div>

            <!-- Sports Management Tab -->
            <div v-if="activeTab === 'home'" class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Header Section -->
                <div class="bg-white rounded-lg pt-6 mb-6">
                    <h1 class="text-lg font-medium text-gray-800">Sports Management</h1>
                    <p class="text-gray-600">Manage and monitor all sports events and tournaments</p>
                </div>
                <!-- Filters Section -->
                <div class="bg-white rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input
                                v-model="searchQuerySports"
                                type="text"
                                placeholder="Search sports..."
                                class="pl-10 w-full border border-gray-300 bg-gray-50 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>

                        <!-- Setup Filter -->
                        <div>
                            <select
                                v-model="setupFilter"
                                class="w-full border border-gray-300 bg-gray-50 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">All Setups</option>
                                <option v-for="setup in uniqueSetups" :key="setup" :value="setup">
                                    {{ setup }}
                                </option>
                            </select>
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <select
                                v-model="typeFilter"
                                class="w-full border border-gray-300 bg-gray-50 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">All Types</option>
                                <option value="Men">Men</option>
                                <option value="Women">Women</option>
                                <option value="Mixed">Mixed</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <select
                                v-model="statusFilter"
                                class="w-full border border-gray-300 bg-gray-50 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">All Status</option>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Completed">Completed</option>
                                <option value="Pending">Pending</option>
                                <option value="scheduled">Scheduled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sports Grid -->
                <div v-if="filteredAndSortedSports.length === 0" class="bg-white shadow-sm rounded-lg p-10">
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="rounded-full bg-blue-50 p-3 mb-4">
                            <svg class="w-8 h-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">No sports found</h3>
                        <p class="text-gray-500 text-sm">We couldn't find any sports that match your search criteria.</p>
                    </div>
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pb-4">
                    <div
                        v-for="(sport, index) in filteredAndSortedSports"
                        :key="index"
                        @click="viewSport(sport.id)"
                        :class="{
                            'shadow-sm rounded-lg hover:shadow-md transition-all duration-200 ring-1 overflow-hidden group cursor-pointer': true,
                            '': sport.status !== 'scheduled',
                            'bg-blue-50 ring-blue-300': sport.status === 'scheduled'
                        }"
                    >
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ sport.sport.name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ sport.categories }}</p>
                                </div>
                                <div class="rounded-full p-2 group-hover:bg-blue-100 transition-colors">
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>

                            <div class="mt-4 space-y-2">
                                <div :class="{
                                    'flex items-center text-sm': true,
                                    'text-gray-500': sport.status !== 'scheduled',
                                    'text-blue-600': sport.status === 'scheduled'
                                }">
                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ sport.setup }}
                                </div>
                                <div :class="{
                                    'flex items-center text-sm': true,
                                    'text-gray-500': sport.status !== 'scheduled',
                                    'text-blue-600': sport.status === 'scheduled'
                                }">
                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ sport.type }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Tab -->
            <div v-if="activeTab === 'settings'" class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg pt-6">
                    <div class="space-y-6">
                        <!-- Account Settings Section -->
                        <div class="">
                            <h2 class="text-lg font-medium text-gray-900">Account Settings</h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Manage your account settings and preferences.
                            </p>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="space-y-4">
                            <div class="bg-white rounded-lg border border-gray-200 p-6">
                                <div class="space-y-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                                            <p class="mt-1 text-sm text-gray-600">Your personal details and information.</p>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Student
                                            </span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                            <p class="mt-2 text-sm text-gray-900">{{ userInfo.firstName }} {{ userInfo.lastName }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">College</label>
                                            <p class="mt-2 text-sm text-gray-900">{{ user.student.college }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                            <p class="mt-2 text-sm text-gray-900">{{ userInfo.email }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Sudent ID</label>
                                            <p class="mt-2 text-sm text-gray-900">{{ userInfo.studentID }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information Section -->
                        <div class="space-y-4">
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <div>
                                <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                                <p class="mt-1 text-sm text-gray-600">Details about your facilitator account.</p>
                                </div>
                                <div class="flex items-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                <label class="block text-sm font-medium text-gray-700">Account Type</label>
                                <div class="mt-2 flex items-center">
                                    <p class="text-sm text-gray-900">Sub Admin</p>
                                </div>
                                </div>

                                <div>
                                <label class="block text-sm font-medium text-gray-700">Account Created</label>
                                <div class="mt-2 flex items-center">
                                    <p class="text-sm text-gray-900">{{ formatDate(user.created_at) }}</p>
                                </div>
                                </div>

                            </div>
                            <!-- Password Change Section -->
                            <div class="space-y-4">
                                <div class="bg-blue-50 rounded-lg p-6">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                                    <div>
                                    <h3 class="text-md font-medium text-blue-800 mb-1">Password & Security</h3>
                                    <p class="text-sm text-blue-500">Update your password to keep your account secure</p>
                                    </div>
                                    <Link
                                    :href="route('password.change.form', user.id)"
                                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                    >
                                    Change Password
                                    </Link>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- Support Section -->
                        <div class="mt-10">
                            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Need Help?</h3>
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-gray-900 font-medium">Contact Support</p>
                                        <p class="text-sm text-gray-600 mb-2">Need assistance? Our support team is here to help.</p>
                                        <a href="mailto:support@palakasan.com" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                            support@palakasan.com
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/Layout/DashboardLayoutSA.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    sports: Array,
    facilitator: Array,
    user: Object
});

// Tabs
const activeTab = ref('home');  // Default to "Details" 

// User Information
const userInfo = ref({
    firstName: props.user?.student?.first_name || '',
    lastName: props.user?.student?.last_name || '',
    email: props.user?.student?.univ_email || '',
    studentID: props.user?.student?.id_number || '',
});

// Password Change Form
const passwordForm = ref({
    currentPassword: '',
    newPassword: '',
    confirmPassword: ''
});

// Password Update Function
const updatePassword = () => {
    if (passwordForm.value.newPassword !== passwordForm.value.confirmPassword) {
        // Show error in Toast
        return;
    }

    router.post(route('update-password'), {
        current_password: passwordForm.value.currentPassword,
        new_password: passwordForm.value.newPassword,
        new_password_confirmation: passwordForm.value.confirmPassword
    }, {
        onSuccess: () => {
            passwordForm.value = {
                currentPassword: '',
                newPassword: '',
                confirmPassword: ''
            };
            // Show success message in Toast
        }
    });
};

// Search and Sort Functionality
const searchQuerySports = ref('');
const typeFilter = ref('');
const statusFilter = ref('');
const setupFilter = ref('');

// Get unique setups from sports array
const uniqueSetups = computed(() => {
    return [...new Set(props.sports.map(sport => sport.setup))].filter(Boolean).sort();
});

const filteredAndSortedSports = computed(() => {
    let filtered = props.sports;
    
    if (searchQuerySports.value) {
        const searchTerm = searchQuerySports.value.toLowerCase();
        filtered = filtered.filter(sport => 
            sport.sport.name.toLowerCase().includes(searchTerm) ||
            sport.type.toLowerCase().includes(searchTerm) ||
            sport.categories.toLowerCase().includes(searchTerm)
        );
    }
    
    if (typeFilter.value) {
        filtered = filtered.filter(sport => 
            sport.type === typeFilter.value
        );
    }
    
    if (statusFilter.value) {
        filtered = filtered.filter(sport => 
            sport.status === statusFilter.value
        );
    }
    
    if (setupFilter.value) {
        filtered = filtered.filter(sport => 
            sport.setup === setupFilter.value
        );
    }
    
    return filtered;
});

const viewSport = (sportId) => {
    router.get(route('sa-sportview.index', { sport: sportId }));
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};
</script>