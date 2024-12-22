<template>
    <Head title="Palakasan"/>
    <AppLayout>
        <template v-slot:default>
            <!-- Tabs Navigation -->
            <nav class="flex relative justify-between  items-center">
                <div class="flex gap-2 ">
                    <div class="flex gap-2">
                        <button 
                            v-for="tab in ['details', 'line ups', 'leagues', 'Schedules', 'rankings', 'settings', ]"
                            :key="tab"
                            @click="setActiveTab(tab)"
                            :class="[
                            'px-5 py-2 text-sm',
                            activeTab === tab
                                ? 'text-gray-800 bg-blue-700 text-white font-medium rounded-lg'
                                : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 font-medium rounded-lg border-transparent'
                            ]"
                        >
                            {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                        </button>
                    </div>
                    </div>
                    <div class="flex space-x-3">
                        <button
                            @click="openHistoryModal"
                            type="button"
                            class="text-gray-600 ring-1 font-medium flex items-center ring-gray-300 hover:bg-gray-100 hover:text-gray-800 hover:ring-gray-400 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-2 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                        >
                            <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.25 12.5h3.5m-10-4.75a4 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span>Archive</span>
                        </button>
                        <Link
                            :href="route('palakasan.documentation')"
                                class="text-gray-600 ring-1 font-medium flex items-center ring-gray-300 hover:bg-gray-100 hover:text-gray-800 hover:ring-gray-400 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-2 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                            >
                            <svg
                                class="w-5 h-5 mr-1"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    fill="currentColor"
                                    d="M11 8.018a.75.75 0 0 1-.75.732c-.75 0-.75-.751-.75-.751V7.99a1 1 0 0 1 .008-.134a2.2 2.2 0 0 1 .42-1.067c.454-.613 1.27-1.062 2.585-1.039c.95.017 1.793.415 2.321 1.07c.537.667.718 1.57.362 2.459c-.362.905-1.181 1.265-1.652 1.471l-.05.023c-.28.123-.413.187-.493.251l-.001.001v.724a.75.75 0 0 1-1.5.001V11c0-.523.252-.897.563-1.147c.25-.2.565-.338.786-.436l.038-.017c.542-.239.8-.387.917-.679a.92.92 0 0 0-.138-.96c-.222-.275-.629-.502-1.179-.511c-.935-.016-1.245.285-1.353.432a.7.7 0 0 0-.134.33zm1.25 7.482a1 1 0 1 0 0-2a1 1 0 0 0 0 2M4 4.5A2.5 2.5 0 0 1 6.5 2H18a2.5 2.5 0 0 1 2.5 2.5v14.25a.75.75 0 0 1-.75.75H5.5a1 1 0 0 0 1 1h13.25a.75.75 0 0 1 0 1.5H6.5A2.5 2.5 0 0 1 4 19.5zm1.5 0V18H19V4.5a1 1 0 0 0-1-1H6.5a1 1 0 0 0-1 1"
                                />
                            </svg>
                            <span>Documentation</span>
                        </Link>
                    </div>
                </nav>

                <!-- Tabs Content -->
                <div class="mt-4">
                <!-- Palakasans (Details) Tab Content -->
                <div v-if="activeTab === 'details'" class="animate-fadeIn max-w-7xl mx-auto">
                    <div v-if="latestPalakasan">
                        <!-- Main Card -->
                        <div class="bg-white rounded-xl   hover:border-blue-200/60 transition-all duration-300">
                            <!-- Header Section -->
                            <div class="flex justify-between items-start border-b border-gray-200/80 pb-5">
                                <!-- Title and Date -->
                                <div class="space-y-2">
                                    <div class="flex items-center gap-3">
                                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-700 to-blue-500 bg-clip-text text-transparent">
                                            Palakasan {{ latestPalakasan.year }}
                                        </h1>
                                        <div :class="[
                                            'px-3 py-1 rounded-full text-xs font-medium shadow-sm', 
                                            statusColor,
                                            'transform transition-all duration-300 hover:scale-105'
                                        ]">
                                            {{ capitalizedStatus }}
                                        </div>
                                    </div>
                                    <div class="flex items-center text-gray-500 text-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ formatDate(latestPalakasan.start_date) }} - {{ formatDate(latestPalakasan.end_date) }}
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-3">
                                    <button 
                                        v-if="progressPercentage < 100 && latestPalakasan.status !== 'cancelled'"
                                        @click="showModal = true"
                                        type="button" 
                                        :disabled="latestPalakasan.status === 'live' || assignedSports.length < 2 || assignedTeams.length < 4"
                                        :class="[
                                            'flex gap-1.5 font-medium text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2',
                                            latestPalakasan.status === 'live' || assignedSports.length < 2 || assignedTeams.length < 4
                                            ? ' text-blue-700 bg-blue-100 cursor-not-allowed'
                                            : 'bg-blue-700 text-white hover:bg-blue-700/90 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700'
                                        ]"
                                    >
                                        {{ assignedSports.length < 2 || assignedTeams.length < 4 ? 'You need to have at least 2 sports and 4 teams to start Palakasan' : 'Start Palakasan' }}
                                    </button>

                                    <button 
                                        v-if="latestPalakasan?.status !== 'live' && latestPalakasan?.status !== 'pending'"
                                        @click="openPalakasanModal" 
                                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md active:transform active:scale-95"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Create New Palakasan
                                    </button>

                                    <button
                                        v-if="latestPalakasan?.status !== 'completed' && latestPalakasan?.status !== 'cancelled' && latestPalakasan?.status !== 'pending' && progressPercentage === 100"
                                        @click="concludePalakasan"
                                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-green-600 text-white hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md active:transform active:scale-95"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Conclude Palakasan
                                    </button>
                                </div>
                            </div>

                            <!-- Content Section -->
                            <div class="mt-6 grid grid-cols-6 gap-8">
                                <!-- Left Column -->
                                <div class="col-span-3 space-y-6">
                                    <!-- Theme and Description -->
                                    <div class="space-y-4">
                                        <h2 class="text-xl font-semibold text-gray-800">{{ latestPalakasan.theme }}</h2>
                                        <p class="text-sm font-medium text-blue-600">{{ latestPalakasan.tagline }}</p>
                                        <p class="text-sm text-gray-600 leading-relaxed">{{ latestPalakasan.description }}</p>
                                    </div>

                                    <!-- Progress Section -->
                                    <div class="bg-white p-5 rounded-xl border border-gray-200/80 hover:border-blue-200/60 transition-all duration-300">
                                        <div class="flex justify-between items-center mb-3">
                                            <h3 class="font-semibold text-gray-800">Palakasan Progress</h3>
                                            <span class="text-sm font-medium text-blue-600">
                                                {{ progressPercentage.toFixed(1) }}%
                                            </span>
                                        </div>
                                        <div class="relative w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div 
                                                class="absolute top-0 left-0 h-full bg-blue-600 rounded-full transition-all duration-700 ease-in-out"
                                                :style="{ width: `${progressPercentage}%` }"
                                            >
                                                <div class="absolute inset-0 bg-white/20 animate-shimmer"></div>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">
                                            {{ completedSports }} out of {{ totalSports }} sports completed
                                        </p>
                                    </div>

                                    <!-- Stats Cards -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Line ups Card -->
                                        <div 
                                            @click="activeTab = 'line ups'"
                                            class="group p-5 bg-white rounded-xl border border-gray-200/80 hover:border-blue-200/60 cursor-pointer transition-all duration-300 hover:shadow-md"
                                        >
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-500 group-hover:text-blue-600">Line ups</p>
                                                    <p class="text-3xl font-bold text-gray-800 mt-2 group-hover:text-blue-600">
                                                        {{ teamsCount }}
                                                    </p>
                                                </div>
                                                <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Leagues Card -->
                                        <div 
                                            @click="activeTab = 'leagues'"
                                            class="group p-5 bg-white rounded-xl border border-gray-200/80 hover:border-blue-200/60 cursor-pointer transition-all duration-300 hover:shadow-md"
                                        >
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-500 group-hover:text-blue-600">Leagues</p>
                                                    <p class="text-3xl font-bold text-gray-800 mt-2 group-hover:text-blue-600">
                                                        {{ sportsCount }}
                                                    </p>
                                                </div>
                                                <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column - Graph -->
                                <div class="col-span-3">
                                    <div class="bg-white p-5 rounded-xl border border-gray-200/80 hover:border-blue-200/60 transition-all duration-300 h-full">
                                        <Graph
                                            :teams="assignedTeams"
                                            :overallResult="overallResult"
                                            :variationResult="variationResult"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rankings Section -->
                        <div class="mt-6">
                            <PalakasanRankings 
                                :teams="assignedTeams" 
                                :overallResult="overallResult" 
                                :variationResult="variationResult" 
                            />
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-16 bg-gradient-to-br from-blue-50 to-white rounded-xl border-2 border-blue-200 border-dashed">
                        <div class="max-w-sm mx-auto">
                            <div class="mb-6">
                                <svg class="w-16 h-16 mx-auto text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-800 mb-2">No Palakasan Created Yet</h1>
                            <p class="text-gray-600 mb-6">Get started by creating your first Palakasan event.</p>
                            <button 
                                @click="openPalakasanModal" 
                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md active:transform active:scale-95"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Create Palakasan
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Teams Tab Content -->
                <div v-if="activeTab === 'line ups'" class="animate-fadeIn max-w-7xl mx-auto">
                    <div class="flex mb-4 items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <!-- Search Input -->
                            <div class="flex items-center">
                                <input
                                    v-model="searchQueryTeams"
                                    type="text"
                                    placeholder="Search teams..."
                                    class=" w-64 border border-gray-300 bg-gray-50 text-sm rounded-lg p-2"
                                />
                            </div>
                            <div class="relative">
                                <Tooltip 
                                    v-if="latestPalakasan?.status !== 'pending'"
                                    text="Cannot form line ups when Palakasan starts"
                                    position="top"
                                >
                                    <button                                                 
                                    @click="openTeamsModal" 
                                    :disabled="latestPalakasan?.status !== 'pending' && assignedSports?.length !== 0"                                    
                                    :class="[
                                    'flex gap-1.5 text-sm focus:outline-none font-medium focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2',
                                    latestPalakasan?.status !== 'pending' && assignedSports.length !== 0
                                        ? ' text-blue-700 bg-blue-100 cursor-not-allowed'
                                        : 'bg-blue-700 text-white hover:bg-blue-700/90 flex items-center gap-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700'
                                    ]"                            
                                    >
                                        Form Line ups
                                    </button>
                                </Tooltip>
                                <button v-else                                               
                                    @click="openTeamsModal" 
                                    :disabled="assignedSports?.length !== 0"                                    
                                    :class="[
                                        'flex gap-1.5 text-sm focus:outline-none font-medium focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2',
                                        assignedSports.length !== 0
                                            ? ' text-blue-700 bg-blue-100 cursor-not-allowed'
                                            : 'bg-blue-700 text-white hover:bg-blue-700/90 flex items-center gap-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700'
                                    ]"                                    >
                                    Form Line ups
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-end border border-gray-300 px-2.5 shadow rounded-lg">
                            <label for="sort" class="text-sm font-semibold">Sort By:</label>
                            <select v-model="sortOrderTeams" id="sort" class=" text-sm rounded-lg  cursor-pointer p-2 outline-none">
                                <option value="default">Default</option>
                                <option value="az">A-Z</option>
                                <option value="za">Z-A</option>
                            </select>
                        </div>
                    </div>
                    <!--add a note here taht if there is a sport already you cant add a team anymore-->
                    <div v-if="assignedSports?.length === 0" class="flex items-center gap-2 p-3 my-3 text-sm bg-blue-50 border border-blue-200 rounded-lg">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        <span class="text-blue-700 font-medium">Important:</span>
                        <span class="text-blue-600">Teams can only be added when no sports are assigned.</span>
                    </div>
                    <!-- Check if assignedTeams array is empty after search and sort -->
                    <div v-if="filteredAndSortedTeams.length === 0" class="flex flex-col items-center justify-center text-center p-10 bg-blue-50 rounded-lg">
                        <i class="fa-solid fa-people-group text-blue-700 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">No team found</h3>
                        <p class="text-gray-500 text-sm mb-4">
                            We couldn't find any team that match your search criteria. <br>
                            If the team you are looking for is not here you can add it <br>
                            by clicking the <span class="text-blue-500 font-semibold">Button</span> form line ups.

                        </p>
                    </div>

                    <!-- Display assigned teams as cards -->
                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div 
                            v-for="(team, index) in filteredAndSortedTeams" 
                            :key="index" 
                            class="bg-white ring-1 ring-gray-200 shadow-sm hover:shadow-md rounded-xl p-5 hover:ring-blue-400 hover:bg-blue-50/80 transition-all duration-300 group"
                        >
                            <div class="space-y-4">
                                <div class="flex justify-between ">
                                    <div class="inline-flex bg-blue-100 w-16 h-16 justify-center items-center rounded-full mb-2 transition-all duration-300 group-hover:bg-blue-200">
                                    <svg class="w-9 h-9 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512"><path fill="currentColor" d="M279 25v30h-46V25zm178 35.396c3.485 1.262 7.267 2.717 11.195 4.55c5.553 2.589 10.945 5.739 14.409 8.742C486.067 76.69 487 78.936 487 80c0 1.063-.933 3.31-4.396 6.313s-8.856 6.152-14.409 8.742c-3.928 1.832-7.71 3.287-11.195 4.549zm-402 0v39.208c-3.485-1.262-7.267-2.717-11.195-4.55c-5.553-2.589-10.945-5.739-14.409-8.742C25.933 83.31 25 81.064 25 80c0-1.063.933-3.31 4.396-6.313s8.856-6.152 14.409-8.742c3.928-1.832 7.71-3.287 11.195-4.549M439 73v14h-46V73zm-64 0v291.578L256 498.453L137 364.578V73zm-256 0v14H73V73zm210 23h-18v252.844l-55 68.75l-55-68.75V96h-18v259.156l73 91.25l73-91.25z"/></svg>
                                </div>
                                    <div>
                                        <template v-if="!(latestPalakasan?.status === 'live' || latestPalakasan?.status === 'completed')">
                                            <button 
                                                @click="openModal(team)" 
                                                :disabled="latestPalakasan?.status === 'live' || latestPalakasan?.status === 'completed'"
                                                :class="[
                                                    'flex gap-1.5 text-sm focus:outline-none text-blue-400 hover:text-blue-600 transition-colors duration-300 font-medium focus:ring-4 focus:ring-blue-300 rounded-lg px-3 py-2',
                                                ]"
                                            >
                                                <i class="fa-solid fa-pen text-sm"></i>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700 transition-colors duration-300">
                                    {{ team.assigned_team_name }}
                                </h3>
                                <span class="text-sm text-gray-500 group-hover:text-blue-600 transition-colors duration-300">
                                    {{ team.college.name }}
                                </span>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sports Tab Content -->
                <div v-if="activeTab === 'leagues' && hasEnoughTeams" class="mt-4 animate-fadeIn max-w-7xl mx-auto">
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
                            <Tooltip 
                                v-if="latestPalakasan?.status !== 'pending' || assignedTeams.length < 4"
                                :text="latestPalakasan?.status !== 'pending' 
                                    ? 'Cannot create league when Palakasan starts'
                                    : 'At least 4 teams must be assigned before creating league'"
                                position="top"
                            >
                                <button 
                                    @click="openSportsModal" 
                                    :disabled="latestPalakasan?.status === 'live' || latestPalakasan?.status === 'completed' || assignedTeams.length < 4"
                                    :class="[
                                        'flex gap-1.5 text-sm focus:outline-none font-medium focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2',
                                        latestPalakasan?.status === 'pending' || latestPalakasan?.status === 'live' || latestPalakasan?.status === 'completed'
                                            ? 'text-blue-700 bg-blue-100 cursor-not-allowed'
                                            : 'flex items-center gap-2 bg-blue-700 text-white hover:bg-blue-700/90 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700'
                                    ]"      
                                >
                                    Create League
                                </button>
                            </Tooltip>
                            <button v-else
                                @click="openSportsModal" 
                                :class="'flex gap-1.5 text-sm focus:outline-none font-medium focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2 flex items-center gap-2 bg-blue-700 text-white hover:bg-blue-700/90 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700'"      
                            >
                                Create League
                            </button>

                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                @click="openLogsModal"
                                class="flex gap-1.5 text-sm focus:outline-none focus:ring-4 ring-gray-300 ring-1 focus:ring-gray-300 rounded-lg px-4 py-2 text-gray-700 font-medium hover:bg-gray-100 hover:text-gray-800 hover:ring-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700"      
                            >
                                Logs
                            </button>
                            <button
                                @click="toggleSelectionMode"
                                :disabled="latestPalakasan?.status === 'live' || latestPalakasan?.status === 'completed'"
                                class="flex gap-1.5 text-sm focus:outline-none focus:ring-4 ring-gray-300 ring-1 focus:ring-gray-300 rounded-lg px-4 py-2 text-gray-700 font-medium hover:bg-gray-100 hover:text-gray-800 hover:ring-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700"
                                :class="{
                                'bg-gray-100': true,
                                'ring-0': latestPalakasan?.status === 'live' || latestPalakasan?.status === 'completed',
                                'opacity-50 cursor-not-allowed': latestPalakasan?.status === 'live' || latestPalakasan?.status === 'completed'
                                }"
                            >
                                {{ isSelectionMode ? 'Cancel Selection' : 'Delete' }}
                            </button>
                            <button 
                                v-if="isSelectionMode && selectedSports.length > 0"
                                @click="deleteSelectedSports"
                                class="flex gap-1.5 text-sm focus:outline-none focus:ring-4 ring-red-300 ring-1 focus:ring-red-300 rounded-lg px-4 py-2 text-red-700 font-medium hover:bg-red-100 hover:text-red-800 hover:ring-red-400 dark:bg-gray-800 dark:hover:bg-gray-700"      
                            >
                                Delete ({{ selectedSports.length }})
                            </button>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="filteredAndSortedSports.length === 0" class="flex flex-col items-center justify-center text-center p-10 bg-blue-50 rounded-lg">
                        <i class="fa-solid fa-basketball text-blue-700 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">No sports found</h3>
                        <p class="text-gray-500 text-sm mb-4">
                            We couldn't find any sports that match your search criteria. <br>
                            If the sport you are looking for is not here you can add it <br>
                            by clicking the <span class="text-blue-500 font-semibold">Button</span> above.
                        </p>
                    </div>

                    <!-- Sports Grid -->
                    <div v-show="filteredAndSortedSports.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                        <div
                            v-for="sport in filteredAndSortedSports"
                            :key="sport.id"
                            @click="isSelectionMode ? toggleSportSelection(sport) : viewSport(sport.id)"
                            class="bg-white ring-1 ring-gray-200 shadow-sm cursor-pointer rounded-xl p-6 transition-all duration-300 ease-in-out hover:bg-blue-50/80 hover:ring-blue-400 hover:shadow-md group relative"
                            :class="{ 'ring-2 ring-blue-500 bg-blue-50': isSelectionMode && selectedSports.includes(sport) }"
                        >
                            <!-- Status Indicator & Checkbox -->
                            <div class="flex items-center gap-2 absolute top-3 right-3">
                                <!-- Selection Checkbox -->
                                <div v-show="isSelectionMode" 
                                    class="transition-transform duration-200 hover:scale-105"
                                >
                                    <div class="w-5 h-5 rounded-md border-2 flex items-center justify-center" 
                                        :class="[
                                            selectedSports.includes(sport) 
                                                ? 'bg-blue-500 border-blue-500' 
                                                : 'border-gray-300'
                                        ]"
                                    >
                                        <svg v-if="selectedSports.includes(sport)" class="w-3.5 h-3.5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Status Indicator -->
                                <div class="flex items-center gap-1.5">
                                    <div
                                        class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                                        :class="{
                                            'bg-gray-300': sport.status === 'pending',
                                            'bg-yellow-400': sport.status === 'scheduled',
                                            'bg-red-500 animate-pulse': sport.status === 'Ongoing',
                                            'bg-green-500': sport.status === 'completed'
                                        }"
                                        :title="sport.status"
                                    ></div>
                                    <span class="text-xs font-medium capitalize" 
                                        :class="{
                                            'text-gray-400': sport.status === 'pending',
                                            'text-yellow-600': sport.status === 'scheduled',
                                            'text-red-600': sport.status === 'Ongoing',
                                            'text-green-600': sport.status === 'completed'
                                        }"
                                    >
                                        {{ sport.status }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <!-- Sport Icon -->
                                <div class="inline-flex bg-blue-100 w-14 h-14 justify-center items-center rounded-full mb-2 transition-all duration-300 group-hover:bg-blue-200">
                                    <i class="fa-solid fa-trophy text-xl text-blue-700"></i>
                                </div>

                                <!-- Sport Details -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700 transition-colors duration-300">
                                        {{ sport.sport?.name }} {{ sport.categories }}
                                    </h3>
                                    
                                    <div class="flex items-center gap-3 mt-2 pb-3 border-b border-gray-200">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-100 group-hover:bg-blue-100 transition-colors duration-300">
                                            <svg class="w-4 h-4 text-gray-600 group-hover:text-blue-600" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                                                <g fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="4">
                                                    <path stroke-linecap="round" d="M8 10.5h32m-16 9h16m-16 9h16m-32 9h32"/>
                                                    <path d="m8 19l8 5l-8 5z"/>
                                                </g>
                                            </svg>
                                            <span class="text-xs font-medium text-gray-600 group-hover:text-blue-600">{{ sport.setup }}</span>
                                        </span>
                                        
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-100 group-hover:bg-blue-100 transition-colors duration-300">
                                            <svg class="w-3.5 h-3.5 text-gray-600 group-hover:text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                <path fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="m12 2l3.104 6.728l7.358.873l-5.44 5.03l1.444 7.268L12 18.28L5.534 21.9l1.444-7.268L1.538 9.6l7.359-.873z"/>
                                            </svg>
                                            <span class="text-xs font-medium text-gray-600 group-hover:text-blue-600">{{ sport.type }}</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Facilitator -->
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-100 rounded-lg group-hover:bg-blue-100 transition-colors duration-300">
                                        <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                            <circle cx="12" cy="6" r="4" fill="currentColor"/>
                                            <path fill="currentColor" d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 group-hover:text-blue-700 transition-colors duration-300">
                                            {{ getFacilitatorName(sport.facilitator_id) }}
                                        </p>
                                        <p class="text-xs text-gray-400 group-hover:text-blue-400">Facilitator</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Arrow Indicator -->
                            <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-all duration-300 group-hover:translate-x-1">
                                <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Not Enough Teams State -->
                <div v-if="activeTab === 'leagues' && !hasEnoughTeams" class="mt-4 animate-fadeIn max-w-7xl mx-auto">
                        <div class="flex flex-col items-center justify-center text-center p-10 bg-yellow-50 rounded-lg">
                            <i class="fa-solid fa-people-group text-yellow-700 text-6xl mb-4"></i>
                            <h3 class="text-xl font-semibold text-yellow-700 mb-2">Not Enough Teams</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                You need at least 4 teams to create leagues and start matches. <br>
                                Currently you have {{ filteredAndSortedTeams.length }} team{{ filteredAndSortedTeams.length !== 1 ? 's' : '' }}. <br>
                                Please add more teams in the Teams tab.
                            </p>
                            <button 
                                @click="activeTab = 'line ups'" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
                            >
                                <i class="fa-solid fa-users text-white text-lg"></i>
                                Go to Teams
                            </button>
                        </div>
                </div>

                <div v-if="activeTab === 'settings'" class="animate-fadeIn max-w-7xl mx-auto">
                    <Settings 
                        :latestPalakasan="latestPalakasan"
                        :admin="admin"
                    />   
                </div>

                <div v-if="activeTab === 'rankings'" class="animate-fadeIn max-w-7xl mx-auto">
                    <div>
                        <SportsRankings 
                            :assigned-sports="assignedSports"
                            :assigned-teams="assignedTeams"
                            :sport-matches="sportMatches"
                            :sportsVariations="sportsVariations"
                            :latest-palakasan="latestPalakasan"
                            :overall-result="overallResult"
                            :variation-result="variationResult"
                            :teams="assignedTeams"
                            :overallResult="overallResult"
                            :variationResult="variationResult"
                        />
                    </div>
                </div>

                <div v-if="activeTab === 'Schedules'" class="animate-fadeIn ">
                    <OverallSchedules
                        :latest-palakasan="latestPalakasan"
                        :active-sports="assignedSports"
                        :active-teams="assignedTeams"
                        :palakasan-year="latestPalakasan?.year"
                    />
                </div>
            </div>

            <!--Modals for palakasan-->

            <!--add or create modals-->
            <!-- Palakasan Modal -->
            <TransitionRoot appear :show="isPalakasanModalOpen" as="template">
                <Dialog as="div" @close="closePalakasanModal" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="w-full max-w-lg transform overflow-hidden rounded-lg bg-white p-6 shadow-xl transition-all">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Create New Palakasan</h2>

                                    <form @submit.prevent="submitPalakasan">
                                        <!-- Theme -->
                                        <div class="mb-4">
                                            <label for="theme" class="block text-sm font-medium text-gray-700">Theme</label>
                                            <input placeholder="Enter theme of the palakasan..." v-model="form.theme" id="theme" type="text" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                                            <span v-if="form.errors.theme" class="text-red-600 text-sm">{{ form.errors.theme }}</span>
                                        </div>

                                        <!-- Tagline -->
                                        <div class="mb-4">
                                            <label for="tagline" class="block text-sm font-medium text-gray-700">Tagline</label>
                                            <input placeholder="Enter tagline for the palakasan..." v-model="form.tagline" id="tagline" type="text" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                                            <span v-if="form.errors.tagline" class="text-red-600 text-sm">{{ form.errors.tagline }}</span>
                                        </div>

                                        <!-- Description -->
                                        <div class="mb-4">
                                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                            <textarea placeholder="Explain the palakasan briefly and the theme..." v-model="form.description" rows="6" id="description" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required></textarea>
                                            <span v-if="form.errors.description" class="text-red-600 text-sm">{{ form.errors.description }}</span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <!-- Start Date -->
                                            <div class="mb-4">
                                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                                <input v-model="form.start_date" id="start_date" type="date" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                                                <span v-if="form.errors.start_date" class="text-red-600 text-sm">{{ form.errors.start_date }}</span>
                                            </div>

                                            <!-- End Date -->
                                            <div class="mb-4">
                                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                                <input v-model="form.end_date" id="end_date" type="date" class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                                                <span v-if="form.errors.end_date" class="text-red-600 text-sm">{{ form.errors.end_date }}</span>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="flex justify-end space-x-2">
                                            <button @click="closePalakasanModal" type="button" class="bg-gray-100 text-gray-700 py-1.5 px-3 rounded-md hover:bg-gray-200">Cancel</button>
                                            <button
                                                type="submit"
                                                :disabled="form.processing"
                                                class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition relative"
                                            >
                                                <span v-if="!form.processing">
                                                    Create
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
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

            <!-- Modal for adding sport -->
            <TransitionRoot appear :show="isSportsModalOpen" as="template">
                <Dialog as="div" @close="closeSportsModal" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="w-full max-w-lg transform overflow-hidden rounded-lg bg-white p-6 shadow-xl transition-all">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Select sport to be played</h2>

                                    <form @submit.prevent="submitSport">
                                        <!-- Hidden Palakasan ID input -->
                                        <input type="text" v-model="form1.palakasan_sport_id" class="hidden" />
                                        
                                        <div class="grid grid-cols-3 gap-2 mb-4">
                                            <label for="sport" class="col-span-3 block text-sm font-medium">Select Sport</label>
                                            <!-- Select Sport -->
                                            <div class="col-span-2 ">
                                                <div class="relative" ref="dropdownContainer">
                                                    <div class="relative">
                                                        <input
                                                            type="text"
                                                            :value="selectedSportName"
                                                            readonly
                                                            placeholder="Select a sport..."
                                                            class="w-full border px-3 py-2 rounded-lg border-gray-300 cursor-pointer"
                                                            @click="showSportDropdown = true"
                                                        />
                                                        <div 
                                                            class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                        </div>
                                                        
                                                        <!-- Dropdown with Search -->
                                                        <div 
                                                            v-show="showSportDropdown"
                                                            class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg"
                                                        >
                                                            <!-- Search input within dropdown -->
                                                            <div class="p-2 border-b border-gray-200">
                                                                <input
                                                                    type="text"
                                                                    v-model="sportSearch"
                                                                    placeholder="Search sports..."
                                                                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 pr-8"
                                                                    @input="filterSports"
                                                                />
                                                                <button
                                                                    v-if="sportSearch"
                                                                    @click="clearSearch"
                                                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                                                >
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            
                                                            <!-- Sports list -->
                                                            <div class="max-h-48 overflow-y-auto">
                                                                <div 
                                                                    v-for="sport in filteredSports" 
                                                                    :key="sport.id"
                                                                    @mousedown.prevent="selectSport(sport)"
                                                                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 flex items-center justify-between"
                                                                    :class="{
                                                                        'opacity-50 cursor-not-allowed': isDisabled(sport.id),
                                                                        'bg-blue-50': form1.sport_id === sport.id
                                                                    }"
                                                                >
                                                                    <span>{{ sport.name }}</span>
                                                                    <span v-if="form1.sport_id === sport.id" class="text-blue-500">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </span>
                                                                </div>
                                                                <div v-if="filteredSports.length === 0" class="px-3 py-2 text-gray-500 text-sm">
                                                                    No sports found
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Select Category -->
                                            <div class="">
                                                <div class="relative">
                                                    <select 
                                                        v-model="form1.categories" 
                                                        id="category" 
                                                        class="w-full border px-3 py-2 rounded-lg border-gray-300"
                                                    >
                                                        <option value="" disabled>Category</option>
                                                        <option 
                                                            v-for="category in availableCategories" 
                                                            :key="category.value"
                                                            :value="category.value"
                                                            :disabled="category.disabled"
                                                        >
                                                            {{ category.value }}{{ category.disabled ? ' (Already Selected)' : '' }}
                                                        </option>
                                                    </select>
                                                    <div 
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none transition-transform duration-200"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-span-3 flex gap-2">
                                                <span v-if="form1.errors.sport_id" class="text-red-500 text-xs">{{ form1.errors.sport_id }}</span>
                                                <span v-if="form1.errors.categories" class="text-red-500 text-xs">{{ form1.errors.categories }}</span>
                                            </div>
                                        </div>

                                        <div class="mb-4 p-3 bg-gray-100/60 rounded-lg">
                                            <label for="setup" class="block text-sm font-medium mb-1">Sport Configuration</label>
                                            <div class="">
                                                <label for="setup" class="block text-xs text-gray-700 font-medium mb-2">Sport Setup</label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <label 
                                                        v-for="setup in ['Single Elimination', 'Double Elimination', 'Free for All', 'Round Robin']" 
                                                        :key="setup"
                                                        class="relative cursor-pointer"
                                                    >
                                                        <input 
                                                            type="radio" 
                                                            :value="setup" 
                                                            v-model="form1.setup"
                                                            name="setup"
                                                            class="peer sr-only hidden"
                                                            @change="updateCategoryForFreeForAll"
                                                        />
                                                        <div class="p-3 rounded-lg ring-1 ring-gray-300 peer-checked:ring-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition-all duration-300">
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-sm font-medium text-gray-700 peer-checked:text-blue-700">{{ setup }}</span>
                                                            </div>
                                                            <div class="mt-1 text-xs text-gray-500 peer-checked:text-blue-600">
                                                                {{ getSetupDescription(setup) }}
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <span v-if="form1.errors.setup" class="text-red-500 text-xs mt-1">{{ form1.errors.setup }}</span>
                                            </div>

                                            <!-- Select Type -->
                                            <div class="mt-4">
                                                <label class="block text-xs text-gray-700 font-medium mb-3">Pointing System</label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <label 
                                                        v-for="type in ['Major', 'Minor']" 
                                                        :key="type"
                                                        class="relative cursor-pointer"
                                                    >
                                                        <input 
                                                            type="radio" 
                                                            :value="type" 
                                                            v-model="form1.type"
                                                            name="type"
                                                            class="peer sr-only"
                                                        />
                                                        <div class="p-3 rounded-lg ring-1 ring-gray-300 peer-checked:ring-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition-all duration-300">
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-sm font-medium text-gray-700 peer-checked:text-blue-700">{{ type }}</span>
                                                            </div>
                                                            <div class="mt-1 text-xs text-gray-500 peer-checked:text-blue-600">
                                                                {{ getTypeDescription(type) }}
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <span v-if="form1.errors.type" class="text-red-500 text-xs mt-1">{{ form1.errors.type }}</span>
                                            </div>
                                        </div>

                                        <!-- Select Facilitator -->
                                        <div class="mb-4">
                                            <label for="facilitator" class="block text-sm font-medium mb-2">Select Facilitator</label>
                                            <div class="relative">
                                                <select 
                                                    v-model="form1.facilitator_id" 
                                                    id="facilitator" 
                                                    class="w-full border px-3 py-2 rounded-lg border-gray-300"
                                                >
                                                    <option value="" disabled>Select a facilitator</option>
                                                    <option 
                                                    v-for="f in facilitator" 
                                                    :key="f.id" 
                                                    :value="f.id"
                                                    >
                                                    {{ f.student.first_name }} {{ f.student.last_name }}
                                                    </option>
                                                </select>
                                                <div 
                                                class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none transition-transform duration-200"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                </div>
                                            </div>
                                            <span v-if="form1.errors.facilitator_id" class="text-red-500 text-xs">{{ form1.errors.facilitator_id }}</span>
                                        </div>

                                        <!-- Modal Buttons -->
                                        <div class="flex justify-end">
                                            <button type="button" @click="closeSportsModal" class="mr-2 px-5 py-2 text-sm font-medium text-gray-500 ring-1 ring-gray-200 rounded-lg">Cancel</button>
                                            <button
                                                type="submit"
                                                :disabled="form1.processing"
                                                class="bg-blue-700 hover:bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium transition relative"
                                            >
                                                <span v-if="!form1.processing">
                                                Confirm
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
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

            <!-- Modal for adding new team -->
            <TransitionRoot appear :show="isTeamsModalOpen" as="template">
                <Dialog as="div" @close="closeTeamsModal" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="w-full max-w-lg transform overflow-hidden rounded-lg bg-white shadow-xl transition-all">
                                    <div class="flex items-center justify-between p-4 ">
                                        <DialogTitle as="h3" class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Select college to participate
                                        </DialogTitle>
                                        <button               
                                            @click="closeTeamsModal" 
                                            type="button" 
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        >
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>

                                    <form @submit.prevent="submitTeam" class="p-4">
                                        <!--select college-->
                                        <div class="mb-4">
                                            <label for="college" class="block text-sm font-medium text-gray-700 mb-2">Select College</label>
                                            <div class="relative mt-1">
                                                <select 
                                                    v-model="form2.college_id" 
                                                    id="college" 
                                                    class="block w-full rounded-lg bg-white pl-4 pr-10 py-2.5 text-sm border border-gray-300
                                                    focus:outline-none focus:ring-2 
                                                    disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed
                                                    transition-all duration-200 ease-in-out shadow-sm"
                                                    @focus="isOpen = true"
                                                    @blur="isOpen = false"
                                                >
                                                    <option value="" disabled class="py-2 text-gray-500">Choose a college...</option>
                                                    <option
                                                        v-for="college in availableColleges"
                                                        :key="college.id"
                                                        :value="college.id"
                                                        :disabled="isCollegeAssigned(college.id)"
                                                        class="py-2 px-3"
                                                        :class="{
                                                            'text-gray-900 hover:bg-blue-50': !isCollegeAssigned(college.id),
                                                            'text-gray-400 bg-gray-50': isCollegeAssigned(college.id)
                                                        }"
                                                    >
                                                        <span class="font-medium">{{ college.shortName }}</span>
                                                        <span class="text-gray-500 ml-2">({{ college.name }})</span>
                                                    </option>
                                                </select>

                                                <!-- Animated chevron icon -->
                                                <div 
                                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none transition-transform duration-200"
                                                    :class="{ 'rotate-180': isOpen }"
                                                >
                                                    <svg 
                                                        class="h-5 w-5 text-gray-400" 
                                                        xmlns="http://www.w3.org/2000/svg" 
                                                        viewBox="0 0 20 20" 
                                                        fill="currentColor"
                                                    >
                                                        <path 
                                                            fill-rule="evenodd" 
                                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" 
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Error message with icon -->
                                            <div 
                                                v-if="form2.errors.college_id" 
                                                class="mt-2 flex items-center gap-1.5 text-sm text-red-600"
                                            >
                                                <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ form2.errors.college_id }}
                                            </div>
                                        </div>

                                        <!-- Input for Team Name -->
                                        <div class="mb-4">
                                            <label for="teamName" class="block text-sm font-medium mb-2">Team Name</label>
                                            <input
                                                v-model="form2.assigned_team_name"
                                                id="teamName"
                                                type="text"
                                                placeholder="Enter the team name..."
                                                class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                            />
                                            <span v-if="form2.errors.assigned_team_name" class="text-red-500 text-sm mt-1">{{ form2.errors.assigned_team_name }}</span>
                                        </div>

                                        <!-- Hidden Palakasan ID input -->
                                        <input type="hidden" v-model="form2.palakasan_id" />

                                        <!-- Modal Buttons -->
                                        <div class="flex justify-end  mt-6">
                                            <button 
                                                type="button" 
                                                @click="closeTeamsModal" 
                                                class="mr-2 px-5 py-2 text-sm font-medium text-gray-500 ring-1 ring-gray-200 rounded-lg"                                            >
                                                Cancel
                                            </button>
                                            <button
                                                type="submit"
                                                :disabled="form2.processing"
                                                class="px-5 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                                            >
                                                <span v-if="!form2.processing">
                                                    Confirm
                                                </span>
                                                <span v-else class="flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.969 7.969 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Processing...
                                                </span>
                                            </button>  
                                        </div>
                                    </form>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
            
            <TransitionRoot appear :show="isHistoryModalOpen" as="template">
                <Dialog as="div" @close="closeHistoryModal" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="w-full max-w-6xl transform overflow-hidden rounded-lg bg-white shadow-xl transition-all">
                                    <div class="flex items-center justify-between p-4">
                                        <DialogTitle as="h3" class="text-lg font-semibold text-gray-900">
                                            Palakasan History
                                        </DialogTitle>
                                        <button 
                                            @click="closeHistoryModal"
                                            class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-colors duration-200"
                                        >
                                            <span class="sr-only">Close</span>
                                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                        </button>
                                    </div>
                                    
                                    <div class="max-h-[calc(90vh-8rem)] overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50 sticky top-0">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Theme</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr v-for="palakasan in completedPalakasans" :key="palakasan.id" class="hover:bg-gray-50/50 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ palakasan.year }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ palakasan.theme }}</div>
                                                        <div class="text-sm text-gray-500">{{ palakasan.tagline }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ formatDate(palakasan.start_date) }} - {{ formatDate(palakasan.end_date) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800">
                                                            {{ palakasan.status }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <button 
                                                            @click="viewPalakasanDetails(palakasan.id)" 
                                                            class="text-blue-600 hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 rounded-md px-2 py-1 transition-colors duration-200"
                                                        >
                                                            View
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
            <!-- Start Palakasan Modal -->
            <TransitionRoot appear :show="showModal" as="template">
                <Dialog as="div" @close="showModal = false" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-lg bg-white shadow-xl transition-all p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <DialogTitle as="h3" class="text-lg font-semibold text-gray-900">
                                            Start Palakasan
                                        </DialogTitle>
                                        <button 
                                            @click="showModal = false"
                                            class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-colors duration-200"
                                        >
                                            <span class="sr-only">Close</span>
                                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                        </button>
                                    </div>

                                    <div v-if="!allSportsScheduled" class="mb-4">
                                        <div class="p-4 bg-yellow-50 rounded-lg">
                                            <div class="space-y-3">
                                                <i class="fas fa-exclamation-triangle text-yellow-600 p-3 bg-yellow-200/50 rounded-md mr-2"></i>
                                                <p class="text-sm text-yellow-700">All sports must be scheduled before starting the Palakasan.</p>
                                            </div>
                                            <ul class="mt-4 ml-6 text-sm text-yellow-600 list-disc">
                                                <li v-for="sport in unscheduledSports" :key="sport.id">
                                                    <span class="font-medium">{{ sport.sport?.name }} {{ sport.categories }}</span> is not yet scheduled
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p v-else class="mb-6">Are you sure you want to start Palakasan? This action cannot be undone.</p>

                                    <div class="flex justify-end space-x-2">
                                        <button 
                                            @click="showModal = false" 
                                            type="button" 
                                            class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors duration-200"
                                        >
                                            {{ allSportsScheduled ? 'No, not ready yet' : 'Close' }}
                                        </button>
                                        <button 
                                            v-if="allSportsScheduled"
                                            @click="confirmStartPalakasan" 
                                            type="button" 
                                            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500/20 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none transition-colors duration-200"
                                        >
                                            Yes, get started
                                        </button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

            <!-- Logs Modal -->
            <!-- Logs Modal -->
            <div v-if="isLogsModalOpen" class="fixed inset-0 overflow-y-auto z-50">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="closeLogsModal"></div>
                    <div class="w-[62rem] h-[37rem] transform overflow-hidden rounded-lg bg-white shadow-xl transition-all flex flex-col">
                        <div class="flex items-center justify-between p-4 border-b">
                            <div class="text-lg font-semibold text-gray-900">
                                Activity Logs
                            </div>
                            <button 
                                @click="closeLogsModal"
                                class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-colors duration-200"
                            >
                                <span class="sr-only">Close</span>
                                <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                            </button>
                        </div>
                        
                        <div class="p-4 space-y-4 overflow-y-auto flex-1">
                            <!-- Empty State -->
                            <div v-if="!allSubmits?.length" class="flex flex-col items-center justify-center h-full text-center">
                                <svg class="w-16 h-16 text-gray-400 mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm5.78 2.47a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 1 0 1.06-1.06z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Activity Logs Yet</h3>
                                <p class="text-gray-500 text-sm">
                                    Activity logs will appear here once actions are performed.
                                </p>
                            </div>
                            <!-- Logs List -->
                            <div v-else>
                                <div v-for="submit in allSubmits" :key="submit.id" class="p-4 bg-gray-50 rounded-lg space-y-2 mb-4">
                                    <!-- Regular Match Result -->
                                    <template v-if="submit.type === 'regular'">
                                        <div class="flex justify-between items-center overflow-y-auto">
                                            <div class="w-full">
                                                <div class="flex w-full justify-between gap-2 mb-2">
                                                    <div class="flex items-center text-gray-600 gap-2">
                                                        <p class="text-sm">{{ submit.match_result?.sport_match?.assigned_sport?.sport?.name }} {{ submit.match_result?.sport_match?.assigned_sport?.categories }}</p>
                                                        <span class="text-sm">•</span>
                                                        <p class="text-sm">Round {{ submit.match_result?.sport_match?.round }}</p>
                                                        <span class="text-sm">•</span>
                                                        <p class="text-sm">{{ submit.match_result?.sport_match?.game }}</p>
                                                    </div>
                                                    <p class="text-xs text-right text-gray-500">
                                                        <span class="ml-2">{{ formatDateTime(submit.created_at) }}</span>
                                                    </p>
                                                </div>

                                                <p class="text-sm font-medium text-gray-800">
                                                    Winner: {{ submit.match_result?.winning_team?.assigned_team_name || 'Unknown Team' }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    {{ submit.match_result?.winning_team?.assigned_team_name || 'Unknown Team' }} won against {{ submit.match_result?.losing_team?.assigned_team_name || 'Unknown Team' }}
                                                </p>
                                                <div class="flex justify-between">
                                                    <div class="mt-3 flex gap-2">
                                                        <p class="text-xs text-gray-500">
                                                            Submitted by: {{ submit.facilitator?.student?.first_name }} {{ submit.facilitator?.student?.last_name }}
                                                        </p>
                                                        <span class="text-xs text-gray-500">•</span>
                                                        <p class="text-xs text-gray-500">
                                                            Validated by: {{ submit.official_name }}
                                                        </p>
                                                    </div>
                                                    <button 
                                                        @click="openSignature(submit.signature, submit.official_name)"
                                                        class="mt-2 py-1.5 px-2.5 rounded-full bg-blue-100 text-xs hover:bg-blue-200 text-blue-600 hover:text-blue-800 font-medium"
                                                    >
                                                        View Signature
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- FFO Match Result -->
                                    <template v-else>
                                        <div class="flex w-full justify-between gap-2 mb-2">
                                            <div class="flex items-center text-gray-600 gap-2">
                                                <p class="text-sm">{{ submit.match?.sport_id?.sport?.name }} {{ submit.match?.sport_id?.categories }}</p>
                                                <span class="text-sm">•</span>
                                                <p class="text-sm">{{ submit.match?.sport_variation_name }}</p>
                                            </div>
                                            <p class="text-xs text-right text-gray-500">
                                                <span class="ml-2">{{ formatDateTime(submit.created_at) }}</span>
                                            </p>
                                        </div>
                                        <h1 class="text-sm font-semibold text-gray-800">Game has been completed</h1>
                                        <p class="text-sm text-gray-600">
                                            You can now check the {{ submit.match?.sport_id?.sport?.name }} to see the results
                                        </p>
                                        <div class="flex justify-between">
                                            <div class="mt-3 flex gap-2">
                                                <p class="text-xs text-gray-500">
                                                    Submitted by: {{ submit.facilitator?.student?.first_name }} {{ submit.facilitator?.student?.last_name }}
                                                </p>
                                                <span class="text-xs text-gray-500">•</span>
                                                <p class="text-xs text-gray-500">
                                                    Validated by: {{ submit.official_name }}
                                                </p>
                                            </div>
                                            <button 
                                                @click="openSignature(submit.signature, submit.official_name)"
                                                class="mt-2 text-xs text-blue-600 py-1.5 px-2.5 rounded-full bg-blue-100 hover:bg-blue-200 hover:text-blue-800 font-medium"
                                            >
                                                View Signature
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signature Modal -->
            <TransitionRoot appear :show="showSignature" as="template">
                <Dialog as="div" @close="closeSignature" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="max-w-md w-full mx-4 transform overflow-hidden rounded-lg bg-white shadow-xl transition-all p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <DialogTitle as="h3" class="text-lg font-semibold text-gray-900">
                                            Official Signature
                                        </DialogTitle>
                                        <button 
                                            @click="closeSignature"
                                            class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-colors duration-200"
                                        >
                                            <span class="sr-only">Close</span>
                                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                        </button>
                                    </div>


                                    <div class="">
                                        <img 
                                            :src="selectedSignature" 
                                            alt="Official Signature" 
                                            class="max-w-full max-h-[300px] object-contain border border-gray-200 rounded-lg mb-4 bg-white p-2"
                                        />
                                        <div>
                                            <label for="official_name" class="block text-sm font-medium mb-2">Official Full Name</label>
                                            <div 
                                                :src="selectedOfficialName"
                                                class="text-sm text-gray-700 p-2.5 border border-gray-300 rounded-md"
                                                >
                                                {{ selectedOfficialName }}
                                            </div>
                                        </div>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

            <UpdateTeamModal
                v-if="showModalTeam"
                :isOpen="showModalTeam"
                :team="selectedTeam"
                @close="closeModal"
                @update="updateTeam"
                class="z-50"
            />
            
        </template>
    </AppLayout>
    <SuccessModal
        :show="showSuccessModal"
        :message="successMessage"
        @close="showSuccessModal = false"
     />
</template>

<script setup>
    import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
    import { ref, computed, onMounted, watch, watchEffect } from 'vue';
    import AppLayout from '@/Layout/DashboardLayout.vue';
    import { XMarkIcon } from '@heroicons/vue/24/outline'
    import { Disclosure, DisclosureButton, DisclosurePanel, TransitionChild } from '@headlessui/vue';
    import Tooltip from '@/Components/Tooltip.vue';
    import PalakasanRankings from '@/Components/PalakasanStandings.vue'; 
    import Graph from '@/Components/BarGraph.vue';  // Adjust the path as necessary
    import Settings from '@/Components/Settings.vue';
    import OverallSchedules from '@/Components/OverallSchedules.vue';
    import SportsRankings from '@/Components/SportsRankings.vue';
    import SuccessModal from '@/Components/SuccessModal.vue';
    import { Dialog, DialogPanel, DialogTitle, TransitionRoot } from '@headlessui/vue';
    import UpdateTeamModal from '@/Components/UpdateTeamModal.vue';

    const props = defineProps({
        colleges: Array,
        sports: Array,
        palakasans: Array,      // Palakasans data array
        assignedTeams: {
            type: Array,
            required: true
        },
        assignedSports: {
            type: Array,
            required: true
        },

        overallResult: {
            type: Array,
            default() {
                return [];
            }
        },
        
        variationResult: Array,
        facilitator: Array,
        matchResults: Array,
        matchRankings: Array,
        facilitatorRankSubmits: Array,
        facilitatorSubmits: Array,
        ffofacilitatorSubmits: Array,
        admin: Array,
        sportMatches: {
            type: Array,
            default() {
                return [];
            }
        },
        latestPalakasan: {
            type: Object,
            required: true
        },
        initialActiveTab: {
            type: String,
            default: 'details'
        },

        sportsVariations: {
            type: Array,
            default() {
                return [];
            }
        },
    });

    const showSuccessModal = ref(false);
    const successMessage = ref('');

    const isPalakasanModalOpen = ref(false);
    const isSportsModalOpen = ref(false);
    const isTeamsModalOpen = ref(false);
    const isStatusModalOpen = ref(false);
    const isHistoryModalOpen = ref(false);
    const page = usePage();
    const showSignature = ref(false);
    const selectedSignature = ref(null);
    const selectedOfficialName = ref(null);
    // Reactive state
    const showModalTeam = ref(false);
    const selectedTeam = ref(null);
    const openSignature = (signature,official_name) => {
        selectedSignature.value = signature;
        selectedOfficialName.value = official_name;
        showSignature.value = true;
    };

    const closeSignature = () => {
        showSignature.value = false;
    };

    const allSubmits = computed(() => {
        const combined = [
            ...(props.facilitatorSubmits || []).map(submit => ({
                ...submit,
                type: 'regular'
            })),
            ...(props.ffofacilitatorSubmits || []).map(submit => ({
                ...submit,
                type: 'ffo'
            }))
        ];
        return combined.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    });

    //this is for viewing the sport
    const viewSport = (sportId) => {
        router.get(route('sportview.index', { sport: sportId }));
    };

    const viewPalakasanDetails = (palakasanId) => {
    router.get(route('palakasanHistory.show', { palakasan: palakasanId }));
    };

    const goToDocumentation = () => {
    router.get(route('palakasan.documentation'));
    };

    const form3 = useForm({
        assigned_team_name: '',
        college_id: '',
        palakasan_id: ''
    })

    const openModal = (team) => {
        selectedTeam.value = team;
        showModalTeam.value = true;
    }

    const closeModal = () => {
        showModalTeam.value = false;
        selectedTeam.value = null;
    }

    const updateTeam = (teamData) => {
        form3.assigned_team_name = teamData.assigned_team_name;
        form3.college_id = teamData.college_id;
        form3.palakasan_id = teamData.palakasan_id;

        form3.put(route('palakasan.updateTeams', teamData.id), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                showSuccessModal.value = true;
                successMessage.value = 'Team updated successfully!';
            },
            onError: (errors) => {
                console.error(errors);
            }
        });
    };

    //tabs funtcions
    const activeTab = ref(props.initialActiveTab);

    const setActiveTab = (tab) => {
    activeTab.value = tab;
    updateQueryString(tab);
    };

    const updateQueryString = (tab) => {
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.history.pushState({}, '', url);
    };

    onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const tabFromQuery = urlParams.get('tab');
    if (tabFromQuery && ['details', 'lineups', 'leagues', 'overallSchedules'].includes(tabFromQuery)) {
        activeTab.value = tabFromQuery;
    }
    });

    const animateValue = (start, end, duration, setValue) => {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            setValue(Math.floor(progress * end));
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    };

    const teamsCount = ref(0);
    const sportsCount = ref(0);

    onMounted(() => {
        // Start the animations when the component is mounted
        animateValue(0, props.assignedTeams.length, 2000, (value) => teamsCount.value = value);
        animateValue(0, props.assignedSports.length, 2000, (value) => sportsCount.value = value);
    });

    watch(() => route().params.tab, (newTab) => {
    if (newTab && ['details', 'lineups', 'leagues', 'overallSchedules'].includes(newTab)) {
        activeTab.value = newTab;
    }
    });

    const showModal = ref(false);

    const confirmStartPalakasan = async () => {
        if (!allSportsScheduled.value) {
            return;
        }

        try {
            console.log('Starting Palakasan update process...');
            
            // Update Palakasan status which will also update all sports
            await router.put(`/palakasan/${props.latestPalakasan.id}/update-status`, {
                status: 'live'
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    showModal.value = false;
                    showSuccessModal.value = true;
                    successMessage.value = 'Palakasan is now live!';
                },
                onError: (errors) => {
                    console.error('Failed to update Palakasan:', errors);
                }
            });

        } catch (error) {
            console.error('Failed to start Palakasan:', error);
        }
    };

    const concludePalakasan = () => {
        if (!props.latestPalakasan) {
            toastRef.value.show({
                severity: 'error',
                summary: 'Error',
                detail: 'No active Palakasan found',
                life: 3000
            });
            return;
        }

        if (!confirm('Are you sure you want to conclude this Palakasan? This action cannot be undone.')) {
            return;
        }

        router.post(route('palakasan.conclude'), {}, {
            onSuccess: () => {
                showSuccessModal.value = true;
                successMessage.value = 'Palakasan has been concluded!';
            },
            onError: (errors) => {
                console.error('Failed to conclude Palakasan:', errors);
            }
        });
    };

    // Initialize palakasan useForm with default values
    const form = useForm({
        theme: '',
        tagline: '',
        description: '',
        start_date: '',
        end_date: '',
        status: 'pending', 
    });

    // sport form
    const form1 = useForm({
        palakasan_sport_id: props.latestPalakasan?.id || '',
        sport_id: '',
        categories: '',
        setup: '',
        type: '',
        status: 'pending',
        facilitator_id: '',
    });

    // team form
    const form2 = useForm({
        assigned_team_name: '',
        college_id: '',
        palakasan_id: props.latestPalakasan?.id || '', // Use optional chaining and provide a default
    });
    //status
    const statusForm = useForm({
        status: props.latestPalakasan?.status || false,
    });

    const availableSports = ref(props.sports);
    const availableColleges = ref(props.colleges);

    const openPalakasanModal = () => {
        isPalakasanModalOpen.value = true;
    };

    // Function to close the Palakasan modal
    const closePalakasanModal = () => {
        isPalakasanModalOpen.value = false;
        form.reset(); // Reset form when modal is closed
    };

    const submitPalakasan = () => {
        form.post(route('palakasan.store'), {
            onSuccess: () => {
                closePalakasanModal();
                showSuccessModal.value = true;
                successMessage.value = 'Palakasan created successfully!';
            },
        });
    };

    //---------------------------------------------------------

    // Open Sports modal
    const openSportsModal = () => {
        isSportsModalOpen.value = true;
    };
  
    // Close Sports modal and reset form
    const closeSportsModal = () => {
        isSportsModalOpen.value = false;
        form1.reset();
    };
  
    // Submit form for adding a new sports
    const submitSport = () => {
        form1.post(route('palakasanSport.store'), {
        onSuccess: () => {
            closeSportsModal();
            showSuccessModal.value = true;
            successMessage.value = 'League created successfully!';
            },
            onError: () => {
                console.error('Form submission error');
            },
        });
    };

    

    const isDisabled = (sportId) => {
    return props.assignedSports.some(assignedSport => 
        assignedSport.sport_id === sportId && 
        assignedSport.categories === form1.categories
    );
    };

    //---------------------------------------------
