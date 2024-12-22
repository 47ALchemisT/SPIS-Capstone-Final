<template>
  <div v-if="isOpen">
    <div class="fixed inset-0 flex items-center bg-black bg-opacity-50 justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
        <h2 class="text-xl font-semibold mb-4">Update Team</h2>
        <form @submit.prevent="updateTeam">
          <div class="mb-4">
            <label for="teamName" class="block text-sm font-medium text-gray-700">Team Name</label>
            <input
              v-model="formData.assigned_team_name"
              type="text"
              id="teamName"
              class="mt-1 block w-full border border-gray-300 rounded-md p-2"
              required
            />
          </div>
          <div class="mb-4">
            <label for="collegeName" class="block text-sm font-medium text-gray-700">College Name</label>
            <input
              v-model="team.college.name"
              type="text"
              id="collegeName"
              disabled
              class="mt-1 block w-full border border-gray-300 rounded-md p-2"
              required
            />
          </div>
          <div class="flex justify-end">
            <button
              type="button"
              @click="closeModal"
              class="mr-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-md px-5 py-2.5 hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="bg-blue-600 text-white text-sm font-medium rounded-md px-5 py-2.5 hover:bg-blue-700"
            >
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isOpen: Boolean,
    team: Object,
  },
  data() {
    return {
      formData: {
        assigned_team_name: '',
        college_id: '',
        palakasan_id: ''
      }
    }
  },
  watch: {
    team: {
      immediate: true,
      handler(newTeam) {
        if (newTeam) {
          this.formData = {
            assigned_team_name: newTeam.assigned_team_name || '',
            college_id: newTeam.college?.id || '',
            palakasan_id: newTeam.palakasan_id || ''
          }
        }
      }
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    updateTeam() {
      const updateData = {
        ...this.formData,
        id: this.team.id
      };
      this.$emit('update', updateData);
    },
  },
};
</script>