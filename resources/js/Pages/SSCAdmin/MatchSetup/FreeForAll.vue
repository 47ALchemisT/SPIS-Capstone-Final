<template>
  <div>
    <Head :title="sport.sport.name" />
    <AppLayout>
      <template v-slot:default>
        <!-- Header Section -->
        <div class="flex flex-col">
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

                <div class="flex items-center text-gray-600 gap-2 capitalize">
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
        </div>
        <div class="w-full pt-4">
          <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b">
              <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                  <h3 class="text-lg font-semibold text-gray-900">
                    {{ progressPercentage < 100 ? 'Initial Rankings' : 'Final Rankings' }}
                  </h3>
                  <span 
                    v-if="progressPercentage < 100" 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                  >
                    In Progress
                  </span>
                </div>
                
                <div class="flex items-center gap-2">
                  <button
                    type="button"
                    class="inline-flex items-center p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                    title="More Information"
                  >
                    <svg 
                      class="w-5 h-5" 
                      fill="none" 
                      viewBox="0 0 24 24" 
                      stroke="currentColor"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                      Rank
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Team
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      College
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Points
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr
                    v-for="(entry, index) in rankedTeams"
                    :key="entry.teamId"
                    class="hover:bg-gray-50 transition-colors duration-150"
                  >
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                      <div
                        v-if="rankedTeams[index]"
                        :class="[ 
                          'inline-flex items-center justify-center h-8 w-8 rounded-full font-semibold text-sm',
                          rankedTeams[index].rank === 1 ? 'bg-yellow-100 text-yellow-800 ring-2 ring-yellow-200' :
                          rankedTeams[index].rank === 2 ? 'bg-gray-100 text-gray-800 ring-2 ring-gray-200' :
                          'bg-orange-100 text-orange-800 ring-2 ring-orange-200'
                        ]"
                      >
                        {{ rankedTeams[index].rank }}
                      </div>
                      <span v-else class="text-sm text-gray-600">
                        {{ rankedTeams[index]?.rank || 'Unranked' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ entry.teamName }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-600">{{ entry.collegeName }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <div class="text-sm font-medium text-gray-900">{{ entry.points }}</div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <nav class="flex relative justify-between mt-4  items-center">
          <div class="flex gap-2 rounded-lg ">
              <div class=" flex gap-2 rounded-lg">
                  <button 
                      v-for="tab in ['sports', 'players']"
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
                  @click="showModal = true"                 
                  type="button"
                  :disabled="sport?.status !== 'pending'"
                  class="flex items-center gap-2 text-white bg-blue-700 font-medium hover:bg-blue-700/90 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 disabled:bg-blue-100 disabled:text-blue-700"
                  >
                  Add Variation
                </button>
                <button 
                    v-if="sport.status === 'pending'"
                    type="button"
                    class="flex items-center gap-2 font-medium text-white bg-blue-700 hover:bg-blue-700/90 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 disabled:bg-blue-100 disabled:text-blue-700"
                    :disabled="!allMatchesScheduled || sportVariations.length === 0"
                    @click="handleScheduleClick"
                >
                    {{ sportVariations.length === 0 ? 'No Sports Available' : (allMatchesScheduled ? 'Save Schedule' : 'Set All Schedules First') }}
                </button>
              </div>
        </nav>
        
        <div v-if="sport.status === 'pending'" class="mt-4 p-6 rounded-lg bg-blue-50 text-sm text-center text-blue-700">
            <i class="fa-solid fa-circle-info mr-2"></i>
          <span class="font-medium">Important:</span> All matches must be scheduled with date and time before the sport can be marked as scheduled, this is required to start the Palakasan.
        </div>

        <div class="mt-4">
          <div v-if="activeTab === 'sports'">
            <!-- Sport Variations List -->
            <div class=" mb-6">
              <div v-if="sportVariations.length === 0" class="text-gray-500">
                No variations added yet.
              </div>
              <ul v-else class="space-y-4">
                <li v-for="variation in sportVariations" :key="variation.id" 
                  class="pb-4 p-4 rounded-lg"
                  :class="{ 
                    'bg-blue-50 border border-blue-400': variation.status?.toLowerCase() === 'completed',
                    'border border-gray-300': variation.status?.toLowerCase() !== 'completed'
                  }">
                  <div class="flex justify-between items-center">
                    <div class="flex items-center justify-between w-full gap-2">
                      <div class="flex gap-2 w-full">
                        <div>
                          <button v-if="variation.status?.toLowerCase() !== 'completed'"
                            @click="openUpdateTimeModal(variation)"
                            type="button" 
                            class="p-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                          >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M21 12a9 9 0 1 0-9.972 8.948q.48.051.972.052"/><path d="M12 7v5l2 2m4.42 1.61a2.1 2.1 0 0 1 2.97 2.97L18 22h-3v-3z"/></g></svg>
                          </button>
                        </div>
                        <div>
                          <h3 class="text-md font-semibold">{{ variation.sport_variation_name }}</h3>
                          <div class="flex items-center mt-1 gap-2.5">
                            <div class="text-xs text-gray-600">
                              <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ getVenueName(variation.sport_variation_venue_id) }}</span>
                              </div>
                            </div>
                            <p class="text-xs text-gray-400">|</p>
                            <div class="text-xs text-gray-600">
                              <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span :class="{'text-red-500': !variation.date || !variation.time}">
                                  {{ formatDateTime(variation.date, variation.time) }}
                                </span>
                              </div>
                            </div>
                            <p class="text-xs text-gray-400">|</p>
                            <div class="text-xs flex justify-between gap-1.5 items-center">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0m5 0v.01m4-.01v.01m4-.01v.01"/></svg>            
                              <span :class="getStatusClass(variation.status)">
                                {{ formatStatus(variation.status) }}
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="flex items-center gap-2">
                        <!-- Delete Button -->
                        <button
                          @click="openDeleteModal(variation.id)"
                          v-if="sport.status !== 'Ongoing'"
                          type="button" 
                          class="py-2 px-3 text-sm font-medium text-gray-500 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-red-600 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                        >
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Variation Matches -->
                  <div v-if="getVariationMatches(variation.id).length > 0" class="mt-4">
                    <table class="w-full text-sm">
                      <thead>
                        <tr>
                          <th class="px-4 py-2 text-left" :class="{ 'bg-blue-100': variation.status?.toLowerCase() === 'completed' }">Team</th>
                          <th class="px-4 py-2 text-center" :class="{ 'bg-blue-100': variation.status?.toLowerCase() === 'completed' }">Rank</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="match in getSortedMatches(getVariationMatches(variation.id))" 
                            :key="match.id" 
                            class="border-b">
                          <td class="px-4 py-2">{{ getTeamName(match.sport_variation_team_id) }}</td>
                          <td class="px-4 py-2 text-center">{{ match.rank || '--' }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div v-if="activeTab === 'players'">
            <PlayersDisplay class="mt-1" :players="players" :teams="teams" />
          </div>
        </div>


        <!-- Sport Variation Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold">Add Sport Variation</h2>
              <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-times"></i>
              </button>
            </div>

            <form @submit.prevent="submitForm" class="space-y-4">
              <!-- Sport Name (Readonly) -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Sport</label>
                <input 
                  type="text" 
                  :value="sport.sport.name" 
                  readonly
                  class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md"
                >
              </div>

              <!-- Variation Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Variation Name</label>
                <input 
                  v-model="form.sport_variation_name"
                  type="text" 
                  required
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': form.errors.sport_variation_name }"
                >
                <p v-if="form.errors.sport_variation_name" class="mt-1 text-sm text-red-600">
                  {{ form.errors.sport_variation_name }}
                </p>
              </div>

              <!-- Venue Selection -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Venue</label>
                <select 
                  v-model="form.sport_variation_venue_id"
                  required
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': form.errors.venue_id }"
                >
                  <option value="" disabled>Select a venue</option>
                  <option v-for="venue in venues" :key="venue.id" :value="venue.id">
                    {{ venue.name }}
                  </option>
                </select>
                <p v-if="form.errors.venue_id" class="mt-1 text-sm text-red-600">
                  {{ form.errors.venue_id }}
                </p>
              </div>

              <!-- Modal Buttons -->
              <div class="flex justify-end gap-2">
                <button 
                  type="button"
                  @click="closeModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                >
                  Cancel
                </button>
                <button 
                  type="submit"
                  :disabled="form.processing"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50"
                >
                  {{ form.processing ? 'Saving...' : 'Save' }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Update Time Modal -->
        <TransitionRoot appear :show="showUpdateTimeModal" as="template">
          <Dialog as="div" @close="closeUpdateTimeModal" class="relative z-50">
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
              <div class="flex min-h-full items-center justify-center p-4 text-center">
                <TransitionChild
                  as="template"
                  enter="duration-300 ease-out"
                  enter-from="opacity-0 scale-95"
                  enter-to="opacity-100 scale-100"
                  leave="duration-200 ease-in"
                  leave-from="opacity-100 scale-100"
                  leave-to="opacity-0 scale-95"
                >
                  <DialogPanel class="relative w-full max-w-[30rem] transform overflow-hidden rounded-lg bg-white text-left align-middle shadow-xl transition-all">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 border-b">
                      <DialogTitle as="h3" class="text-lg font-semibold text-gray-900">
                        Update Schedule
                      </DialogTitle>
                      <button @click="closeUpdateTimeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                      </button>
                    </div>

                    <!-- Modal body -->
                    <div class="p-4 pt-0">
                      <div class="text-xs p-3 bg-blue-50 text-blue-700 rounded-lg border mb-3 border-blue-400">
                        <h1 class="font-semibold mb-1">Note</h1>
                        <p>All available times can be selected. Times that are not available will be grayed out. This is first come first serve, so make sure to set the time and venue correctly.</p>
                      </div>

                      <!-- Venue Selection -->
                      <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                          Select Venue
                        </label>
                        <select 
                          v-model="updateTimeForm.sport_variation_venue_id"
                          @change="updateAvailableTimeSlots"
                          required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                          <option value="" disabled>Select a venue</option>
                          <option v-for="venue in venues" :key="venue.id" :value="venue.id">
                            {{ venue.name }}
                          </option>
                        </select>
                      </div>

                      <!-- Date Selection -->
                      <label class="block text-sm font-medium text-gray-900 mb-2">
                        Pick the Date and Time
                      </label>
                      <div class="mx-auto sm:mx-0 flex justify-center mb-5">
                        <input
                          type="date"
                          v-model="updateTimeForm.date"
                          :min="getCurrentDate()"
                          @change="updateAvailableTimeSlots"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                      </div>

                      <!-- Time Input Toggle -->
                      <div class="mb-4">
                        <div class=" mb-4">
                          <div class="grid grid-cols-2 items-center gap-4">
                            <label class="inline-flex items-center p-2.5 ring-1 ring-gray-200 rounded-md">
                              <input 
                                type="radio" 
                                v-model="timeInputType" 
                                value="slot" 
                                class="form-radio"
                              >
                              <span class="ml-2 text-sm">Select time slot</span>
                            </label>
                            <label class="inline-flex items-center p-2.5 ring-1 ring-gray-200 rounded-md">
                              <input 
                                type="radio" 
                                v-model="timeInputType" 
                                value="custom" 
                                class="form-radio"
                              >
                              <span class="ml-2 text-sm">Enter custom time</span>
                            </label>
                          </div>
                        </div>

                        <!-- Time Slots Selection -->
                        <div v-if="timeInputType === 'slot'" class="grid grid-cols-4 gap-2 mb-4">
                          <button
                            v-for="slot in availableTimeSlots"
                            :key="slot.value"
                            @click="!slot.disabled && (updateTimeForm.time = slot.value)"
                            :class="[
                              'px-3 py-2 text-sm rounded-md',
                              updateTimeForm.time === slot.value
                                ? 'bg-blue-600 text-white'
                                : 'border border-gray-200 text-gray-700 hover:bg-blue-100 hover:text-blue-500 hover:border-blue-300',
                              { 'opacity-50 bg-gray-200 cursor-not-allowed': slot.disabled }
                            ]"
                            type="button"
                            :disabled="slot.disabled"
                          >
                            {{ slot.label }}
                          </button>
                        </div>

                        <!-- Custom Time Input -->
                        <div v-else class="flex items-center gap-2">
                          <input 
                            type="time" 
                            v-model="customTime"
                            class="px-3 py-2 border w-full border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            @input="updateScheduleTime"
                          >
                        </div>
                      </div>

                      <div v-if="formError" class="mb-4 text-red-500 text-sm">{{ formError }}</div>

                      <!-- Action Buttons -->
                      <div class="grid grid-cols-2 gap-2">
                        <button 
                          @click="closeUpdateTimeModal" 
                          type="button" 
                          :disabled="isProcessing"
                          class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 disabled:opacity-50"
                        >
                          Discard
                        </button>
                        <button 
                          @click="updateTime" 
                          type="button"
                          :disabled="isProcessing"
                          class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 disabled:opacity-50"
                        >
                          {{ isProcessing ? 'Saving...' : 'Save' }}
                        </button>
                      </div>
                    </div>
                  </DialogPanel>
                </TransitionChild>
              </div>
            </div>
          </Dialog>
        </TransitionRoot>

        <div v-if="showRankModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="relative p-4 w-full max-w-[32rem] max-h-full">
            <div class="relative bg-white rounded-lg shadow">
              <div class="flex items-center justify-between p-4 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">
                  Set Rankings and Points
                </h3>
                <button @click="closeRankModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center">
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                  </svg>
                </button>
              </div>
              
              <div class="p-4 pt-0">
                <form @submit.prevent="updateRanks">
                  <div class="mt-4">
                    <table class="w-full text-sm">
                      <thead>
                        <tr>
                          <th class="px-4 py-2 font-medium text-left">Team</th>
                          <th class="px-4 py-2 font-medium text-left">Rank</th>
                          <th class="px-4 py-2 font-medium text-left">Points</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="match in sortedMatches" :key="match.id">
                          <td class="px-4 py-2 w-72">{{ getTeamName(match.sport_variation_team_id) }}</td>
                          <td class="px-4 py-2 w-44">
                            <select 
                              v-model="rankUpdates[match.id].rank"
                              class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                              <option value="" disabled>Select rank</option>
                              <option 
                                v-for="rank in availableRanks(match.id)" 
                                :key="rank"
                                :value="rank"
                              >
                                {{ formatRank(rank) }}
                              </option>
                            </select>
                          </td>
                          <td class="px-4 py-2 w-12">
                            <input 
                              type="number"
                              v-model="rankUpdates[match.id].points"
                              min="0"
                              class=" px-2 py-1 border w-20 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Enter points"
                            />
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div v-if="rankUpdateError" class="mt-4 text-red-500 text-sm">{{ rankUpdateError }}</div>

                  <div class="grid grid-cols-2 gap-2 mt-4">
                    <button 
                      type="button"
                      @click="closeRankModal"
                      class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100"
                    >
                      Cancel
                    </button>
                    <button 
                      type="submit"
                      :disabled="!isValidRankSelection"
                      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 disabled:opacity-50"
                    >
                      {{ rankUpdateForm.processing ? 'Saving...' : 'Save' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- Delete Modal -->
        <div
          v-if="isDeleteModalOpen"
          class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
          >
          <div class="bg-white rounded-lg p-6 w-96 shadow-lg dark:bg-gray-800">
            <div>
              <i
                class="fa-solid fa-circle-exclamation text-red-500 text-2xl py-4 px-5 bg-red-100 rounded-full mb-5"
              ></i>
              <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Confirm Deletion
              </h2>
            </div>

            <p class="text-sm text-gray-600 mt-2 dark:text-gray-300">
              Are you sure you want to delete this item? This action cannot be undone.
            </p>

            <div class="flex justify-end gap-3 mt-6">
              <button
                @click="closeDeleteModal"
                class="py-2 px-4 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                :disabled="isDeleting"
              >
                Cancel
              </button>
              <button
                @click="confirmDelete"
                class="py-2 px-4 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 dark:focus:ring-red-800 flex items-center justify-center gap-2"
                :disabled="isDeleting"
              >
                <template v-if="isDeleting">
                  <svg
                    class="animate-spin h-5 w-5 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <circle
                      class="opacity-25"
                      cx="12"
                      cy="12"
                      r="10"
                      stroke="currentColor"
                      stroke-width="4"
                    ></circle>
                    <path
                      class="opacity-75"
                      fill="currentColor"
                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                    ></path>
                  </svg>
                  Deleting...
                </template>
                <span v-else>Delete</span>
              </button>
            </div>
          </div>
        </div>
      </template>
    </AppLayout>
    <SuccessModal
        :show="showSuccessModal"
        :message="successMessage"
        @close="showSuccessModal = false"
     />
  </div>
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayout.vue';
import PlayersDisplay from '@/Components/PlayersDisplay.vue';
import SuccessModal from '@/Components/SuccessModal.vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

const props = defineProps({
  sport: {
    type: [Array, Object],
    required: true
  },
  points: {
    type: [Array, Object],
    required: true
  },
  tournaments: {
    type: Array,
    default: () => []
  },
  venues: {
    type: Array,
    required: true
  },
  teams: {
    type: Array,
    default: () => []
  },
  allMatches: {
    type: Array,
    default: () => []
  },
  sportVariations: {
    type: Array,
    default: () => []
  },
  sportVariationMatches: {
    type: Array,
    default: () => []
  },
  variationMatches: {
    type: Array,
    default: () => []
  },
  venueRecords: {
    type: Array,
    default: () => []
  },
  players: Array,
  latestPalakasan: {
    type: Object,
    default: () => ({})
  },

});
console.log('Sport:', props.sport);
const showSuccessModal = ref(false);
const successMessage = ref('');

const showModal = ref(false);
const errorMessage = ref('');
const showRankModal = ref(false);
const selectedVariationMatches = ref([]);
const rankUpdateError = ref('');
const rankUpdates = ref({});
const showUpdateTimeModal = ref(false);
const selectedVariation = ref(null);
const activeTab = ref('sports');
const timeSelectionType = ref('select'); // Default to selecting from available times
const totalMatches = computed(() => props.sportVariations.length);
const completedMatches = computed(() => props.sportVariations.filter(variation => variation.status === 'completed').length);
const progressPercentage = computed(() => totalMatches.value > 0 ? (completedMatches.value / totalMatches.value) * 100 : 0);
const selectedTime = ref(''); // Reactive variable for selected time
const selectedDate = ref('');
const allMatchesScheduled = computed(() => {
  return props.sportVariations.every(variation => 
    variation.date && variation.time && variation.sport_variation_venue_id
  );
});

const isDeleteModalOpen = ref(false);
const isDeleting = ref(false);
const variationIdToDelete = ref(null);

const openDeleteModal = (variationId) => {
  variationIdToDelete.value = variationId;
  isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
  isDeleteModalOpen.value = false;
  variationIdToDelete.value = null;
  isDeleting.value = false; // Reset in case of reopening
};

const confirmDelete = () => {
  isDeleting.value = true; // Set to true before making the request

  router.delete(route('variation.destroy', variationIdToDelete.value), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                closeDeleteModal();
                showSuccessModal.value = true;
                successMessage.value = 'Variation deleted successfully!';
            },
            onError: () => {
                // Handle errors if needed
                showSuccessModal.value = false;
                closeDeleteModal();
                isDeleting.value = false; // Reset after error
            }
        });
};

const pointsType = computed(() => {
  return props.sport.type === 'Major' ? props.majorPoints : props.minorPoints;
});

const getPoints = (index) => {
  if (!pointsType.value || !Array.isArray(pointsType.value)) {
    return 'No Points';
  }
  return pointsType.value[index]?.points ?? 'No Points';
};

console.log('Points Type:', pointsType.value);

// Calculate accumulated points with separated team and college information
const accumulatedPoints = computed(() => {
  return props.variationMatches.reduce((acc, match) => {
    const team = match.assigned_team_variation_i_d;
    const teamId = team?.id || `unknown-${Math.random()}`; // Unique identifier for each team
    const teamName = team?.assigned_team_name || 'Unknown Team';
    const collegeName = team?.college?.name || 'Unknown College';
    const points = match.points || 0;

    if (!acc[teamId]) {
      acc[teamId] = {
        teamId,
        teamName,
        collegeName,
        points: 0
      };
    }

    acc[teamId].points += points;
    return acc;
  }, {});
});

const rankedTeams = computed(() => {
  const teams = Object.values(accumulatedPoints.value);

  // Sort teams by points in descending order
  teams.sort((a, b) => b.points - a.points);

  let currentRank = 1;
  let previousPoints = null;
  let skippedRanks = 0; // To account for ties

  return teams.map((team, index) => {
    if (team.points === previousPoints) {
      // If points are tied, assign the same rank as the previous team
      skippedRanks++;
    } else {
      // If points are not tied, calculate rank based on the index and skippedRanks
      currentRank = index + 1;
      currentRank -= skippedRanks; // Adjust rank based on skipped ranks
      skippedRanks = 0; // Reset skipped ranks for the next group
    }

    previousPoints = team.points;

    // Assign the rank to the team
    return { ...team, rank: currentRank };
  });
});


// Sort teams by points and convert to array for easier template rendering
const sortedTeams = computed(() => {
  return Object.values(accumulatedPoints.value)
    .sort((a, b) => b.points - a.points);
});

const isAnyFieldFilled = computed(() => {
  return Object.values(rankUpdates.value).some(match => 
    match.rank !== '' || match.points !== ''
  );
});

const setPoints = (matchId) => {
  const rank = rankUpdates.value[matchId].rank;
  const pointsMapping = {
    "1": 20, 
    "2": 18,  
    "3": 16,  
    "4": 14,
    "5": 12,
    "6": 10,
    "0": 0,
  };

  rankUpdates.value[matchId].points = pointsMapping[rank] || 0; 
};


const handleScheduleClick = async () => {
  try {
    await router.patch(route('assigned-sports.update-admin-status', props.sport.id), {
      status: 'scheduled'
    });
    // Update local state
    props.sport.status = 'scheduled';
    showSuccessModal.value = true;
    successMessage.value = 'Successfully scheduled the matches!';
  } catch (error) {
    console.error('Failed to update sport status:', error);
  }
};

const updateTimeForm = useForm({
  sport_variation_id: '',
  sport_variation_venue_id: '',
  date: '',
  time: '',
});

const rankUpdateForm = useForm({
  matches: [],
});

const form = useForm({
  assigned_sport_id: props.sport.id,
  sport_variation_name: '',
  sport_variation_venue_id: '',
  date: '',
  time: '',
  status: 'pending',
});

onMounted(() => {
  form.assigned_sport_id = props.sport.id;
});

const closeModal = () => {
  showModal.value = false;
  errorMessage.value = '';
  form.reset();
  form.clearErrors();
};

const submitForm = async () => {
  try {
    await form.post(route('sport-variations.store'), {
      onSuccess: () => {
        form.reset();
        showModal.value = false;
        errorMessage.value = '';
        showSuccessModal.value = true;
        successMessage.value = 'Successfully created a Sport Variation!';
      },
      onError: (errors) => {
        console.error('Validation errors:', errors);
        errorMessage.value = 'Please correct the errors below.';
      },
      onFinish: () => {
        form.processing = false;
      }
    });
  } catch (error) {
    console.error('Submission error:', error);
    errorMessage.value = 'An unexpected error occurred. Please try again.';
    form.processing = false;
  }
};

const returnToPalakasan = () => {
  router.get(route('palakasan.details', { tab: 'leagues' }));
};

const getVenueName = (venueId) => {
  if (!venueId) return 'No venue assigned';
  const venue = props.venues.find(v => v.id === venueId);
  return venue ? venue.name : 'Unknown venue';
};

const getTeamName = (teamId) => {
  const team = props.teams.find((t) => t.id === teamId && t.palakasan_id === props.sport.palakasan_sport_id);
  return team ? team.assigned_team_name : 'Unknown team';
};

// Add a new computed property to filter teams for the current sport
const currentSportTeams = computed(() => {
  return props.teams.filter(team => team.palakasan_id === props.sport.palakasan_sport_id);
});

const openUpdateTimeModal = (variation) => {
  selectedVariation.value = variation;
  updateTimeForm.sport_variation_id = variation.id;
  updateTimeForm.sport_variation_venue_id = variation.sport_variation_venue_id;
  updateTimeForm.date = variation.date;
  updateTimeForm.time = variation.time;
  showUpdateTimeModal.value = true;
  updateAvailableTimeSlots();
};

const closeUpdateTimeModal = () => {
  showUpdateTimeModal.value = false;
  selectedVariation.value = null;
  selectedDate.value = '';
  selectedTime.value = '';
  updateTimeForm.reset();
  updateTimeForm.clearErrors();
  errorMessage.value = '';
};

const updateTime = async () => {
  if (!isValidTimeSelection.value) {
    errorMessage.value = 'Please select an available time slot.';
    return;
  }

  try {
    await updateTimeForm.patch(route('sport-variations.update-time', selectedVariation.value.id), {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        closeUpdateTimeModal();
        showSuccessModal.value = true;
        successMessage.value = 'Successfully scheduled the time!';
      },
      onError: (errors) => {
        console.error('Update time errors:', errors);
        errorMessage.value = 'Please correct the errors and try again.';
      },
    });
  } catch (error) {
    console.error('Update time error:', error);
    errorMessage.value = 'An unexpected error occurred. Please try again.';
  }
};

