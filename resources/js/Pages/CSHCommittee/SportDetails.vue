<template>
    <Head :title="`Players in ${sport.sport.name}`" />
    <AppLayout>
        <template v-slot:default>
              <!-- Flash Messages -->
<div v-if="flash.success" class="bg-green-100 text-green-800 p-3 rounded mb-4">
    {{ flash.success }}
</div>
<div v-if="flash.warnings" class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">
    <ul>
        <li v-for="(warning, index) in flash.warnings" :key="index">
            {{ warning }}
        </li>
    </ul>
</div>
<div v-if="flash.error" class="bg-red-100 text-red-800 p-3 rounded mb-4">
    {{ flash.error }}
</div>

            <!-- Back Button -->
            <button
                @click="goBack"
                class="mb-4 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg"
            >
                ← Back to Sports
            </button>
            <div class="mt-6 flex justify-end">
                <button
                    @click="openAssignModal"
                    class="bg-blue-600 hover:bg-blue-500 text-white py-2 px-4 rounded-lg shadow"
                >
                    Assign Players
                </button>
            </div>

            <!-- Sport Details -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-3xl font-semibold text-gray-800">{{ sport.sport.name }}</h1>
                <p class="text-sm text-gray-600 mb-4">Category: {{ sport.categories }}</p>

                <!-- Players Table -->
                <div v-if="newlyAssignedPlayers.length > 0">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Newly Assigned Players ({{ newlyAssignedPlayers.length }})</h2>
                    <ul>
                        <li
                            v-for="player in newlyAssignedPlayers"
                            :key="player.student.id"
                            class="py-2 border-b flex justify-between items-center"
                        >
                            <span class="font-medium text-gray-700">
                                {{ player.student.first_name }} {{ player.student.last_name }}
                            </span>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-center text-gray-500">
                    No new players have been assigned yet.
                </div>

                <!-- Assign Players Button -->
                </div>

              <!-- Assign Players Modal -->
<div v-if="isAssignModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">Assign Players to {{ sport.sport.name }}</h3>

        <form @submit.prevent="submitAssignPlayers">
            <!-- Students Selection -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Select Players</label>
                <div class="max-h-64 overflow-y-auto border rounded-lg p-2">
                    <!-- Players List with Clickable Names -->
                    <div
                        v-for="student in availableStudents"
                        :key="student.id"
                        class="flex items-center mb-2"
                    >
                        <label
                            :for="'player-' + student.id"
                            class="flex items-center w-full cursor-pointer"
                            @click="toggleSelection(student.id)"
                        >
                            <input
                                type="checkbox"
                                :value="student.id"
                                v-model="selectedStudentIds"
                                :id="'player-' + student.id"
                                class="mr-2"
                            />
                            <span class="text-gray-700">
                                {{ student.first_name }} {{ student.last_name }}
                            </span>
                        </label>
                    </div>
                </div>
                <span v-if="form.errors.student_ids" class="text-red-500 text-sm">
                    {{ form.errors.student_ids }}
                </span>
            </div>

            <!-- Form Buttons -->
            <div class="flex justify-end text-sm">
                <button
                    type="button"
                    @click="closeAssignModal"
                    class="mr-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    :disabled="form.processing || !selectedStudentIds.length"
                    class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition relative"
                >
                    <span v-if="!form.processing">
                        Assign Players
                    </span>
                    <span v-else>
                        <svg class="animate-spin h-4 w-4 mr-3 border-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.969 7.969 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

        </template>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head} from '@inertiajs/vue3';
import AppLayout from '@/Layout/DashboardLayoutCSH.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const { props } = usePage();
const sport = props.sport;
const initialPlayers = ref(props.players || []); // Store initial players
const newlyAssignedPlayers = ref([]); // Store newly assigned players
const { flash } = usePage().props; 

// Filter out students who are already assigned to this specific sport
const availableStudents = computed(() => {
    const assignedStudentIds = initialPlayers.value.map(player => player.student.id);
    return props.students.filter(student => !assignedStudentIds.includes(student.id));
});

const isAssignModalOpen = ref(false);
const selectedStudentIds = ref([]); // Array to store selected player IDs
const form = useForm({
    student_ids: [],
    student_assigned_sport_id: sport.id,
});

// Open and close modal
const openAssignModal = () => (isAssignModalOpen.value = true);
const closeAssignModal = () => (isAssignModalOpen.value = false);

// Submit form to assign players
const submitAssignPlayers = () => {
    form.student_ids = selectedStudentIds.value;
    form.post(route('studentplayer.store'), {
        onSuccess: () => {
            // Add newly assigned players to the list
            const newPlayers = selectedStudentIds.value.map(id => {
                const student = props.students.find(s => s.id === id);
                return {
                    student: student,
                    student_id: student.id,
                    student_assigned_sport_id: sport.id
                };
            });
            newlyAssignedPlayers.value = [...newlyAssignedPlayers.value, ...newPlayers];
            
            closeAssignModal();
            selectedStudentIds.value = []; // Clear selected IDs
            form.reset();
        },
    });
};

// Toggle selection when the row is clicked
const toggleSelection = (studentId) => {
    const index = selectedStudentIds.value.indexOf(studentId);
    if (index === -1) {
        selectedStudentIds.value.push(studentId);
    } else {
        selectedStudentIds.value.splice(index, 1);
    }
};

// Go back to previous page
const goBack = () => {
    history.back();
};
</script>
