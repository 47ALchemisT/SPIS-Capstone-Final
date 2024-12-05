<template>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
      <div class="space-y-6">
        <!-- Account Settings Section -->
        <div class="">
          <h2 class="text-lg font-medium text-gray-900">Account Settings</h2>
          <p class="mt-1 text-sm text-gray-600">
            Manage your account settings and preferences.
          </p>
        </div>

        <!-- Personal Information Section -->
        <div class="space-y-4">
          <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="space-y-6">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                  <p class="mt-1 text-sm text-gray-600">Your personal details and information.</p>
                </div>
                <div class="flex items-center">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    Student
                  </span>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Full Name</label>
                  <p class="mt-2 text-sm text-gray-900">{{ facilitator.student.first_name }} {{ facilitator.student.last_name }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Student ID</label>
                  <p class="mt-2 text-sm text-gray-900">{{ facilitator.student.id_number }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Email Address</label>
                  <p class="mt-2 text-sm text-gray-900">{{ facilitator.student.univ_email }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Course</label>
                  <p class="mt-2 text-sm text-gray-900">{{ facilitator.student.college }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Account Information Section -->
        <div class="space-y-4">
          <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="space-y-6">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                  <p class="mt-1 text-sm text-gray-600">Details about your facilitator account.</p>
                </div>
                <div class="flex items-center">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Active
                  </span>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Account Type</label>
                  <div class="mt-2 flex items-center">
                    <p class="text-sm text-gray-900">Facilitator</p>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Account Created</label>
                  <div class="mt-2 flex items-center">
                    <p class="text-sm text-gray-900">{{ formatDate(facilitator.created_at) }}</p>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Assigned Sports</label>
                  <div class="mt-2 flex items-center">
                    <p class="text-sm text-gray-900">{{ assignedSports.length || 0 }} Sports</p>
                  </div>
                </div>
              </div>
              <!-- Password Change Section -->
              <div class="space-y-4">
                <div class="bg-blue-50 rounded-lg p-6">
                  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                    <div>
                      <h3 class="text-md font-medium text-blue-800 mb-1">Password & Security</h3>
                      <p class="text-sm text-blue-500">Update your password to keep your account secure</p>
                    </div>
                    <Link
                      :href="route('password.change.form', facilitator.id)"
                      class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                    >
                      Change Password
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Support Section -->
        <div class="mt-10">
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Need Help?</h3>
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                  <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z" clip-rule="evenodd" />
                </svg>
              </div>
              <div>
                <p class="text-gray-900 font-medium">Contact Support</p>
                <p class="text-sm text-gray-600 mb-2">Need assistance? Our support team is here to help.</p>
                <a href="mailto:support@palakasan.com" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                  support@palakasan.com
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  facilitator: {
    type: Object,
    required: true
  },
  activityLogs: {
    type: Array,
    default: () => []
  },
  assignedSports: Array
});

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};
</script>