const formatTo12Hour = (time) => {
  const [hours, minutes] = time.split(':');
  const hour = parseInt(hours);
  const period = hour >= 12 ? 'PM' : 'AM';
  const formattedHour = hour % 12 || 12; // Convert 0 to 12 for 12 AM
  return `${formattedHour}:${minutes} ${period}`;
};


  const isValidRankSelection = computed(() => {
    const selectedMatches = Object.values(rankUpdates.value);
    const allFieldsFilled = selectedMatches.every(match => 
      match.rank !== '' && match.points !== ''
    );
    const selectedRanks = new Set(selectedMatches.map(match => match.rank).filter(rank => rank !== ''));
    return allFieldsFilled && selectedRanks.size === selectedVariationMatches.value.length;
  });


const formatDateTime = (date, time) => {
  if (!date && !time) return 'Not scheduled';
  if (!date) return `Time: ${time}`;
  if (!time) return `Date: ${formatDate(date)}`;
  return `${formatDate(date)} at ${time}`;
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  });
};

const formatStatus = (status) => {
  if (!status) return 'Pending';
  return status.charAt(0).toUpperCase() + status.slice(1);
};

const getStatusClass = (status) => {
  const statusClasses = {
    pending: 'text-yellow-600',
    ongoing: 'text-green-600',
    completed: 'text-blue-600',
    cancelled: 'text-red-600'
  };
  return statusClasses[status?.toLowerCase()] || 'text-gray-600';
};

