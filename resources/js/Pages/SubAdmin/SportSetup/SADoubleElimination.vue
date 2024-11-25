<template>
    <Head :title="sport.sport.name"/>
    <AppLayout>
        <template v-slot:default>
            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <h1 class="text-4xl font-semibold">{{ sport.sport.name }} {{ sport.categories }}</h1>
                    <div>
                        <button 
                            @click="returnToPalakasan" 
                            type="button" 
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-2 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            >
                            <i class="fa-solid fa-right-to-bracket mr-2"></i>
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
                        <button
                            @click="openCreateMatchesModal"
                            type="button"
                            class="flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-700/90 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 disabled:bg-blue-100 disabled:text-blue-700 "
                            :disabled="hasExistingMatches"
                        >
                            {{ hasExistingMatches ? 'Game Already Started' : 'Start Game' }}
                        </button>
                    </div>
                </nav>

                <div class="mt-4">
                    <div v-if="activeTab === 'matches'" >
                        <GameSchedule :matches="matches" :teams="teams" :results="results" :venues="venues"  :allMatches="allMatches" :venueRecords="venueRecords"/>                
                    </div>
                    <div v-if="activeTab === 'standing'">
                        <div class="w-full">
                            <Standing :teams="teams" :results="results" />
                        </div>
                    </div>
                    <div v-if="activeTab === 'brackets'">
                        <div class="overflow-hidden mt-1">
                            <h2 class="text-md font-semibold mb-4">Tournament Bracket</h2>
                            <div class="overflow-hidden max-w-full">
                                <div v-if="Object.keys(groupedBrackets).length > 0" class="overflow-x-auto overflow-y-hidden">
                                    <div class="space-y-8">
                                    <!-- Winners Bracket -->
                                    <div>
                                        <h3 class="text-md font-semibold text-green-700 bg-white  py-2">Winners Bracket</h3>
                                        <div class="flex gap-8 pb-4">
                                            <div 
                                                v-for="(matches, round) in groupedBrackets.winners" 
                                                :key="`winners-${round}`"
                                                class="space-y-6"
                                            >
                                                <h4 class="text-center text-sm font-bold bg-white py-2">Round {{ round }}</h4>
                                                <div 
                                                v-for="match in matches"
                                                @click="openScoreModal(match)"
                                                :key="match.id"
                                                class=" w-56  space-y-2"
                                                :disabled="!canUpdateMatch(match)"
                                                >
                                                    <div class="flex justify-between items-center">
                                                        <p class="font-medium text-xs flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1-7 0a5 5 0 0 0-7 0zm0 16v-7"/></svg>
                                                            {{ match.game }}
                                                        </p>
                                                    </div>
                                                    <div 
                                                    class="shadow border border-gray-300 p-2 rounded-lg"
                                                    :class="{ 'border-2 border-green-500 bg-green-50': isChampionshipMatch(match) }"
                                                    >
                                                        <span class="mb-1 bg-gray-100 text-xs flex rounded-md justify-between"
                                                        :class="getTeamBackgroundColor(match.id, match.teamA_id)"
                                                        >
                                                            <p class="p-2">{{ getTeamName(match.teamA_id) }}</p>
                                                            <div v-if="getMatchResult(match.id)" class="mt-2 mr-2">
                                                                <p>{{ getMatchResult(match.id).teamA_score }}</p>
                                                            </div>
                                                        </span>
                                                        <span class=" bg-gray-100 text-xs flex rounded-md justify-between"
                                                        :class="getTeamBackgroundColor(match.id, match.teamB_id)">
                                                            <p class="p-2">{{ getTeamName(match.teamB_id) }}</p>
                                                            <div v-if="getMatchResult(match.id)" class="mt-2 mr-2">
                                                                <p>{{ getMatchResult(match.id).teamB_score }}</p>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Deciding Match -->
                                            <div v-if="decidingMatch" class="space-y-6">
                                                <h4 class="text-center text-sm font-bold bg-white py-2">
                                                    Deciding Match
                                                </h4>
                                                <div 
                                                    @click="openScoreModal(decidingMatch)"
                                                    :key="decidingMatch.id"
                                                    class=" w-56 space-y-2"
                                                    :disabled="!canUpdateMatch(decidingMatch)"
                                                >
                                                <div class="flex justify-between items-center">
                                                        <p class="font-medium text-xs flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1-7 0a5 5 0 0 0-7 0zm0 16v-7"/></svg>
                                                            {{ decidingMatch.game }}
                                                        </p>
                                                    </div>
                                                    <div  class="shadow ring-2 ring-yellow-400 bg-yellow-50 p-2 rounded-lg">
                                                        <span class="mb-1 bg-yellow-200 text-xs rounded-md flex justify-between"
                                                            :class="getTeamBackgroundColor(decidingMatch.id, decidingMatch.teamA_id)">
                                                            <p class="p-2">{{ getTeamName(decidingMatch.teamA_id) }}</p>
                                                            <div v-if="getMatchResult(decidingMatch.id)" class="mt-2 mr-2">
                                                                <p>{{ getMatchResult(decidingMatch.id).teamA_score }}</p>
                                                            </div>
                                                        </span>
                                                        <span class=" bg-yellow-200 text-xs rounded-md flex justify-between"
                                                            :class="getTeamBackgroundColor(decidingMatch.id, decidingMatch.teamB_id)">
                                                            <p class="p-2">{{ getTeamName(decidingMatch.teamB_id) }}</p>
                                                            <div v-if="getMatchResult(decidingMatch.id)" class="mt-2 mr-2">
                                                                <p>{{ getMatchResult(decidingMatch.id).teamB_score }}</p>
                                                            </div>  
                                                        </span>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Losers Bracket -->
                                    <div>
                                        <h3 class="text-md font-semibold text-orange-700 sticky top-0 bg-white z-10 py-2">Losers Bracket</h3>
                                        <div class="flex gap-8 pb-4">
                                        <div 
                                            v-for="(matches, round) in groupedBrackets.losers"
                                            :key="`losers-${round}`"
                                            class="space-y-6"
                                        >
                                            <h4 class="text-center text-sm font-bold py-2">Round {{ round }}</h4>
                                            <div 
                                                v-for="match in matches"
                                                @click="openScoreModal(match)"
                                                :key="match.id"
                                                class=" w-56 space-y-2"
                                                :disabled="!canUpdateMatch(match)"
                                                >
                                                <div class="flex justify-between items-center">
                                                    <p class="font-medium text-xs flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1-7 0a5 5 0 0 0-7 0zm0 16v-7"/></svg>
                                                        {{ match.game }}
                                                    </p>
                                                </div>    
                                                
                                                <div class="shadow border border-gray-300 p-2 rounded-lg">
                                                    <span class="mb-1 text-xs rounded-md bg-gray-100 flex justify-between"
                                                    :class="getTeamBackgroundColor(match.id, match.teamA_id)"
                                                    >
                                                        <p class="p-2">{{ getTeamName(match.teamA_id) }}</p>
                                                        <div v-if="getMatchResult(match.id)" class="mt-2 mr-2">
                                                        <p>{{ getMatchResult(match.id).teamA_score }}</p>
                                                        </div>
                                                    </span>
                                                    <span class=" text-xs rounded-md bg-gray-100 flex justify-between"
                                                    :class="getTeamBackgroundColor(match.id, match.teamB_id)">
                                                        <p class="p-2">{{ getTeamName(match.teamB_id) }}</p>
                                                        <div v-if="getMatchResult(match.id)" class="mt-2 mr-2">
                                                        <p>{{ getMatchResult(match.id).teamB_score }}</p>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <p v-else class="text-center py-4">No matches have been created yet. Click 'Start Tournament' to begin.</p>
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
                        <div v-for="team in rankingTeams" :key="team.id" class="mb-4">
                            <div class="flex items-center justify-between">
                            <span class="font-medium">{{ team.assigned_team_name }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="font-medium">Rank</span>
                                <input
                                v-model="team.rank"
                                type="number"
                                min="1"
                                :max="rankingTeams.length"
                                class="w-16 border rounded px-2 py-1"
                                required
                                disabled
                                />
                                <span class="font-medium">Points</span>
                                <input
                                v-model="team.points"
                                type="number"
                                min="0"
                                class="w-16 border rounded px-2 py-1"
                                required
                                />
                            </div>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            :disabled="isSubmittingRankings"
                            >
                            {{ isSubmittingRankings ? 'Submitting...' : 'Submit Rankings' }}
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
                                    class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-600 w-full disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{ createMatchesLoading ? 'Creating...' : 'Create Matches' }}
                                </button>
                                <p v-if="createMatchesError" class="text-red-600 mt-2">{{ createMatchesError }}</p>
                            </div>
                        </form>
                        <!-- Form Ends Here -->

                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
 </template>
 
 <script setup>
    import { Head, useForm, router } from '@inertiajs/vue3';
    import { ref, onMounted, computed } from 'vue';
    import { route } from 'ziggy-js';
    import AppLayout from '@/Layout/DashboardLayout.vue';
    import Standing from '@/Components/Standing.vue'
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
});

