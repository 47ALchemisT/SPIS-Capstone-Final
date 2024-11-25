<template>
    <Head title="Account"/>
    <AppLayout>
        <template v-slot:default>
            <!--Head-->
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Account</h1>
                <h1 class="text-sm font-normal text-gray-700">List of Accounts</h1>
            </div>

            <!--Content-->
            <div class="mt-3 space-y-4">
                <!--Utility-->
                <div class="utility">
                    <div class="flex justify-between items-center">
                        <div class="search flex space-x-2 items-center">
                            <!-- Search Input -->
                            <div class="relative">
                                <!-- Search Icon (Left) -->
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </span>

                                <!-- Input Field -->
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search accounts..."
                                    class="w-64 pl-10 pr-10 py-2 bg-white shadow-sm border border-gray-300 focus:border-blue-500 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                                >

                                <!-- Clear Button (Right) -->
                                <button
                                    v-if="searchQuery"
                                    @click="clearSearch"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!--Buttons-->
                        <div class="flex items-center space-x-2.5">
                            <button @click="openModal(false)" type="button" class="flex items-center gap-2 text-white text-sm bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M18 6h-6a2 2 0 0 0-2-2H6C4.346 4 3 5.346 3 7v10c0 1.654 1.346 3 3 3h12c1.654 0 3-1.346 3-3V9c0-1.654-1.346-3-3-3m0 12H6a1 1 0 0 1-1-1v-7h4c.275 0 .5-.225.5-.5S9.275 9 9 9H5V7a1 1 0 0 1 1-1h4a2 2 0 0 0 2 2h6a1 1 0 0 1 1 1h-4c-.275 0-.5.225-.5.5s.225.5.5.5h4v7a1 1 0 0 1-1 1m-3-6h-2v-2a1 1 0 1 0-2 0v2H9a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2"/></svg>
                                Create Account
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Main Content, List of Accounts -->
                <div>
                    <div v-if="studentAccounts.length" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Loop through each account -->
                        <div v-for="account in studentAccounts" :key="account.id" class="group bg-white ring-1 ring-gray-300 p-4 rounded-lg shadow hover:ring-blue-400 hover:bg-blue-50 transition">
                            <div class="mb-2">
                                <svg class="w-8 h-8 group-hover:text-blue-600" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a5 5 0 1 0 5 5a5 5 0 0 0-5-5m0 8a3 3 0 1 1 3-3a3 3 0 0 1-3 3m9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"/></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ account.student.first_name}} {{account.student.last_name}}</h3>
                            <p class="text-xs text-gray-600">{{ account.role }}</p>
                        </div>
                    </div>
                    <p v-else class="text-gray-500 text-center">No accounts found.</p>
                </div>
            </div>

            <!-- Modal for adding new account -->
            <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                    <h3 class="text-lg font-semibold mb-4">Add New Account</h3>

                    <form @submit.prevent="submitAccount">
                        <!-- Display selected student -->
                        <label for="student" class=" text-gray-800 text-sm font-medium">Select a student</label>
                        <div class="mb-2 mt-1.5 grid grid-cols-4 items-center gap-3">
                            <div class="col-span-3 py-2 px-4 bg-gray-50 ring-1 ring-gray-300 rounded-lg text-sm w-full">
                                <span v-if="selectedStudentName" class="text-sm text-gray-700"><strong>{{ selectedStudentName }}</strong></span>
                                <span v-else class="text-sm text-gray-500">No student selected</span>
                            </div>
                            <button type="button" @click="openStudentModal" class="col-span-1 bg-blue-700 hover:bg-blue-700/90 text-white py-2 px-3 rounded-lg text-sm font-medium shadow  transition-colors">
                                <i class="fa-solid fa-file mr-1"></i>   
                                Student                          
                            </button>
                            <span v-if="form.errors.student_id" class="text-red-500">{{ form.errors.student_id }}</span>
                        </div>
                        <div class="p-2 mb-4 text-xs bg-blue-50 text-blue-700 rounded-lg">
                            <h1 class="font-bold mb-1">Note</h1>
                            <p>
                                In selecting a student, you need to click the <span class="font-semibold">Student</span> button at the right side of the form and select a student you want to put in this role
                            </p>
                        </div>

                        <!-- Input for Role -->
                        <div class="mb-4">
                            <label for="role" class="block font-medium mb-1.5 text-sm text-gray-800">Role</label>
                            <select
                                v-model="form.role"
                                id="role"
                                class="w-full border px-3 py-2 text-sm text-gray rounded-lg border-gray-300"
                            >
                                <option value="" disabled>Select role...</option>
                                <option value="Admin">Admin</option>
                                <option value="Sub Admin">Sub Admin</option>
                                <option value="Facilitator">Facilitator</option>
                                <option value="College Sport Head">College Sport Head</option>
                            </select>
                            <span v-if="form.errors.role" class="text-red-500">{{ form.errors.role }}</span>
                        </div>

                        <!-- Modal Buttons -->
                        <div class="flex justify-end text-sm">
                            <button type="button" @click="closeModal" class="mr-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg">Cancel</button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition relative"
                            >
                                <span v-if="!form.processing">
                                    Create account
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

            <!-- Second modal for listing students -->
            <div v-if="isStudentModalOpen" class="fixed inset-0 bg-black bg-opacity-10 z-50 flex justify-center items-center">
                <div class="bg-white relative p-6 rounded-lg shadow-lg h-[28rem] w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-4">Select a Student</h3>
                    <div class="grid grid-cols-5  items-center gap-3 mb-3">
                        <!-- Search Input for students -->
                        <div class="col-span-3 relative">
                            <input
                                v-model="studentSearchQuery"
                                type="text"
                                placeholder="Search student..."
                                class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:outline-none"
                            >
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-sm w-full py-2.5 rounded-lg text-blue-700 px-4 bg-blue-50">Total Students :  <span class="font-bold">{{ students.length }}</span></span>
                        </div>

                    </div>

                    <!-- List of students with search filter -->
                    <ul class="max-h-60 overflow-y-auto mb-4">
                        <li
                            v-for="student in filteredStudents"
                            :key="student.id"
                            @click="selectStudentForPreview(student)"
                            class="py-2 px-4 cursor-pointer hover:bg-gray-100 transition-colors "
                            :class="{ 'bg-gray-200 hover:bg-gray-200': student.id === selectedStudentId }"
                        >
                            {{ student.first_name }}
                        </li>
                    </ul>

                    <!-- Modal Buttons -->
                    <div class="flex absolute bottom-6 right-6 justify-end">
                        <button type="button" @click="closeStudentModal" class="px-4 py-2 text-sm bg-gray-50 hover:bg-gray-100 rounded-lg">Cancel</button>
                        <button
                            type="button"
                            @click="confirmStudentSelection"
                            class="ml-2 bg-blue-700 hover:bg-blue-700/90 text-white text-sm px-4 py-2 rounded-lg"
                            :disabled="!selectedStudentId"
                        >
                            Select
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/Layout/DashboardLayout.vue';

