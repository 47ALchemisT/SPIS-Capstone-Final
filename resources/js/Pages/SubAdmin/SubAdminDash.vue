<template>
    <Head title="Sub-Admin Dashboard"/>
    <AppLayout>
        <template v-slot:default>
            <Toast ref="toastRef" />
            <div class="mt-4">
                <div class="flex mb-4 items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <!-- Search Input -->
                        <div class="flex items-center">
                            <input
                                v-model="searchQuerySports"
                                type="text"
                                placeholder="Search sport..."
                                class="w-64 border border-gray-300 bg-gray-50 text-sm rounded-lg p-2"
                            />
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="filteredAndSortedSports.length === 0" class="flex flex-col items-center justify-center border-2 border-dashed border-blue-400 text-center p-6 bg-blue-50 rounded-lg">
                    <i class="fa-solid fa-basketball text-blue-700 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-blue-700 mb-2">No sports found</h3>
                    <p class="text-gray-500 text-sm mb-4">
                        We couldn't find any sports that match your search criteria.
                    </p>
                </div>

                <!-- Sports Grid -->
                <div v-else class="grid grid-cols-1 pb-4 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div
                        v-for="(sport, index) in filteredAndSortedSports"
                        :key="index"
                        @click="viewSport(sport.id)"
                        class="relative bg-white ring-1 ring-gray-300 shadow rounded-lg hover:ring-blue-400 hover:bg-blue-50/80 transition p-4 group cursor-pointer"
                    >
                        <div class="flex items-center justify-between">
                            <div class="w-full">
                                <svg class="w-6 h-6 mb-3 group-hover:text-blue-600 transition" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 2048 2048">
                                    <path fill="currentColor" d="M1792 256q27 0 50 10t40 27t28 41t10 50v192q0 86-30 163t-85 137t-127 98t-159 48q-19 62-51 116t-76 98t-97 77t-114 56q35 34 58 76t35 91h70q40 0 75 15t61 41t41 61t15 75v192H384v-192q0-40 15-75t41-61t61-41t75-15h70q11-48 34-90t59-77q-61-22-114-55t-96-78t-76-98t-52-116q-86-9-159-47t-127-99t-84-137T0 576V384q0-27 10-50t27-40t41-28t50-10h256V128h1152v128zm-1280 0v128h896V256zM128 576q0 57 19 109t53 93t81 71t103 41V384H128zm1280 1152q0-26 19-45t45-19H576q-26 0-45 19t-19 45v64h896zm-267-192q-10-29-28-52t-42-41t-52-26t-59-9q-30 0-58 9t-53 26t-42 40t-28 53zm-181-256q81 0 161-27t144-76t103-121t40-160V512H512v384q0 89 39 160t103 120t144 77t162 27m832-896h-256v506q56-12 103-41t81-70t53-94t19-109z"/>
                                </svg>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">{{ sport.sport.name }}</h3>
                                        <p class="text-sm text-gray-500">{{ sport.type }}</p>
                                        <p class="text-sm text-gray-500">Sub-Admin: {{ getFacilitatorName(sport.facilitator_id) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Animated Arrow -->
                        <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-all duration-300 group-hover:translate-x-1">
                            <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7l-7 7"/>
                            </svg>
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
    facilitator: Array
});

// Search and Sort Functionality
const searchQuerySports = ref('');
const filteredAndSortedSports = computed(() => {
    let filtered = props.sports;
    
    if (searchQuerySports.value) {
        const searchTerm = searchQuerySports.value.toLowerCase();
        filtered = filtered.filter(sport => 
            sport.sport.name.toLowerCase().includes(searchTerm) ||
            sport.type.toLowerCase().includes(searchTerm)
        );
    }
    
    return filtered;
});

// Helper Functions
const viewSport = (sportId) => {
    router.get(route('sa-sportview.index', { sport: sportId }));
};

const getFacilitatorName = (facilitatorId) => {
    const facilitator = props.facilitator.find(f => f.id === facilitatorId);
    return facilitator ? `${facilitator.student.first_name} ${facilitator.student.last_name}` : 'Not assigned';
};
</script>
