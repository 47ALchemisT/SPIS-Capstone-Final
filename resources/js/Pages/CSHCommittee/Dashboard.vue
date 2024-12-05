<template>
    <Head title="Dashboard"/>
    <AppLayout :comHead="comHead">
        <template v-slot:default>
            <div class="space-y-6">
                <!-- Unassigned State -->
                <div v-if="!assignedCollege" class="flex flex-col items-center justify-center py-36 px-4 h-full">
                    <div class="text-center max-w-md">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome {{ comHead.student.first_name }} {{ comHead.student.last_name }} !</h2>
                        <p class="text-gray-600 text-sm mb-8">To get started, please select the college you'll be managing during the Palakasan.</p>
                        <div v-if="assignedTeams.some(team => !team.is_assigned)" class="space-y-4">
                            <button
                                @click="openAssignModal"
                                class="inline-flex items-center text-sm px-6 py-2.5 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Select Team
                            </button>
                        </div>
                        <div v-else class="space-y-4">
                            <div class="p-4 bg-yellow-50 rounded-lg">
                                <p class="text-yellow-800 text-sm">All teams have already been assigned to committee heads.</p>
                            </div>
                            <button
                                disabled
                                class="inline-flex items-center text-sm px-6 py-2.5 border border-transparent text-base font-medium rounded-md text-white bg-gray-400 cursor-not-allowed"
                            >
                                No Teams Available
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Assigned State -->
                <div v-else class="space-y-6">
                    <!-- Tabs Navigation -->
                    <div class="sticky top-[4rem] z-40 bg-white/80 backdrop-blur-sm py-2">
                        <nav class="flex relative justify-between items-center px-4">
                            <div class="">
                                <h1 class="text-lg font-medium text-gray-700">College Committee Head Dashboard</h1>
                                <h1 class="text-xs text-gray-600">{{ assignedCollege.assigned_team.college.name }}</h1>
                            </div>
                            <div class="flex gap-2 rounded-lg">
                                <div class="flex gap-2 rounded-lg">
                                    <button 
                                        v-for="tab in ['home', 'sports', 'schedule', 'Overview', 'settings']"
                                        :key="tab"
                                        @click="activeTab = tab"
                                        :class="[
                                        'px-5 py-2 text-sm',
                                        activeTab === tab
                                            ? 'text-gray-800 bg-blue-700 text-white font-medium rounded-lg'
                                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700 font-medium border-transparent'
                                        ]"
                                    >
                                        {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                                    </button>
                                </div>
                            </div>
                        </nav>
                    </div>

                    <div class="px-4 pb-4">
                            <div class="py-6" v-if="activeTab === 'home'">
                                <div class="grid grid-cols-1 gap-5">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="">
                                            <p class="text-2xl text-gray-800">Hello, <span class="font-semibold">{{ comHead.student.first_name }} {{ comHead.student.last_name }}</span> !</p>
                                            <p class="text-md text-gray-600 mb-4">It's great to see you, are you ready to get started.</p>
                                            <!--add a task list here-->
                                            <div class="mt-8">
                                                <h3 class="text-md font-semibold text-gray-700">Tasks</h3>
                                                <p class="text-sm text-gray-600 mb-4">You are assigned to assign players to these sports</p>
                                                <div class="space-y-3">
                                                    <div class="px-3 bg-white rounded-lg">
                                                        <div class="space-y-2">
                                                            <div v-for="sport in sportsWithStatus" :key="sport.id" class="flex items-center justify-between">
                                                                <div class="flex items-center gap-2">
                                                                    <svg :class="[
                                                                        'w-4 h-4',
                                                                        sport.assigned_players && sport.assigned_players.length > 0 ? 'text-green-600' : 'text-gray-400'
                                                                    ]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M17 3.34a10 10 0 1 1-14.995 8.984L2 12l.005-.324A10 10 0 0 1 17 3.34m-1.293 5.953a1 1 0 0 0-1.32-.083l-.094.083L11 12.585l-1.293-1.292l-.094-.083a1 1 0 0 0-1.403 1.403l.083.094l2 2l.094.083a1 1 0 0 0 1.226 0l.094-.083l4-4l.083-.094a1 1 0 0 0-.083-1.32"/></svg>                                                            
                                                                    <span class="text-sm text-gray-600">{{ sport.sport.name }} {{ sport.categories }}</span>
                                                                </div>
                                                                <span :class="[
                                                                    'px-2 py-0.5 text-xs rounded-full font-medium',
                                                                    sport.assigned_players && sport.assigned_players.length > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'
                                                                ]">
                                                                    {{ sport.assigned_players && sport.assigned_players.length > 0 ? `Players (${sport.assigned_players.length})` : 'No Players' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-8">
                                                <button 
                                                    @click="activeTab = 'sports'"
                                                    type="button" 
                                                    class="bg-white hover:bg-gray-100 focus:ring-4 text-gray-700 focus:ring-gray-300 ring-1 ring-gray-200 hover:ring-gray-400 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition ease-in-out duration-300"
                                                >
                                                    Start assigning players
                                                    <i class="fa-solid fa-arrow-right ms-2"></i>  
                                                </button>
                                            </div>
                                        </div>
                                        <div class="h-[28rem] w-full ">
                                            <img class="w-full h-full object-cover" src="/resources/assets/comhead.png" alt="com head">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 h-full">
                                        <div class="bg-blue-50 rounded-lg p-6">
                                            <svg class="w-10 h-10 mb-3 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="M256 23.57l-16.1 48.86h32.2zM73 90.43v15.97h366V90.43zm48 33.97V479l135-105l135 105V124.4zm87 37h96l-32 80l80-32v96l-80-32l32 80h-96l32-80l-80 32v-96l80 32zm48 235.4l-23 17.9v73.7h46v-73.7z"/></svg>
                                            <p class="text-2xl font-medium text-blue-700 mt-4">{{ assignedCollege.assigned_team.assigned_team_name }}</p>
                                            <p class="text-sm text-blue-500 mb-3">{{ assignedCollege.assigned_team.college.name }}</p>
                                        </div>
                                        <div class="bg-blue-50 h-full rounded-lg p-6">
                                            <i class="fa-solid fa-graduation-cap text-blue-700 w-8 h-8 mb-6 text-3xl"></i>

                                            <p class="text-sm text-blue-600 mb-1.5">Total number of Students</p>
                                            <p class="text-2xl font-bold text-blue-700">{{ students.length }}</p>
                                        </div>
                                        <div class="bg-blue-50 h-full rounded-lg p-6">
                                            <i class="fa-solid fa-user text-blue-700 w-8 h-8 mb-6 text-3xl"></i>
                                            <p class="text-sm text-blue-600 mb-1.5">Assigned Students</p>
                                            <p class="text-2xl font-bold text-blue-700">{{ totalAssignedStudents }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="activeTab === 'sports'">
                                <!--Content-->
                                <div class="space-y-3 py-6">
                                    <div>
                                        <div class="mb-4 flex justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="relative w-full sm:w-64 sm:mb-0">
                                                    <input
                                                        v-model="sportsSearchQuery"
                                                        type="text"
                                                        placeholder="Search sport..."
                                                        class="w-full pl-10 pr-10 py-2 bg-white border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                    >
                                                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                                                    <button
                                                        v-if="sportsSearchQuery"
                                                        @click="clearSearch"
                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                                        >
                                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <h2 class="text-md font-medium text-gray-700">Assigned Sports ({{ assignedSports.length }})</h2>
                                            </div>
                                        </div>

                                        <ul class="space-y-4">
                                            <li v-for="sport in filteredSports" :key="sport.id" class="p-5 bg-white rounded-lg ring-1 ring-gray-200">
                                                <div class="flex justify-between mb-2">
                                                    <div>
                                                        <p class="text-gray-800 text-lg font-medium">{{ sport.sport.name }} {{ sport.categories }}</p>
                                                        <div class="flex items-center gap-2 text-sm text-gray-500">
                                                            <span>{{ sport.setup }}</span>
                                                            <span class="text-xs">•</span>
                                                            <span>{{ sport.type }}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <button
                                                            @click="openPlayerModal(sport)"
                                                            class="bg-blue-700 hover:bg-blue-600 font-medium text-white py-2 px-4 rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-700"
                                                            :disabled="palakasan.status === 'live' || palakasan.status === 'completed'"
                                                            >
                                                            Assign Players
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <!-- Assigned Players List Accordion -->
                                                <Disclosure v-slot="{ open }" as="div" class="mt-4 rounded-lg bg-gray-50" v-if="sport.assigned_players">
                                                    <DisclosureButton class="flex w-full justify-between rounded-lg  bg-gray-50 px-4 py-4 text-left text-sm font-medium text-gray-900 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75">
                                                        <span>Players ({{ sport.assigned_players.length }})</span>
                                                        <svg
                                                            :class="[open ? 'rotate-180 transform' : '', 'h-4 w-4 text-gray-500']"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                        >
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                                                        </svg>
                                                    </DisclosureButton>
                                                    <TransitionChild
                                                        enter="transition duration-100 ease-out"
                                                        enterFrom="transform scale-95 opacity-0"
                                                        enterTo="transform scale-100 opacity-100"
                                                        leave="transition duration-75 ease-out"
                                                        leaveFrom="transform scale-100 opacity-100"
                                                        leaveTo="transform scale-95 opacity-0"
                                                    >
                                                        <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                                                            <div v-if="sport.assigned_players.length > 0" class="space-y-2">
                                                                <div v-for="player in sport.assigned_players" :key="player.id" 
                                                                    class="flex items-center justify-between"
                                                                >
                                                                    <div class="flex gap-2 items-center">
                                                                        <div class="flex-shrink-0">
                                                                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                                                <span class="text-gray-700 font-medium">
                                                                                    {{ player.student.first_name[0] }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <p class="text-sm font-medium text-gray-900">
                                                                                {{ player.student.first_name }} {{ player.student.last_name }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <button 
                                                                        @click="removePlayer(sport.id, player.id)"
                                                                        class="p-1 text-red-600 hover:text-red-800 rounded-full hover:bg-red-50"
                                                                        title="Remove player"
                                                                    >
                                                                        <i class="fa-solid fa-xmark"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <p v-else class="text-center text-gray-500 py-2">No players assigned yet</p>
                                                        </DisclosurePanel>
                                                    </TransitionChild>
                                                </Disclosure>
                                                <p v-else class="text-sm text-gray-500 italic mt-2">No players assigned yet</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div v-if="activeTab === 'Overview'" class="py-6">
                                <div class="max-w-7xl mx-auto px-12 space-y-8">
                                    <!-- Important Dates Section -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Important Dates</h3>
                                        <div class="space-y-3">
                                            <div class="flex items-start gap-4">
                                                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-md text-sm font-medium">
                                                    {{ formatDate(palakasan.start_date) }}
                                                </div>
                                                <div>
                                                    <p class="text-gray-900 font-medium">Event Start</p>
                                                    <p class="text-sm text-gray-600">Opening ceremony and start of competitions</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-4">
                                                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-md text-sm font-medium">
                                                    {{ formatDate(palakasan.end_date) }}
                                                </div>
                                                <div>
                                                    <p class="text-gray-900 font-medium">Event End</p>
                                                    <p class="text-sm text-gray-600">Closing ceremony and awarding</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Guidelines Section -->
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Committee Head Guidelines</h3>
                                        <div class="space-y-4">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 w-6 h-6 text-blue-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">Player Assignment</h4>
                                                    <p class="text-sm text-gray-600">Ensure all players are properly assigned to their respective sports before the event starts.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 w-6 h-6 text-blue-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.694 3.397 1.672c.8.488 1.484 1.064 1.978 1.69c.486.615.844 1.351.844 2.138c0 .845-.411 1.511-1.003 1.986c-.56.45-1.299.748-2.084.956c-1.578.417-3.684.558-5.913.558s-4.335-.14-5.913-.558c-.785-.208-1.524-.506-2.084-.956C3.41 20.01 3 19.345 3 18.5c0-5.03 4.428-9 9.75-9s9.75 3.97 9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">Verification</h4>
                                                    <p class="text-sm text-gray-600">Double-check player eligibility and sport category assignments.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 w-6 h-6 text-blue-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">Timely Updates</h4>
                                                    <p class="text-sm text-gray-600">Keep player assignments and team information up to date throughout the event.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="activeTab === 'schedule'" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Upcoming Matches</h2>
                                    
                                    <div v-if="!upcomingSchedules || upcomingSchedules.length === 0" class="text-center py-8">
                                        <p class="text-gray-500">No upcoming matches scheduled for your team.</p>
                                    </div>
                                    
                                    <div v-else class="space-y-4">
                                        <div class="flex justify-between mb-4">
                                            <div class="flex gap-2 rounded-lg">
                                                <button 
                                                    v-for="tab in ['pending', 'completed']"
                                                    :key="tab"
                                                    @click="matchTab = tab"
                                                    :class="[
                                                        'px-4 py-2 text-sm font-medium rounded-md',
                                                        matchTab === tab
                                                            ? 'bg-blue-100 text-blue-700'
                                                            : 'text-gray-500 hover:text-gray-700'
                                                    ]"
                                                >
                                                    {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div v-for="match in displayedFreeForAllMatches" :key="match.id" 
                                                class="bg-white border rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <h3 class="text-lg font-medium text-black-900">
                                                                {{ match.assignedSport?.sport?.name || 'Free for All Event' }}
                                                                <span class="text-sm font-normal text-green-700">
                                                                    - {{ match.sport_variation_name }}
                                                                </span>
                                                            </h3>
                                                            <span :class="[
                                                                'px-2 py-1 text-xs rounded-full font-medium',
                                                                match.status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                                            ]">
                                                                {{ match.status }}
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="mt-2 text-sm text-black-700">
                                                            <div class="flex items-center gap-1">
                                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                                                                </svg>
                                                                {{ formatDate(match.date) }} at {{ formatTime(match.time) }}
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mt-2 text-sm text-black-700" v-if="match.matchVenue">
                                                            <div class="flex items-center gap-1">
                                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" />
                                                                </svg>
                                                                {{ match.matchVenue.name }}
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mt-2">
                                                            <h4 class="text-sm font-medium text-gray-700">Participating Teams:</h4>
                                                            <div v-if="match.participating_teams?.length" class="mt-1 space-y-1">
                                                                <div v-for="team in match.participating_teams" :key="team.team_name" 
                                                                     class="flex justify-between px-3 py-2 bg-gray-50 rounded">
                                                                    <div>
                                                                        <span class="text-sm font-medium">{{ team.team_name }}</span>
                                                                        <span class="text-xs text-gray-500 ml-2">({{ team.college_name }})</span>
                                                                    </div>
                                                                    <div v-if="match.status === 'Completed'" class="text-sm">
                                                                        Rank: {{ team.rank }} ({{ team.points }} pts)
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p v-else class="text-sm text-gray-500 mt-1">No teams registered yet</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                    <div v-for="match in displayedMatches" :key="match.id" 
                                        class="bg-white border rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-lg font-medium text-gray-900">
                                                        {{ match.game || '?' }}
                                                    </h3>
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                                                        :class="{
                                                            'bg-yellow-100 text-yellow-800': match.status === 'Pending',
                                                            'bg-green-100 text-green-800': match.status === 'Completed',
                                                            'bg-blue-100 text-blue-800': match.status === 'In Progress'
                                                        }">
                                                        {{ match.status || 'Pending' }}
                                                    </span>
                                                </div>
                                                <div class="mt-2 flex items-center gap-2 text-sm text-gray-500">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                                                    </svg>
                                                    {{ match.match_venue?.name || 'Venue TBD' }}
                                                </div>
                                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ formatDate(match.date) }} {{ formatTime(match.time) }}
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="flex items-center space-x-4">
                                                    <div class="text-right flex-1">
                                                        <p class="font-medium text-gray-900">
                                                            {{ match.teamA?.assigned_team_name || 'TBD' }}
                                                        </p>
                                                        <p class="text-sm text-gray-600">
                                                            {{ match.teamA?.college?.name || 'TBD' }}
                                                        </p>
                                                    </div>
                                                    <div class="text-gray-400 font-medium">VS</div>
                                                    <div class="text-left flex-1">
                                                        <p class="font-medium text-gray-900">
                                                            {{ match.teamB?.assigned_team_name || 'TBD' }}
                                                        </p>
                                                        <p class="text-sm text-gray-600">
                                                            {{ match.teamB?.college?.name || 'TBD' }}
                                                        </p>
                                                    </div>  
                                                </div>                                   
                                            </div>                        
                                        </div>
                                        
                                        
                                        <!--completed-->
                                        <div class="mt-2 text-sm text-gray-500">
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                </svg>
                                                {{ match.assigned_sport?.sport?.name || 'Sport TBD' }}
                                            </span>
                                            <span class="mx-2">•</span>
                                            <span>Round {{ match.round || '?' }}</span>
                                            <template v-if="match.match_result">
                                                <span class="mx-2">•</span>
                                                <span class="text-green-600 font-medium">
                                                    Winner: {{ match.match_result.winning_team?.assigned_team_name || 'Not specified' }}
                                                </span>

                                                <span class="text-red-600 font-medium">
                                                    Loser: {{ match.match_result.losing_team?.assigned_team_name || 'Not specified' }}
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="activeTab === 'settings'">
                                <CSHSettings :facilitator="comHead" :assignedSports="assignedSports" :assignedCollege="assignedCollege" />
                            </div>
                    </div>

                </div>
                <!-- Player Assignment Modal -->
                <TransitionRoot appear :show="isPlayerModalOpen" as="template">
                            <Dialog as="div" @close="closePlayerModal" class="relative z-50">
                                <TransitionChild
                                    enter="duration-300 ease-out"
                                    enter-from="opacity-0"
                                    enter-to="opacity-100"
                                    leave="duration-200 ease-in"
                                    leave-from="opacity-100"
                                    leave-to="opacity-0"
                                >
                                    <div class="fixed inset-0 bg-black bg-opacity-25" />
                                </TransitionChild>

                                <div class="fixed inset-0 overflow-y-auto">
                                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                                        <TransitionChild
                                            enter="duration-300 ease-out"
                                            enter-from="opacity-0 scale-95"
                                            enter-to="opacity-100 scale-100"
                                            leave="duration-200 ease-in"
                                            leave-from="opacity-100 scale-100"
                                            leave-to="opacity-0 scale-95"
                                        >
                                            <DialogPanel class=" w-[28rem] transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                                    Assign Players to {{ selectedSport ? selectedSport.sport.name : '' }} 
                                                    {{ selectedSport ? selectedSport.categories : '' }}
                                                </DialogTitle>

                                                <div class="mt-4">
                                                    <input
                                                        type="text"
                                                        v-model="searchQuery"
                                                        placeholder="Search students..."
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                    />
                                                    
                                                    <div class="mt-4 max-h-96 overflow-y-auto">
                                                        <div v-for="student in filteredStudents" :key="student.id" 
                                                            class="flex items-center p-3 rounded-lg hover:bg-gray-50"
                                                            :class="{'opacity-50': isStudentAssigned(student.id)}"
                                                        >
                                                            <input
                                                                type="checkbox"
                                                                :value="student.id"
                                                                v-model="selectedStudents"
                                                                :id="'student-' + student.id"
                                                                class="mr-3"
                                                                :disabled="isStudentAssigned(student.id)"
                                                            />
                                                            <label :for="'student-' + student.id" class="flex-1">
                                                                {{ student.first_name }} {{ student.last_name }}
                                                                <span v-if="isStudentAssigned(student.id)" 
                                                                    class="text-sm text-gray-500 italic ml-2"
                                                                >(Already assigned)</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-6 flex justify-end space-x-3">
                                                    <button
                                                        @click="closePlayerModal"
                                                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-md"
                                                    >
                                                        Cancel
                                                    </button>
                                                    <button
                                                        @click="assignPlayers"
                                                        :disabled="selectedStudents.length === 0"
                                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-md disabled:opacity-50 disabled:cursor-not-allowed"
                                                    >
                                                        Assign Players
                                                    </button>
                                                </div>
                                            </DialogPanel>
                                        </TransitionChild>
                                    </div>
                                </div>
                            </Dialog>
                </TransitionRoot>

                <!-- College Assignment Modal -->
                <TransitionRoot appear :show="isModalOpen" as="template">
                            <Dialog as="div" @close="closeAssignModal" class="relative z-50">
                                <TransitionChild
                                    enter="duration-300 ease-out"
                                    enter-from="opacity-0"
                                    enter-to="opacity-100"
                                    leave="duration-200 ease-in"
                                    leave-from="opacity-100"
                                    leave-to="opacity-0"
                                >
                                    <div class="fixed inset-0 bg-black bg-opacity-25" />
                                </TransitionChild>

                                <div class="fixed inset-0 overflow-y-auto">
                                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                                        <TransitionChild
                                            enter="duration-300 ease-out"
                                            enter-from="opacity-0 scale-95"
                                            enter-to="opacity-100 scale-100"
                                            leave="duration-200 ease-in"
                                            leave-from="opacity-100 scale-100"
                                            leave-to="opacity-0 scale-95"
                                        >
                                            <DialogPanel class="max-w-xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                                    Select Your Team
                                                </DialogTitle>

                                                <div class="mt-4">
                                                    <div class="space-y-2">
                                                        <div v-for="team in assignedTeams" :key="team.id" 
                                                            class="flex items-center p-4 rounded-lg"
                                                            :class="{
                                                                'bg-blue-50 border border-blue-200': selectedTeam === team.id,
                                                                'hover:bg-gray-50 border border-gray-200': selectedTeam !== team.id && !team.is_assigned,
                                                                'opacity-50 border border-gray-200 cursor-not-allowed': team.is_assigned
                                                            }"
                                                            @click="!team.is_assigned && selectTeam(team.id)"
                                                        >
                                                            <div class="flex-1">
                                                                <h4 class="text-base font-medium text-gray-900">{{ team.assigned_team_name }}</h4>
                                                                <p class="text-sm text-gray-500">{{ team.college.name }}</p>
                                                                <span v-if="team.is_assigned" class="text-sm text-gray-500 italic">(Already assigned)</span>
                                                            </div>
                                                            <div v-if="selectedTeam === team.id && !team.is_assigned" class="text-blue-600">
                                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-6 flex justify-end space-x-3">
                                                    <button
                                                        @click="closeAssignModal"
                                                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-md"
                                                    >
                                                        No, cancel
                                                    </button>
                                                    <button
                                                        @click="assignTeam"
                                                        :disabled="!selectedTeam"
                                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-md disabled:opacity-50 disabled:cursor-not-allowed"
                                                    >
                                                        Select, let's get started
                                                    </button>
                                                </div>
                                            </DialogPanel>
                                        </TransitionChild>
                                    </div>
                                </div>
                            </Dialog>
                </TransitionRoot>
            </div>
        </template>
    </AppLayout>
