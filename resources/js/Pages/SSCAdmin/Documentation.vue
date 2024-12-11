<template>
  <Head title="Documentation"/>
  <Layout>
    <div class="min-h-screen flex">
      <!-- Main Content -->
      <div class="flex-1 max-w-4xl mx-auto px-4 py-8 lg:max-w-5xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Documentation</h1>
        
        <div class="bg-white rounded-lg space-y-6">
          <section id="create-event" class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">1. Create a Palakasan Event</h2>
            <div class="">
              <p class="text-gray-700">
                Start by clicking the <span><span class="text-blue-600 font-semibold">Create Palakasan</span></span> button to create a new Palakasan event with a theme, date range, and description. This is the foundational step that sets the context for your entire event.
              </p>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/step1.png" alt="">
              </div>
              <p class="text-gray-700 mt-4">
                Fill the form with the necessary information regarding the Palakasan, including the event theme, date range, and description.
              </p>
            </div>
          </section>

          <section id="add-teams" class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">2. Form Line Ups</h2>
            <div class="">
              <p class="text-gray-700">
                Once the Palakasan event is created,navigate to the <span><span class="text-blue-600 font-semibold">Line Ups</span></span> tab. Here you can add teams to the lineup. This step is crucial as it sets the foundation for the event's teams and their performance.
              </p>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/lineup1.png" alt="">
              </div>
              <p class="text-gray-700 mt-4">
                When in the lineup tab, click the <span><span class="text-blue-600 font-semibold">Form Line Ups</span></span> button to add the lineup.
              </p>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/lineup3.png" alt="">
              </div>
              <p class="text-gray-700 mt-4">
                Fill the form with the necessary information regarding the line up, after you click the <span><span class="text-blue-600 font-semibold">Confirm</span></span> button.
              </p>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/lineup2.png" alt="">
              </div>
              <div class="bg-blue-50 mt-4 p-4">
                <p class="text-blue-700">
                  <span class="font-semibold">Note:</span> If there are leagues that are already created, you cannot add a team.
                </p>
              </div>
            </div>
          </section>

          <section id="select-sports" class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">3. Create a League</h2>
            <div class="">
              <p class="text-gray-700">
                Go to the <span><span class="text-blue-600 font-semibold">Leagues</span></span> tab, there you will be able to create a league.
              </p>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/leagues.png" alt="">
              </div>
              <p class="text-gray-700 mt-4">
                In the Leagues tab there is a required number of <span><span class="text-blue-600 font-semibold">Line ups</span></span> before you can start a league, the minimum number of Line ups required is <span class="text-blue-600 font-semibold">4</span> before you can create a league.
              </p>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/leagues1.png" alt="">
              </div>
              <p class="text-gray-700 mt-4">
                When there are enough Line ups, click the <span><span class="text-blue-600 font-semibold">Create a League</span></span> button to create a league.
              </p>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/leagues2.png" alt="">
              </div>
              <p class="text-gray-700 mt-4">
                Fill up the form with the necessary information regarding the league, after you click the <span><span class="text-blue-600 font-semibold">Confirm</span></span> button.
              </p>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/leagues3.png" alt="">
              </div>
              <p class="text-gray-700 mt-4">
                After you click the <span><span class="text-blue-600 font-semibold">Confirm</span></span> button, the league will be created. If you notice on the top right corner of the card you will see a circle <span class="text-gray-300 font-semibold"><i><i class="fa-solid fa-circle text-xs"></i></i></span> this is the status of the league.
                There are 4 status you need to know of and their color:
                <ul class=" mt-4 text-gray-700 space-y-2">
                  <li><span class="text-gray-300 font-semibold"><i><i class="fa-solid fa-circle text-xs"></i></i> <span class="text-gray-700">Pending</span></span> - this means that the league is not scheduled yet and need to be scheduled.</li>
                  <li><span class="text-yellow-500 font-semibold"><i><i class="fa-solid fa-circle text-xs"></i></i> <span class="text-gray-700">Scheduled</span></span> - this means that the league is scheduled and ready to be played.</li>
                  <li><span class="text-red-500 font-semibold"><i><i class="fa-solid fa-circle text-xs"></i></i> <span class="text-gray-700">Live</span></span> - this means that the league is live and the game is ready to be played.</li>
                  <li><span class="text-green-500 font-semibold"><i><i class="fa-solid fa-circle text-xs"></i></i> <span class="text-gray-700">Completed</span></span> - this means that the league is completed and the game is over.</li>
                </ul>
              </p>
              <div class="mt-4">
                <img  src="/resources/assets/Documentation/leagues4.png" alt="">
              </div>
              <p class="text-gray-700 mt-4">
                If you click the card it will redirect you to the league details page. Once you are on the league details page, click the <span><span class="text-blue-600 font-semibold">Start Game</span></span> button it will display a modal that lets you select the venue and there is an option to randomize the matchmaking of the teams.
              </p>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/leagues5.png" alt="">
              </div>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/leagues6.png" alt="">
              </div>
              <p class="text-gray-700 mt-4">
                when the matches are created all you need to do now is set the schedule of each game, after setting all the schedule a button will show up at the top right side saying "Save Schedule" this will save the schedule of the league.
              </p>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/leagues7.png" alt="">
              </div>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/leagues8.png" alt="">
              </div>
              <div class="mt-4">
                <img src="/resources/assets/Documentation/leaues10.png" alt="">
              </div>
              <p class="text-gray-700 mt-4">
                That is all you need to know for creating a league. There are also tabs there where you can see the standing, bracket and players of this specific league.
              </p>
            </div>
          </section>

          <section id="assign-facilitators" class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">4. Print Reports</h2>
            <div class="">
              <p class="text-gray-700">
                In the tabs there is a Schedules and Ranking, you can use them to generate the reports needed if you want to print them out.
              </p>
            </div>
          </section>

          <section id="start-event" class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">5. Start the Event</h2>
            <div class=" ">
              <p class="text-gray-700">
                Once everything is set up, start the Palakasan event to begin tracking scores and rankings. Ensure all systems are ready.
              </p>
            </div>
            <ul class="list-disc list-inside mt-4 text-gray-700 space-y-2">
              <li>Conduct a final systems check</li>
              <li>Brief all facilitators</li>
              <li>Prepare scoring and tracking tools</li>
              <li>Open the event officially</li>
            </ul>
          </section>
        </div>
      </div>

      <!-- Right Side Navigation -->
      <div class="hidden lg:block lg:w-64 xl:w-80  p-6 sticky top-0 h-screen overflow-y-auto">
        <nav>
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Documentation Contents</h3>
          <ul class="space-y-2">
            <li>
              <a 
                href="#create-event" 
                @click.prevent="scrollToSection('create-event')"
                :class="{'text-blue-800 font-bold': activeSection === 'create-event'}"
                class="text-blue-600 hover:text-blue-800 transition-colors flex items-center"
              >
                1. Create a Palakasan Event
              </a>
            </li>
            <li>
              <a 
                href="#add-teams" 
                @click.prevent="scrollToSection('add-teams')"
                :class="{'text-blue-800 font-bold': activeSection === 'add-teams'}"
                class="text-blue-600 hover:text-blue-800 transition-colors flex items-center"
              >
                2. Form Line Ups
              </a>
            </li>
            <li>
              <a 
                href="#select-sports" 
                @click.prevent="scrollToSection('select-sports')"
                :class="{'text-blue-800 font-bold': activeSection === 'select-sports'}"
                class="text-blue-600 hover:text-blue-800 transition-colors flex items-center"
              >
                3. Create a League
              </a>
            </li>
            <li>
              <a 
                href="#assign-facilitators" 
                @click.prevent="scrollToSection('assign-facilitators')"
                :class="{'text-blue-800 font-bold': activeSection === 'assign-facilitators'}"
                class="text-blue-600 hover:text-blue-800 transition-colors flex items-center"
              >
                4. Generate Reports
              </a>
            </li>
            <li>
              <a 
                href="#start-event" 
                @click.prevent="scrollToSection('start-event')"
                :class="{'text-blue-800 font-bold': activeSection === 'start-event'}"
                class="text-blue-600 hover:text-blue-800 transition-colors flex items-center"
              >
                5. Start the Palakasan
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref } from 'vue';
import Layout from '@/Layout/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';

const activeSection = ref('create-event');

const scrollToSection = (sectionId) => {
  const section = document.getElementById(sectionId);
  if (section) {
    section.scrollIntoView({ 
      behavior: 'smooth', 
      block: 'start' 
    });
    activeSection.value = sectionId;
  }
};
</script>

<style scoped>
/* Optional additional styling can be added here */
</style>