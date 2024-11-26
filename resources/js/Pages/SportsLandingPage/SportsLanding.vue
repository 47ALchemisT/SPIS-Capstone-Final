<template>
   <Head title="Landing" />
  <div>
    <!-- Navigation -->
    <nav id="mainNav" class="bg-white border-gray-200 dark:bg-gray-900 shadow-2xl fixed top-0 w-full z-50 transition-transform duration-300" :class="{ '-translate-y-full': !showNav }">
      <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
        <a href="#" class="flex items-center">
          <img src="/resources/assets/logoMSU.png" class="w-12 h-12 rounded-full mr-3" alt="Logo">
          <img src="/resources/assets/Logo.png" class="w-12 h-12 rounded-full mr-3" alt="Logo">
<span class="text-2xl font-bold dark:text-white">Supreme Student Council: Palakasan {{palakasanYear }}</span>
        </a>
        <div class="flex items-center space-x-6 rtl:space-x-reverse">
          <Link :href="route('login')" class="text-white bg-blue-700 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-xl text-sm px-5 py-2.5 text-center me-2">Login</Link>
        </div>
      </div>
    
      <nav id="mainNav" class="border-gray-200 bg-gray-100 shadow-md">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-0">
          <button @click="toggleMenu" data-collapse-toggle="navbar" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
            </svg>
          </button>
    
          <div :class="{ 'hidden': !isMenuOpen }" class="w-full md:block md:w-auto" id="navbar">
            <ul class="flex flex-col md:flex-row md:space-x-8 pl-5 m-2">
       <li class="relative">
         <a href="#" @click.prevent="scrollToHome" 
         :class="[
           'block py-2 px-3 rounded-full font-semibold transition-colors duration-200',
           activeNav === 'home' ? 'text-blue-700 bg-blue-50' : 'text-black dark:text-white hover:text-blue-700 hover:bg-blue-50'
         ]">Home</a>
       </li>
       <li class="relative">
         <a href="#" @click.prevent="scrollToSchedule" 
         :class="[
           'block py-2 px-3 rounded-full font-semibold transition-colors duration-200',
           activeNav === 'schedule' ? 'text-blue-700 bg-blue-50' : 'text-black dark:text-white hover:text-blue-700 hover:bg-blue-50'
         ]">Schedule</a>
       </li>
       <li class="relative">
         <a href="#" @click.prevent="scrollToRank"
         :class="[
           'block py-2 px-3 rounded-full font-semibold transition-colors duration-200',
           activeNav === 'rank' ? 'text-blue-700 bg-blue-50' : 'text-black dark:text-white hover:text-blue-700 hover:bg-blue-50'
         ]">Rank</a>
       </li>
       <li class="relative">
         <button @click="toggleDropdown" 
           :class="[
             'block py-2 px-3 rounded-full font-semibold transition-colors duration-200',
             activeNav === 'teams' ? 'text-blue-700 bg-blue-50' : 'text-black dark:text-white hover:text-blue-700 hover:bg-blue-50'
           ]">
           {{ selectedTeam ? selectedTeam.acronym : 'Teams' }}
         </button>
         <div v-if="isDropdownOpen" 
              class="absolute z-10 w-48 bg-white shadow-lg rounded-lg mt-2 py-2 dark:bg-gray-700">
           <div class="max-h-60 overflow-y-auto">
             <button v-for="team in activeTeams" 
                     :key="team.id"
                     @click="selectTeam(team)"
                     class="w-full text-left px-4 py-2 hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-blue-600 flex items-center space-x-2 transition-colors duration-200">
               <img v-if="team.logo" :src="team.logo" :alt="team.name" class="w-6 h-6 rounded-full">
               <span>{{ team.name }}</span>
             </button>
           </div>
         </div>
       </li>
              <li>
                <a href="#" class="block py-2 px-3 text-gray-900 dark:text-white hover:bg-gray-200 rounded-full dark:hover:bg-gray-700 font-semibold">History</a>
              </li>
            </ul>
          </div>
      
          <!-- Search input -->
          <div class="relative m-1 p-1">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="absolute inset-y-0 left-0 flex items-center p-2 pl-3 pointer-events-none">
              <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l6-6m0 0a6 6 0 1 0-6-6 6 6 0 0 0 6 6z" />
              </svg>
            </div>
            <input type="search" id="default-search" class="block w-96 pl-10 p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search..." required />
            <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
          </div>
        </div>
      </nav>   
    </nav>

    <section>
      <!-- Team Filter Dropdown -->
      <div class="mb-4 w-full max-w-xs mx-auto">
        <label for="teamFilter" class="block text-sm font-medium text-gray-700 mb-2">Filter by Team</label>
        <select
          id="teamFilter"
          v-model="selectedTeamId"
          @change="viewTeamMatches"
          class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="">All Teams</option>
          <option v-for="team in props.activeTeams" :key="team.id" :value="team.id">
            {{ team.name }}
          </option>
        </select>
      </div>
      
      <!-- Team Matches Display -->
      <div v-if="selectedTeam" class="mb-8">
        <h3 class="text-xl font-bold mb-4">{{ selectedTeam.name }}'s Matches</h3>
        <div v-if="selectedTeam.matches.length === 0" class="text-center text-gray-500 py-4">
          No matches scheduled for this team.
        </div>
        <div v-else class="grid gap-4">
          <div v-for="match in selectedTeam.matches" :key="match.id" 
               class="border rounded-lg p-4 hover:bg-gray-50">
            <div class="flex justify-between items-center mb-2">
              <div>
                <span class="font-medium">{{ match.sport }}</span>
                <span class="text-gray-500"> • Game {{ match.game }}</span>
                <span class="text-gray-500"> • Round {{ match.round }}</span>
              </div>
              <span :class="match.statusClass">{{ match.status }}</span>
            </div>
            
            <div class="flex justify-between items-center mb-2">
              <div class="text-center flex-1">
                <p :class="match.team1.color">{{ match.team1.name }}</p>
                <p class="text-gray-600">{{ match.team1.score }}</p>
              </div>
              <div class="text-center px-4">
                <span class="text-gray-400">vs</span>
              </div>
              <div class="text-center flex-1">
                <p :class="match.team2.color">{{ match.team2.name }}</p>
                <p class="text-gray-600">{{ match.team2.score }}</p>
              </div>
            </div>
            
            <div class="text-sm text-gray-600 mt-2">
              <div>{{ match.date }} at {{ match.time }}</div>
              <div>{{ match.venue }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Hero Section -->
    <section class="relative bg-cover bg-center h-screen text-white" style="background-image: url('https://example.com/sports-hero.jpg');">
      <!-- Overlay Picture -->
      <img src="/resources/assets/torches.png" class="rounded-xl shadow-lg absolute inset-0 w-full h-full object-cover opacity-100">
    
      <!-- Gradient and Content -->
      <div class="relative flex items-center justify-center h-full bg-gradient-to-t from-black/30 via-transparent">
              <div class="text-center px-8 md:px-0">
                <!-- Heading with stronger shadow -->
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl lg:text-6xl text-white" 
                    style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); display: inline-block; padding: 10px; border-radius: 5px;">
                  WELCOME TO PALAKASAN
                </h1>
    
          <!-- Paragraph with enhanced visibility -->
          <p class="text-lg md:text-xl mb-8 max-w-md mx-auto text-gray-300" style="text-shadow: 2px 2px 12px rgba(0, 0, 0, 0.8); background-color: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 100px;">
            Join us for an exciting season of sports, competition, and unforgettable moments!
          </p>
    
          <!-- Overlaid Button -->
          <a href="#featured" class="absolute bottom-12 left-1/2 transform -translate-x-1/2 px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-700 rounded-full text-lg font-semibold shadow-md transition-transform duration-300 hover:scale-105 hover:shadow-lg hover:from-blue-600 hover:to-blue-800">
            Get Started
          </a>
        </div>
      </div>
    </section>

    <!-- Upcoming Schedule -->
    <section id="sched" class="mx-auto bg-blue-50 pt-2">
      <div class="text-center mb-12 mt-2">
        <h2 id="featured" class="text-4xl font-medium">Sports Schedules</h2>
        <p class="text-gray-600">Check out the schedules in the palakasan competition.</p>
      </div>
      
     <!-- Sports List with Swiper -->
     <div class="relative px-4">
       <Swiper
         :modules="modules"
         :slides-per-view="3"
         :space-between="24"
         :pagination="{ clickable: true }"
         :navigation="true"
         :breakpoints="{
           '320': {
             slidesPerView: 1,
             spaceBetween: 20
           },
           '640': {
             slidesPerView: 2,
             spaceBetween: 20
           },
           '1024': {
             slidesPerView: 3,
             spaceBetween: 24
           }
         }"
         class="mySwiper"
       >
         <SwiperSlide v-for="sport in activeSports" :key="sport.id">
           <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
             <div class="p-6">
               <div class="mb-4">
                 <h3 class="text-2xl font-bold text-gray-900">{{ sport.name }}</h3>
                 <p class="text-gray-600">{{ sport.category }}</p>
               </div>
               
               <div class="flex justify-between items-center mb-4">
                 <span class="text-sm font-medium text-gray-600">{{ sport.setup }}</span>
                 <span :class="sport.statusClass">{{ sport.status }}</span>
               </div>
     
               <button @click="viewMatches(sport.id)" 
                       class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                 View Matches
               </button>
             </div>
           </div>
         </SwiperSlide>
       </Swiper>
     </div>
    
      <!-- Match Details Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-2xl font-bold">Match Schedule</h3>
              <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
    
            <div class="space-y-4">
              <div v-if="matches.length === 0" class="text-center py-8 text-gray-600">
                No matches scheduled yet.
              </div>
              <div v-else v-for="match in matches" :key="match.id" 
                   class="border rounded-lg p-4 hover:bg-gray-50">
                <div class="flex justify-between items-center mb-2">
                  <span class="font-medium">{{ match.game }}</span>
                  <span class="font-medium">Round {{ match.round }}</span>
                  <span :class="match.statusClass">{{ match.status }}</span>
                </div>
                
                <div class="flex justify-between items-center mb-2">
                  <div class="text-center flex-1" :class="{ 'bg-green-100 rounded-lg p-2': match.team1.isWinner && match.status === 'completed' }">
                    <p :class="match.team1.color">
                      {{ match.team1.name }}
                      <span v-if="match.team1.isWinner && match.status === 'completed'" class="text-sm text-green-600 block">Winner!</span>
                    </p>
                    <p class="text-3xl font-bold mt-2" :class="{ 'text-green-600': match.team1.isWinner && match.status === 'completed' }">
                      {{ match.team1.score }}
                    </p>
                  </div>
                  <div class="mx-4">
                    <span class="text-gray-400 text-xl">VS</span>
                  </div>
                  <div class="text-center flex-1" :class="{ 'bg-green-100 rounded-lg p-2': match.team2.isWinner && match.status === 'completed' }">
                    <p :class="match.team2.color">
                      {{ match.team2.name }}
                      <span v-if="match.team2.isWinner && match.status === 'completed'" class="text-sm text-green-600 block">Winner!</span>
                    </p>
                    <p class="text-3xl font-bold mt-2" :class="{ 'text-green-600': match.team2.isWinner && match.status === 'completed' }">
                      {{ match.team2.score }}
                    </p>
                  </div>
                </div>
                
                <div class="text-sm text-gray-600">
                  <p>Venue: {{ match.venue }}</p>
                  <p>Date: {{ match.date }} - {{ match.time }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

   <!-- Overall Sports Ranking -->
   <section id="ranking" class="mx-auto bg-blue-50 pt-2">
     <div class="text-center mb-12 mt-2">
       <h2 class="text-4xl font-medium">Sports Rankings</h2>
       <p class="text-gray-600">Check out the current standings in each sport.</p>
     </div>
   
     <!-- Sports Rankings with Swiper -->
     <div class="relative px-4">
       <Swiper
         :modules="modules"
         :slides-per-view="3"
         :space-between="24"
         :pagination="{ clickable: true }"
         :navigation="true"
         :breakpoints="{
           '320': {
             slidesPerView: 1,
             spaceBetween: 20
           },
           '640': {
             slidesPerView: 2,
             spaceBetween: 20
           },
           '1024': {
             slidesPerView: 3,
             spaceBetween: 24
           }
         }"
         class="mySwiper"
       >
         <SwiperSlide v-for="sport in activeSports" :key="sport.id">
           <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
             <div class="p-6">
               <div class="mb-4">
                 <h3 class="text-2xl font-bold text-gray-900">{{ sport.name }}</h3>
                 <p class="text-gray-600">{{ sport.category }}</p>
               </div>
               
               <div class="flex justify-between items-center mb-4">
                 <span class="text-sm font-medium text-gray-600">{{ sport.setup }}</span>
                 <span :class="sport.statusClass">{{ sport.status }}</span>
               </div>
   
               <button @click="viewStandings(sport.id)" 
                       class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                 View Standings
               </button>
             </div>
           </div>
         </SwiperSlide>
       </Swiper>
     </div>
   
     <!-- Standings Modal -->
     <div v-if="showStandingsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
       <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
         <div class="p-6">
           <div class="flex justify-between items-center mb-6">
             <div>
               <h2 class="text-2xl font-bold" v-if="currentStandings">
                 {{ currentStandings.sport.name }} - {{ currentStandings.sport.category }}
               </h2>
               <p class="text-gray-600">Team Standings</p>
             </div>
             <button @click="closeStandingsModal" class="text-gray-500 hover:text-gray-700">
               <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
               </svg>
             </button>
           </div>
   
           <div class="overflow-x-auto">
             <table class="min-w-full divide-y divide-gray-200">
               <thead class="bg-gray-50">
                 <tr>
                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">W</th>
                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">L</th>
                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Games</th>
                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Win %</th>
                 </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200">
                 <tr v-for="(team, index) in currentStandings?.standings" :key="team.team_id">
                   <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ index + 1 }}</td>
                   <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                     {{ team.team_name }}
                     <span class="text-gray-500">({{ team.team_acronym }})</span>
                   </td>
                   <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ team.wins }}</td>
                   <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ team.losses }}</td>
                   <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ team.total_games }}</td>
                   <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ team.win_percentage }}%</td>
                 </tr>
               </tbody>
             </table>
           </div>
         </div>
       </div>
     </div>
   </section>

    <<!-- Overall Sports Ranking -->
    <div class="mt-12 bg-blue-50 p-8">
      <h3 class="text-2xl font-bold text-center pt-4 pb-4">Overall Sports Ranking</h3>
      
      <!-- Flex container for table and chart -->
      <div class="flex flex-col md:flex-row justify-center gap-8 max-w-7xl mx-auto">
        <!-- Table section -->
        <div class="w-full md:w-1/2">
          <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Team</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">W/L</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="(team, index) in props.overallRankings" :key="team.team_id" 
                    class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium" :class="getRankClass(index + 1)">
                      {{ index + 1 }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div>
                        <div class="text-sm font-medium text-gray-900">
                          {{ team.name }}
                        </div>
                        <div class="text-sm text-gray-500">
                          {{ team.acronym }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">{{ team.points }}</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ team.wins }}/{{ team.losses }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    
        <!-- Chart section -->
        <div class="w-full md:w-1/2">
          <div class="bg-white rounded-lg shadow-lg p-4">
            <div id="rankingChart"></div>
          </div>
        </div>
      </div>
    </div>

    <footer class="m-auto w-full p-1 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
      <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400"> 2023 <a href="https://flowbite.com/" class="hover:underline">Supreme Student Council</a>. All Rights Reserved.
      </span>
      <ul class="flex flex-wrap items-center text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0 p-0 mt-0">
        <li>
          <div class="hover:underline me-4 md:me-6">Email: ssc@msunaawan.edu.ph</div>
        </li>
        <li>
          <div class="hover:underline">Contact: (+639)777-88-6642</div>
        </li>
      </ul>
    </footer>
  </div>
</template>

<script setup>

import { ref, onMounted, defineProps } from 'vue'
import ApexCharts from 'apexcharts'
import { Link, router, Head } from '@inertiajs/vue3'
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue'
// Import Swiper styles
import 'swiper/css'
import 'swiper/css/pagination'
import 'swiper/css/navigation'
// Import required Swiper modules
import { Pagination, Navigation } from 'swiper/modules'

// Add Swiper modules to the component
const modules = [Pagination, Navigation]

const props = defineProps({
  palakasanYear: {
    type: Number,
    required: true
  },
  activeSports: {
    type: Array,
    required: true
  },
  activeTeams: {
    type: Array,
    required: true
  },
  matches: {
    type: Array,
    default: () => []
  },
  selectedSportId: {
    type: Number,
    default: null
  },
  selectedSport: {
    type: Object,
    default: () => null
  },
  standings: {
    type: Array,
    default: () => []
  },
  overallRankings: {
    type: Array,
    default: () => []
  }

});
const showNav = ref(true)
const lastScrollPosition = ref(0)
const showModal = ref(false)
const isMenuOpen = ref(false)
const isDropdownOpen = ref(false)
const isDateDropdownOpen = ref(false)
const showStandingsModal = ref(false)
const currentStandings = ref(null)

const activeNav = ref('home')

const scrollToSchedule = () => {
  const scheduleSection = document.getElementById('sched')
  if (scheduleSection) {
    scheduleSection.scrollIntoView({ behavior: 'smooth' })
    activeNav.value = 'schedule'
  }
}

const scrollToRank = () => {
  const scheduleSection = document.getElementById('ranking')
  if (scheduleSection) {
    scheduleSection.scrollIntoView({ behavior: 'smooth' })
    activeNav.value = 'rank'
  }
}

const scrollToHome = () => {
  const scheduleSection = document.getElementById('mainNav')
  if (scheduleSection) {
    scheduleSection.scrollIntoView({ behavior: 'smooth' })
  activeNav.value = 'home'
}
}

const handleScroll = () => {
  const currentScrollPosition = window.scrollY
  showNav.value = currentScrollPosition < lastScrollPosition.value || currentScrollPosition < 50
  lastScrollPosition.value = currentScrollPosition
}



const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value
}

const toggleDateDropdown = () => {
  isDateDropdownOpen.value = !isDateDropdownOpen.value
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  if (props.selectedSportId) {
    showModal.value = true
  }
})