const formatRank = (rank) => {
  const suffixes = { 1: 'st', 2: 'nd', 3: 'rd' };
  const suffix = suffixes[rank] || 'th';
  return `${rank}${suffix} place`;
};

const getSortedMatches = (matches) => {
  return [...matches].sort((a, b) => {
    if (a.rank && b.rank) return a.rank - b.rank;
    if (a.rank) return -1;
    if (b.rank) return 1;
    return 0;
  });
};

const getVariationMatches = (variationId) => {
  return props.sportVariationMatches
    .filter(match => match.sport_variation_id === variationId)
    .filter(match => currentSportTeams.value.some(team => team.id === match.sport_variation_team_id));
};

// Update the availableRanks function
const availableRanks = (matchId) => {
  const currentRanks = new Set();
  
  // Collect all currently selected ranks except for the current match
  Object.entries(rankUpdates.value).forEach(([id, data]) => {
    if (id !== matchId.toString() && data.rank) {
      currentRanks.add(data.rank.toString());
    }
  });
  
  // Generate all possible ranks
  const allRanks = Array.from(
    { length: selectedVariationMatches.value.length }, 
    (_, i) => (i + 1).toString()
  );
  
  // Return ranks that are either unselected or currently selected by this match
  return allRanks.filter(rank => 
    !currentRanks.has(rank) || 
    rankUpdates.value[matchId]?.rank === rank
  );
};

