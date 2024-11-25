<template>
  <div>
    <!-- Navigation -->
    <nav id="mainNav" class="bg-white border-gray-200 dark:bg-gray-900 shadow-2xl fixed top-0 w-full z-50">
      <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
        <a href="#" class="flex items-center">
          <img src="../img/msun.png" class="w-12 h-12 rounded-full mr-3" alt="Logo">
          <img src="../img/logo.jpg" class="w-12 h-12 rounded-full mr-3" alt="Logo">
          <span class="text-2xl font-bold dark:text-white">Supreme Student Council: Palakasan 2024</span>
        </a>
        <div class="flex items-center space-x-6 rtl:space-x-reverse">
          <a href="tel:5541251234" class="text-sm text-gray-500 dark:text-white hover:underline">(+639)777-88-6642</a>
          <a href="Login.vue" class="text-white bg-blue-700 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-xl text-sm px-5 py-2.5 text-center me-2">Login</a>
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
                <a href="(2)homepage.html" class="block py-2 px-3 text-blue-700 dark:text-white hover:bg-gray-200 rounded-full dark:hover:bg-gray-700 font-semibold">Home</a>
              </li>
              <li>
                <a href="(3)schedule.html" class="block py-2 px-3 text-black dark:text-white hover:bg-gray-200 rounded-full dark:hover:bg-gray-700 font-semibold">Schedule</a>
              </li>
              <li>
                <a href="(4)ranking.html" class="block py-2 px-3 text-black dark:text-white hover:bg-gray-200 rounded-full dark:hover:bg-gray-700 font-semibold">Ranking</a>
              </li>
              <li class="relative">
                <button @click="toggleDropdown" id="dropdownButtonCollege" data-dropdown-toggle="dropdownCollege" class="block py-2 px-3 text-gray-900 dark:text-white hover:bg-gray-200 rounded-full dark:hover:bg-gray-700 font-semibold">Teams</button>
                <div v-if="isDropdownOpen" id="dropdownCollege" class="z-10 w-44 bg-white shadow-md rounded-md dark:bg-gray-700">
                  <ul class="py-1 text-gray-700 dark:text-gray-200">
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">CMAS</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">CBAA</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">CAFES</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">SMFT</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">CESS-IDS</a></li>
                  </ul>
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
      <div class="h-24"></div>
    </section>

    <!-- Hero Section -->
    <section class="relative bg-cover bg-center h-screen text-white" style="background-image: url('https://example.com/sports-hero.jpg');">
      <!-- Overlay Picture -->
      <img src="../img/arnis.jpg" class="rounded-xl blur-none shadow-lg absolute inset-0 w-full h-full object-cover opacity-30">
    
      <!-- Gradient and Content -->
      <div class="relative flex items-center justify-center h-full bg-gradient-to-t from-black via-black bg-opacity-80">
        <div class="text-center px-8 md:px-0">
          <!-- Heading with stronger shadow -->
          <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl lg:text-6xl dark:text-white" 
              style="text-shadow: 12px 10px 15px rgba(0, 0, 0, 0.9); display: inline-block; padding: 10px; border-radius: 5px;">
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

    <!-- Upcoming Events Section -->
    <section id="sched" class="mx-auto bg-blue-50 pt-2">
      <div class="text-center mb-12 mt-2">
        <h2 id="featured" class="text-4xl font-medium">Sports Schedules</h2>
        <p class="text-gray-600">Check out the schedules in the palakasan competition.</p>
      </div>
      <!-- Game Info: Sport, Game Number, and Round -->
      <div class="flex flex-wrap justify-center space-x-4 mt-1">
        <!-- Game cards -->
        <div v-for="(game, index) in games" :key="index" class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-md p-6 m-4">
          <!-- Game Info: Sport, Game Number, and Round -->
          <div class="mb-4">
            <span class="block text-xl font-bold text-gray-900">{{ game.sport }}</span>
            <span class="block text-md text-gray-500">{{ game.gameInfo }}</span>
          </div>
        
          <!-- Date Section -->
          <div class="flex items-center justify-between mb-4 space-x-4">
            <span class="text-base font-semibold text-gray-900">{{ game.date }}</span>
            <span class="text-sm text-gray-500">{{ game.time }}</span>
          </div>
        
          <!-- Teams Section -->
          <div class="flex items-center justify-between mb-4 ">
            <div :class="game.team1.color">{{ game.team1.name }}</div>
            <span class="text-gray-500">vs</span>
            <div :class="game.team2.color">{{ game.team2.name }}</div>
          </div>

          <!-- Teams Section with Scores-->
          <div class="flex items-center justify-center mb-4 space-x-8">
            <div :class="game.team1.color">{{ game.team1.score }}</div>
            <div :class="game.team2.color">{{ game.team2.score }}</div>
          </div>
        
          <!-- Venue Section -->
          <div class="flex items-center justify-between mb-4">
            <span class="text-md font-medium text-gray-700">Venue:</span>
            <span class="text-md text-gray-600">{{ game.venue }}</span>
          </div>
        
          <!-- Category Section -->
          <div class="flex items-center justify-between mb-4">
            <span class="text-md font-medium text-gray-700">Category:</span>
            <span class="text-md text-gray-600">{{ game.category }}</span>
          </div>
        
          <!-- Status Section -->
          <div class="mb-4 flex justify-center">
            <span :class="game.statusClass">
              {{ game.status }}
            </span>
          </div>
        
          <!-- Action Button -->
          <a :href="game.detailsLink" class="block text-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
            View Details
          </a>
        </div>
      </div>
    </section>

    <!-- Ranking Section -->
    <section id="ranking" class="container mx-auto py-16 mt-4 flex flex-col items-center justify-center text-center">
      <div class="text-center mb-12 mt-2">
        <h2 class="text-4xl font-bold text-black">Ranking</h2>
        <p class="text-gray-500">Explore the top rankings for each sport and the overall points tally.</p>
      </div>

      <!-- Sport Rankings -->
      <div class="inline-flex gap-8 p-4 justify-items-center">
        <!-- Ranking cards -->
        <div v-for="(ranking, index) in rankings" :key="index" class="bg-gradient-to-r from-blue-100 to-blue-200 border border-gray-300 rounded-lg shadow-lg p-8 transition-transform transform hover:scale-105 hover:rotate-1 hover:shadow-xl">
          <div class="mb-4 flex items-center justify-between">
            <span class="text-2xl font-bold text-gray-900">{{ ranking.sport }}</span>
          </div>

          <div class="flex items-center justify-start mb-4 space-x-2">
            <span class="text-md font-medium text-gray-700">Category:</span>
            <span class="text-md text-gray-600">{{ ranking.category }}</span>
          </div>
          
          <ol class="list-decimal list-inside space-y-3">
            <li v-for="(team, teamIndex) in ranking.teams" :key="teamIndex" class="text-lg flex justify-between items-center">
              <span :class="team.color">{{ team.name }}</span> 
              <span :class="team.badgeClass">{{ team.place }}</span>
            </li>
          </ol>
          <div class="mt-4">
            <span class="inline-block px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-gray-800">
              {{ ranking.status }}
            </span>
          </div>
        </div>
      </div>
    </section>

    <!-- Overall Sports Ranking -->
    <div class="mt-12 bg-blue-50">
      <h3 class="text-2xl font-bold text-center pt-4 pb-4">Overall Sports Ranking</h3>
      
      <!-- Flex container for table and chart, closer and centered -->
      <div class="w-1/3 flex justify-center gap-5 items-start space-x-1 ">
        <!-- Table section -->
        <div class="w-auto h-full">
          <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-fullshadow-lg">
              <thead>
                <tr class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                  <th class="py-4 px-6 border-b border-gray-200 text-left rounded-tl-lg">Team</th>
                  <th class="py-4 px-6 border-b border-gray-200 text-left">Place</th>
                  <th class="py-4 px-6 border-b border-gray-200 text-center">Total Points</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(team, index) in overallRanking" :key="index" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'" class="hover:bg-gray-100 transition-all">
                  <td class="py-4 px-6 border-b border-gray-200">{{ team.name }}</td>
                  <td class="py-4 px-6 border-b border-gray-200">{{ team.place }}</td>
                  <td class="py-4 px-6 border-b border-gray-200 text-center">{{ team.points }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    
        <!-- Chart section -->
        <div class="max-w-sm w-full h- bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-2 flex-1">
          <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
              <div class="w-16 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center me-3">
                <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 19">
                  <path d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z"/>
                  <path d="M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z"/>
                </svg>
              </div>
              <div>
                <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1">College Teams</h5>
                <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Overall Sports Partial Ranking</p>
              </div>
            </div>
            <div>
              <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300">
                <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                </svg>
                Ongoing
              </span>
            </div>
          </div>
      
          <!-- Column Chart -->
          <div id="column-chart"></div>
      
          <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
            <div class="flex justify-between items-center pt-5">
              <!-- Button -->
              <button
                id="dropdownDefaultButton"
                @click="toggleDateDropdown"
                class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                type="button">
                Date
                <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
              </button>
              <!-- Dropdown menu -->
              <div v-if="isDateDropdownOpen" id="lastDaysdropdown" class="z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                  <li v-for="date in dates" :key="date">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ date }}</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="m-auto w-full p-1 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
      <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="https://flowbite.com/" class="hover:underline">Supreme Student Council</a>. All Rights Reserved.
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
import { ref, onMounted } from 'vue'
import ApexCharts from 'apexcharts'

const isMenuOpen = ref(false)
const isDropdownOpen = ref(false)
const isDateDropdownOpen = ref(false)

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value
}

