<template>
    <Head title="Palakasan"/>
    <AppLayout>
        <template v-slot:default>
            <Toast ref="toastRef" />
            <!-- Tabs Navigation -->
            <nav class="flex relative justify-between  items-center">
                <div class="flex gap-2 ">
                    <div class="flex gap-2">
                        <button 
                            v-for="tab in ['details', 'line ups', 'leagues', 'Schedules', 'settings', ]"
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
                            class="text-gray-600 ring-1 font-medium flex items-center ring-gray-300 hover:bg-gray-100 hover:text-gray-800 hover:ring-gray-400 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-2 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 group transition-all duration-500 ease-in-out"
                        >
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.25 12.5h3.5m-10-4.75a4 4.5 0 0 1 4-4h8.5a4 4.5 0 0 1 4 4v8.5a4 4.5 0 0 1-4 4h-8.5a4 4.5 0 0 1-4-4zm0 1h16.5"/></svg>                        
                            <span class="max-w-0 overflow-hidden group-hover:ml-1.5 group-hover:max-w-[200px] whitespace-nowrap transition-all duration-500 ease-in-out">
                            Archive
                            </span>
                        </button>
                        <HowToStartModal
                            :is-open="showHowToStart"
                            @close="showHowToStart = false"
                        />
                    </div>
                </nav>

                <!-- Tabs Content -->
                <div class="mt-4">
                    <!-- Palakasans (Details) Tab Content -->
                    <div v-if="activeTab === 'details'">
                        <div v-if="latestPalakasan">
                            <div class="p-5 ring-1 ring-gray-300 rounded-lg">
                                <!--head-->
                                <div class="flex justify-between border-b pb-3 border-gray-300">
                                    <div class="flex gap-2">
                                        <div>
                                            <div class="flex gap-2 items-center">
                                                <h1  class="text-2xl font-semibold text-gray-800">Palakasan {{ latestPalakasan.year }}</h1>
                                            </div>
                                            <p v-if="latestPalakasan" class="text-xs text-gray-500">
                                                {{ formatDate(latestPalakasan.start_date) }} - {{ formatDate(latestPalakasan.end_date) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p :class="['text-xs px-2.5 py-1 rounded-xl mt-1', statusColor]">
                                                {{ capitalizedStatus }}
                                            </p>
                                        </div>

                                    </div>
                                    <!--utility, history and creating-->
                                    <div class="flex">
                                        <div>  <!-- Start Palakasan Button -->
                                                <button 
                                                    v-if="progressPercentage < 100 && latestPalakasan.status !== 'cancelled'"
                                                    @click="showModal = true"
                                                    type="button" 
                                                    :disabled="latestPalakasan.status === 'live' || assignedSports === 0 || assignedTeams.length < 4"
                                                    :class="[
                                                    'flex gap-1.5 font-medium text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2',
                                                    latestPalakasan.status === 'live'
                                                        ? ' text-blue-700 bg-blue-100 cursor-not-allowed'
                                                        : 'bg-blue-700 text-white hover:bg-blue-700/90 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700'
                                                    ]"
                                                    >
                                                    Start Palakasan
                                                </button>
                                            </div>
                                            <div>
                                                <div v-if="latestPalakasan?.status !== 'live' && latestPalakasan?.status !== 'pending'">
                                                    <button 
                                                        @click="openPalakasanModal" 
                                                        type="button"
                                                        class="flex gap-1.5 text-sm ml-2 font-medium focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2 bg-blue-700 text-white hover:bg-blue-700/90 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                                                    >
                                                        Create New Palakasan            
                                                    </button>
                                                </div>
                                            </div>
                                </div>
                            </div>

                            <!--content-->
                            <div class="mt-5 grid grid-cols-6 gap-6">
                                <div class="col-span-3 flex flex-col justify-between">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-800">{{ latestPalakasan.theme }}</p>
                                        <p class="text-sm text-gray-700">{{ latestPalakasan.tagline }}</p>
                                        <p class="text-sm text-gray-700 mt-2">{{ latestPalakasan.description }}</p>

                                        <!--Add a progress bar here-->
                                        <div class="w-full mt-5">
                                            <h3 class="text-md font-medium mb-2">Palakasan Progress</h3>
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                            <div class="bg-blue-600 h-2.5 rounded-full" :style="{ width: `${progressPercentage}%` }"></div>
                                            </div>
                                            <p class="text-xs text-gray-600 mt-2">
                                            {{ completedSports }} out of {{ totalSports }} sports completed ({{ progressPercentage.toFixed(1) }}%)
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 mt-4 gap-4">
                                        <div class="p-4 bg-blue-50 hover:bg-blue-100 cursor-pointer rounded-lg" @click="activeTab = 'lineups'">
                                            <div>
                                                <h1 class="text-sm font-semibold text-blue-700">Lineups</h1>
                                            </div>
                                            <p class="mt-4 text-3xl font-semibold text-blue-700 mb-3">{{ teamsCount }}</p>
                                        </div>

                                        <div class="p-4 bg-blue-50 hover:bg-blue-100 cursor-pointer rounded-lg" @click="activeTab = 'leagues'">
                                            <div>
                                                <h1 class="text-sm font-semibold text-blue-700">Leagues</h1>
                                            </div>
                                            <p class="mt-4  text-3xl font-semibold text-blue-700 mb-3">{{ sportsCount }}</p>
                                        </div>

                                    </div>
                                </div>
                                <!--display the accumolated points and ranking of all the teams here-->
                                <div class="col-span-3 rounded-lg h-full">
                                    <Graph
                                        :teams="assignedTeams"
                                        :overallResult="overallResult"
                                        :variationResult="variationResult"
                                    />
                                </div>

                            </div>
                        </div>
                        <div class=" mt-5">
                            <PalakasanRankings :teams="assignedTeams" :overallResult="overallResult" :variationResult="variationResult" />
                        </div>

                    </div>
                    <div v-else class="text-center py-10 bg-blue-50 border-2 border-blue-500 rounded-lg border-dashed border-spacing-4">
                        <h1 class="text-2xl font-semibold text-blue-700">No Palakasan Created Yet</h1>
                        <p class="text-blue-600 mb-4">Please create a new Palakasan event.</p>
                        <button 
                            @click="openPalakasanModal" 
                            class="text-white py-1.5 px-2.5 text-sm rounded-md bg-blue-700 shadow hover:bg-blue-700/90 transition-colors">
                            <i class="fa-solid fa-file mr-1.5"></i> Create Palakasan
                        </button>
                    </div>
                </div>

                <!-- Teams Tab Content -->
                <div v-if="activeTab === 'line ups'">
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
                                <button                                                 
                                @click="openTeamsModal" 
                                @mouseenter="showTooltip = true"
                                @mouseleave="showTooltip = false"
                                :disabled="latestPalakasan?.status !== 'pending'"                                
                                :class="[
                                'flex gap-1.5 text-sm focus:outline-none font-medium focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2',
                                latestPalakasan?.status !== 'pending'
                                    ? ' text-blue-700 bg-blue-100 cursor-not-allowed'
                                    : 'bg-blue-700 text-white hover:bg-blue-700/90 flex items-center gap-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700'
                                ]"                            
                                >
                                    Form Line ups
                                </button>
                                <Transition
                                    enter-active-class="transition duration-200 ease-out"
                                    enter-from-class="transform scale-95 opacity-0"
                                    enter-to-class="transform scale-100 opacity-100"
                                    leave-active-class="transition duration-150 ease-in"
                                    leave-from-class="transform scale-100 opacity-100"
                                    leave-to-class="transform scale-95 opacity-0"
                                >
                                    <div v-if="showTooltip && latestPalakasan?.status === 'live'"
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-blue-700 text-white text-xs rounded-md shadow-lg whitespace-nowrap z-50"
                                    >
                                        Cannot form line ups while tournament is live
                                        <div class="absolute top-10 left-1/2 transform -translate-x-1/2 -mt-1">
                                            <div class="border-4 border-transparent border-t-blue-900"></div>
                                        </div>
                                    </div>
                                </Transition>
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
                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div v-for="(team, index) in filteredAndSortedTeams" :key="index" class="bg-white ring-1 ring-gray-300 shadow rounded-lg hover:ring-blue-400 hover:bg-blue-50/80 transition p-4 group">
                            <div class="flex items-center">
                                <div class="">
                                    <svg class='w-7 h-7 mb-2' xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M14.754 10c.966 0 1.75.784 1.75 1.75v4.749a4.501 4.501 0 0 1-9.002 0V11.75c0-.966.783-1.75 1.75-1.75zm0 1.5H9.252a.25.25 0 0 0-.25.25v4.749a3.001 3.001 0 0 0 6.002 0V11.75a.25.25 0 0 0-.25-.25M3.75 10h3.381a2.74 2.74 0 0 0-.618 1.5H3.75a.25.25 0 0 0-.25.25v3.249a2.5 2.5 0 0 0 3.082 2.433c.085.504.24.985.453 1.432Q6.539 18.999 6 19a4 4 0 0 1-4-4.001V11.75c0-.966.784-1.75 1.75-1.75m13.125 0h3.375c.966 0 1.75.784 1.75 1.75V15a4 4 0 0 1-5.03 3.866c.214-.448.369-.929.455-1.433q.277.066.575.067a2.5 2.5 0 0 0 2.5-2.5v-3.25a.25.25 0 0 0-.25-.25h-2.757a2.74 2.74 0 0 0-.618-1.5M12 3a3 3 0 1 1 0 6a3 3 0 0 1 0-6m6.5 1a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5m-13 0a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5m6.5.5a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3m6.5 1a1 1 0 1 0 0 2a1 1 0 0 0 0-2m-13 0a1 1 0 1 0 0 2a1 1 0 0 0 0-2"/></svg>
                                    <h3 class="text-xl font-semibold">{{ team.assigned_team_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ team.college.name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sports Tab Content -->
                <div v-if="activeTab === 'leagues' && hasEnoughTeams" class="mt-4">
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
                            <button 
                                @click="openSportsModal" 
                                @mouseenter="showTooltip = true"
                                @mouseleave="showTooltip = false"
                                :disabled="latestPalakasan?.status !== 'pending' || assignedTeams.length < 4"
                                :class="[
                                    'flex gap-1.5 text-sm focus:outline-none font-medium focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2',
                                    latestPalakasan?.status !== 'pending'
                                        ? 'text-blue-700 bg-blue-100 cursor-not-allowed'
                                        : 'flex items-center gap-2 bg-blue-700 text-white hover:bg-blue-700/90 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700'
                                ]"      
                            >
                                Create League
                            </button>
                            <Transition
                                    enter-active-class="transition duration-200 ease-out"
                                    enter-from-class="transform scale-95 opacity-0"
                                    enter-to-class="transform scale-100 opacity-100"
                                    leave-active-class="transition duration-150 ease-in"
                                    leave-from-class="transform scale-100 opacity-100"
                                    leave-to-class="transform scale-95 opacity-0"
                                >
                                    <div v-if="showTooltip && latestPalakasan?.status === 'live'"
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-blue-700 text-white text-xs rounded-md shadow-lg whitespace-nowrap z-50"
                                    >
                                        Cannot create league while tournament is live
                                        <div class="absolute top-10 left-1/2 transform -translate-x-1/2 -mt-1">
                                            <div class="border-4 border-transparent border-t-blue-900"></div>
                                        </div>
                                    </div>
                            </Transition>
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
                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div
                        v-for="(sport, index) in filteredAndSortedSports"
                        :key="index"
                        @click="isSelectionMode ? toggleSportSelection(sport) : viewSport(sport.id)"
                        class="bg-white ring-1 ring-gray-300 cursor-pointer rounded-lg p-4 transition-all duration-300 ease-in-out hover:bg-blue-50/80 hover:ring-blue-400 group relative"
                        :class="{ 'ring-2 ring-blue-500 bg-blue-50': isSelectionMode && selectedSports.includes(sport) }"
                        >
                        <!-- Selection Checkbox -->
                        <div v-if="isSelectionMode" class="absolute top-2 right-2">
                            <div class="w-5 h-5 rounded border-2" :class="[
                                selectedSports.includes(sport) 
                                    ? 'bg-blue-500 border-blue-500' 
                                    : 'border-gray-300'
                            ]">
                                <svg v-if="selectedSports.includes(sport)" class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                        </div>
                        <div 
                            class="absolute top-2" 
                            :class="{ 'right-9': isSelectionMode, 'right-2': !isSelectionMode }"
                        >
                            <div
                                class="w-2.5 h-2.5 rounded-full"
                                :class="{
                                    'bg-gray-300': sport.status === 'pending',
                                    'bg-yellow-400': sport.status === 'scheduled',
                                    'bg-red-500': sport.status === 'Ongoing',
                                    'bg-green-500': sport.status === 'completed'
                                }"
                                :title="sport.status"
                            ></div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="w-full">
                            <svg class="w-6 h-6 mb-3 group-hover:text-blue-600 transition" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 2048 2048">
                                <path fill="currentColor" d="M1792 256q27 0 50 10t40 27t28 41t10 50v192q0 86-30 163t-85 137t-127 98t-159 48q-19 62-51 116t-76 98t-97 77t-114 56q35 34 58 76t35 91h70q40 0 75 15t61 41t41 61t15 75v192H384v-192q0-40 15-75t41-61t61-41t75-15h70q11-48 34-90t59-77q-61-22-114-55t-96-78t-76-98t-52-116q-86-9-159-47t-127-99t-84-137T0 576V384q0-27 10-50t27-40t41-28t50-10h256V128h1152v128zm-1280 0v128h896V256zM128 576q0 57 19 109t53 93t81 71t103 41V384H128zm1280 1152q0-26 19-45t45-19H576q-26 0-45 19t-19 45v64h896zm-267-192q-10-29-28-52t-42-41t-52-26t-59-9q-30 0-58 9t-53 26t-42 40t-28 53zm-181-256q81 0 161-27t144-76t103-121t40-160V512H512v384q0 89 39 160t103 120t144 77t162 27m832-896h-256v506q56-12 103-41t81-70t53-94t19-109z"/>
                            </svg>
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-semibold">{{ sport.sport?.name }} {{ sport.categories }}</h3>
                            </div>

                            <div class="flex border-b pb-2 border-gray-300 text-gray-700 items-center gap-2 mt-2">
                                <p class="text-xs flex items-center gap-1">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M10.75 2.567a2.5 2.5 0 0 1 2.332-.089l.168.089L19.544 6.2a2.5 2.5 0 0 1 1.244 1.987l.006.178v7.268a2.5 2.5 0 0 1-1.099 2.07l-.15.095l-6.295 3.634a2.5 2.5 0 0 1-2.332.089l-.168-.09L4.456 17.8a2.5 2.5 0 0 1-1.244-1.987l-.006-.178V8.366a2.5 2.5 0 0 1 1.1-2.07l.15-.095zm5.78 2.47a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 1 0 1.06-1.06z"/></g></svg>
                                {{ sport.setup }}
                                </p>
                                <p class="text-xs text-gray-400">|</p>
                                <p class="text-xs flex items-center gap-1">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M16.45 13.29c.74-.29 4.3-1.96 4.3-7.13c0-.41-.34-.75-.75-.75h-2.28V4c0-.41-.34-.75-.75-.75H7.03c-.41 0-.75.34-.75.75v1.41H4c-.41 0-.75.34-.75.75c0 5.17 3.56 6.84 4.3 7.13c.9 1.12 2.21 1.88 3.7 2.08v1.29H8.5c-.32 0-.61.21-.71.51l-.86 2.59a.75.75 0 0 0 .1.68c.14.2.37.31.61.31h8.72c.24 0 .47-.12.61-.31s.18-.45.1-.68l-.86-2.59a.74.74 0 0 0-.71-.51h-2.75v-1.29c1.49-.2 2.8-.96 3.7-2.08m-1.13 5.96H8.68l.36-1.09h5.91zm3.9-12.34c-.15 1.81-.86 3-1.59 3.77c.06-.32.09-.64.09-.98V6.91zm-14.44 0h1.5V9.7c0 .33.03.66.09.98c-.73-.77-1.43-1.96-1.58-3.77zm3 2.79V4.75h8.45V9.7a4.22 4.22 0 0 1-4.22 4.22A4.23 4.23 0 0 1 2 13.25zm5.78 2.47a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 1 0 1.06-1.06z"/>
                            </svg>
                                {{ sport.type }}
                                </p>
                            </div>
                            <!-- Facilitator information -->
                            <div class="flex items-center mt-3 gap-2">
                                <div class="p-2 bg-gray-200/80 rounded-md group-hover:bg-blue-200/50">
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><circle cx="12" cy="6" r="4" fill="currentColor"/><path fill="currentColor" d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5"/></svg>
                                </div>
                                <div>
                                <p class="text-sm text-gray-800 font-semibold">
                                    {{ getFacilitatorName(sport.facilitator_id) }}
                                </p>
                                <p class="text-xs text-gray-400 group-hover:text-blue-400">Facilitator</p>
                                </div>
                                
                            </div>
                            <!-- Animated Arrow -->
                            <div class="absolute bottom-6  right-6 opacity-0 group-hover:opacity-100 transition-all duration-300 group-hover:translate-x-1">
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="h-5 w-5 text-gray-500  group-hover:text-blue-600" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        stroke-width="2" 
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"
                                    />
                                </svg>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- Not Enough Teams State -->
                <div v-if="activeTab === 'leagues' && !hasEnoughTeams" class="mt-4">
                        <div class="flex flex-col items-center justify-center text-center p-10 bg-yellow-50 rounded-lg">
                            <svg class="w-16 h-16 text-yellow-400 mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2A2.25 2.25 0 0 1 12.75 22h-2A8.75 8.75 0 0 1 2 13.25zm5.78 2.47a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 1 0 1.06-1.06z"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-yellow-700 mb-2">Not Enough Teams</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                You need at least 4 teams to create leagues and start matches. <br>
                                Currently you have {{ filteredAndSortedTeams.length }} team{{ filteredAndSortedTeams.length !== 1 ? 's' : '' }}. <br>
                                Please add more teams in the Teams tab.
                            </p>
                            <button 
                                @click="activeTab = 'lineups'" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
                            >
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2A2.25 2.25 0 0 1 12.75 22h-2A8.75 8.75 0 0 1 2 13.25zm5.78 2.47a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 1 0 1.06-1.06z"/></svg>
                                Go to Teams
                            </button>
                        </div>
                </div>

                <div v-if="activeTab === 'settings'">
                    <Settings 
                        :latestPalakasan="latestPalakasan"
                        :admin="admin"
                    />   
                </div>

                <div v-if="activeTab === 'Schedules'">
                    <OverallSchedules
                        :latestPalakasan="{
                            ...latestPalakasan,
                            matches: sportMatches
                        }"
                        :activeSports="assignedSports"
                        :activeTeams="assignedTeams"
                        :palakasanYear="latestPalakasan?.year"
                        :admin="admin"
                    />
                </div>

            </div>

            <!--Modals for palakasan-->

            <!--add or create modals-->
            <!-- Palakasan Modal -->
            <div v-if="isPalakasanModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
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
                    </div>
            </div>

            <!-- Modal for adding sport -->
            <div v-if="isSportsModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
                    <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Select sport to be played
                        </h3>
                        <button               
                            @click="closeSportsModal" 
                            type="button" 
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form @submit.prevent="submitSport">
                        <div class="p-4">
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
                                                    <div class="relative">
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
                                <button type="button" @click="closeSportsModal" class="mr-2 px-5 py-2.5 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                                <button
                                    type="submit"
                                    :disabled="form1.processing"
                                    class="bg-blue-700 hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm transition relative"
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
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal for adding new team -->
            <div v-if="isTeamsModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
                    <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Select college to participate
                        </h3>
                        <button               
                            @click="closeTeamsModal" 
                            type="button" 
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form @submit.prevent="submitTeam">
                        <div class="p-4">
                            <!--select college-->
                            <div class="mb-4">
                                <label for="college" class="block text-sm mb-2 font-medium">Select College</label>
                                <div class="relative">
                                    <select 
                                        v-model="form2.college_id" 
                                        id="college" 
                                        class="w-full appearance-none border border-gray-300 px-3 py-2 pr-10 rounded-lg"
                                        @focus="isOpen = true"
                                        @blur="isOpen = false"
                                    >
                                        <option value="" disabled>Select a college</option>
                                        <option
                                            v-for="college in availableColleges"
                                            :key="college.id"
                                            :value="college.id"
                                            :disabled="isCollegeAssigned(college.id)"
                                        >
                                            {{ college.shortName }} {{ isCollegeAssigned(college.id) ? ' (Already Selected)' : '' }}
                                        </option>
                                    </select>
                                    <div 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none transition-transform duration-200"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                    </div>
                                </div>
                                <span v-if="form2.errors.college_id" class="text-red-500">{{ form2.errors.college_id }}</span>
                            </div>

                            <!-- Input for Team Name -->
                            <div class="mb-4">
                                <label for="teamName" class="block text-sm mb-2 font-medium">Team Name</label>
                                <input
                                    v-model="form2.assigned_team_name"
                                    id="teamName"
                                    type="text"
                                    placeholder="Enter the team name..."
                                    class="w-full border border-gray-300 px-3 py-2 rounded-lg"
                                />
                                <span v-if="form2.errors.assigned_team_name" class="text-red-500">{{ form2.errors.assigned_team_name }}</span>
                            </div>

                            <!-- Hidden Palakasan ID input -->
                            <input type="hidden" v-model="form2.palakasan_id" />

                            <!-- Modal Buttons -->
                            <div class="flex justify-end">
                                <button type="button" @click="closeTeamsModal" class="mr-2 px-5 text-sm py-2.5 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                                <button
                                    type="submit"
                                    :disabled="form2.processing"
                                    class="bg-blue-700 hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm transition relative"
                                >
                                    <span v-if="!form2.processing">
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
                        </div>
                    </form>
                </div>
            </div>
            
            <div v-if="isHistoryModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
                <div class="p-6 border-b border-gray-200 sticky top-0 bg-white z-10 flex justify-between items-center rounded-t-lg">
                    <h3 class="text-2xl font-semibold text-gray-900" id="modal-title">
                    Palakasan History
                    </h3>
                    <button @click="closeHistoryModal" class="text-gray-400 hover:text-gray-500 transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>
                </div>
                
                <div class="overflow-y-auto flex-grow">
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
                        <tr v-for="palakasan in completedPalakasans" :key="palakasan.id" class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ palakasan.year }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ palakasan.theme }}</div>
                            <div class="text-sm text-gray-500">{{ palakasan.tagline }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ formatDate(palakasan.start_date) }} - {{ formatDate(palakasan.end_date) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ palakasan.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="viewPalakasanDetails(palakasan.id)" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                            View
                            </button>
                        </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                </div>
            </div>

            <!-- Start Palakasan Modal -->
            <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                <div class="bg-white p-5 rounded-lg shadow-xl max-w-md w-full">
                    <h3 class="text-lg font-bold mb-6">Start Palakasan</h3>
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
                        <button @click="showModal = false" type="button" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            {{ allSportsScheduled ? 'No, not ready yet' : 'Close' }}
                        </button>
                        <button 
                            v-if="allSportsScheduled"
                            @click="confirmStartPalakasan" 
                            type="button" 
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                        >
                            Yes, get started
                        </button>
                    </div>
                </div>
            </div>
            <!-- Logs Modal -->
             <div v-if="isLogsModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                <div class="bg-white rounded-lg shadow-lg h-[37rem] w-[62rem] flex flex-col">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Activity Logs</h3>
                        <button 
                            @click="closeLogsModal" 
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 inline-flex justify-center items-center"
                        >
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
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
                        <div v-for="submit in allSubmits" :key="submit.id" class="p-4 bg-gray-50 rounded-lg">
                                    <!-- Regular Match Result -->
                                    <template v-if="submit.type === 'regular'">
                                        <div class="flex justify-between items-center  overflow-y-auto ">
                                            <div class="w-full">
                                                <div class="flex w-full justify-between gap-2 mb-2">
                                                    <div class="flex items-center text-gray-600 gap-2 ">
                                                        <p class="text-sm"> {{  submit.match_result?.sport_match?.assigned_sport?.sport?.name }} {{ submit.match_result?.sport_match?.assigned_sport?.categories }}</p>
                                                        <span class="text-sm">•</span>
                                                        <p class=" text-sm">Round {{  submit.match_result?.sport_match?.round }}</p>
                                                        <span class="text-sm">•</span>
                                                        <p class=" text-sm">{{  submit.match_result?.sport_match?.game }}</p>
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
                                                            Submitted by : {{ submit.facilitator?.student?.first_name }} {{ submit.facilitator?.student?.last_name }}
                                                        </p>
                                                        <span class="text-xs text-gray-500">•</span>
                                                        <p class="text-xs text-gray-500">
                                                            Validated by : {{ submit.official_name }}
                                                        </p>
                                                    </div>
                                                    <button 
                                                        @click="openSignature(submit.signature)"
                                                        class="mt-2 py-1.5 px-2.5 rounded-full bg-blue-100 text-xs hover:bg-blue-200  text-blue-600 hover:text-blue-800 font-medium"
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
                                            <div class="flex items-center text-gray-600 gap-2 ">
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
                                                    Submitted by : {{ submit.facilitator?.student?.first_name }} {{ submit.facilitator?.student?.last_name }}
                                                </p>
                                                <span class="text-xs text-gray-500">•</span>
                                                <p class="text-xs text-gray-500">
                                                    Validated by : {{ submit.official_name }}
                                                </p>
                                            </div>
                                            <button 
                                                @click="openSignature(submit.signature)"
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
            <!-- Signature Modal -->
            <div v-if="showSignature" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full mx-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Official Signature</h3>
                        <button 
                            @click="closeSignature"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex justify-center">
                        <img 
                            :src="selectedSignature" 
                            alt="Official Signature" 
                            class="max-w-full max-h-[300px] object-contain border border-gray-200 rounded-lg bg-white p-2"
                        />
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<script setup>
    import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
    import { ref, computed, onMounted, watch, watchEffect } from 'vue';
    import AppLayout from '@/Layout/DashboardLayout.vue';
    import { route } from 'ziggy-js';
    import PalakasanRankings from '@/Components/PalakasanStandings.vue'; 
    import Graph from '@/Components/BarGraph.vue';  // Adjust the path as necessary
    import Toast from '@/Components/Toast.vue';  // Ad/just the import path as needed
    import HowToStartModal from '@/Components/HowToStart.vue';
    import Settings from '@/Components/Settings.vue';
    import OverallSchedules from '@/Components/OverallSchedules.vue';
    

    const props = defineProps({
        colleges: Array,
        sports: Array,
        palakasans: Array,      // Palakasans data array
        thisPalakasan: Array,
        assignedTeams: Array,   // Teams data array
        assignedSports: Array,  
        overallResult: Array,  
        variationResult: Array,  
        facilitator: Array,
        matchResults: Array,
        matchRankings: Array,
        facilitatorRankSubmits: Array,
        facilitatorSubmits: Array,
        ffofacilitatorSubmits: Array,
        admin: Array,
        sportMatches: Array,  // Add this line
        latestPalakasan: {
            type: Object,
            default: () => ({})
        },
        initialActiveTab: {
            type: String,
            default: 'details'
        }
    });

    const isPalakasanModalOpen = ref(false);
    const isSportsModalOpen = ref(false);
    const isTeamsModalOpen = ref(false);
    const isStatusModalOpen = ref(false);
    const isHistoryModalOpen = ref(false);
    const toastRef = ref(null);
    const page = usePage();
    const showSignature = ref(false);
    const selectedSignature = ref(null);
    const showTooltip = ref(false);
    const openSignature = (signature) => {
        selectedSignature.value = signature;
        showSignature.value = true;
    };

    const closeSignature = () => {
        showSignature.value = false;
        selectedSignature.value = null;
    };

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
                preserveState: false,
                onSuccess: () => {
                    console.log('Palakasan and sports updated successfully');
                    showModal.value = false;
                    router.visit(window.location.href, {
                        preserveScroll: true,
                        preserveState: false,
                        replace: true
                    });
                },
                onError: (errors) => {
                    console.error('Failed to update Palakasan:', errors);
                }
            });

        } catch (error) {
            console.error('Failed to start Palakasan:', error);
        }
    };

    const concludePalakasan = (palakasanId) => {
        router.put(`/palakasan/${palakasanId}/update-status`, {
            status: 'completed'
        }, {
            preserveScroll: true,
            onSuccess: () => {
            console.log('Palakasan concluded successfully')
            },
            onError: (errors) => {
            // Optional: Handle errors
            }
        })
    }

    const cancelPalakasan = async () => {
        // Show confirmation dialog
        const confirmed = await Swal.fire({
            title: 'Cancel Palakasan?',
            text: 'Are you sure you want to cancel this Palakasan? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, cancel it',
            cancelButtonText: 'No, keep it'
        });

        if (confirmed.isConfirmed) {
            try {
                // Make API call to cancel Palakasan
                await axios.patch(`/api/palakasan/${latestPalakasan.value.id}/cancel`);
                
                // Show success message
                toastRef.value.showToast('Palakasan has been cancelled successfully', 'success');
                
                // Refresh the page to show updated status
                window.location.reload();
            } catch (error) {
                console.error('Error cancelling Palakasan:', error);
                toastRef.value.showToast('Failed to cancel Palakasan', 'error');
            }
        }
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
                router.visit(route('palakasan.details'), {
                    preserveScroll: true,
                    preserveState: false,
                    replace: true
                });
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
        let filteredSports = props.assignedSports;

        // Filter sports based on search query
        if (searchQuerySports.value) {
            filteredSports = filteredSports.filter(sport =>
                sport.sport?.name.toLowerCase().includes(searchQuerySports.value.toLowerCase()) ||
                sport.description.toLowerCase().includes(searchQuerySports.value.toLowerCase())
            );
        }

        // Sort sports based on sort order
        if (sortOrderSports.value === 'az') {
            return filteredSports.sort((a, b) =>
                a.sport?.name.localeCompare(b.sport?.name)
            );
        } else if (sortOrderSports.value === 'za') {
            return filteredSports.sort((a, b) =>
                b.sport?.name.localeCompare(a.sport?.name)
            );
        }

        // Default sort order (as per the original assignedSports array)
        return filteredSports;
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
        return 'text-red-500 bg-red-100';
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
    return props.palakasans.filter(palakasan => palakasan.status === 'completed');
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
                return 'Higher point value destribution';
            case 'Minor':
                return 'Lower point value destribution';
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
</style>