const getCurrentDate = () => {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
};

const isValidTimeSelection = computed(() => {
  return updateTimeForm.sport_variation_venue_id && 
         updateTimeForm.date && 
         updateTimeForm.time && 
         !availableTimeSlots.value.find(slot => slot.value === updateTimeForm.time)?.disabled;
});

const availableTimeSlots = ref([
  { value: '6:00 AM', label: '6:00 AM', disabled: false  },
  { value: '6:30 AM', label: '6:30 AM', disabled: false  },
  { value: '7:00 AM', label: '7:00 AM', disabled: false  },
  { value: '7:30 AM', label: '7:30 AM', disabled: false  },
  { value: '8:00 AM', label: '8:00 AM', disabled: false  },
  { value: '8:30 AM', label: '8:30 AM', disabled: false  },
  { value: '9:00 AM', label: '9:00 AM', disabled: false  },
  { value: '9:30 AM', label: '9:30 AM', disabled: false  },
  { value: '10:00 AM', label: '10:00 AM', disabled: false  },
  { value: '10:30 AM', label: '10:30 AM', disabled: false  },
  { value: '11:00 AM', label: '11:00 AM', disabled: false  },
  { value: '11:30 AM', label: '11:30 AM', disabled: false  },
  { value: '12:00 PM', label: '12:00 PM', disabled: false  },
  { value: '1:00 PM', label: '1:00 PM', disabled: false  },
  { value: '1:30 PM', label: '1:30 PM', disabled: false  },
  { value: '2:00 PM', label: '2:00 PM', disabled: false  },
  { value: '2:30 PM', label: '2:30 PM', disabled: false  },
  { value: '3:00 PM', label: '3:00 PM', disabled: false  },
  { value: '3:30 PM', label: '3:30 PM', disabled: false  },
  { value: '4:00 PM', label: '4:00 PM', disabled: false  },
  { value: '4:30 PM', label: '4:30 PM', disabled: false  },
  { value: '5:00 PM', label: '5:00 PM', disabled: false  },
  { value: '5:30 PM', label: '5:30 PM', disabled: false  },
  { value: '6:00 PM', label: '6:00 PM', disabled: false  }
]);

