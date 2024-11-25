<template>
    <div>
      <!-- Navigation -->
      <nav id="mainNav" class="bg-white border-gray-200 dark:bg-gray-900 shadow-2xl fixed top-0 w-full z-50">
        <!-- Navigation content -->
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
          <a href="#" class="flex items-center">
            <img src="../img/msun.png" class="w-12 h-12 rounded-full mr-3" alt="Logo">
            <img src="../img/logo.jpg" class="w-12 h-12 rounded-full mr-3" alt="Logo">
            <span class="text-3xl font-medium dark:text-white">MSUN Supreme Student Council</span>
          </a>
          <div class="flex items-center space-x-6 rtl:space-x-reverse">
            <a href="(1)login.html" class="text-white bg-blue-700 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-xl text-sm px-5 py-2.5 text-center me-2">Login</a>
          </div>
        </div>
  
        <!-- Secondary Navigation -->
        <nav id="mainNav" class="border-gray-200 bg-gray-100 shadow-md">
          <!-- Secondary navigation content -->
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
                  <a href="(2)homepage.html" class="block py-2 px-3 text-black dark:text-white hover:bg-gray-200 rounded-full dark:hover:bg-gray-700 font-semibold">Home</a>
                </li>
                <li>
                  <a href="(3)schedule.html" class="block py-2 px-3 text-blue-700 dark:text-white hover:bg-gray-200 rounded-full dark:hover:bg-gray-700 font-semibold">Schedule</a>
                </li>
                <li>
                  <a href="(4)ranking.html" class="block py-2 px-3 text-black dark:text-white hover:bg-gray-200 rounded-full dark:hover:bg-gray-700 font-semibold">Ranking</a>
                </li>
                <li class="relative">
                  <button @click="toggleDropdown" id="dropdownButtonCollege" data-dropdown-toggle="dropdownCollege" class="block py-2 px-3 text-gray-900 dark:text-white hover:bg-gray-200 rounded-full dark:hover:bg-gray-700 font-semibold">Teams</button>
                  <div v-if="isDropdownOpen" id="dropdownCollege" class="z-10 w-44 bg-white shadow-md rounded-md dark:bg-gray-700">
                    <ul class="py-1 text-gray-700 dark:text-gray-200">
                      <li v-for="team in teams" :key="team">
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">{{ team }}</a>
                      </li>
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
  
      <div class="m-auto p-2 bg-gray-100 shadow-lg">
        <div class="text-center m-1 p-2 ">
          <h2 class="text-3xl font-medium ">Sports Schedules</h2>
          <p class="text-gray-500">Check out the schedules in the palakasan competition.</p>
        </div>
      </div>
      
      <div class="w-auto h-full bg- m-4 shadow-md">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg items-center">
          <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-blue-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                <th v-for="header in tableHeaders" :key="header" scope="col" class="px-6 py-3 text-center">
                  {{ header }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(sport, index) in sportsData" :key="index" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                  {{ sport.name }}
                </th>
                <td class="px-6 py-4 text-center">
                  {{ sport.category }}
                </td>
                <td class="px-6 py-4 text-center">
                  {{ sport.setup }}
                </td>
                <td class="px-6 py-4 text-center">
                  <div :class="getStatusClass(sport.status)">
                    {{ sport.status }}
                  </div>
                </td>
                <td class="px-6 py-4 text-center">
                  {{ sport.facilitator }}
                </td>
                <td class="px-6 py-4 text-center">
                  <a :href="sport.detailsLink" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View Details</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  
      <div class="p-4 shadow-lg pb-4 m-4 bg-gray-100 rounded-md">
        <div>
          <div class="flex items-center space-x-6">
            <!-- Datepicker Input -->
            <div class="relative max-w-sm">
              <label for="datepicker-autohide" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Calendar</label>
              <div class="pt-8 absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                </svg>
              </div>
              <input id="datepicker-autohide" type="text" 
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                     placeholder="Select date">
            </div>
  
            <!-- Sport Dropdown -->
            <div>
              <label for="sports" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select a sport</label>
              <select id="sports" 
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 
                             dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected>Choose a sport</option>
                <option v-for="sport in sports" :key="sport" :value="sport.toLowerCase()">{{ sport }}</option>
              </select>
            </div>
  
            <!-- Category Dropdown -->
            <div>
              <label for="categories" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select a category</label>
              <select id="categories" 
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 
                             dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected>Choose a category</option>
                <option v-for="category in categories" :key="category" :value="category.toLowerCase()">{{ category }}</option>
              </select>
            </div>
          </div>
        </div>
      </div>
  
      <div class="w-auto my-4 flex gap-5 justify-center flex-wrap">
        <div v-for="(game, index) in games" :key="index" class="block w-60 p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
          <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4">
            <div class="flex-1">
              <div class="text-center font-bold tracking-tight text-lg text-gray-900 dark:text-white">
                <h5>{{ game.sport }}</h5>
              </div>
              <div class="flex justify-center text-gray-700 text-sm dark:text-gray-400 mt-2">
                <span class="font-sans">{{ game.info }}</span>
              </div>
              <div class="text-sm text-gray-600 dark:text-gray-400 mt-1 flex justify-center items-center">
                <svg class="w-[20px] h-[20px] text-gray-800 dark:text-white mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/>
                </svg>
                {{ game.date }}
              </div>
              <div class="text-sm text-gray-600 dark:text-gray-400 mt-1 flex justify-center items-center">
                <svg class="w-[20px] h-[20px] text-gray-800 dark:text-white mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                {{ game.time }}
              </div>
              <div class="text-sm text-gray-600 dark:text-gray-400 mt-1 flex justify-center items-center">
                <svg class="w-[20px] h-[20px] text-gray-800 dark:text-white mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z"/>
                </svg>
                {{ game.location }}
              </div>
              <div class="text-sm text-gray-600 dark:text-gray-400 mt-1 rounded-md text-center bg-green-100 p-1">
                {{ game.status }}
              </div>
              <div class="flex justify-between items-center text-gray-700 dark:text-gray-400 mt-1">
                <span class="text-sm font-medium">{{ game.team1.name }}:</span>
                <span class="font-semibold text-gray-500">{{ game.team1.score }}</span>
              </div>
              <div class="flex justify-between items-center text-gray-700 dark:text-gray-400 mt-2">
                <span class="text-sm font-medium">{{ game.team2.name }}:</span>
                <span class="font-semibold text-gray-500">{{ game.team2.score }}</span>
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
  
  const isMenuOpen = ref(false)
  const isDropdownOpen = ref(false)
  
  const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value
  }
  
  const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value
  }
  
  const teams = ['CMAS', 'CBAA', 'CAFES', 'SMFT', 'CESS-IDS']
  
  const tableHeaders = ['Sports', 'Category', 'Set-Up', 'Status', 'Facilitator', 'Action']
  
  const sportsData = [
    { name: 'Basketball', category: 'Men', setup: 'Double-Elimination', status: 'Ongoing', facilitator: 'Jolander Guiral', detailsLink: '(3)viewschedule.html' },
    { name: 'Basketball', category: 'Women', setup: 'Double-Elimination', status: 'Completed', facilitator: 'Jolander Guiral', detailsLink: '#' },
    { name: 'Volleyball', category: 'Men', setup: 'Double-Elimination', status: 'Not Started', facilitator: 'Jolander Guiral', detailsLink: '#' },
    { name: 'Volleyball', category: 'Women', setup: 'Double Elimination', status: 'Ongoing', facilitator: 'Jolander Guiral', detailsLink: '#' },
    { name: 'Chess', category: 'Mixed', setup: 'Round Robin', status: 'Ongoing', facilitator: 'James Bryan Balambao', detailsLink: '#' }
  ]
  
  const getStatusClass = (status) => {
    const baseClasses = 'rounded-full p-2 flex mx-1 justify-center items-center'
    switch (status) {
      case 'Ongoing':
        return `${baseClasses} bg-orange-300`
      case 'Completed':
        return `${baseClasses} bg-green-300`
      case 'Not Started':
        return `${baseClasses} bg-gray-300`
      default:
        return baseClasses
    }
  }
  
  const sports = ['Basketball', 'Volleyball', 'Table Tennis']
  const categories = ['Men', 'Women', 'Mixed']
  
  const games = [
    {
      sport: 'Basketball',
      info: 'Game 3 | Men',
      date: 'Nov. 19, 2024',
      time: '12:00 AM',
      location: 'TSAC COURT',
      status: 'Ongoing',
      team1: { name: 'CMAS', score: '--' },
      team2: { name: 'SMFT', score: '--' }
    },
    // Add more game objects as needed
  ]
  
  onMounted(() => {
    const navbar = document.getElementById("mainNav")
    let prevScrollPos = window.pageYOffset
  
    window.onscroll = function () {
      const currentScrollPos = window.pageYOffset
      if (prevScrollPos > currentScrollPos) {
        navbar.style.transform = "translateY(0)"
      } else {
        navbar.style.transform = "translateY(-80px)"
      }
      prevScrollPos = currentScrollPos
    }
  })
  </script>
  
  <style scoped>
  #mainNav {
    transition: transform 0.3s ease-in-out;
  }
  </style>