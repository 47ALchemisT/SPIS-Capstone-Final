<template>
    <Head title="Student Players" />
    <AppLayout>
        <template v-slot:default>
            
            <!-- Heading -->
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Sports</h1>
                <p class="text-sm text-gray-700">Select a sport to view its players</p>
            </div>
            

            <!-- Sports Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div
                v-for="sport in availableSports"
                :key="sport.id"
                @click="viewSport(sport)"
                class="cursor-pointer border rounded-lg p-6 bg-white shadow hover:shadow-lg hover:border-blue-600 transition"
            >
                <h2 class="text-xl font-semibold text-gray-800">{{ sport.sport.name }}</h2>
                <p class="text-sm text-gray-600">Category: {{ sport.categories }}</p>
            </div>
            </div>


            <!-- Players List -->
            <div v-if="selectedSport" class="mt-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                    Players in {{ selectedSport.sport.name }} ({{ selectedPlayers.length }})
                </h2>

                <div
                    v-if="selectedPlayers.length > 0"
                    class="bg-white shadow-md rounded-lg p-4"
                >
                    <ul>
                        <li
                            v-for="player in selectedPlayers"
                            :key="player.id"
                            class="py-2 border-b flex justify-between items-center"
                        >
                            <span class="font-medium text-gray-700">
                                {{ player.student.first_name }} {{ player.student.last_name }}
                            </span>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-center text-gray-500">
                    No players are currently assigned to this sport.
                </div>
            </div>

  
        </template>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layout/DashboardLayoutCSH.vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    sports: Array,
    students: Array,
    assignedSports: Array,
    studentPlayers: Array,
});
const viewSport = (sport) => {
    router.get(route('sports.show', sport.id));
};
const availableSports = ref(props.assignedSports || []);
const allPlayers = ref(props.studentPlayers || []);

// Selected sport and players
const selectedSport = ref(null);
const selectedPlayers = computed(() => {
    if (!selectedSport.value) return [];
    return allPlayers.value.filter(
        (player) => player.student_assigned_sport_id === selectedSport.value.id
    );
});

// Select a sport
const selectSport = (sport) => {
    selectedSport.value = sport;
};
const isModalOpen = ref(false);
const selectedStudentIds = ref([]);
const selectedStudentNames = ref([]);

// Form for submission
const form = useForm({
    student_ids: [],
    student_assigned_sport_id: null,
});

// Methods
const openModal = () => (isModalOpen.value = true);
const closeModal = () => (isModalOpen.value = false);
const submitForm = () => {
    form.student_ids = selectedStudentIds.value;
    form.post(route('studentplayer.store'), {
        onSuccess: () => {
            closeModal();
            form.reset();
        },
    });
};
</script>