const updateAvailableTimeSlots = () => {
  if (!updateTimeForm.date || !updateTimeForm.sport_variation_venue_id) return;

  console.log('Updating available time slots');
  console.log('Selected Date:', updateTimeForm.date);
  console.log('Selected Venue:', updateTimeForm.sport_variation_venue_id);

  // Check UsedVenueRecord for conflicts
  const conflictingRecords = props.venueRecords.filter(record => 
    record.venue_id === updateTimeForm.sport_variation_venue_id &&
    record.date === updateTimeForm.date &&
    (!selectedVariation.value || record.id !== selectedVariation.value.id) // Exclude the current variation if editing
  );

  console.log('Conflicting Records:', conflictingRecords);

  // Create a Set of used time slots for quick lookup
  const usedTimeSlots = new Set(conflictingRecords.map(record => record.time));

  console.log('Used Time Slots:', usedTimeSlots);

  // Update available time slots
  availableTimeSlots.value = availableTimeSlots.value.map(slot => ({
    ...slot,
    disabled: usedTimeSlots.has(slot.value)
  }));

  // If editing and the current time is selected, make it available
  if (selectedVariation.value && selectedVariation.value.time && selectedVariation.value.date === updateTimeForm.date) {
    const currentTimeSlot = availableTimeSlots.value.find(slot => 
      slot.value === selectedVariation.value.time
    );
    if (currentTimeSlot) {
      currentTimeSlot.disabled = false;
    }
  }

  console.log('Updated available time slots:', availableTimeSlots.value);

  // Reset the selected time if it's now disabled
  if (updateTimeForm.time && availableTimeSlots.value.find(slot => slot.value === updateTimeForm.time)?.disabled) {
    updateTimeForm.time = '';
  }
};