const selectedTeam = ref(null)

const selectTeam = (team) => {
  selectedTeam.value = team
  isDropdownOpen.value = false
  activeNav.value = 'teams'
}

const viewMatches = (sportId) => {
  router.get(`/sports/${sportId}/matches`, {}, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      showModal.value = true
    }
  })
}

const closeModal = () => {
  showModal.value = false
  // Reset the URL without the matches
  router.get('/landing', {}, {
    preserveState: true,
    preserveScroll: true
  })
}

const viewStandings = (sportId) => {
  router.get(`/sports/${sportId}/standings`, {}, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: (page) => {
      if (page.props.selectedSport && page.props.standings) {
        currentStandings.value = {
          sport: page.props.selectedSport,
          standings: page.props.standings
        };
        showStandingsModal.value = true;
      }
    },
    onError: (errors) => {
      console.error('Error:', errors);
    }
  });
};

const closeStandingsModal = () => {
  router.get('/landing', {}, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      showStandingsModal.value = false;
      currentStandings.value = null;
      const rankingSection = document.getElementById('ranking');
      if (rankingSection) {
        rankingSection.scrollIntoView({ behavior: 'smooth' });
      }
    }
  });
};

const getRankClass = (rank) => {
  switch(rank) {
    case 1: return 'text-yellow-500 font-bold';
    case 2: return 'text-gray-400 font-bold';
    case 3: return 'text-amber-600 font-bold';
    default: return 'text-gray-700';
  }
};



