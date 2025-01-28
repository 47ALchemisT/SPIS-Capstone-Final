<template>
    <Head :title="sport.sport.name" />
    <AppLayout :facilitator="facilitator">
      <template v-slot:default>
        <!-- Header Section -->
        <div class=" flex items-center justify-between gap-2 pt-4">
          <h1 class="text-2xl font-semibold">{{ sport.sport.name }} {{ sport.categories }}</h1>
          <div class="hidden sm:block flex justify-end mb-2">

                        <button 
                            @click="returnToFacilitator" 
                            type="button" 
                            class="text-white bg-blue-700 font-medium hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-2.5 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            >
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Return
                        </button>
                    </div>
        </div>

        <!-- Tags Section -->
        <div class="flex items-center text-gray-600 gap-2">
            <p class="text-sm"> {{ sport.setup }}</p>
            <span class="text-sm">•</span>
            <p class=" text-sm">{{ sport.type }}</p>
            <span class="text-sm">•</span>
            <p class=" text-sm capitalize">{{ sport.status }}</p>
        </div>

        <!-- Progress Bar -->
        <div class="mt-3 space-y-2">
          <div class="sm:w-1/3 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div 
              class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" 
              :style="{ width: `${progressPercentage}%` }"
            ></div>
          </div>
          <div class="flex items-center text-xs">
            <h2 class="text-gray-600">Sport Progress :</h2>
            <span class="font-medium">{{ progressPercentage.toFixed(0) }}% completed</span>
          </div>

        </div>
        
        <div class="w-full pt-4 grid grid-cols-4 gap-6">
          <!-- Rankings Section -->
          <div class="col-span-3 bg-white border border-gray-200 rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-6 py-4 border-b flex items-center justify-between">
              <div>
                <h3 class="text-xl font-semibold text-gray-900">
                  {{ progressPercentage < 100 ? 'Initial Rankings' : 'Final Rankings' }}
                </h3>
                <span 
                  v-if="progressPercentage < 100" 
                  class="inline-block mt-1 text-xs font-medium bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded"
                >
                  In Progress
                </span>
              </div>
              <div>
                <button
                  v-if="!(progressPercentage < 100 || sport.status === 'completed')"
                  @click="openRankingModal"
                  class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500"
                >
                  <svg 
                    class="w-4 h-4 mr-1 inline-block" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Submit
                </button>
              </div>
            </div>

            <!-- Table -->
            <div class="overflow-hidden">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-600 text-center uppercase">Rank</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-600 text-left uppercase">Team</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-600 text-left uppercase">College</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-600 text-center uppercase">Points</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                  <tr
                    v-for="(entry, index) in sortedTeams"
                    :key="entry.teamId"
                    class="hover:bg-gray-50"
                  >
                    <td class="px-6 py-4 text-center">
                      <div
                        v-if="rankedTeams[index]"
                        class="inline-flex items-center justify-center h-8 w-8 rounded-full text-sm font-semibold"
                        :class="{
                          'bg-yellow-100 text-yellow-800': rankedTeams[index].rank === 1,
                          'bg-gray-100 text-gray-800': rankedTeams[index].rank === 2,
                          'bg-orange-100 text-orange-800': rankedTeams[index].rank > 2
                        }"
                      >
                        {{ rankedTeams[index].rank }}
                      </div>
                      <span v-else class="text-sm text-gray-500">Unranked</span>
                    </td>
                    <td class="px-6 py-4">
                      <div class="text-sm font-medium text-gray-900">{{ entry.teamName }}</div>
                    </td>
                    <td class="px-6 py-4">
                      <div class="text-sm text-gray-600">{{ entry.collegeName }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                      <div class="text-sm font-semibold text-gray-900">{{ entry.points }}</div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pointing System -->
          <div class="col-span-1 bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pointing System</h3>
            <div class="space-y-3">
              <ul class="mt-4 space-y-2 text-sm text-gray-600">
                <li class="grid grid-cols-2">
                  <span>1st Place</span>
                  <span>12 Points</span>
                </li>
                <li class="grid grid-cols-2">
                  <span>2nd Place</span>
                  <span>10 Points</span>
                </li>
                <li class="grid grid-cols-2">
                  <span>3rd Place</span>
                  <span>8 Points</span>
                </li>
                <li class="grid grid-cols-2">
                  <span>4th Place</span>
                  <span>6 Points</span>
                </li>
                <li class="grid grid-cols-2">
                  <span>5th Place</span>
                  <span>4 Points</span>
                </li>
                <li class="grid grid-cols-2">
                  <span>6th Place</span>
                  <span>2 Points</span>
                </li>
                <li class="grid grid-cols-2">
                  <span>Non-Finisher</span>
                  <span>1 Point</span>
                </li>
                <li class="grid grid-cols-2">
                  <span>No Player</span>
                  <span>0 Points</span>
                </li>
              </ul>
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
        </nav>

        <div class="mt-4">
          <div v-if="activeTab === 'sports'">
            <!-- Sport Variations List -->
            <div class=" mb-6">
              <div v-if="sportVariations.length === 0" class="text-gray-500">
                No variations added yet.
              </div>
              <ul v-else class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                <li v-for="variation in sportVariations" 
                  :key="variation.id" 
                  class="border p-4 border-gray-300 rounded-lg"
                  :class="{
                    'bg-gray-50': latestPalakasan?.status?.toLowerCase() === 'completed',
                    'bg-green-50 border-green-400': variation.status?.toLowerCase() === 'completed'
                  }">
                  <div class="flex justify-between items-center">
                    <div class="flex flex-col sm:flex-row justify-between gap-3 w-full">
                      <div>
                        <h3 class="text-md font-semibold">{{ variation.sport_variation_name }}</h3>
                        <div class="flex flex-col sm:flex-row sm:items-center mt-1 sm:gap-2.5">
                          <div class="text-xs text-gray-600">
                            <div class="flex items-center gap-1.5">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                              </svg>
                              <span>{{ getVenueName(variation.sport_variation_venue_id) }}</span>
                            </div>
                          </div>
                          <p class="sm:block hidden text-xs text-gray-400">|</p>
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
                        </div>
                      </div>
                      <div>
                        <button 
                          type="button"
                          @click="openRankModal(variation)  || !isMatchTimeReached(variation)" 
                          :disabled="!isRankingAllowed(variation) || variation.status === 'completed' || sport.status === 'completed'"
                          class="bg-blue-700 hover:bg-blue-600 text-white w-full text-sm font-medium py-2.5 px-4 rounded-md disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-700 transition duration-200"
                        >
                          <i class="fa-solid fa-ranking-star mr-2"></i>
                          {{ isRankingAllowed(variation) ? 'Set Rankings' : 'Not Yet Available' }}
                        </button>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Variation Matches -->
                  <div v-if="getVariationMatches(variation.id).length > 0" class="mt-4">
                    <table class="w-full text-sm">
                      <thead>
                        <tr>
                          <th class="px-4 py-2 text-left" :class="{ 'bg-green-100': variation.status?.toLowerCase() === 'completed' }">Team</th>
                          <th class="px-4 py-2 text-left" :class="{ 'bg-green-100': variation.status?.toLowerCase() === 'completed' }">Rank</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="match in getSortedMatches(getVariationMatches(variation.id))" 
                            :key="match.id" 
                            class="border-b">
                          <td class="px-4 py-2 text-left">{{ getTeamName(match.sport_variation_team_id) }}</td>
                          <td class="px-4 py-2 text-left">
                            {{ match.rank === '0' ? 'Disqualified' : (match.rank ? formatRank(match.rank) : '--') }}
                          </td>
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

        <!-- Ranking Modal-->
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
                <div class="mt-4 p-2 bg-gray-100 rounded-md border border-gray-300">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="grid grid-cols-3">
                        <th class="px-4 py-2 font-medium text-left">Team</th>
                        <th class="px-4 py-2 font-medium text-left">Rank</th>
                        <th class="px-4 py-2 font-medium text-left">Points</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="match in sortedMatches" :key="match.id" class="grid grid-cols-3">
                        <td class="px-4 py-2 w-72">{{ getTeamName(match.sport_variation_team_id) }}</td>
                        <td class="px-4 py-2 w-36">
                          <select 
                            v-model="rankUpdates[match.id].rank"
                            @change="setPoints(match.id)" 
                            class="w-full px-2 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          >
                            <option value="">Select</option>                              
                            <option 
                              v-for="rank in availableRanks(match.id)" 
                              :key="rank"
                              :value="rank"
                            >
                              {{ rank === '0' ? 'No Player' : (rank === '10' ? 'Non-Finisher' : formatRank(rank)) }}
                            </option>
                          </select>
                        </td>
                        <td class="px-4 py-2 w-auto ">
                          <input 
                            type="number"
                            v-model="rankUpdates[match.id].points"
                            min="0"
                            class="px-2 py-2 border w-full border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Points"
                            readonly
                          />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-4">
                  <button 
                    type="button"
                    @click="closeRankModal"
                    class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100"
                  >
                    Cancel
                  </button>
                  <button 
                    @click="openValidationModal"
                    :disabled="!isAnyFieldFilled" 
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 disabled:opacity-50"
                  >
                    Validate
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div> 

        <div
          v-if="showRankingModal"
          class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        >
          <div class="relative p-4 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="relative bg-white rounded-lg shadow">
              <!-- Header -->
              <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">
                  Set Rankings and Points
                </h3>
                <button
                  @click="closeRankingModal"
                  class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg p-2 inline-flex items-center"
                >
                  <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                    />
                  </svg>
                </button>
              </div>

              <!-- Info Section -->
              <div class="p-4 bg-blue-50 border-b">
                <div class="flex items-start space-x-3">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 text-blue-600 mt-0.5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4" />
                    <path d="M12 8h.01" />
                  </svg>
                  <div>
                    <h4 class="font-medium text-blue-900 mb-1">
                      Understanding Points Calculation
                    </h4>
                    <p class="text-sm text-blue-800">
                      Teams are ranked based on their total points, then assigned final
                      points according to their position. This helps normalize scores
                      across different rounds and ensures fair progression in the
                      tournament.
                    </p>
                  </div>
                </div>
              </div>

              <!-- Form Content -->
              <form @submit.prevent="submitRankings" class="p-4">
                <div class="overflow-x-auto">
                  <table class="w-full">
                    <thead class="bg-gray-50">
                      <tr>
                        <th
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Teams
                        </th>
                        <th
                          class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Accumulated Points
                        </th>
                        <th class="w-8"></th>
                        <th
                          class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Final Points
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr
                          v-for="(team, index) in rankedTeams"
                          :key="team.teamId"
                          class="hover:bg-gray-50"
                        >
                          <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            {{ team.teamName }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-center font-medium text-gray-900">
                            {{ team.points }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-center text-gray-900">
                            <i class="fa-solid fa-arrow-right-long"></i>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-center font-medium text-blue-600">
                            {{ getPoints(index) }}
                          </td>
                        </tr>
                      </tbody>

                  </table>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-6">
                  <button
                    type="button"
                    @click="closeRankingModal"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Submit Rankings
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div>

        </div>
        <!-- Validation Modal -->
        <div v-if="showValidationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="relative p-4 w-full max-w-[32rem] max-h-full">
            <div class="relative bg-white rounded-lg shadow">
              <div class="flex items-center justify-between p-4 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">
                  Validate Rankings
                </h3>
                <button @click="closeValidationModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center">
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                  </svg>
                </button>
              </div>
              
              <div class="p-4">
                <!-- Official Name Input -->
                <div class="mt-4">
                  <label for="official_name" class="block text-sm font-medium text-gray-700">Official Name</label>
                  <input 
                    type="text"
                    id="official_name"
                    v-model="rankUpdateForm.official_name"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="Enter official name"
                    required
                  />
                </div>

                <!-- Signature Pad -->
                <div class="mt-4">
                  <label class="block text-sm font-medium text-gray-700">Signature</label>
                  <div ref="signaturePadContainer" class="mt-2 border-2 border-gray-300 rounded-md bg-white"></div>
                  <div class="flex justify-between mt-2">
                    <button 
                      type="button"
                      @click="clearSignature" 
                      class="text-sm text-red-600 hover:bg-red-50 px-3 py-1 rounded"
                    >
                      Clear Signature
                    </button>
                    <span class="text-xs text-gray-500">Sign within the box above</span>
                  </div>
                </div>

                <div v-if="rankUpdateError" class="mt-4 text-red-500 text-sm">{{ rankUpdateError }}</div>

                <div class="grid grid-cols-2 gap-2 mt-4">
                  <button 
                    type="button"
                    @click="backToRankModal"
                    class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100"
                  >
                    Back
                  </button>
                  <button 
                    @click="updateRanks"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2"
                  >
                    {{ rankUpdateForm.processing ? 'Saving...' : 'Save' }}
                  </button>
                </div>
              </div>
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
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch, nextTick } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayoutF.vue';
import PlayersDisplay from '@/Components/PlayersDisplay.vue';
import SuccessModal from '@/Components/SuccessModal.vue';
import VariationPointSummary from '@/Components/VariationPointSummary.vue';

const props = defineProps({
  players: {
    type: Array,
    default: () => []
  },
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
  facilitator: [Array, Object],
  majorPoints: {
    type: Array,
    default: () => []
  },
  minorPoints: {
    type: Array,
    default: () => []
  },
});

const pointsType = computed(() => {
  return props.sport.type === 'Major' ? props.majorPoints : props.minorPoints;
});

const getPoints = (index) => {
  const rankedTeam = rankedTeams.value[index]; // Access the team from rankedTeams

  if (!rankedTeam || !pointsType.value || !Array.isArray(pointsType.value)) {
    return 'No Points'; // Handle invalid cases
  }

  // Find matching pointsType by rank
  const matchingPointsType = pointsType.value.find(
    (point) => point.rank === rankedTeam.rank
  );

  return matchingPointsType ? matchingPointsType.points : 'No Points';
};

console.log('Points Type:', pointsType.value);

const showSuccessModal = ref(false);
const successMessage = ref('');

const showRankModal = ref(false);
const selectedVariationMatches = ref([]);
const rankUpdateError = ref('');
const rankUpdates = ref({});
const selectedVariation = ref(null);
const activeTab = ref('sports');

const totalMatches = computed(() => props.sportVariations.length);
const completedMatches = computed(() => props.sportVariations.filter(variation => variation.status === 'completed').length);
const progressPercentage = computed(() => totalMatches.value > 0 ? (completedMatches.value / totalMatches.value) * 100 : 0);
const hasValidSignature = ref(false);
const signaturePadContainer = ref(null);
const signaturePad = ref(null);

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

console.log('Ranked Teams:', rankedTeams.value);


// Sort teams by points and convert to array for easier template rendering
const sortedTeams = computed(() => {
  return Object.values(accumulatedPoints.value)
    .sort((a, b) => b.points - a.points);
});


const rankUpdateForm = useForm({
  matches: [],
  official_name: '',
  signature: '',
  facilitator_id: props.facilitator?.id,
});
//new added
const showValidationModal = ref(false);

const openValidationModal = () => {
  showRankModal.value = false;
  showValidationModal.value = true;
  nextTick(() => {
    initSignaturePad();
  });
};

const closeValidationModal = () => {
  showValidationModal.value = false;
  rankUpdateError.value = '';
};

const backToRankModal = () => {
  showValidationModal.value = false;
  showRankModal.value = true;
};

const closeRankModal = () => {
  showRankModal.value = false;
  showValidationModal.value = false;
  selectedVariation.value = null;
  selectedVariationMatches.value = [];
  rankUpdates.value = {};
  rankUpdateError.value = '';
  rankUpdateForm.reset();
};

const getSortedMatches = (matches) => {
  return [...matches].sort((a, b) => {
    if (a.rank === '0' && b.rank !== '0') return 1; // Move Disqualified to the end
    if (b.rank === '0' && a.rank !== '0') return -1; // Move Disqualified to the end
    if (a.rank && b.rank) return a.rank - b.rank; // Sort by rank
    if (a.rank) return -1; // If a has a rank but b doesn't, a comes first
    if (b.rank) return 1; // If b has a rank but a doesn't, b comes first
    return 0; // If neither has a rank, maintain order
  });
};

const formatRank = (rank) => {
  const suffixes = { 1: 'st', 2: 'nd', 3: 'rd' };
  const suffix = suffixes[rank] || 'th'; // Default to 'th' for all other numbers
  return `${rank}${suffix} place`;
};
//
const isMatchTimeReached = (variation) => {
  if (!variation.date || !variation.time) return false; // Ensure both date and time are available

  // Parse the scheduled date and time
  const scheduledDateTime = new Date(`${variation.date}T${variation.time}`);
  const currentDateTime = new Date(); // Get the current date and time

  return currentDateTime >= scheduledDateTime; // Return true if current time is past or equal to scheduled time
};

const isRankingAllowed = (variation) => {
  if (!variation.date || !variation.time) return false;
  
  try {
    // Parse the date
    const [year, month, day] = variation.date.split('-').map(Number);
    
    // Parse the time (handling AM/PM format)
    let [timeStr, period] = variation.time.split(' ');
    let [hours, minutes] = timeStr.split(':').map(Number);
    
    // Convert to 24-hour format
    if (period === 'PM' && hours !== 12) {
      hours += 12;
    } else if (period === 'AM' && hours === 12) {
      hours = 0;
    }
    
    // Create the scheduled date object
    const scheduledDateTime = new Date(year, month - 1, day, hours, minutes);
    const currentDateTime = new Date();
    
    // Ensure we have a valid date before comparing
    if (isNaN(scheduledDateTime.getTime())) {
      console.error('Invalid scheduled date/time');
      return false;
    }

    return currentDateTime >= scheduledDateTime;
  } catch (error) {
    console.error('Error parsing date/time:', error);
    return false;
  }
};

const returnToFacilitator = () => {
  const currentPath = window.location.pathname;
  const matches = currentPath.match(/sportview\/\d+\/(.+)$/);
  if (matches && matches[1]) {
    router.visit(route('facilitator.show', { id: matches[1] }));
  }
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

const isAnyFieldFilled = computed(() => {
  return Object.values(rankUpdates.value).some(match => 
    match.rank !== '' || match.points !== ''
  );
});

const currentSportTeams = computed(() => {
  return props.teams.filter(team => team.palakasan_id === props.sport.palakasan_sport_id);
});

const sortedMatches = computed(() => {
  return [...selectedVariationMatches.value].sort((a, b) => {
    const rankA = rankUpdates.value[a.id]?.rank || Infinity; // Use Infinity for unassigned ranks
    const rankB = rankUpdates.value[b.id]?.rank || Infinity;
    return rankA - rankB; // Sort by user-defined ranks
  });
});


// SignaturePad class implementation
class SignaturePad {
  constructor(canvas, options = {}) {
    this.canvas = canvas;
    this.ctx = canvas.getContext('2d');
    this.points = [];
    this.isDrawing = false;

    this.canvas.addEventListener('mousedown', this.onMouseDown.bind(this));
    this.canvas.addEventListener('mousemove', this.onMouseMove.bind(this));
    this.canvas.addEventListener('mouseup', this.onMouseUp.bind(this));
    this.canvas.addEventListener('touchstart', this.onTouchStart.bind(this));
    this.canvas.addEventListener('touchmove', this.onTouchMove.bind(this));
    this.canvas.addEventListener('touchend', this.onTouchEnd.bind(this));
  }

  onMouseDown(event) {
    this.isDrawing = true;
    const rect = this.canvas.getBoundingClientRect();
    this.addPoint(event.clientX - rect.left, event.clientY - rect.top);
  }

  onMouseMove(event) {
    if (!this.isDrawing) return;
    const rect = this.canvas.getBoundingClientRect();
    this.addPoint(event.clientX - rect.left, event.clientY - rect.top);
  }

  onMouseUp() {
    this.isDrawing = false;
  }

  onTouchStart(event) {
    event.preventDefault();
    if (event.touches.length === 1) {
      const touch = event.changedTouches[0];
      const rect = this.canvas.getBoundingClientRect();
      this.addPoint(touch.clientX - rect.left, touch.clientY - rect.top);
    }
  }

  onTouchMove(event) {
    event.preventDefault();
    if (event.touches.length === 1) {
      const touch = event.changedTouches[0];
      const rect = this.canvas.getBoundingClientRect();
      this.addPoint(touch.clientX - rect.left, touch.clientY - rect.top);
    }
  }

  onTouchEnd(event) {
    event.preventDefault();
  }

  addPoint(x, y) {
    this.points.push({ x, y });
    this.redraw();
  }

  redraw() {
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    this.ctx.strokeStyle = 'black';
    this.ctx.lineWidth = 2;
    this.ctx.beginPath();
    for (let i = 0; i < this.points.length; i++) {
      const point = this.points[i];
      if (i === 0) {
        this.ctx.moveTo(point.x, point.y);
      } else {
        this.ctx.lineTo(point.x, point.y);
      }
    }
    this.ctx.stroke();
  }

  clear() {
    this.points = [];
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
  }

  isValid() {
    return this.points.length > 10;
  }

  toDataURL() {
    return this.canvas.toDataURL('image/png');
  }
}

onMounted(() => {
  nextTick(() => {
    if (showRankModal.value) {
      initSignaturePad();
    }
  });
});


const initSignaturePad = () => {
  if (signaturePadContainer.value) { // Check if the container is available
    const canvas = document.createElement('canvas');
    canvas.width = 300;
    canvas.height = 200;
    signaturePadContainer.value.appendChild(canvas);
    
    signaturePad.value = new SignaturePad(canvas);
    
    // Add event listeners to update hasValidSignature
    canvas.addEventListener('mouseup', checkSignatureValidity);
    canvas.addEventListener('touchend', checkSignatureValidity);
  } else {
  }
};

const checkSignatureValidity = () => {
  if (signaturePad.value) {
    hasValidSignature.value = signaturePad.value.isValid();
  }
};

const clearSignature = () => {
  if (signaturePad.value) {
    signaturePad.value.clear();
    hasValidSignature.value = false;
  }
};
//end

const openRankModal = (variation) => {
  selectedVariation.value = variation;
  
  // Get matches for the selected variation
  selectedVariationMatches.value = props.sportVariationMatches
    .filter(m => m.sport_variation_id === variation.id)
    .filter(m => currentSportTeams.value.some(team => team.id === m.sport_variation_team_id));
  
  // Initialize rank updates 
  rankUpdates.value = selectedVariationMatches.value.reduce((acc, match) => ({
    ...acc,
    [match.id]: {
      rank: match.rank || '', 
      points: match.points || ''
    }
  }), {});

  showRankModal.value = true;
  nextTick(() => {
    initSignaturePad();
  });
};

watch(() => rankUpdates.value, (newRankUpdates) => {
  // Instead of using pointsToUse, directly use setPoints logic
  const selectedRanks = Object.values(newRankUpdates)
    .filter(match => match.rank !== '' && match.rank !== '0' && match.rank !== '99')  // Exclude Disqualified rank
    .map(match => parseInt(match.rank))
    .sort((a, b) => a - b);

  // Assign points based on rank order
  Object.keys(newRankUpdates).forEach((matchId) => {
    const rank = newRankUpdates[matchId].rank;
    if (rank !== '') {
      setPoints(matchId); // Call setPoints to assign points based on the rank
    }
  });
}, { deep: true });



const updateRanks = async () => {
  if (!signaturePad.value || !signaturePad.value.isValid()) {
    alert('Please provide a valid signature before submitting.');
    return;
  }

  if (!rankUpdateForm.official_name) {
    alert('Please enter your official name.');
    return;
  }

  try {
    // Log the data being sent
    console.log('Form data:', {
      matches: Object.entries(rankUpdates.value).map(([id, data]) => ({
        id: parseInt(id),
        rank: parseInt(data.rank),
        points: parseInt(data.points)
      })),
      official_name: rankUpdateForm.official_name,
      facilitator_id: props.facilitator?.id,
      signature: signaturePad.value.toDataURL('image/png')
    });

    // Prepare the form data
    rankUpdateForm.signature = signaturePad.value.toDataURL('image/png');
    rankUpdateForm.matches = Object.entries(rankUpdates.value).map(([id, data]) => ({
      id: parseInt(id),
      rank: parseInt(data.rank),
      points: parseInt(data.points)
    }));

    await rankUpdateForm.patch(route('sport-variations.update-ranks', selectedVariation.value.id), {
      preserveScroll: true,
      onSuccess: () => {
        closeRankModal();
        showSuccessModal.value = true;
        successMessage.value = 'Rankings submitted successfully!';
      },
      onError: (errors) => {
        console.error('Update ranks errors:', errors);
        if (errors.signature) {
          rankUpdateError.value = 'Please provide a valid signature.';
        } else if (errors.official_name) {
          rankUpdateError.value = 'Please provide your official name.';
        } else if (errors.matches) {
          rankUpdateError.value = 'Please ensure all ranks and points are properly assigned.';
        } else {
          rankUpdateError.value = errors.error || 'An error occurred while updating ranks. Please try again.';
        }
      },
    });
  } catch (error) {
    console.error('Update ranks error:', error);
    rankUpdateError.value = 'An unexpected error occurred. Please try again.';
  }
};

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
    completed: 'text-blue-600',
  };
  return statusClasses[status?.toLowerCase()] || 'text-gray-600';
};