// Props for students and errors
const props = defineProps({
    students: Array,
    studentAccounts: Array,
    errors: Object
});

// Available students and modal states
const availableStudents = ref(props.students || []);
const form = useForm({
    student_id: '',
    role: '',
    status: true
});

const isModalOpen = ref(false);
const isStudentModalOpen = ref(false);

// Search query for students
const studentSearchQuery = ref('');
const selectedStudentId = ref(null);
const selectedStudentName = ref('');

// Computed property to filter students based on search query
const filteredStudents = computed(() => {
    const query = studentSearchQuery.value.toLowerCase();
    return availableStudents.value.filter(student =>
    student.first_name && student.first_name.toLowerCase().includes(query)
    );
});

// Functions to open/close the first modal
const openModal = () => {
    isModalOpen.value = true;
};
const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    selectedStudentName.value = ''; // Reset the selected student
    selectedStudentId.value = null; // Reset the student ID
};

// Functions to open/close the second modal
const openStudentModal = () => {
    isStudentModalOpen.value = true;
};
const closeStudentModal = () => {
    isStudentModalOpen.value = false;
    selectedStudentId.value = null; // Reset selected student when modal is closed
};

// Function to select a student for preview before confirmation
const selectStudentForPreview = (student) => {
    selectedStudentId.value = student.id;
    selectedStudentName.value = student.first_name;  // Update the name here as well
};


// Function to confirm student selection
const confirmStudentSelection = () => {
    const selectedStudent = availableStudents.value.find(student => student.id === selectedStudentId.value);
    if (selectedStudent) {
        form.student_id = selectedStudent.id;
        selectedStudentName.value = selectedStudent.first_name;
    }
    closeStudentModal();
};

// Submit account function
const submitAccount = () => {
    form.post(route('account.store'), {
        onSuccess: () => {
            closeModal();
        }
    });
};
</script>

<style scoped>
/* Modal-specific styles */
</style>