// Open Teams modal
const openTeamsModal = () => {
        isTeamsModalOpen.value = true;
    };

    // Close Teams modal and reset form
    const closeTeamsModal = () => {
        isTeamsModalOpen.value = false;
        form2.reset();
    };

    // Submit form for adding a new team
    const submitTeam = () => {
        if (!form2.college_id) {
            form2.setError('college_id', 'Please select a college');
            return;
        }

        if (!form2.assigned_team_name.trim()) {
            form2.setError('assigned_team_name', 'Please enter a team name');
            return;
        }

        if (isCollegeAssigned(form2.college_id)) {
            form2.setError('college_id', 'This college is already assigned to a team');
            return;
        }

        form2.post(route('team.store'), {
            preserveScroll: true,
            onSuccess: () => {
                closeTeamsModal();
                form2.reset();
                showSuccessModal.value = true;
                successMessage.value = 'Line up has been formed!';
            },
            onError: () => {
                console.error('Form submission error');
            },
        });
    };

    //Search and sort functionalities
    // State variables for search and sort (for both teams and sports)
    const searchQueryTeams = ref('');
    const searchQuerySports = ref('');
    const sortOrderTeams = ref('default');
    const sortOrderSports = ref('default');

    // Computed property for filtering and sorting assigned teams from props
    const filteredAndSortedTeams = computed(() => {
        let filteredTeams = props.assignedTeams;

        // Filter teams based on search query
        if (searchQueryTeams.value) {
            filteredTeams = filteredTeams.filter(team =>
                team.assigned_team_name.toLowerCase().includes(searchQueryTeams.value.toLowerCase()) ||
                team.college.name.toLowerCase().includes(searchQueryTeams.value.toLowerCase())
            );
        }

        // Sort teams based on sort order
        if (sortOrderTeams.value === 'az') {
            return filteredTeams.sort((a, b) =>
                a.assigned_team_name.localeCompare(b.assigned_team_name)
            );
        } else if (sortOrderTeams.value === 'za') {
            return filteredTeams.sort((a, b) =>
                b.assigned_team_name.localeCompare(a.assigned_team_name)
            );
        }

        // Default sort order (as per the original assignedTeams array)
        return filteredTeams;
    });

    // Computed property for filtering and sorting assigned sports from props
    const filteredAndSortedSports = computed(() => {
        // Return original array if no search query
        if (!searchQuerySports.value) {
            return props.assignedSports;
        }
        
        const searchTerm = searchQuerySports.value.toLowerCase();
        
        // Create a cached facilitator name lookup
        const facilitatorNameCache = new Map();
        
        return props.assignedSports.filter(sport => {
            // Get facilitator name from cache or compute and cache it
            let facilitatorName = facilitatorNameCache.get(sport.facilitator_id);
            if (!facilitatorName) {
                facilitatorName = (getFacilitatorName(sport.facilitator_id) || '').toLowerCase();
                facilitatorNameCache.set(sport.facilitator_id, facilitatorName);
            }
            
            // Early return if sport name matches
            if (sport.sport?.name?.toLowerCase().includes(searchTerm)) {
                return true;
            }
            
            // Check other fields only if necessary
            return (sport.categories?.toLowerCase() || '').includes(searchTerm) ||
                   (sport.type?.toLowerCase() || '').includes(searchTerm) ||
                   (sport.setup?.toLowerCase() || '').includes(searchTerm) ||
                   facilitatorName.includes(searchTerm);
        });
    });

    //progress bar
    const completedSports = computed(() => props.assignedSports.filter(sport => sport.status === 'completed').length);
    const totalSports = computed(() => props.assignedSports.length);
    const progressPercentage = computed(() => totalSports.value > 0 ? (completedSports.value / totalSports.value) * 100 : 0);
    
    //status
    const capitalizedStatus = computed(() => {
    if (!props.latestPalakasan.status) return '';
    return props.latestPalakasan.status.charAt(0).toUpperCase() + props.latestPalakasan.status.slice(1);
    });

    const statusColor = computed(() => {
    switch (props.latestPalakasan.status) {
        case 'pending':
        return 'text-blue-500 bg-blue-100';
        case 'live':
        return 'text-red-500 bg-red-100';
        case 'completed':
        return 'text-green-500 bg-green-100';
        case 'cancelled':
        return 'text-gray-500 bg-gray-100';
        default:
        return 'text-gray-500';
    }
    });

    //History
    const openHistoryModal = () => {
    isHistoryModalOpen.value = true;
    };

    const closeHistoryModal = () => {
    isHistoryModalOpen.value = false;
    };

    const completedPalakasans = computed(() => {
    return props.palakasans
        .filter(palakasan => palakasan.status === 'completed')
        .sort((a, b) => new Date(b.start_date) - new Date(a.start_date));
    });

    const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
    };

    const facilitatorMap = computed(() => {
    return props.facilitator.reduce((acc, f) => {
        acc[f.id] = `${f.student.first_name} ${f.student.last_name}`;
        return acc;
    }, {});
    });

    const getFacilitatorName = (facilitatorId) => {
    return facilitatorMap.value[facilitatorId] || 'Not Assigned';
    };


    // Add these refs
    const isLogsModalOpen = ref(false);
    const activeLogTab = ref('results');
    
    // Add these methods
    const openLogsModal = () => {
        isLogsModalOpen.value = true;
    };
    
    const closeLogsModal = () => {
        isLogsModalOpen.value = false;
        activeLogTab.value = 'results';
    };
    
    const formatDateTime = (dateString) => {
        const options = { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric',
            hour: '2-digit', 
            minute: '2-digit'
        };
        return new Date(dateString).toLocaleString(undefined, options);
    };

    const showHowToStart = ref(false);

    // Selection mode state
    const isSelectionMode = ref(false);
    const selectedSports = ref([]);
    const showDeleteConfirmation = ref(false);

    const toggleSelectionMode = () => {
        isSelectionMode.value = !isSelectionMode.value;
        if (!isSelectionMode.value) {
            selectedSports.value = [];
        }
    };

    const toggleSportSelection = (sport) => {
        const index = selectedSports.value.indexOf(sport);
        if (index === -1) {
            selectedSports.value.push(sport);
        } else {
            selectedSports.value.splice(index, 1);
        }
    };

    const deleteSelectedSports = () => {
        if (selectedSports.value.length === 0) {
            alert('Please select sports to delete');
            return;
        }

        if (confirm('Are you sure you want to delete the selected sports?')) {
            router.post(route('onepalakasan.delete-sports'), {
                sportIds: selectedSports.value.map(sport => sport.id)
            }, {
                onSuccess: () => {
                    selectedSports.value = [];
                    isSelectionMode.value = false;
                    showSuccessModal.value = true;
                    successMessage.value = 'Succesfully deleted a League!';
                },
                onError: (errors) => {
                    alert('Failed to delete sports. Please try again.');
                    console.error(errors);
                }
            });
        }
    };

    const isCollegeAssigned = (collegeId) => {
        return props.assignedTeams.some(team => team.college_id === collegeId);
    };

    const unassignedCollegesCount = computed(() => {
        return props.colleges.filter(college => !isCollegeAssigned(college.id)).length;
    });

    const hasEnoughTeams = computed(() => {
        return filteredAndSortedTeams.value.length >= 4;
    });

    const getUsedCategories = computed(() => {
        if (!form1.sport_id) return [];
        return filteredAndSortedSports.value
            .filter(sport => sport.sport?.id === form1.sport_id)
            .map(sport => sport.categories);
    });

    const availableCategories = computed(() => {
        const allCategories = ['Men', 'Women', 'Mixed'];
        const usedCategories = getUsedCategories.value;
        return allCategories.map(category => ({
            value: category,
            disabled: usedCategories.includes(category)
        }));
    });

    const allSportsScheduled = computed(() => {
        return props.assignedSports.every(sport => sport.status === 'scheduled');
    });

    const unscheduledSports = computed(() => {
        return props.assignedSports.filter(sport => sport.status !== 'scheduled');
    });

    const getSetupDescription = (setup) => {
        switch (setup) {
            case 'Single Elimination':
                return 'Teams are eliminated after one loss';
            case 'Double Elimination':
                return 'Teams are eliminated after two losses';
            case 'Free for All':
                return 'All teams compete simultaneously';
            case 'Round Robin':
                return 'Each team plays against every team';
            default:
                return '';
        }
    };
    
    const getTypeDescription = (type) => {
        switch (type) {
            case 'Major':
                return 'Higher point value distribution';
            case 'Minor':
                return 'Lower point value distribution';
            default:
                return '';
        }
    };

    const sportSearch = ref('');
    const showSportDropdown = ref(false);
    const filteredSports = ref([]);

    const onFocus = () => {
        showSportDropdown.value = true;
        if (selectedSportName.value) {
            sportSearch.value = '';
        }
    };

    const filterSports = () => {
        if (!sportSearch.value) {
            filteredSports.value = availableSports.value;
            return;
        }
        const search = sportSearch.value.toLowerCase();
        filteredSports.value = availableSports.value.filter(sport => 
            sport.name.toLowerCase().includes(search)
        );
    };

    const selectSport = (sport) => {
        if (isDisabled(sport.id)) return;
        form1.sport_id = sport.id;
        sportSearch.value = sport.name;
        showSportDropdown.value = false;
        updateAvailableCategories();
    };

    const selectedSportName = computed(() => {
        const sport = availableSports.value.find(s => s.id === form1.sport_id);
        return sport ? sport.name : '';
    });

    // Add click outside handler to close dropdown
    const dropdownContainer = ref(null);

    onMounted(() => {
        document.addEventListener('click', (e) => {
            if (dropdownContainer.value && !dropdownContainer.value.contains(e.target)) {
                showSportDropdown.value = false;
                sportSearch.value = ''; // Clear search when dropdown closes
                filterSports(); // Reset the filtered list
            }
        });
    });

    // Initialize filtered sports
    watchEffect(() => {
        filteredSports.value = availableSports.value;
    });

    watch(sportSearch, (newValue) => {
        if (!newValue && selectedSportName.value) {
            sportSearch.value = selectedSportName.value;
        }
    });

    const clearSearch = () => {
        sportSearch.value = '';
        filterSports();
    };

</script>

<style scoped>
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

select::-ms-expand {
  display: none;
}
.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}

.animate-shimmer {
    animation: shimmer 2s infinite linear;
    background: linear-gradient(
        to right,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.3) 50%,
        rgba(255, 255, 255, 0) 100%
    );
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes shimmer {
    from { transform: translateX(-100%); }
    to { transform: translateX(100%); }
}
</style>