</template>
 
<script setup>
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { ChevronDownIcon } from '@heroicons/vue/20/solid'
    import { Head, useForm, router, Link } from '@inertiajs/vue3';
    import { ref, onMounted, computed, watch } from 'vue';
    import { route } from 'ziggy-js';
    import AppLayout from '@/Layout/DashboardLayoutCSH.vue';
    import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot, Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
    import CSHSettings from '@/Components/CSHSettings.vue'

    const props = defineProps({
        comHead: {
            type: Object,
            required: true
        },
        assignedCollege: {
            type: Object,
            default: null
        },
        assignedSports: {
            type: Array,
            default: () => []
        },
        assignedTeams: {
            type: Array,
            default: () => []
        },
        assignedPlayers: {
            type: Array,
            default: () => []
        },
        students: {
            type: Object,
            required: true
        },
        palakasan: {
            type: Object,
            required: true
        },
        upcomingSchedules: {
            type: Array,
            default: () => []
        },
        totalAssignedStudents: {
            type: Number,
            default: 0
        },
        freeForAllMatches: {
            type: Array,
            default() { return [] }
        }
    });

    const isModalOpen = ref(false);
    const selectedTeam = ref(null);
    const activeTab = ref('home');  

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    };

    const formatTime = (timeString) => {
        if (!timeString) return '';
        return timeString; // Just return the time string as is since it's already in the correct format
    };

    // Compute sports with player assignment status
    const sportsWithStatus = computed(() => {
        return props.assignedSports.map(sport => ({
            ...sport,
            has_players: props.assignedPlayers.some(player => player.student_assigned_sport_id === sport.id)
        }));
    });

    const sportsSearchQuery = ref('');

    const openAssignModal = () => {
        isModalOpen.value = true;
    };

    const closeAssignModal = () => {
        isModalOpen.value = false;
        selectedTeam.value = null;
    };

    const assignTeam = () => {
        if (!selectedTeam.value || !props.comHead?.id || !props.palakasan?.id) {
            console.error('Missing required data for college assignment');
            return;
        }
        
        router.post(route('chs.assign-college'), {
            assigned_team_id: selectedTeam.value,
            com_head_id: props.comHead.id,
            palakasan_sport_id: props.palakasan.id
        }, {
            onSuccess: () => {
                closeAssignModal();
            }
        });
    };

    const isPlayerModalOpen = ref(false);
    const selectedSport = ref(null);
    const selectedStudents = ref([]);
    const searchQuery = ref('');
    const newlyAssignedPlayers = ref([]);

    const isStudentAssigned = (studentId) => {
        return props.assignedSports.some(sport => 
            sport.assigned_players && 
            sport.assigned_players.some(player => player.student.id === studentId)
        );
    };

    const filteredStudents = computed(() => {
        if (!searchQuery.value) return props.students;
        
        const query = searchQuery.value.toLowerCase();
        return props.students.filter(student => 
            student.first_name.toLowerCase().includes(query) ||
            student.last_name.toLowerCase().includes(query)
        );
    });

    const openPlayerModal = (sport) => {
        selectedSport.value = sport;
        isPlayerModalOpen.value = true;
    };

    const closePlayerModal = () => {
        isPlayerModalOpen.value = false;
        selectedSport.value = null;
        selectedStudents.value = [];
        searchQuery.value = '';
    };

    const assignPlayers = () => {
        router.post(route('chs.assign-players'), {
            sport_id: selectedSport.value.id,
            student_ids: selectedStudents.value,
            assigned_team_id: props.assignedCollege.assigned_team.id
        }, {
            onSuccess: () => {
                // Add newly assigned players to the list
                const newPlayers = selectedStudents.value.map(studentId => {
                    const student = props.students.find(s => s.id === studentId);
                    return {
                        student: {
                            id: student.id,
                            first_name: student.first_name,
                            last_name: student.last_name
                        }
                    };
                });
                newlyAssignedPlayers.value = [...newlyAssignedPlayers.value, ...newPlayers];
                
                closePlayerModal();
            }
        });
    };

    const removePlayer = async (sportId, playerId) => {
        try {
            const response = await axios.delete(route('assigned-players.destroy', { sport: sportId, player: playerId }));
            if (response.status === 200) {
                // Remove the player from the local state
                const sport = props.assignedSports.find(s => s.id === sportId);
                if (sport) {
                    sport.assigned_players = sport.assigned_players.filter(p => p.id !== playerId);
                }
            }
        } catch (error) {
            console.error('Error removing player:', error);
        }
    };

    const filteredSports = computed(() => {
        if (!sportsSearchQuery.value) return props.assignedSports;
        
        const query = sportsSearchQuery.value.toLowerCase();
        return props.assignedSports.filter(sport => {
            return sport.sport.name.toLowerCase().includes(query) ||
                sport.categories.toLowerCase().includes(query);
        });
    });
    const clearSearch = () => {
        sportsSearchQuery.value = '';
    };

    const selectTeam = (teamId) => {
        selectedTeam.value = teamId;
    };

    const matchTab = ref('pending');

    const pendingMatches = computed(() => {
        return props.upcomingSchedules
            .filter(match => !match.match_result)
            .sort((a, b) => {
                // Convert dates to timestamps for comparison
                const dateA = new Date(a.date + ' ' + a.time);
                const dateB = new Date(b.date + ' ' + b.time);
                return dateA - dateB; // Sort in ascending order (upcoming first)
            });
    });

    const completedMatches = computed(() => {
        return props.upcomingSchedules
            .filter(match => match.match_result)
            .sort((a, b) => {
                // Convert dates to timestamps for comparison
                const dateA = new Date(a.date + ' ' + a.time);
                const dateB = new Date(b.date + ' ' + b.time);
                return dateB - dateA; // Sort in descending order (most recent first)
            })
            .map(match => ({
                ...match,
                winner: match.match_result?.winning_team?.assigned_team_name || 'Unknown'
            }));
    });

    const displayedMatches = computed(() => {
        return matchTab.value === 'pending' ? pendingMatches.value : completedMatches.value;
    });

    const displayedFreeForAllMatches = computed(() => {
        if (!props.freeForAllMatches) return [];
        
        console.log('Free for all matches:', props.freeForAllMatches);
        
        // Filter based on the selected tab
        const filteredMatches = props.freeForAllMatches.filter(match => {
            if (matchTab.value === 'pending') {
                return match.status === 'Pending';
            } else {
                return match.status === 'Completed';
            }
        });

        // Sort by date and time
        const sortedMatches = filteredMatches.sort((a, b) => {
            // Convert date and time to comparable timestamps
            const dateTimeA = new Date(a.date + ' ' + a.time);
            const dateTimeB = new Date(b.date + ' ' + b.time);
            
            // For completed matches, show latest first (descending)
            // For pending matches, show earliest first (ascending)
            if (matchTab.value === 'completed') {
                return dateTimeB - dateTimeA;
            }
            return dateTimeA - dateTimeB;
        });
        
        console.log('Sorted matches:', sortedMatches);
        
        return sortedMatches;
    });

    // Removed the watch since we don't need it anymore
</script>
 
<style scoped>
    /* Home page specific styles */
</style>