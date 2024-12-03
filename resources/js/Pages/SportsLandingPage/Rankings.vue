<template>
    <Head title="Rankings" />
    <MainLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <!-- Header Section -->
                <div class="sm:text-left text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Overall Rankings</h1>
                    <p class="mt-2 text-gray-600">List of rankings in different sports</p>
                </div>

                <div class="p-6">
                    <RankingsComponent 
                        :latestPalakasan="latestPalakasan"
                        :sportMatches="sportMatches"
                        :overallResult="overallResult"
                        :sportsVariationMatches="sportsVariationMatches"
                        :sportsVariations="sportsVariations"
                        :activeSports="activeSports"
                        :activeTeams="activeTeams"
                        :assignedTeams="assignedTeams"
                        :assignedSports="assignedSports"
                    />
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import RankingsComponent from '@/Components/RankingsComponent.vue';

defineProps({
    latestPalakasan: {
        type: Object,
        required: true
    },
    sportMatches: {
        type: Array,
        default() {
            return [];
        }
    },
    overallResult: {
        type: Array,
        default() {
            return [];
        }
    },
    sportsVariationMatches: {
        type: Array,
        required: true
    },
    sportsVariations: {
        type: Array,
        default() {
            return [];
        }
    },
    activeSports: {
        type: Array,
        required: true
    },
    activeTeams: {
        type: Array,
        required: true
    },
    assignedTeams: {
        type: Array,
        required: true
    },
    assignedSports: {
        type: Array,
        required: true
    }
});
</script>