onMounted(() => {
  if (props.overallRankings.length > 0) {
    const options = {
      chart: {
        type: 'bar',
        height: 350
      },
      plotOptions: {
        bar: {
          horizontal: true,
          distributed: true,
          dataLabels: {
            position: 'top'
          }
        }
      },
      colors: ['#FCD34D', '#9CA3AF', '#D97706', ...Array(props.overallRankings.length - 3).fill('#1F2937')],
      dataLabels: {
        enabled: true,
        textAnchor: 'start',
        style: {
          colors: ['#000']
        },
        formatter: function(val) {
          return val + ' pts';
        },
        offsetX: 0
      },
      series: [{
        name: 'Points',
        data: props.overallRankings.map(team => ({
          x: team.name,
          y: team.points
        }))
      }],
      xaxis: {
        categories: props.overallRankings.map(team => team.name)
      },
      yaxis: {
        title: {
          text: 'Points'
        }
      }
    };

    const chart = new ApexCharts(document.querySelector("#rankingChart"), options);
    chart.render();
  }
});
</script>

<style scoped>
#mainNav {
  transition: transform 0.3s ease-in-out;
}
/* Swiper custom styles */
.swiper {
  padding: 20px 40px !important;
}

.swiper-button-next,
.swiper-button-prev {
  color: #2563eb !important;
  background: white;
  width: 40px !important;
  height: 40px !important;
  border-radius: 50%;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}

.swiper-button-next:after,
.swiper-button-prev:after {
  font-size: 20px !important;
}

.swiper-pagination-bullet-active {
  background: #2563eb !important;
}

/* Add some padding to accommodate navigation buttons */
.swiper-slide {
  padding: 0 5px;
}

html {
  scroll-behavior: smooth;
}

</style>