const toggleDateDropdown = () => {
  isDateDropdownOpen.value = !isDateDropdownOpen.value
}

const games = ref([
  {
    sport: 'Basketball',
    gameInfo: 'Game 1 - Round 1',
    date: 'October 15, 2024',
    time: '3:00 PM',
    team1: { name: 'SMFT', color: 'text-lg font-bold text-blue-600', score: '--' },
    team2: { name: 'CMAS', color: 'text-lg font-bold text-red-600', score: '--' },
    venue: 'OVAL',
    category: 'Women',
    status: 'Ongoing',
    statusClass: 'inline-block px-3 py-1 text-sm font-medium rounded-full bg-orange-100 text-orange-800',
    detailsLink: '(3)schedule.html'
  },
  // Add more game objects here
])

const rankings = ref([
  {
    sport: 'Basketball',
    category: 'Men',
    teams: [
      { name: 'CMAS', color: 'font-semibold text-red-600', place: '1st Place', badgeClass: 'px-3 py-1 bg-yellow-300 text-gray-800 rounded-full text-sm' },
      { name: 'CBAA', color: 'font-semibold text-yellow-300', place: '2nd Place', badgeClass: 'px-3 py-1 bg-gray-300 text-gray-800 rounded-full text-sm' },
      { name: 'SMFT', color: 'font-semibold text-blue-600', place: '3rd Place', badgeClass: 'px-3 py-1 bg-orange-300 text-gray-800 rounded-full text-sm' },
      { name: 'CAFES', color: 'font-semibold text-green-600', place: '4th Place', badgeClass: 'px-3 py-1 bg-gray-200 text-gray-600 rounded-full text-sm' },
      { name: 'CESS-IDS', color: 'font-semibold text-pink-400', place: '5th Place', badgeClass: 'px-3 py-1 bg-gray-200 text-gray-600 rounded-full text-sm' }
    ],
    status: 'Completed'
  },
  // Add more ranking objects here
])

