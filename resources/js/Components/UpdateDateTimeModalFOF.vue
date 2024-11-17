<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="relative p-4 w-full max-w-[30rem] max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
          <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              Update Schedule
            </h3>
            <button @click="close" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <div class="p-4 pt-0">
            <form @submit.prevent="updateTime">
              <!-- Venue Selection -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mt-5 mb-2">Venue</label>
                <select 
                  v-model="form.sport_variation_venue_id"
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
              <label class="text-sm font-medium text-gray-900 mt-5 dark:text-white mb-2 block">
                Pick your date
              </label>
              <div class="mx-auto sm:mx-0 flex justify-center mb-5">
                <input
                  type="date"
                  v-model="form.date"
                  :min="getCurrentDate()"
                  @change="updateAvailableTimeSlots"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
  
              <!-- Time Slots -->
              <label class="text-sm font-medium text-gray-900 dark:text-white mb-2 block">
                Pick your time
              </label>
              <ul class="grid w-full grid-cols-4 gap-2 mb-5">
                <li v-for="slot in availableTimeSlots" :key="slot.value">
                  <input 
                    type="radio" 
                    :id="slot.value" 
                    :value="slot.value" 
                    v-model="form.time" 
                    class="hidden peer" 
                    name="timetable"
                    :disabled="slot.disabled"
                  >
                  <label :for="slot.value"
                    :class="[
                      'inline-flex items-center justify-center w-full px-2 py-1.5 text-sm font-medium text-center',
                      slot.disabled ? 'cursor-not-allowed bg-gray-100 text-gray-400' : 'hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border rounded-lg cursor-pointer text-gray-500 border-gray-300 dark:border-gray-700 dark:peer-checked:border-blue-500 peer-checked:border-blue-700 dark:hover:border-gray-600 dark:peer-checked:text-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-600 dark:peer-checked:bg-blue-900'
                    ]"
                  >
                    {{ slot.label }}
                  </label>
                </li>
              </ul>
  
              <div v-if="errorMessage" class="mb-4 text-red-500 text-sm">{{ errorMessage }}</div>
  
              <!-- Modal Buttons -->
              <div class="grid grid-cols-2 gap-2">
                <button 
                  type="button"
                  @click="close"
                  class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                >
                  Discard
                </button>
                <button 
                  type="submit"
                  :disabled="form.processing"
                  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                >
                  {{ form.processing ? 'Saving...' : 'Save' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, watch } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import axios from 'axios';
  
  const props = defineProps({
    show: Boolean,
    variation: Object,
    venues: Array,
  });
  
  const emit = defineEmits(['close', 'updated']);
  
  const form = useForm({
    sport_variation_id: '',
    sport_variation_venue_id: '',
    date: '',
    time: '',
  });
  
  const errorMessage = ref('');
  const availableTimeSlots = ref([
    { label: '8:00 AM', value: '08:00:00', disabled: false },
    { label: '9:00 AM', value: '09:00:00', disabled: false },
    { label: '10:00 AM', value: '10:00:00', disabled: false },
    { label: '11:00 AM', value: '11:00:00', disabled: false },
    { label: '1:00 PM', value: '13:00:00', disabled: false },
    { label: '2:00 PM', value: '14:00:00', disabled: false },
    { label: '3:00 PM', value: '15:00:00', disabled: false },
    { label: '4:00 PM', value: '16:00:00', disabled: false },
  ]);
  
  watch(() => props.variation, (newVariation) => {
    if (newVariation) {
      form.sport_variation_id = newVariation.id;
      form.sport_variation_venue_id = newVariation.sport_variation_venue_id;
      form.date = newVariation.date;
      form.time = newVariation.time;
      updateAvailableTimeSlots();
    }
  }, { immediate: true });
  
  const close = () => {
    emit('close');
    form.reset();
    form.clearErrors();
    errorMessage.value = '';
  };
  
  const getCurrentDate = () => {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  };
  
  const updateAvailableTimeSlots = async () => {
    if (!form.date || !form.sport_variation_venue_id) return;
  
    try {
      const response = await axios.post('/api/check-venue-availability', {
        venue_id: form.sport_variation_venue_id,
        date: form.date,
        variation_id: form.sport_variation_id,
      });
  
      availableTimeSlots.value = response.data.availableTimeSlots.map(slot => ({
        label: slot.label,
        value: slot.value,
        disabled: !slot.available
      }));
  
      // Reset the selected time if it's no longer available
      if (form.time && availableTimeSlots.value.find(slot => slot.value === form.time)?.disabled) {
        form.time = '';
      }
    } catch (error) {
      console.error('Error fetching available time slots:', error);
      errorMessage.value = 'Failed to fetch available time slots. Please try again.';
    }
  };
  
  const updateTime = async () => {
    errorMessage.value = '';
    try {
      await form.patch(route('sport-variations.update-time', form.sport_variation_id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          emit('updated');
          close();
        },
        onError: (errors) => {
          console.error('Update time errors:', errors);
          errorMessage.value = errors.time || 'Please correct the errors and try again.';
        },
      });
    } catch (error) {
      console.error('Update time error:', error);
      errorMessage.value = 'An unexpected error occurred. Please try again.';
    }
  };
  
  watch(() => form.date, updateAvailableTimeSlots);
  watch(() => form.sport_variation_venue_id, updateAvailableTimeSlots);
  </script>