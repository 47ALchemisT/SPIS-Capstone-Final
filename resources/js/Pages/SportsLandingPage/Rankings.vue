<template>
    <Head title="Rankings" />
    <MainLayout :latestPalakasan="latestPalakasan">
        <div class="py-2 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <!-- Header Section -->
                <div class="text-left mb-2 sm:mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Overall Rankings</h1>
                    <p class=" text-gray-600">List of rankings in different sports</p>
                </div>

                <div class="">
                    <PalakasanRankings 
                        :assignedTeams="assignedTeams" 
                        :overallResult="overallResult" 
                        :variationResult="variationResult"
                        :assignedSports="assignedSports"
                        :sportsVariations="sportsVariationMatches"
                        :sportMatches="sportMatches"
                        :latestPalakasan="latestPalakasan"
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
import PalakasanRankings from '@/Components/SportsRankingsHome.vue';

defineProps({
    latestPalakasan: {
        type: Object,
        required: true
    },
    variationResult: {
        type: Array,
        default() {
            return [];
        }
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