const getVariationMatches = (variationId) => {
  return props.sportVariationMatches
    .filter(match => match.sport_variation_id === variationId)
    .filter(match => currentSportTeams.value.some(team => team.id === match.sport_variation_team_id));
};

const setPoints = (matchId) => {
  const rank = rankUpdates.value[matchId].rank;
  const pointsMapping = {
    "1": 12, 
    "2": 10,  
    "3": 8,  
    "4": 6,
    "5": 4,
    "6": 2,
    "10": 1,
    "0": 0,
  };

  rankUpdates.value[matchId].points = pointsMapping[rank] || 0; 
};

const availableRanks = (matchId) => {
  const currentRanks = new Set();
  
  // Gather current ranks for all matches except the current match
  Object.entries(rankUpdates.value).forEach(([id, data]) => {
    if (id !== matchId.toString() && data.rank) {
      currentRanks.add(data.rank.toString());
    }
  });
  
  const allRanks = Array.from(
    { length: selectedVariationMatches.value.length }, 
    (_, i) => (i + 1).toString()
  );

  // Include Disqualified option and No Player option in available ranks
  allRanks.push('0'); // Adding Disqualified option explicitly
  allRanks.push('10'); // Adding No Player option explicitly

  return allRanks.filter(rank => 
    rank === '0' || rank === '10' || !currentRanks.has(rank)
  );
};