const sortedMatches = computed(() => {
  return [...selectedVariationMatches.value].sort((a, b) => {
    const rankA = rankUpdates.value[a.id]?.rank || Infinity;
    const rankB = rankUpdates.value[b.id]?.rank || Infinity;
    return rankA - rankB;
  });
});
watch(
  () => rankUpdates.value,
  () => {
    // The computed property will automatically update the sort
  },
  { deep: true }
);

const updateAssignedSportStatus = async () => {
  if (progressPercentage.value === 100) {
    try {
      await router.patch(route('assigned-sports.update-status', props.sport.id), {
        status: 'completed'
      });
      // Optionally, you can update the local state here if needed
      props.sport.status = 'completed';
    } catch (error) {
      console.error('Failed to update assigned sport status:', error);
    }
  }
};

watch(() => updateTimeForm.date, updateAvailableTimeSlots);
watch(() => updateTimeForm.sport_variation_venue_id, updateAvailableTimeSlots);
watch(progressPercentage, (newValue) => {
  if (newValue === 100) {
    updateAssignedSportStatus();
  }
});

const timeInputType = ref('slot'); // To toggle between slot and custom input
const customTime = ref(''); // For custom time input
const customTimePeriod = ref('AM'); // For AM/PM selection

const updateScheduleTime = () => {
  if (timeInputType.value === 'custom' && customTime.value) {
    const [hours, minutes] = customTime.value.split(':');
    let formattedHours = parseInt(hours);
    
    // Determine AM/PM
    if (formattedHours >= 12) {
      customTimePeriod.value = 'PM';
      if (formattedHours > 12) {
        formattedHours -= 12; // Convert to 12-hour format
      }
    } else {
      customTimePeriod.value = 'AM';
      if (formattedHours === 0) {
        formattedHours = 12; // Convert 0 to 12 for 12 AM
      }
    }
    
    updateTimeForm.time = `${formattedHours}:${minutes} ${customTimePeriod.value}`;
  }
};

</script>