const overallRanking = ref([
  { name: 'CMAS', place: '1st Place', points: 259 },
  { name: 'SMFT', place: '2nd Place', points: 243 },
  { name: 'CBAA', place: '3rd Place', points: 231 },
  { name: 'CESS-IDS', place: '4th Place', points: 229 },
  { name: 'CAFES', place: '5th Place', points: 199 }
])

const dates = ref([
  'Nov. 21, 2024',
  'Nov. 22, 2024',
  'Nov. 23, 2024',
  'Nov. 24, 2024',
  'Nov. 25, 2024'
])

onMounted(() => {
  const navbar = document.getElementById("mainNav")
  let prevScrollPos = window.pageYOffset

  window.onscroll = function () {
    const currentScrollPos = window.pageYOffset
    if (prevScrollPos > currentScrollPos) {
      navbar.style.transform = "translateY(0)" // Show navbar
    } else {
      navbar.style.transform = "translateY(-80px)" // Hide navbar
    }
    prevScrollPos = currentScrollPos
  }

  const options = {
    colors: ["#1A56DB", "#FDBA8C"],
    series: [
      {
        name: "Total Points",
        color: "#1A56DB",
        data: [
          { x: "CMAS", y: 123 },
          { x: "SMFT", y: 122 },
          { x: "CBAA", y: 63 },
          { x: "CESS IDS", y: 421 },
          { x: "CAFES", y: 122 },
        ],
      },
    ],
    chart: {
      type: "bar",
      height: "320px",
      fontFamily: "Inter, sans-serif",
      toolbar: {
        show: false,
      },
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "70%",
        borderRadiusApplication: "end",
        borderRadius: 8,
      },
    },
    tooltip: {
      shared: true,
      intersect: false,
      style: {
        fontFamily: "Inter, sans-serif",
      },
    },
    states: {
      hover: {
        filter: {
          type: "darken",
          value: 1,
        },
      },
    },
    stroke: {
      show: true,
      width: 0,
      colors: ["transparent"],
    },
    grid: {
      show: false,
      strokeDashArray: 4,
      padding: {
        left: 2,
        right: 2,
        top: -14
      },
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
    },
    xaxis: {
      floating: false,
      labels: {
        show: true,
        style: {
          fontFamily: "Inter, sans-serif",
          cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
        }
      },
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
    },
    yaxis: {
      show: false,
    },
    fill: {
      opacity: 1,
    },
  }

  if(document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.getElementById("column-chart"), options)
    chart.render()
  }
})
</script>

<style scoped>
#mainNav {
  transition: transform 0.3s ease-in-out;
}
</style>