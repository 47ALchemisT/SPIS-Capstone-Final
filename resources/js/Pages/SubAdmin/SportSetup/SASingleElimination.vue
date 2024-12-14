<template>
    <Head title="College"/>
    <AppLayout :user="user">
        <template v-slot:default>
            <div class="flex items-center justify-between gap-2 pt-6">
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
            <!-- Progress Bar -->
            <div class="flex mt-2 flex-col">
                <div class="flex items-center mb-2">
                    <h2 class="text-xs text-gray-600">Sport Progress  </h2>
                    <h2 class="text-xs text-gray-600 px-2">:</h2>
                    <span class="text-xs font-medium">{{ progressPercentage.toFixed(0) }}% completed</span>
                </div>
                <div class="w-1/3 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" :style="{ width: `${progressPercentage}%` }"></div>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-2">
                <p class="text-sm"> {{ sport.setup }}</p>
                <p class="text-xs">|</p>
                <p class=" text-sm">{{ sport.type }}</p>
                <p class="text-xs">|</p>
                <p class=" text-sm">{{ sport.status }}</p>
            </div>
            <div v-if="tournamentWinner" class="mt-6 p-8 bg-blue-100 flex justify-center items-center flex-col rounded-lg">
                    <h2 class="text-xs font-semibold mb-2 text-blue-700">Game Winner</h2>

                    <div class="flex gap-4">
                        <div class="text-center flex gap-4">
                            <svg class="w-10 h-10 mt-5 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-14 h-14 mt-4 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-16 h-16 mt-4 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-20 h-20 mt-3 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                        </div>
                        <div class="relative">
                            <svg class="w-36 h-36 text-blue-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.987 9.492L5.678 7.514C4.155 6.204 4.15 4.302 3.988 3.02c1.666.08 2.956.23 4.08 1.18l1.176 1.336l1.273 1.428m8.941 11.455l-2.964-3m-2.471 3c.022-.272.2-.978 1.019-1.734c.734-.679 2.327-2.305 3.042-3.01c.406-.4 1.07-.68 1.374-.679m-3.889-.187l1.337 1.479m-3.229.15l1.495 1.313m5.29 2.23c.83.002 1.54.615 1.538 1.445c-.001.83-.707 1.557-1.538 1.556c-.83-.002-1.47-.732-1.469-1.562c.054-.826.665-1.328 1.47-1.439m-15.867.412l2.958-2.892m-2.992-2.514c.273.022 1.032.204 1.712 1.054c.625.78 2.31 2.29 3.017 3.004c.4.404.68 1.044.68 1.348M7.266 14.23l8.239-9.566c1.34-1.496 3.214-1.528 4.5-1.666c-.112 1.664-.288 2.95-1.26 4.055L8.55 15.927m-3.543 3.572a1.503 1.503 0 1 1-3.006 0a1.503 1.503 0 0 1 3.006 0" />
                            </svg>
                            <div class="absolute inset-0 bottom-2 flex items-center justify-center">
                            <p class="text-md font-bold text-blue-700 bg-blue-100 w-32 rounded-md text-center break-words max-w-full">
                                {{ getTeamName(tournamentWinner) }}
                            </p>
                            </div>
                        </div>
                        <div class="text-center flex gap-4">
                            <svg class="w-20 h-20 mt-3 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-16 h-16 mt-4 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-14 h-14 mt-4 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                            <svg class="w-10 h-10 mt-5 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>

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
                </div>
            </nav>

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
        </template>
    </AppLayout>
</template>
 
<script setup>
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayoutSA.vue';
import Standing from '@/Components/Standing.vue';
import GameSchedule from '@/Components/GameSchedule.vue';
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
    players: Array,
    user: Object
});

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
    router.get(route('subadmin.show'));
};

const hasExistingMatches = computed(() => {
    return props.matches.some(match => match.assigned_sport_id === props.sport.id);
});

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
    return (completedMatches.value / totalMatches.value) * 100;
});


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