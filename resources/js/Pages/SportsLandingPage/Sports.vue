<template>
    <Head title="Sports" />
    <MainLayout :latestPalakasan="latestPalakasan">
        <div class="min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-4">
                <div class="sm:text-left text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">All Sports</h1>
                    <p class="mt-2 text-gray-600">Browse all available sports in Palakasan</p>
                </div>

                <!-- Search and Filter Bar -->
                <div class="mt-6 flex flex-col sm:flex-row justify-between gap-4">
                    <div class="relative flex-1 sm:max-w-sm max-w-full">
                        <input 
                            type="text" 
                            v-model="searchQuery"
                            placeholder="Search sports..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <div class="relative">
                            <select 
                                v-model="selectedCategory"
                                class="appearance-none text-sm pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                            >
                                <option value="">All Categories</option>
                                <option v-for="category in uniqueCategories" :key="category" :value="category">
                                    {{ category }}
                                </option>
                            </select>
                            <div class="absolute bottom-1 inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>
                        <div class="relative">
                            <select 
                                v-model="selectedType"
                                class="appearance-none text-sm pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                            >
                                <option value="">All Types</option>
                                <option v-for="type in uniqueTypes" :key="type" :value="type">
                                    {{ type }}
                                </option>
                            </select>
                            <div class="absolute bottom-1 inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>
                        <div class="relative">
                            <select 
                                v-model="selectedStatus"
                                class="appearance-none text-sm pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                            >
                                <option value="">All Status</option>
                                <option v-for="status in uniqueStatuses" :key="status" :value="status">
                                    {{ status }}
                                </option>
                            </select>
                            <div class="absolute text-xs bottom-1 inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div v-if="filteredSports.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="sport in filteredSports" :key="sport.id" 
                            @click="viewSport(sport.id)"
                            class="bg-white ring-1 ring-gray-200 rounded-lg p-6 hover:shadow-md transition cursor-pointer  duration-300">
                            <div>
                                <div class="h-14 w-14" :class="[
                                    sport.status === 'completed' ? 'bg-green-50' : 'bg-blue-50',
                                    'rounded-full flex items-center justify-center'
                                ]">
                                    <i :class="[
                                        sport.sport.icon,
                                        sport.status === 'completed' ? 'text-green-600' : 'text-blue-600',
                                        'text-xl'
                                    ]"></i>
                                </div>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ sport.sport.name }} {{ sport.categories }}</h3>
                                <div class="flex items-center text-gray-600 gap-2 mt-1">
                                    <p class="text-sm">{{ sport.setup }}</p>
                                    <span class="text-sm">•</span>
                                    <p class="text-sm">{{ sport.type }}</p>
                                    <span class="text-sm">•</span>
                                    <p class="text-sm capitalize">{{ sport.status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center mt-10">
                        <p class="text-gray-500">No sports found matching your search.</p>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { ref, computed } from 'vue';

const searchQuery = ref('');
const selectedCategory = ref('');
const selectedType = ref('');
const selectedStatus = ref('');

const props = defineProps({
    latestPalakasan: Object,
    sportMatches: Array,
    palakasanYear: [String, Number],
    activeSports: Array,
    activeTeams: Array,
    overallRankings: Array,
    overallResult: Array,
    variationResult: Array,
    assignedSports: Array,
    assignedTeams: Array
});

    //this is for viewing the sport
const viewSport = (sportId) => {
    router.get(route('home-sportview.index', { sport: sportId }));
};

const uniqueCategories = computed(() => {
    return [...new Set(props.activeSports.map(sport => sport.categories))].sort();
});

const uniqueTypes = computed(() => {
    return [...new Set(props.activeSports.map(sport => sport.type))].sort();
});

const uniqueStatuses = computed(() => {
    return [...new Set(props.activeSports.map(sport => sport.status))].sort();
});

const filteredSports = computed(() => {
    if (!props.activeSports) return [];
    
    return props.activeSports.filter(sport => {
        const matchesSearch = !searchQuery.value || 
            sport.sport.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            sport.categories.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            sport.type.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            sport.setup.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            sport.status.toLowerCase().includes(searchQuery.value.toLowerCase());

        const matchesCategory = !selectedCategory.value || 
            sport.categories === selectedCategory.value;

        const matchesType = !selectedType.value || 
            sport.type === selectedType.value;

        const matchesStatus = !selectedStatus.value || 
            sport.status === selectedStatus.value;

        return matchesSearch && matchesCategory && matchesType && matchesStatus;
    });
});
</script>