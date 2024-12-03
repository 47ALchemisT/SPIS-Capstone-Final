<template>
    <Head title="College"/>
    <AppLayout>
        <template v-slot:default>
            <div class="flex items-center justify-between gap-2">
                    <h1 class="text-2xl font-semibold">{{ sport.sport.name }} {{ sport.categories }}</h1>
                    <div>
                        <button 
                            @click="returnToPalakasan" 
                            type="button" 
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-2 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            >
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Return
                        </button>
                    </div>
                </div>

                <div class="flex items-center text-gray-600 gap-2 ">
                    <p class="text-sm"> {{ sport.setup }}</p>
                    <span class="text-sm">•</span>
                    <p class=" text-sm">{{ sport.type }}</p>
                    <span class="text-sm">•</span>
                    <p class=" text-sm">{{ sport.status }}</p>
                </div>
                
                <!-- Progress Bar -->
                <div class="flex mt-3 flex-col">
                    <div class="w-1/3 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" :style="{ width: `${progressPercentage}%` }"></div>
                    </div>
                    <div class="flex items-center mt-2">
                        <h2 class="text-xs text-gray-600">Sport Progress  </h2>
                        <h2 class="text-xs text-gray-600 px-2">:</h2>
                        <span class="text-xs font-medium">{{ progressPercentage.toFixed(0) }}% completed</span>
                    </div>
                </div>
            <Toast ref="toastRef" />

            <div v-if="tournamentWinner" class="mt-6 p-8 bg-blue-100 flex justify-center items-center flex-col rounded-lg">
                    <h2 class="text-sm font-semibold mb-2 text-blue-700">Game Winner</h2>
                    <div class="flex gap-4">
                        <div class="relative">
                            <div class="flex items-center justify-center flex-col">
                                <p class="text-2xl font-bold text-blue-700 bg-blue-100 w-32 rounded-md text-center break-words max-w-full mb-2">
                                    {{ getTeamName(tournamentWinner) }}
                                </p>
                                <div class="text-sm text-blue-700 font-medium">
                                    <span>W: {{ calculateTeamRecords()[tournamentWinner]?.wins || 0 }}</span>
                                    <span class="mx-2">-</span>
                                    <span>L: {{ calculateTeamRecords()[tournamentWinner]?.losses || 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- Tabs Navigation -->
            <nav class="flex relative justify-between mt-4  items-center">
                    <div class="flex gap-2 rounded-lg ">
                        <div class=" flex gap-2 rounded-lg">
                            <button 
                                v-for="tab in ['matches', 'standing', 'brackets', 'players']"
                                :key="tab"
                                @click="activeTab = tab"
                                :class="[
                                'px-5 py-2 text-sm',
                                activeTab === tab
                                    ? 'text-gray-800  bg-blue-700 text-white font-medium rounded-md'
                                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 border-transparent'
                                ]"
                            >
                                {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                            </button>
                            </div>
                    </div>
                    <div class="flex items-center gap-2">
                    <button
                        @click="openCreateMatchesModal"
                        type="button"
                        class="flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-700/90 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 disabled:bg-blue-100 disabled:text-blue-700 "
                        :disabled="hasExistingMatches"
                    >
                        {{ hasExistingMatches ? 'Game Already Started' : 'Start Game' }}
                    </button>
                    <button
                            v-if="sport.status === 'pending'"
                            type="button"
                            class="flex items-center gap-2 font-medium text-white bg-blue-700 hover:bg-blue-700/90 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 disabled:bg-blue-100 disabled:text-blue-700 "
                            :disabled="!allMatchesScheduled"
                            @click="handleScheduleClick"
                        >
                        {{ allMatchesScheduled ? 'Save Schedule' : 'Not Scheduled' }}
                    </button>
                </div>
            </nav>

            <div v-if="sport.status === 'pending'" class="mt-4 p-6 rounded-lg bg-blue-50 text-sm text-center text-blue-700">
                    <i class="fa-solid fa-circle-info mr-2"></i>
                    <span class="font-medium">Important:</span> All matches must be scheduled with date and time before the sport can be marked as scheduled, this is required to start the Palakasan.
                </div>

            <div class="mt-4">
                
                <div v-if="activeTab === 'matches'">
                    <GameSchedule :matches="matches" :teams="teams" :results="results" :venues="venues"  :allMatches="allMatches" :venueRecords="venueRecords"/>                
                </div>
                <div v-if="activeTab === 'standing'">
                    <div class="w-full">
                        <Standing :teams="teams" :results="results" />
                    </div>
                </div>

                <div v-if="activeTab === 'brackets'">
                    <!-- Display Bracket -->
                    <div class="mt-2">
                        <div class="flex justify-between">
                            <h2 class="text-md font-semibold mb-4">Tournament Bracket</h2>
                        </div>
                        <div v-if="groupedMatches.length > 0" class="flex gap-8">
                            <div 
                                v-for="(round, index) in groupedMatches" 
                                :key="index" 
                                class="space-y-6"
                            >
 
                                <h3 class="text-center text-sm font-bold">
                                    {{ index + 1 === groupedMatches.length ? 'Championship' : `Round ${index + 1}` }}
                                </h3>
                                <div 
                                    v-for="match in round" 
                                    :key="match.id" 
                                    @click="openScoreModal(match)"
                                    class=" w-56 rounded-lg cursor-pointer space-y-2"
                                    :disabled="!canUpdateMatch(match)"
                                >

                                    <div class="flex justify-between items-center">
                                        <p class="font-medium text-xs flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1-7 0a5 5 0 0 0-7 0zm0 16v-7"/></svg>
                                            {{ match.game }}
                                        </p>
                                    </div>
                                    <div class="shadow border border-gray-300 p-2 rounded-lg" :class="{'ring-1 bg-green-50 ring-green-500': isChampionshipMatch(match)}"
                                    >
                                        <span 
                                            class="mb-1 px-2 text-xs rounded-md flex items-center justify-between"
                                            :class="getTeamBackgroundColor(match.id, match.teamA_id)"
                                        >
                                            <p class="p-2">{{ getTeamName(match.teamA_id) }}</p>
                                            <div v-if="getMatchResult(match.id)" class="p-2 text-center">
                                                <p>{{ getMatchResult(match.id).teamA_score }}</p>
                                            </div>
                                        </span>
                                        <span 
                                            class=" px-2 text-xs rounded-md flex items-center justify-between"
                                            :class="getTeamBackgroundColor(match.id, match.teamB_id)"
                                        >
                                            <p class="p-2">{{ getTeamName(match.teamB_id) }}</p>
                                            <div v-if="getMatchResult(match.id)" class=" p-2 text-center">
                                                <p>{{ getMatchResult(match.id).teamB_score }}</p>
                                            </div>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div v-else>
                            <p>No matches have been created yet. Click 'Start Tournament' to begin.</p>
                        </div>
                    </div>
                </div>
                <div v-if="activeTab === 'players'">
                    <PlayersDisplay class="mt-1" :players="players" :teams="teams" />
                </div>
            </div>

            <!-- Ranking Modal -->
            <div v-if="showRankingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg relative w-96 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Submit Rankings</h3>
                        <button @click="closeRankingModal" class="text-gray-400 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <form @submit.prevent="submitRankings">
                        <div v-for="(team) in rankingTeams" :key="team.id" class="mb-4">
                            <div class="flex items-center justify-between">
                                <span class="font-medium">{{ team.assigned_team_name }}</span>
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium">Rank</span>
                                    <input
                                    v-model="team.rank"
                                    type="text"
                                    min="1"
                                    disabled
                                    :max="teams.length"
                                    class="w-8 border text-center rounded px-2 py-1"
                                    placeholder="Rank"
                                    />
                                    <span class="font-medium">Points</span>
                                    <input
                                    v-model="team.points"
                                    type="number"
                                    min="0"
                                    class="w-16 border rounded px-2 py-1"
                                    placeholder="Points"
                                    required
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            :disabled="isSubmittingRankings || rankingsSubmitted"
                            >
                            {{ isSubmittingRankings ? 'Submitting...' : (rankingsSubmitted ? 'Rankings Submitted' : 'Submit Rankings') }}
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Score Setting Modal -->
            <div v-if="showScoreModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg relative w-72">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Add Score
                        </h3>
                        <button @click="closeScoreModal"  type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 ">
                        
                        <form @submit.prevent="submitScore">
                            <div class="mb-4">
                                <label class="block mb-2 font-medium text-sm">
                                    {{ getTeamName(selectedMatch.teamA_id) }}
                                    <input 
                                        v-model="scoreFormData.teamA_score"
                                        type="number"
                                        min="0"
                                        required
                                        class="border px-3 py-2 mt-2  rounded-lg border-gray-300  w-full"
                                    />
                                </label>
                            </div>

                            <div class="mb-4">
                                <label class="block mb-3 font-medium text-sm">
                                    {{ getTeamName(selectedMatch.teamB_id) }}
                                    <input 
                                        v-model="scoreFormData.teamB_score"
                                        type="number"
                                        min="0"
                                        required
                                        class="border mt-2 rounded-lg border-gray-300 px-3 py-2 w-full"
                                    />
                                </label>
                            </div>

                            <p v-if="scoreError" class="text-red-600 mb-4">{{ scoreError }}</p>

                            <div class="grid grid-cols-2 items-center gap-2">
                                <button  
                                    @click="closeScoreModal" 
                                    type="button" class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                    Cancel
                                </button>

                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    {{ scoreLoading ? 'Saving...' : 'Save Score' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Create Matches Modal -->
            <div v-if="showCreateMatchesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg relative w-96">
                    <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Create {{ sport.sport.name }} Matches
                        </h3>
                        <button               
                            @click="closeCreateMatchesModal" 
                            type="button" 
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <!-- Form Starts Here -->
                    <form @submit.prevent="createInitialMatches">
                        <div class="p-5">
                            <!-- Venue Selection -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Venue
                                </label>
                                <div class="relative">
                                    <select
                                        v-model="selectedVenue"
                                        class="w-full p-2 pr-8 rounded-md bg-white ring-1 ring-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-300 appearance-none"
                                        required
                                    >
                                        <option value="" disabled>Choose a venue</option>
                                        <option
                                            v-for="venue in props.venues"
                                            :key="venue.id"
                                            :value="venue.id"
                                        >
                                            {{ venue.name }}
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Shuffle Teams Option -->
                            <div class="mb-4 py-2.5 px-3 bg-gray-50 rounded-md ring-1 ring-gray-300">
                                <label class="flex items-center">
                                    <input
                                        v-model="shuffleTeams"
                                        type="checkbox"
                                        class="form-checkbox h-4 w-4 text-blue-600 border-blue-600 focus:ring-blue-500"
                                    >
                                    <span class="ml-2 text-gray-700 text-sm">Shuffle and randomize teams</span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit"
                                :disabled="createMatchesLoading || !selectedVenue"
                                class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-600 w-full disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ createMatchesLoading ? 'Creating...' : 'Create Matches' }}
                            </button>

                            <p v-if="createMatchesError" class="text-red-600 mt-2">{{ createMatchesError }}</p>
                        </div>
                    </form>
                    <!-- Form Ends Here -->
                    </div>
            </div>

        </template>
    </AppLayout>
</template>
 
<script setup>
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayout.vue';
import Standing from '@/Components/Standing.vue';
import GameSchedule from '@/Components/GameSchedule.vue';
import Toast from '@/Components/Toast.vue';  // Adjust the import path as needed
import PlayersDisplay from '@/Components/PlayersDisplay.vue';


const props = defineProps({
    sport: [Array, Object],
    tournaments: Array,
    teams: Array,
    matches: Array,
    results: Array,
    venues: Array,
    allMatches: Array,
    venueRecords: Array,
    players: Array
});

const toastRef = ref(null);
const page = usePage();

// Watch for flash messages
watch(
    () => page.props.flash,
    (flash) => {
        if (flash.message) {
            toastRef.value.addToast(flash.message, 'success');
        }
        if (flash.error) {
            toastRef.value.addToast(flash.error, 'error');
        }
    },
    { deep: true }
);

const activeTab = ref('matches');
const selectedVenue = ref('');
const error = ref('');
const showScoreModal = ref(false);
const scoreLoading = ref(false);
const scoreError = ref('');
const selectedMatch = ref(null);
const showCreateMatchesModal = ref(false);
const shuffleTeams = ref(false);
const createMatchesLoading = ref(false);
const createMatchesError = ref('');

const returnToPalakasan = () => {
    router.get(route('palakasan.details', { tab: 'leagues' }));
};

const hasExistingMatches = computed(() => {
    return props.matches.some(match => match.assigned_sport_id === props.sport.id);
});

const openCreateMatchesModal = () => {
    if (hasExistingMatches.value) {
        error.value = 'Tournament has already been started';
        return;
    }
    showCreateMatchesModal.value = true;
};

const closeCreateMatchesModal = () => {
    showCreateMatchesModal.value = false;
    shuffleTeams.value = false;
    selectedVenue.value = '';
    createMatchesError.value = '';
};

const shuffleArray = (array) => {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
};

// In the script setup section, modify the generateMatchStructure function:
const generateMatchStructure = (teams) => {
    const venueId = parseInt(selectedVenue.value, 10);
    if (!venueId) {
        throw new Error('Please select a valid venue');
    }

    const numTeams = teams.length;
    let matches = [];

    if (numTeams === 5) {
        matches = [
            { round: 1, game: 'Game 1', teamA_id: teams[0].id, teamB_id: teams[1].id },
            { round: 2, game: 'Game 2', teamA_id: teams[2].id, teamB_id: teams[3].id },
            { round: 2, game: 'Game 3', teamA_id: null, teamB_id: teams[4].id },
            { round: 3, game: 'Game 4', teamA_id: null, teamB_id: null }
        ];
    } else if (numTeams === 6) {
        matches = [
            { round: 1, game: 'Game 1', teamA_id: teams[0].id, teamB_id: teams[1].id },
            { round: 1, game: 'Game 2', teamA_id: teams[2].id, teamB_id: teams[3].id },
            { round: 2, game: 'Game 3', teamA_id: teams[4].id, teamB_id: null },
            { round: 2, game: 'Game 4', teamA_id: teams[5].id, teamB_id: null },
            { round: 3, game: 'Game 5', teamA_id: null, teamB_id: null }
        ];
    } else if (numTeams === 7) {
        matches = [
            { round: 1, game: 'Game 1', teamA_id: teams[0].id, teamB_id: teams[1].id },
            { round: 1, game: 'Game 2', teamA_id: teams[2].id, teamB_id: teams[3].id },
            { round: 1, game: 'Game 3', teamA_id: teams[4].id, teamB_id: teams[5].id },
            { round: 2, game: 'Game 4', teamA_id: teams[6].id, teamB_id: null },
            { round: 2, game: 'Game 5', teamA_id: null, teamB_id: null },
            { round: 3, game: 'Game 6', teamA_id: null, teamB_id: null }
        ];
    } else {
        const numRounds = Math.ceil(Math.log2(numTeams));
        let gameCounter = 1;

        for (let round = 1; round <= numRounds; round++) {
            const matchesInRound = round === 1
                ? Math.floor(numTeams / 2)
                : Math.pow(2, numRounds - round);

            for (let i = 0; i < matchesInRound; i++) {
                const match = {
                    round: round,
                    game: `Game ${gameCounter}`,
                    teamA_id: round === 1 ? teams[i * 2]?.id || null : null,
                    teamB_id: round === 1 ? teams[i * 2 + 1]?.id || null : null
                };

                matches.push(match);
                gameCounter++;
            }
        }
    }

    // Add required fields to each match
    return matches.map(match => ({
        ...match,
        assigned_sport_id: props.sport.id,
        bracket_type: 'winners',
        elimination_type: props.sport.setup,
        status: 'pending',
        match_venue_id: venueId,  // Make sure this is included
        date: null,
        time: null,
    }));
};

const createInitialMatches = async () => {
    createMatchesError.value = '';

    if (!selectedVenue.value) {
        createMatchesError.value = 'Please select a venue';
        return;
    }

    createMatchesLoading.value = true;

    try {
        let teamsToUse = [...props.teams];
        if (shuffleTeams.value) {
            teamsToUse = shuffleArray([...teamsToUse]);
        }

        const matches = generateMatchStructure(teamsToUse);

        if (!matches.length) {
            throw new Error('No matches were generated');
        }

        // Add logging before sending request
        console.log("Match data being sent:", matches);

        const response = await router.post(route('matchesSingle.store'), matches, {
            preserveScroll: true,
            onSuccess: (page) => {
                console.log('Success response:', page);
                createMatchesLoading.value = false;
                closeCreateMatchesModal();
            },
            onError: (errors) => {
            console.error('Error creating matches:', errors);
            createMatchesError.value = errors.message || 'Failed to create matches';
        }
        });
    } catch (err) {
        console.error('Error in createInitialMatches:', err);
        createMatchesLoading.value = false;
        createMatchesError.value = err.message || 'Failed to create matches';
    }
};


const getTeamName = (teamId) => {
    if (!teamId) return 'TBD';
    if (typeof teamId === 'string' && teamId.startsWith('winner:')) {
        const [, round, game] = teamId.split(':');
        return `Winner of R${round} G${game}`;
    }
    const team = props.teams.find(t => t.id === teamId);
    return team ? team.assigned_team_name : 'Unknown';
};

const groupedMatches = computed(() => {
    const rounds = {};
    const sportMatches = props.matches.filter(match => match.assigned_sport_id === props.sport.id);
    
    sportMatches.forEach(match => {
        const round = match.round || 1;
        if (!rounds[round]) {
            rounds[round] = [];
        }
        rounds[round].push(match);
    });
    
    Object.values(rounds).forEach(round => {
        round.sort((a, b) => {
            const gameA = parseInt(a.game.replace('Game ', ''));
            const gameB = parseInt(b.game.replace('Game ', ''));
            return gameA - gameB;
        });
    });
    
    return Object.values(rounds);
});

const canUpdateMatch = (match) => {
    return match.teamA_id && match.teamB_id && 
           !match.teamA_id.toString().startsWith('winner:') && 
           !match.teamB_id.toString().startsWith('winner:');
};

const scoreFormData = ref({
    sport_match_id: '',
    teamA_score: '',
    teamB_score: '',
    winning_team_id: '',
    losing_team_id: ''
});

const getMatchResult = (matchId) => {
    return props.results.find(result => result.sport_match_id === matchId);
};

const openScoreModal = (match) => {
    if (!canUpdateMatch(match)) return;
    
    selectedMatch.value = match;
    const existingResult = getMatchResult(match.id);
    
    scoreFormData.value = {
        sport_match_id: match.id,
        teamA_score: existingResult ? existingResult.teamA_score : '',
        teamB_score: existingResult ? existingResult.teamB_score : '',
        winning_team_id: '',
        losing_team_id: ''
    };
    
    showScoreModal.value = true;
};

const closeScoreModal = () => {
    showScoreModal.value = false;
    selectedMatch.value = null;
    scoreFormData.value = {
        sport_match_id: '',
        teamA_score: '',
        teamB_score: '',
        winning_team_id: '',
        losing_team_id: ''
    };
    scoreError.value = '';
};

const submitScore = async () => {
    scoreLoading.value = true;
    scoreError.value = '';

    const { teamA_score, teamB_score } = scoreFormData.value;
    const teamAId = selectedMatch.value.teamA_id;
    const teamBId = selectedMatch.value.teamB_id;

    if (teamA_score === teamB_score) {
        scoreError.value = 'Scores cannot be tied in this elimination bracket.';
        scoreLoading.value = false;
        return;
    }

    scoreFormData.value.winning_team_id = teamA_score > teamB_score ? teamAId : teamBId;
    scoreFormData.value.losing_team_id = teamA_score > teamB_score ? teamBId : teamAId;

    try {
        await router.post(route('results.store'), scoreFormData.value, {
            onSuccess: () => {
                console.log('Score updated successfully');
                closeScoreModal();
                scoreLoading.value = false;
            },
            onError: (errors) => {
                console.error('Error updating score:', errors);
                scoreError.value = 'Failed to update score';
                scoreLoading.value = false;
            }
        });
    } catch (error) {
        scoreError.value = 'Failed to update score';
        console.error('Error updating score:', error);
        scoreLoading.value = false;
    }
};

const getTeamBackgroundColor = (matchId, teamId) => {
    const result = getMatchResult(matchId);
    if (!result || !teamId) return 'bg-gray-200';

    if (result.winning_team_id === teamId) {
        return 'bg-green-200 text-green-600';
    } else if (result.losing_team_id === teamId) {
        return 'bg-red-200 text-red-600';
    }
    return 'bg-gray-200';
};

const tournamentWinner = computed(() => {
    const lastRound = groupedMatches.value[groupedMatches.value.length - 1];
    if (!lastRound || lastRound.length === 0) return null;
    
    const championshipMatch = lastRound[0];
    const result = getMatchResult(championshipMatch.id);
    
    return result ? result.winning_team_id : null;
});

const isChampionshipMatch = (match) => {
    const lastRound = groupedMatches.value[groupedMatches.value.length - 1];
    return lastRound && lastRound[0]?.id === match.id;
};

const totalMatches = computed(() => props.matches.length);

const completedMatches = computed(() => {
    return props.matches.filter(match => match.status === 'completed').length;
});

const progressPercentage = computed(() => {
  if (totalMatches.value === 0) return 0;
  return (completedMatches.value / totalMatches.value) * 100;
});

const allMatchesScheduled = computed(() => {
    const sportMatches = props.matches.filter(match => match.assigned_sport_id === props.sport.id);
    return sportMatches.length > 0 && sportMatches.every(match => match.date && match.time);
});

const handleScheduleClick = () => {
    router.patch(route('assigned-sports.update-status', props.sport.id), {
        status: 'scheduled'
    }, {
        onSuccess: () => {
            if (toastRef.value) {
                toastRef.value.addToast('Sport status updated to scheduled', 'success');
            }
        },
        onError: (errors) => {
            if (toastRef.value) {
                toastRef.value.addToast('Failed to update sport status', 'error');
            }
        }
    });
};

//
const showRankingModal = ref(false);
const isSubmittingRankings = ref(false);
const rankingTeams = ref([]);
const rankingsSubmitted = ref(false);

const calculateTeamRecords = () => {
  const records = {};
  props.teams.forEach(team => {
    records[team.id] = { wins: 0, losses: 0, points: 0 };
  });

  props.results.forEach(result => {
    if (result.winning_team_id) {
      records[result.winning_team_id].wins++;
      records[result.winning_team_id].points += result.winning_points || 0;
    }
    if (result.losing_team_id) {
      records[result.losing_team_id].losses++;
      records[result.losing_team_id].points += result.losing_points || 0;
    }
  });

  return records;
};

const sortTeamsByRecord = (teams, records) => {
  return [...teams].sort((a, b) => {
    const teamA = records[a.id] || { wins: 0, losses: 0, points: 0 };
    const teamB = records[b.id] || { wins: 0, losses: 0, points: 0 };

    // Sort by wins first, then by points, then by losses
    if (teamA.wins !== teamB.wins) return teamB.wins - teamA.wins;
    if (teamA.points !== teamB.points) return teamB.points - teamA.points;
    return teamA.losses - teamB.losses;
  });
};

const openRankingModal = () => {
  if (rankingsSubmitted.value) return;

  const records = calculateTeamRecords();
  const sortedTeams = sortTeamsByRecord(props.teams, records);

  rankingTeams.value = sortedTeams.map((team, index) => ({
    ...team,
    rank: index + 1,
    points: records[team.id].points || 0,
  }));

  showRankingModal.value = true;
};

const closeRankingModal = () => {
  showRankingModal.value = false;
};

const submitRankings = async () => {
  if (rankingsSubmitted.value) return;

  isSubmittingRankings.value = true;

  const rankings = rankingTeams.value.map(team => ({
    assigned_sport_id: props.sport.id,
    assigned_team_id: team.id,
    rank: parseInt(team.rank),
    points: team.points,
  }));

  try {
    await router.post(route('overall-results.store'), { rankings }, {
      preserveState: true,
      preserveScroll: true,
    });
    rankingsSubmitted.value = true;
    closeRankingModal();
  } catch (error) {
    console.error('Error submitting rankings:', error);
    // Handle error (e.g., show error message to user)
  } finally {
    isSubmittingRankings.value = false;
  }
};
</script>
 
<style scoped>
/* Home page specific styles */
</style>