const loading = ref(false);
const error = ref('');
const showScoreModal = ref(false);
const scoreLoading = ref(false);
const scoreError = ref('');
const selectedMatch = ref(null);
const selectedVenue = ref('');
const shuffleTeams = ref(false);
const showCreateMatchesModal = ref(false);
const createMatchesLoading = ref(false);
const createMatchesError = ref('');

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


const activeTab = ref('matches');  // Default to "Details" 
const returnToPalakasan = () => {
  router.get(route('palakasan.details', { tab: 'leagues' }));
};

// Check if matches already exist for this sport
const hasExistingMatches = computed(() => {
    return props.matches.some(match => match.assigned_sport_id === props.sport.id);
});

// Updated shuffleArray function to make it more random
const shuffleArray = (array) => {
    for (let i = array.length - 1; i > 0; i--) {
        // Generate a random index from 0 to i
        const j = Math.floor(Math.random() * (i + 1));
        // Swap elements array[i] and array[j]
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
};

// Generate matches for the entire tournament
const generateMatchStructure = (teams) => {
    const venueId = parseInt(selectedVenue.value, 10);
    if (!venueId) {
        throw new Error('Please select a valid venue');
    }

    const numTeams = teams.length;
    let matches = [];

    if (numTeams === 4) {
        matches = [
            // Winner's bracket - Round 1
            { round: 1, game: 'Game 1', teamA_id: teams[0].id, teamB_id: teams[1].id, bracket_type: 'winners' },
            { round: 1, game: 'Game 2', teamA_id: teams[2].id, teamB_id: teams[3].id, bracket_type: 'winners' },

            // Loser's bracket - Round 1
            { round: 1, game: 'Game 3', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // losers of games 1 and 2

            // Winner's bracket - Round 2
            { round: 2, game: 'Game 4', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winners of games 1 and 2

            // Loser's bracket - Round 2
            { round: 2, game: 'Game 5', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // winner of game 3 vs loser of game 4

            // Finals
            { round: 3, game: 'Game 6', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winner of game 4 vs winner of game 5
        ];
    } else if (numTeams === 5) {
        matches = [
            // Winner's bracket - Round 1
            { round: 1, game: 'Game 1', teamA_id: teams[0].id, teamB_id: teams[1].id, bracket_type: 'winners' },
            { round: 2, game: 'Game 2', teamA_id: teams[2].id, teamB_id: teams[3].id, bracket_type: 'winners' },
            { round: 2, game: 'Game 3', teamA_id: null, teamB_id: teams[4].id, bracket_type: 'winners' },

            // Loser's bracket - Round 1
            { round: 1, game: 'Game 4', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // loser of game 1 and game 2
            { round: 2, game: 'Game 5', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // winner of game 4 vs loser of game 3

            // Winner's bracket - Round 3
            { round: 3, game: 'Game 6', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winners of game 2 and 3

            // Loser's bracket - Round 3
            { round: 3, game: 'Game 7', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // winner of game 5 vs loser of game 6

            // Finals
            { round: 4, game: 'Game 8', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winner of game 6 vs winner of game 7
        ];
    } else if (numTeams === 6) {
        matches = [
            // Winner's bracket - Round 1
            { round: 1, game: 'Game 1', teamA_id: teams[0].id, teamB_id: teams[1].id, bracket_type: 'winners' },
            { round: 1, game: 'Game 2', teamA_id: teams[2].id, teamB_id: teams[3].id, bracket_type: 'winners' },
            { round: 2, game: 'Game 3', teamA_id: teams[4].id, teamB_id: null, bracket_type: 'winners' },
            { round: 2, game: 'Game 4', teamA_id: teams[5].id, teamB_id: null, bracket_type: 'winners' }, // winner of game 1 vs winner of game 2


            // Loser's bracket - Round 1
            { round: 1, game: 'Game 5', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // losers of games 1 and 2
            { round: 1, game: 'Game 6', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // winner of game 1  vs winner of game 2
            { round: 2, game: 'Game 7', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // winner of game 4 vs loser of game 3
            
            // Winner's bracket - Round 3
            { round: 3, game: 'Game 8', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winner of game 3 vs winner of game 5

            // Loser's bracket - Round 3
            { round: 3, game: 'Game 9', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // winner of game 6 vs loser of game 7

            // Finals
            { round: 4, game: 'Game 10', teamA_id: null, teamB_id: null, bracket_type: 'winners' },  // winner of game 7 vs winner of game 8

        ];
    } else if (numTeams === 7) {
        matches = [
            // Winner's bracket - Round 1
            { round: 1, game: 'Game 1', teamA_id: teams[0].id, teamB_id: teams[1].id, bracket_type: 'winners' },
            { round: 1, game: 'Game 2', teamA_id: teams[2].id, teamB_id: teams[3].id, bracket_type: 'winners' },
            { round: 1, game: 'Game 3', teamA_id: teams[4].id, teamB_id: teams[5].id, bracket_type: 'winners' },
            
            // Winner's bracket - Round 2
            { round: 2, game: 'Game 4', teamA_id: teams[6].id, teamB_id: null, bracket_type: 'winners' }, // team 7 vs winner of game 1
            { round: 2, game: 'Game 5', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winners of games 2 and 3

            // Loser's bracket - Round 1
            { round: 1, game: 'Game 6', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // losers of games 1 and 2
            { round: 1, game: 'Game 7', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // loser of game 3 vs loser of game 4

            // Loser's bracket - Round 2
            { round: 2, game: 'Game 8', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // winners of games 6 and 7

            // Winner's bracket - Round 3
            { round: 3, game: 'Game 9', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winners of games 4 and 5

            // Loser's bracket - Round 3
            { round: 3, game: 'Game 10', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // winner of game 8 vs loser of game 9

            // Finals
            { round: 4, game: 'Game 11', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winner of game 9 vs winner of game 10

            
        ];
    } else if (numTeams === 8) {
        matches = [
            // Winner's bracket - Round 1
            { round: 1, game: 'Game 1', teamA_id: teams[0].id, teamB_id: teams[1].id, bracket_type: 'winners' },
            { round: 1, game: 'Game 2', teamA_id: teams[2].id, teamB_id: teams[3].id, bracket_type: 'winners' },
            { round: 1, game: 'Game 3', teamA_id: teams[4].id, teamB_id: teams[5].id, bracket_type: 'winners' },
            { round: 1, game: 'Game 4', teamA_id: teams[6].id, teamB_id: teams[7].id, bracket_type: 'winners' },

            // Loser's bracket - Round 1
            { round: 1, game: 'Game 5', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // losers of games 1 and 2
            { round: 1, game: 'Game 6', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // losers of games 3 and 4

            // Winner's bracket - Round 2
            { round: 2, game: 'Game 7', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winners of games 1 and 2
            { round: 2, game: 'Game 8', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winners of games 3 and 4

            // Loser's bracket - Round 2
            { round: 2, game: 'Game 9', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // winners of games 5 and 6

            // Winner's bracket - Round 3
            { round: 3, game: 'Game 10', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winners of games 7 and 8

            // Loser's bracket - Round 3
            { round: 3, game: 'Game 11', teamA_id: null, teamB_id: null, bracket_type: 'losers' }, // winner of game 9 vs loser of game 10

            // Finals
            { round: 4, game: 'Game 12', teamA_id: null, teamB_id: null, bracket_type: 'winners' }, // winner of game 10 vs winner of game 11

        ];
    } else {
        throw new Error('This tournament format supports 5-8 teams only');
    }

    return matches.map(match => ({
        ...match,
        assigned_sport_id: props.sport.id,
        elimination_type: props.sport.setup,
        status: 'pending',
        match_venue_id: venueId,  // Make sure this is included
        date: null,
        time: null,
    }));
};

const createInitialMatches = async () => {
    if (hasExistingMatches.value) {
        error.value = 'Tournament has already been started';
        return;
    }

    loading.value = true;
    error.value = '';
    createMatchesLoading.value = true;

    try {
        // Create a copy of teams array to avoid mutating the original
        let teamsToUse = [...props.teams];
        
        // Shuffle teams if the option is selected
        if (shuffleTeams.value) {
            teamsToUse = shuffleArray([...teamsToUse]);
        }
        
        const matches = generateMatchStructure(teamsToUse);
        
        // Create matches
        await router.post(route('matches.store'), matches, {
            onSuccess: () => {
                console.log('Matches created successfully');
                loading.value = false;
                createMatchesLoading.value = false;
                closeCreateMatchesModal(); // Close the modal after successful creation
            },
            onError: (errors) => {
                console.error('Error creating matches:', errors);
                error.value = 'Failed to create matches';
                loading.value = false;
                createMatchesLoading.value = false;
            }
        });
        
    } catch (err) {
        loading.value = false;
        createMatchesLoading.value = false;
        error.value = 'Failed to create matches';
        console.error('Error creating matches:', err);
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
const groupedBrackets = computed(() => {
    const sportMatches = props.matches.filter(match => match.assigned_sport_id === props.sport.id);
    
    const brackets = {
        winners: {},
        losers: {},
        deciding: {}
    };
    
    sportMatches.forEach(match => {
        const bracket = match.bracket_type === 'deciding match' ? 'deciding' : match.bracket_type;
        const round = match.round || 1;
        
        if (!brackets[bracket]) {
            brackets[bracket] = {};
        }
        
        if (!brackets[bracket][round]) {
            brackets[bracket][round] = [];
        }
        
        brackets[bracket][round].push(match);
    });
    
    // Sort matches within each round by game number
    Object.values(brackets).forEach(bracketRounds => {
        Object.values(bracketRounds).forEach(round => {
            round.sort((a, b) => {
                const gameA = parseInt(a.game.replace('Game ', ''));
                const gameB = parseInt(b.game.replace('Game ', ''));
                return gameA - gameB;
            });
        });
    });
    
    return brackets;
});
// Check if a match can be updated (has both teams assigned and is not a placeholder)
const canUpdateMatch = (match) => {
    return match.teamA_id && match.teamB_id && 
           !match.teamA_id.toString().startsWith('winner:') && 
           !match.teamB_id.toString().startsWith('winner:');
};

// Score form data
const scoreFormData = ref({
    sport_match_id: '',
    teamA_score: '',
    teamB_score: '',
    winning_team_id: '',
    losing_team_id: ''
});

// Get match result if it exists
const getMatchResult = (matchId) => {
    return props.results.find(result => result.sport_match_id === matchId);
};

// Open score modal
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

// Close score modal
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

// Submit score
const submitScore = async () => {
    scoreLoading.value = true;
    scoreError.value = '';

    const { teamA_score, teamB_score } = scoreFormData.value;
    const teamAId = selectedMatch.value.teamA_id;
    const teamBId = selectedMatch.value.teamB_id;

    // Determine winning and losing teams
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

const tournamentWinner = computed(() => {
  const winnersBracket = groupedBrackets.value.winners;
  const lastRound = winnersBracket[Math.max(...Object.keys(winnersBracket).map(Number))];
  if (!lastRound || lastRound.length === 0) return null;
  
  const championshipMatches = lastRound.filter(match => isChampionshipMatch(match));
  if (championshipMatches.length === 0) return null;

  const lastChampionshipMatch = championshipMatches[championshipMatches.length - 1];
  const result = getMatchResult(lastChampionshipMatch.id);
  
  // Check if there's a deciding match
  if (decidingMatch.value) {
    const decidingResult = getMatchResult(decidingMatch.value.id);
    // If the deciding match is completed, use its result
    if (decidingResult && decidingMatch.value.status === 'completed') {
      return decidingResult.winning_team_id;
    }
    // If there's a deciding match but it's not completed, don't declare a winner yet
    return null;
  }
  
  // If there's no deciding match, use the result of the last championship match
  return result && lastChampionshipMatch.status === 'completed' ? result.winning_team_id : null;
});

// Modify the isChampionshipMatch function to include the deciding match
const isChampionshipMatch = (match) => {
  const winnersBracket = groupedBrackets.value.winners;
  const lastRound = winnersBracket[Math.max(...Object.keys(winnersBracket).map(Number))];
  return lastRound && (lastRound[0]?.id === match.id || match.bracket_type === 'deciding match');
};

// Add this computed property to get the deciding match
const decidingMatch = computed(() => {
  const decidingMatches = props.matches.filter(match => match.bracket_type === 'deciding match');
  return decidingMatches.length > 0 ? decidingMatches[0] : null;
});

// New computed properties for progress bar
const totalMatches = computed(() => props.matches.length);

const completedMatches = computed(() => {
  return props.matches.filter(match => match.status === 'completed').length;
});

const progressPercentage = computed(() => {
  return (completedMatches.value / totalMatches.value) * 100;
});


const getTeamBackgroundColor = (matchId, teamId) => {
    const result = getMatchResult(matchId);
    if (!result || !teamId) return 'bg-gray-200';

    if (result.winning_team_id === teamId) {
        return 'bg-green-200 text-green-600';
    } else if (result.losing_team_id === teamId) {
        return 'bg-red-200 text-red-600';
    }
    return 'bg-gray-200 ';
};

//

const rankingsSubmitted = ref(false);

//

const showRankingModal = ref(false)
const isSubmittingRankings = ref(false)
const rankingTeams = ref([])

const openRankingModal = () => {
  rankingTeams.value = props.teams.map((team, index) => ({
    ...team,
    rank: index + 1,
    points: 0,
  }))
  showRankingModal.value = true
}

const closeRankingModal = () => {
  showRankingModal.value = false
}

const form = useForm({
  rankings: [],
})

const submitRankings = () => {
  isSubmittingRankings.value = true
  form.rankings = rankingTeams.value.map(team => ({
    assigned_sport_id: props.sport.id,
    assigned_team_id: team.id,
    rank: parseInt(team.rank),
    points: parseInt(team.points),
  }))

  form.post(route('overall-results.store'), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      closeRankingModal()
      isSubmittingRankings.value = false
    },
    onError: () => {
      isSubmittingRankings.value = false
    },
  })
}
const checkWinsAndLosses = (teamId) => {
  let wins = 0;
  let losses = 0;

  props.results.forEach(result => {
    if (result.winning_team_id === teamId) {
      wins++;
    } else if (result.losing_team_id === teamId) {
      losses++;
    }
  });

  return { wins, losses };
};

const winnerStats = computed(() => {
  return checkWinsAndLosses(props.tournamentWinner);
});
 </script>
 
 <style scoped>
    /* Home page specific styles */
 </style>