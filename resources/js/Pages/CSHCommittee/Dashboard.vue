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
                    <div class="sticky top-[4rem] py-3 z-40 bg-white/95 backdrop-blur-md">
                        <nav class="flex flex-col sm:flex-row relative justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                            <div class="sm:block hidden px-1">
                                <h1 class="text-xl font-semibold text-gray-800">College Committee Head Dashboard</h1>
                                <p class="text-sm text-gray-500 hidden sm:block">{{ assignedCollege.assigned_team.college.name }}</p>
                            </div>
                            
                            <div class="w-full sm:w-auto">
                                <div class="flex gap-1 p-1.5 bg-blue-100/80 rounded-xl">
                                    <button 
                                        v-for="tab in ['home', 'sports', 'schedule', 'Overview', 'settings']"
                                        :key="tab"
                                        @click="activeTab = tab"
                                        :class="[
                                            'px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg',
                                            activeTab === tab
                                                ? 'text-blue-700 bg-white shadow-sm'
                                                : 'text-blue-400 hover:text-blue-600 hover:bg-blue-100'
                                        ]"
                                    >
                                        <div class="flex items-center gap-2">
                                            <i :class="[
                                                'fas',
                                                {
                                                    'fa-home': tab === 'home',
                                                    'fa-running': tab === 'sports',
                                                    'fa-calendar': tab === 'schedule',
                                                    'fa-chart-bar': tab === 'Overview',
                                                    'fa-cog': tab === 'settings'
                                                }
                                            ]"></i>
                                            <span>{{ tab.charAt(0).toUpperCase() + tab.slice(1) }}</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </nav>
                    </div>

                    <div class="px-4 pb-4">
                            <div class="py-8" v-if="activeTab === 'home'">
                                <div class="space-y-8">
                                    <!-- Welcome Section -->
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                        <div class="space-y-6">
                                            <div>
                                                <h1 class="text-3xl font-bold text-gray-900">
                                                    Welcome back, <span class="text-blue-600">{{ comHead.student.first_name }}</span>
                                                </h1>
                                                <p class="mt-2 text-gray-600">Manage your team and track progress for the Palakasan event.</p>
                                            </div>

                                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <h2 class="text-2xl font-bold">{{ assignedCollege.assigned_team.assigned_team_name }}</h2>
                                                        <p class="text-blue-100">{{ assignedCollege.assigned_team.college.name }}</p>
                                                    </div>
                                                    <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center">
                                                        <i class="fa-solid fa-trophy text-2xl"></i>
                                                    </div>
                                                </div>
                                                <div class="mt-4 rounded-xl">
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <button 
                                                            @click="activeTab = 'sports'"
                                                            class="flex items-center justify-center gap-3 p-2.5 rounded-lg  bg-blue-500 hover:bg-blue-600 transition-all duration-200"
                                                        >
                                                            <i class="fa-solid fa-user-plus text-white text-lg"></i>
                                                            <span class="text-sm font-medium text-white">Assign Players</span>
                                                        </button>
                                                        <button 
                                                            @click="activeTab = 'schedule'"
                                                            class="flex items-center justify-center gap-3 p-2.5 rounded-lg  bg-blue-500 hover:bg-blue-600  transition-all duration-200"
                                                        >
                                                            <i class="fa-solid fa-calendar text-white text-lg"></i>
                                                            <span class="text-sm font-medium text-white">View Schedule</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                                                        <i class="fa-solid fa-graduation-cap text-blue-600 text-xl"></i>
                                                    </div>
                                                    <p class="text-sm text-gray-500">Total Students</p>
                                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ students.length }}</p>
                                                </div>
                                                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                                                        <i class="fa-solid fa-user-check text-green-600 text-xl"></i>
                                                    </div>
                                                    <p class="text-sm text-gray-500">Assigned Players</p>
                                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ totalAssignedStudents }}</p>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- Stats Cards -->
                                        <div class="space-y-6">
                                            <!-- Task Progress -->
                                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h2 class="text-lg font-semibold text-gray-900">Sports Assignment Progress</h2>
                                                    <span class="text-sm text-gray-500">
                                                        {{ sportsWithStatus.filter(s => s.assigned_players?.length > 0).length }}/{{ sportsWithStatus.length }} completed
                                                    </span>
                                                </div>
                                                <div class="space-y-3">
                                                    <div v-for="sport in sportsWithStatus" :key="sport.id" 
                                                        class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-all duration-200"
                                                    >
                                                        <div class="flex items-center gap-3">
                                                            <div :class="[
                                                                'w-8 h-8 rounded-full flex items-center justify-center',
                                                                sport.assigned_players?.length > 0 ? 'bg-green-100' : 'bg-gray-100'
                                                            ]">
                                                                <i :class="[
                                                                    'fas fa-check',
                                                                    sport.assigned_players?.length > 0 ? 'text-green-600' : 'text-gray-400'
                                                                ]"></i>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-900">{{ sport.sport.name }}</p>
                                                                <p class="text-xs text-gray-500">{{ sport.categories }}</p>
                                                            </div>
                                                        </div>
                                                        <span :class="[
                                                            'px-2.5 py-1 text-xs rounded-full font-medium',
                                                            sport.assigned_players?.length > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'
                                                        ]">
                                                            {{ sport.assigned_players?.length || 0 }} Players
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="activeTab === 'sports'" class="min-h-screen">
                                <div class="space-y-5 py-8">
                                    <!-- Header Section with improved styling -->
                                    <div class="bg-white rounded-xl ">
                                        <div class="space-y-4 sm:items-center sm:justify-between gap-4">
                                            <div>
                                                <h2 class="text-2xl font-semibold text-gray-900">Sports Management</h2>
                                                <p class="mt-1 text-sm text-gray-500">Manage and assign players to different sports</p>
                                            </div>
                                            <!-- Enhanced search bar -->
                                            <div class="relative w-full sm:w-80">
                                                <input
                                                    v-model="sportsSearchQuery"
                                                    type="text"
                                                    placeholder="Search sports..."
                                                    class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400 transition-all duration-200"
                                                >
                                                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                                <button
                                                    v-if="sportsSearchQuery"
                                                    @click="clearSearch"
                                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                                >
                                                    <i class="fa-solid fa-xmark w-5 h-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sports List with improved card design -->
                                    <ul class="grid grid-cols-1 gap-6">
                                        <li v-for="sport in filteredSports" :key="sport.id" 
                                            class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-100 transition-all duration-200"
                                        >
                                            <div class="p-6">
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                                                            <i class="fa-solid fa-trophy text-blue-600 text-2xl"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="text-lg font-semibold text-gray-900">
                                                                {{ sport.sport.name }} {{ sport.categories }}
                                                            </h3>
                                                            <div class="flex items-center gap-2 mt-2">
                                                                <span class="px-3 py-1.5 text-xs font-medium bg-blue-50 text-blue-700 rounded-full">{{ sport.setup }}</span>
                                                                <span class="px-3 py-1.5 text-xs font-medium bg-gray-50 text-gray-600 rounded-full">{{ sport.type }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button
                                                        @click="openPlayerModal(sport)"
                                                        class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-sm"
                                                        :disabled="palakasan.status === 'live' || palakasan.status === 'completed'"
                                                    >
                                                        <i class="fa-solid fa-user-plus mr-2"></i>
                                                        Assign Players
                                                    </button>
                                                </div>
                                                
                                                <!-- Enhanced Accordion -->
                                                <Disclosure v-slot="{ open }" as="div" class="mt-6" v-if="sport.assigned_players">
                                                    <DisclosureButton 
                                                        class="flex w-full items-center justify-between rounded-lg bg-gray-50 px-5 py-3.5 text-left text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus-visible:ring focus-visible:ring-blue-500/50 focus-visible:ring-opacity-75 transition-all duration-200"
                                                    >
                                                        <div class="flex items-center gap-3">
                                                            <i class="fa-solid fa-users text-gray-500"></i>
                                                            <span>Assigned Players</span>
                                                            <span class="ml-1 px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                                                                {{ sport.assigned_players.length }}
                                                            </span>
                                                        </div>
                                                        <i :class="[open ? 'fa-chevron-up' : 'fa-chevron-down', 'fa-solid text-gray-400']"></i>
                                                    </DisclosureButton>
                                                    
                                                    <!-- Enhanced player list -->
                                                    <TransitionChild
                                                        enter="transition duration-100 ease-out"
                                                        enterFrom="transform scale-95 opacity-0"
                                                        enterTo="transform scale-100 opacity-100"
                                                        leave="transition duration-75 ease-out"
                                                        leaveFrom="transform scale-100 opacity-100"
                                                        leaveTo="transform scale-95 opacity-0"
                                                    >
                                                        <DisclosurePanel class="px-4 pt-4 pb-2">
                                                            <div v-if="sport.assigned_players.length > 0" class="divide-y divide-gray-100">
                                                                <div v-for="player in sport.assigned_players" :key="player.id" 
                                                                    class="flex items-center justify-between py-4 hover:bg-gray-50 px-3 rounded-lg transition-colors duration-200"
                                                                >
                                                                    <div class="flex items-center gap-3">
                                                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                                                                            <span class="text-sm font-medium text-blue-700">
                                                                                {{ player.student.first_name[0] }}{{ player.student.last_name[0] }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <p class="text-sm font-medium text-gray-900">
                                                                                {{ player.student.first_name }} {{ player.student.last_name }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <button 
                                                                        @click="removePlayer(sport.id, player.id)"
                                                                        class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-all duration-200"
                                                                        title="Remove player"
                                                                    >
                                                                        <i class="fa-solid fa-xmark"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <p v-else class="text-center text-sm text-gray-500 py-6 bg-gray-50 rounded-lg">No players assigned yet</p>
                                                        </DisclosurePanel>
                                                    </TransitionChild>
                                                </Disclosure>
                                                <p v-else class="mt-6 text-sm text-gray-500 text-center py-6 bg-gray-50 rounded-lg">No players assigned yet</p>
                                            </div>
                                        </li>
                                    </ul>
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
                                                        <path fill-rule="evenodd" d="M11.645 20.91l-16.1 48.86h32.2zM73 90.43v15.97h366V90.43zm48 33.97V479l135-105l135 105V124.4zm87 37h96l-32 80l80-32v96l-80-32l32 80h-96l32-80l-80 32v-96l80 32zm48 235.4l-23 17.9v73.7h46v-73.7z"/></svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">Player Assignment</h4>
                                                    <p class="text-sm text-gray-600">Ensure all players are properly assigned to their respective sports before the event starts.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 w-6 h-6 text-blue-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.694 3.397 1.672c.8.488 1.484 1.064 1.978 1.69c.486.615.844 1.351.844 2.138c0 .845-.411 1.511-1.003 1.986c-.56.45-1.299.748-2.084.956c-1.578.417-3.684.558-5.913.558s-4.335-.14-5.913-.558c-.785-.208-1.524-.506-2.084-.956C3.41 20.01 3 19.345 3 18.5c0-5.03 4.428-9 9.75-9s9.75 3.97 9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z"/></svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">Verification</h4>
                                                    <p class="text-sm text-gray-600">Double-check player eligibility and sport category assignments.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 w-6 h-6 text-blue-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" /></svg>
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

                            <div v-if="activeTab === 'schedule'" class="bg-white overflow-hidden sm:rounded-lg">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-6">
                                        <h2 class="text-2xl font-bold text-gray-900">Match Schedule</h2>
                                        
                                        <!-- Enhanced Tab Navigation -->
                                        <div class="inline-flex p-1 space-x-1 bg-gray-100 rounded-xl">
                                            <button 
                                                v-for="tab in ['all', 'pending', 'completed']"
                                                :key="tab"
                                                @click="matchTab = tab"
                                                :class="[
                                                    'px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200',
                                                    matchTab === tab
                                                        ? 'bg-white text-blue-600 shadow-sm'
                                                        : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
                                                ]"
                                            >
                                                <div class="flex items-center gap-2">
                                                    <i :class="[
                                                        'fas',
                                                        {
                                                            'fa-list': tab === 'all',
                                                            'fa-clock': tab === 'pending',
                                                            'fa-check-circle': tab === 'completed'
                                                        }
                                                    ]"></i>
                                                    {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                                                </div>
                                            </button>
                                        </div>
                                    </div>

                                    <div v-if="!hasMatches" class="text-center py-12 bg-gray-50 rounded-xl">
                                        <i class="fas fa-calendar-times text-4xl text-gray-400 mb-3"></i>
                                        <p class="text-gray-500">No matches scheduled for your team at this time.</p>
                                    </div>
                                    
                                    <div v-else class="space-y-6">
                                        <!-- Free for All Matches -->
                                        <div v-for="match in filteredFreeForAllMatches" :key="'ffa-' + match.id" 
                                            class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200"
                                        >
                                            <!-- Header -->
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex items-center gap-3">
                                                    <div>
                                                        <h3 class="text-lg font-semibold text-gray-900">
                                                            {{ match.assignedSport?.sport?.name || 'Free for All Event' }}
                                                            <span class="text-gray-600">• {{ match.sport_variation_name }}</span>
                                                        </h3>
                                                        <div class="flex items-center gap-3 mt-1">
                                                            <span :class="[
                                                                'px-3 py-1 text-xs font-medium rounded-full',
                                                                {
                                                                    'bg-yellow-100 text-yellow-800': match.status === 'Pending',
                                                                    'bg-green-100 text-green-800': match.status === 'Completed',
                                                                    'bg-blue-100 text-blue-800': match.status === 'In Progress'
                                                                }
                                                            ]">
                                                                {{ match.status }}
                                                            </span>
                                                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                                                <i class="fas fa-map-marker-alt"></i>
                                                                {{ match.matchVenue?.name || 'Venue TBD' }}
                                                            </div>
                                                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                                                <i class="far fa-calendar"></i>
                                                                {{ formatDate(match.date) }} at {{ formatTime(match.time) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Teams Grid -->
                                            <div class="mt-4">
                                                <h4 class="text-sm font-medium text-gray-700 mb-3">Participating Teams</h4>
                                                <div v-if="match.participating_teams?.length" 
                                                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"
                                                >
                                                    <div v-for="team in match.participating_teams" :key="team.team_name" 
                                                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200"
                                                    >
                                                        <div>
                                                            <p class="font-medium text-gray-900">{{ team.team_name }}</p>
                                                            <p class="text-sm text-gray-500">{{ team.college_name }}</p>
                                                        </div>
                                                        <div v-if="match.status === 'Completed'" 
                                                            class="px-3 py-1 bg-white rounded-full text-sm font-medium"
                                                            :class="{
                                                                'text-yellow-600': team.rank === 1,
                                                                'text-gray-600': team.rank !== 1
                                                            }"
                                                        >
                                                            {{ team.rank }}{{ getOrdinalSuffix(team.rank) }} 
                                                            <span class="text-xs text-gray-500">({{ team.points }}pts)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p v-else class="text-sm text-gray-500 text-center py-4 bg-gray-50 rounded-lg">
                                                    No teams registered yet
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Head to Head Matches -->
                                        <div v-for="match in filteredHeadToHeadMatches" :key="'h2h-' + match.id" 
                                            class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200"
                                        >
                                            <!-- Match Header -->
                                            <div class="flex items-center justify-between mb-6">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900">
                                                        {{ match.assigned_sport?.sport?.name || 'Sport TBD' }}
                                                        <span class="text-gray-600">• Round {{ match.round || '?' }}</span>
                                                        <span class="text-gray-600">• {{ match.game || '?' }}</span>
                                                    </h3>
                                                    <div class="flex items-center gap-3 mt-1">
                                                        <span :class="[
                                                            'px-3 py-1 text-xs font-medium rounded-full',
                                                            {
                                                                'bg-yellow-100 text-yellow-800': match.status === 'Pending',
                                                                'bg-green-100 text-green-800': match.status === 'Completed',
                                                                'bg-blue-100 text-blue-800': match.status === 'In Progress'
                                                            }
                                                        ]">
                                                            {{ match.status || 'Pending' }}
                                                        </span>
                                                        <div class="flex items-center gap-2 text-sm text-gray-500">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            {{ match.match_venue?.name || 'Venue TBD' }}
                                                        </div>
                                                        <div class="flex items-center gap-2 text-sm text-gray-500">
                                                            <i class="far fa-calendar"></i>
                                                            {{ formatDate(match.date) }} at {{ formatTime(match.time) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Teams VS Display -->
                                            <div class="flex items-center justify-between gap-4 bg-gray-50 rounded-xl p-6">
                                                <div class="flex-1 text-center">
                                                    <div class="p-4 bg-white rounded-lg">
                                                        <p class="font-semibold text-lg text-gray-900">
                                                            {{ match.teamA?.assigned_team_name || 'TBD' }}
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            {{ match.teamA?.college?.name || 'TBD' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col items-center">
                                                    <span class="text-2xl font-bold text-gray-400">VS</span>
                                                    <div v-if="match.status === 'Completed'" class="mt-2 text-sm">
                                                        <div class="space-y-1 text-center">
                                                            <p class="text-green-600 font-medium">
                                                                Winner: {{ match.match_result.winning_team?.assigned_team_name }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-1 text-center">
                                                    <div class="p-4 bg-white rounded-lg">
                                                        <p class="font-semibold text-lg text-gray-900">
                                                            {{ match.teamB?.assigned_team_name || 'TBD' }}
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            {{ match.teamB?.college?.name || 'TBD' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
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
                            leave="duration-300 ease-out"
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
                                    leave="duration-300 ease-out"
                                    leave-from="opacity-100 scale-100"
                                    leave-to="opacity-0 scale-95"
                                >
                                    <DialogPanel class="w-[32rem] transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                        <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                            Assign Players to {{ selectedSport ? selectedSport.sport.name : '' }} 
                                            {{ selectedSport ? selectedSport.categories : '' }}
                                        </DialogTitle>

                                        <!-- Search input -->
                                        <div class="mt-4">
                                            <div class="relative">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                                </div>
                                                <input 
                                                    type="text" 
                                                    v-model="searchQuery" 
                                                    class="block w-full rounded-lg border-0 py-2.5 pl-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" 
                                                    placeholder="Search students..."
                                                />
                                            </div>
                                        </div>

                                        <!-- Student list -->
                                        <div class="mt-4 max-h-96 overflow-y-auto">
                                            <div v-for="student in filteredStudents" :key="student.id" 
                                                class="flex items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer"
                                            >
                                                <label :for="'student-' + student.id" class="flex-1 cursor-pointer">
                                                    <div class="flex flex-col">
                                                        <div class="flex items-center">
                                                            <input
                                                                type="checkbox"
                                                                :id="'student-' + student.id"
                                                                :value="student.id"
                                                                v-model="selectedStudents"
                                                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600"
                                                            />
                                                            <span class="ml-3 text-sm font-medium text-gray-900">{{ student.first_name }} {{ student.last_name }}</span>
                                                        </div>
                                                        <!-- Display assigned sports -->
                                                        <div v-if="getStudentAssignedSports(student).length > 0" class="ml-7 mt-1">
                                                            <div class="text-xs text-gray-500">
                                                                <span class="font-medium">Assigned to:</span>
                                                                <span v-for="(sport, index) in getStudentAssignedSports(student)" :key="sport.id" class="ml-1">
                                                                    {{ sport.sport.name }} {{ sport.categories }} {{ index < getStudentAssignedSports(student).length - 1 ? ',' : '' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Button section in the modal -->
                                        <div class="mt-6 flex justify-end gap-3">
                                            <button
                                                type="button"
                                                class="inline-flex justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                                                @click="closePlayerModal"
                                                :disabled="isAssigning"
                                            >
                                                Cancel
                                            </button>
                                            <button
                                                type="button"
                                                class="inline-flex justify-center rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                                @click="assignPlayers"
                                                :disabled="selectedStudents.length === 0 || isAssigning"
                                            >
                                                <template v-if="isAssigning">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Assigning...
                                                </template>
                                                <template v-else>
                                                    Assign Players
                                                </template>
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
                                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"/></svg>
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
                <ConfirmModal
                    :show="showConfirmModal"
                    title="Remove Player"
                    message="Are you sure you want to remove this player from the sport?"
                    @confirm="confirmRemovePlayer"
                    @close="showConfirmModal = false"
                />
            </div>
        </template>
    </AppLayout>
    <SuccessModal
        :show="showSuccessModal"
        :message="successMessage"
        @close="showSuccessModal = false"
     />
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
    import SuccessModal from '@/Components/SuccessModal.vue';
    import ConfirmModal from '@/Components/ConfirmModal.vue';
    import axios from 'axios';

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

    const showSuccessModal = ref(false);
    const successMessage = ref('');
    const showConfirmModal = ref(false);
    const playerToRemove = ref(null);
    const sportIdToRemove = ref(null);

    const isModalOpen = ref(false);
    const selectedTeam = ref(null);
    const isAssigning = ref(false);
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

    // Check if student is already assigned to any sport
    const isStudentAssigned = (studentId) => {
        return props.assignedSports.some(sport => 
            sport.assigned_players && 
            sport.assigned_players.some(player => player.student.id === studentId)
        );
    };

    // Get list of sports a student is assigned to
    const getStudentAssignedSports = (student) => {
        return props.assignedSports.filter(sport => 
            sport.assigned_players && 
            sport.assigned_players.some(player => player.student.id === student.id)
        );
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
                showSuccessModal.value = true;
                successMessage.value = 'College assigned successfully!';
            }
        });
    };

    const isPlayerModalOpen = ref(false);
    const selectedSport = ref(null);
    const selectedStudents = ref([]);
    const searchQuery = ref('');
    const newlyAssignedPlayers = ref([]);

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
        isAssigning.value = false;
    };

    const assignPlayers = () => {
        isAssigning.value = true;
        
        router.post(route('chs.assign-players'), {
            sport_id: selectedSport.value.id,
            student_ids: selectedStudents.value,
            assigned_team_id: props.assignedCollege.assigned_team.id
        }, {
            onSuccess: () => {
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
                showSuccessModal.value = true;
                successMessage.value = 'Players assigned successfully!';
                isAssigning.value = false;
            },
            onError: () => {
                isAssigning.value = false;
            }
        });
    };

    const removePlayer = (sportId, playerId) => {
        sportIdToRemove.value = sportId;
        playerToRemove.value = playerId;
        showConfirmModal.value = true;
    };

    const confirmRemovePlayer = async () => {
        try {
            const response = await axios.delete(route('assigned-players.destroy', {
                sport: sportIdToRemove.value,
                player: playerToRemove.value
            }));
            
            if (response.status === 200) {
                showConfirmModal.value = false;
                showSuccessModal.value = true;
                successMessage.value = 'Player removed successfully!';
                
                // Reload the page after a short delay to show the success message
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                showConfirmModal.value = false;
                showSuccessModal.value = true;
                successMessage.value = 'Player removed successfully!';
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

    const matchTab = ref('all');

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

    const hasMatches = computed(() => {
        return (props.upcomingSchedules && props.upcomingSchedules.length > 0) || 
               (props.freeForAllMatches && props.freeForAllMatches.length > 0);
    });

    const filteredFreeForAllMatches = computed(() => {
        if (!props.freeForAllMatches) return [];
        
        return props.freeForAllMatches.filter(match => {
            if (matchTab.value === 'all') return true;
            if (matchTab.value === 'pending') return match.status === 'Pending';
            if (matchTab.value === 'completed') return match.status === 'Completed';
            return true;
        }).sort((a, b) => {
            const dateA = new Date(a.date + ' ' + a.time);
            const dateB = new Date(b.date + ' ' + b.time);
            return matchTab.value === 'completed' ? dateB - dateA : dateA - dateB;
        });
    });

    const filteredHeadToHeadMatches = computed(() => {
        if (!props.upcomingSchedules) return [];
        
        return props.upcomingSchedules.filter(match => {
            if (matchTab.value === 'all') return true;
            if (matchTab.value === 'pending') return !match.match_result;
            if (matchTab.value === 'completed') return match.match_result;
            return true;
        }).sort((a, b) => {
            const dateA = new Date(a.date + ' ' + a.time);
            const dateB = new Date(b.date + ' ' + b.time);
            return matchTab.value === 'completed' ? dateB - dateA : dateA - dateB;
        });
    });

    const getOrdinalSuffix = (number) => {
        const j = number % 10;
        const k = number % 100;
        if (j == 1 && k != 11) return "st";
        if (j == 2 && k != 12) return "nd";
        if (j == 3 && k != 13) return "rd";
        return "th";
    };
</script>
 
<style scoped>
    /* Home page specific styles */
</style>