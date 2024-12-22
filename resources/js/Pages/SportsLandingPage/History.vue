<template>
    <Head title="History" />
    <MainLayout :latestPalakasan="latestPalakasan">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">Palakasan History</h1>
                
                <!-- Search Input -->
                <div class="mb-4">
                    <div class="relative">
                        <input 
                            type="text" 
                            v-model="searchQuery" 
                            placeholder="Search palakasan events..." 
                            class="w-full sm:w-72 pr-4 pl-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Desktop Table View -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Year</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Theme</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tagline</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duration</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="palakasan in paginatedPalakasans" :key="palakasan.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ palakasan.year }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ palakasan.theme }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ palakasan.tagline }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ formatDate(palakasan.start_date) }} - {{ formatDate(palakasan.end_date) }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span :class="{
                                        'px-2 py-1 text-xs font-medium rounded-full capitalize': true,
                                        'bg-green-100 text-green-800': palakasan.status === 'completed'
                                    }">
                                        {{ palakasan.status }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm">
                                    <Link 
                                        :href="route('history.show', palakasan.id)"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                    >
                                        View Details
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="sm:hidden space-y-4">
                    <div v-for="palakasan in paginatedPalakasans" :key="palakasan.id" 
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ palakasan.year }}
                            </div>
                            <span :class="{
                                'px-2 py-1 text-xs font-medium rounded-full capitalize': true,
                                'bg-green-100 text-green-800': palakasan.status === 'completed'
                            }">
                                {{ palakasan.status }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <div class="text-sm font-medium text-gray-500">Theme</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ palakasan.theme }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Tagline</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ palakasan.tagline }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Duration</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ formatDate(palakasan.start_date) }} - {{ formatDate(palakasan.end_date) }}
                                </div>
                            </div>
                            <div class="pt-2">
                                <Link 
                                    :href="route('history.show', palakasan.id)"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                                >
                                    View Details →
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                    <div class="text-sm text-gray-500 text-center sm:text-left">
                        Showing {{ paginatedPalakasans.length === 0 ? 0 : (currentPage - 1) * itemsPerPage + 1 }} 
                        to {{ Math.min(currentPage * itemsPerPage, filteredPalakasans.length) }} 
                        of {{ filteredPalakasans.length }} entries
                    </div>
                    <div class="flex items-center space-x-2">
                        <button 
                            @click="currentPage--" 
                            :disabled="currentPage === 1"
                            class="px-3 py-1 rounded border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Previous
                        </button>
                        <span class="px-3 py-1 text-sm text-gray-700">
                            Page {{ currentPage }} of {{ totalPages }}
                        </span>
                        <button 
                            @click="currentPage++" 
                            :disabled="currentPage >= totalPages"
                            class="px-3 py-1 rounded border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Next
                        </button>
                    </div>
                </div>

                <!-- No Results Message -->
                <div v-if="filteredPalakasans.length === 0" class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">No palakasan events found.</p>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
    palakasans: {
        type: Array,
        default: () => []
    },
    latestPalakasan: {
        type: Object,
        required: true
    }
});

const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 15;

const filteredPalakasans = computed(() => {
    if (!searchQuery.value) return props.palakasans;
    
    const query = searchQuery.value.toLowerCase();
    return props.palakasans.filter(palakasan => 
        palakasan.theme.toLowerCase().includes(query) ||
        palakasan.tagline.toLowerCase().includes(query) ||
        palakasan.year.toString().includes(query) ||
        palakasan.status.toLowerCase().includes(query)
    );
});

const totalPages = computed(() => Math.ceil(filteredPalakasans.value.length / itemsPerPage));

const paginatedPalakasans = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredPalakasans.value.slice(start, end);
});

watch(searchQuery, () => {
    currentPage.value = 1;
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>