watch(
  () => rankUpdates.value,
  () => {
  },
  { deep: true }
);

const updateAssignedSportStatus = async () => {
  if (progressPercentage.value === 100) {
    try {
      await router.patch(route('assigned-sports.update-status', props.sport.id), {
        status: 'completed'
      });
      props.sport.status = 'completed';
    } catch (error) {
      console.error('Failed to update assigned sport status:', error);
    }
  }
};

console.log('Points Type:', pointsType.value);

const showRankingModal = ref(false)
const openRankingModal = () => {
  showRankingModal.value = true;
};
const closeRankingModal = () => {
  showRankingModal.value = false;
};

const submitRankings = async () => {
  console.log('Ranked Teams:', rankedTeams.value);

  const rankings = [];
  let currentRank = 1;

  for (let i = 0; i < rankedTeams.value.length; i++) {
    const team = rankedTeams.value[i];
    const previousTeam = rankedTeams.value[i - 1];

    // Check for a tie
    if (i > 0 && team.rank === previousTeam.rank) {
      // Assign the same rank as the previous team
      rankings.push({
        assigned_sport_id: props.sport.id,
        assigned_team_id: team.teamId,
        rank: rankings[rankings.length - 1].rank, // Use the rank of the previous team
        points: getPoints(i), // Use points from getPoints method
      });
    } else {
      // Assign the current rank to the team
      rankings.push({
        assigned_sport_id: props.sport.id,
        assigned_team_id: team.teamId,
        rank: currentRank, // Use the team's current rank
        points: getPoints(i), // Use points from getPoints method
      });
      currentRank++; // Increment the rank for the next team
    }
  }

  const payload = {
    rankings: rankings,
  };

  console.log('Final payload:', payload);

  try {
    const response = await router.post(route('overall-resultsFof.store'), {
      data: payload,
      preserveScroll: true,
      onSuccess: () => {
        console.log('Rankings submitted successfully');
        showSuccessModal.value = true;
        showRankingModal.value = false; // Ensure the modal closes
        successMessage.value = 'Rankings submitted successfully!';
        closeRankingModal();
        updateAssignedSportStatus();
      },
      onError: (errors) => {
        console.error('Submission errors:', errors);
        const errorMessage = typeof errors === 'string' 
          ? errors 
          : Object.values(errors).flat().join('\n');
        alert('Error submitting rankings: ' + errorMessage);
      }
    });

    console.log('Response:', response);
  } catch (error) {
    console.error('Critical error:', error);
    alert('An unexpected error occurred. Please try again.');
  }
};

</script>

<style scoped>
/* Add any component-specific styles here */
</style>