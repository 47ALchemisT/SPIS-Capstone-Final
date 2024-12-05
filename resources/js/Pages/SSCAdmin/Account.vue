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
            <div class="mt-3 flex">
                <!-- Sidebar -->
                <div class="w-[20rem] sticky top-0 overflow-y-auto bg-white px-2">
                    <!-- Create Account Button -->
                    <div class="sticky top-0 bg-white pt-4 pb-2">
                        <button @click="openModal(false)" type="button" class="w-full flex items-center justify-center gap-2 text-white text-sm bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-4 py-2.5 mb-4">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M18 6h-6a2 2 0 0 0-2-2H6C4.346 4 3 5.346 3 7v10c0 1.654 1.346 3 3 3h12c1.654 0 3-1.346 3-3V9c0-1.654-1.346-3-3-3m0 12H6a1 1 0 0 1-1-1v-7h4c.275 0 .5-.225.5-.5S9.275 9 9 9H5V7a1 1 0 0 1 1-1h4a2 2 0 0 0 2 2h6a1 1 0 0 1 1 1h-4c-.275 0-.5.225-.5.5s.225.5.5.5h4v7a1 1 0 0 1-1 1m-3-6h-2v-2a1 1 0 1 0-2 0v2H9a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2"/></svg>
                            Create Account
                        </button>

                        <!-- Search -->
                        <div class="relative mb-4">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search accounts..."
                                class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-300 focus:border-blue-500 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            >
                            <button
                                v-if="searchQuery"
                                @click="clearSearch"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Role Folders -->
                    <div class="space-y-2 pb-4">
                        <div 
                            v-for="role in uniqueRoles" 
                            :key="role"
                            @click="selectRole(role)"
                            class="cursor-pointer"
                        >
                            <div class="p-3 rounded-lg transition-all duration-200"
                                :class="{ 'bg-gray-100': selectedRole === role }">
                                <div class="flex gap-3">
                                    <div class="text-gray-400">
                                        <svg v-if="selectedRole === role" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19 20H4c-1.11 0-2-.9-2-2V6c0-1.11.89-2 2-2h6l2 2h7c1.097 0 2 .903 2 2H4v10l2.14-8h17.07l-2.28 8.5c-.23.87-1.01 1.5-1.93 1.5z"/>
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h6l2 2h8q.825 0 1.413.588T22 8v10q0 .825-.587 1.413T20 20z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">{{ role }}</h3>
                                        <p class="text-xs text-gray-500">{{ getRoleCount(role) }} accounts</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1 p-4">
                    <!-- Table View (shows when folder is clicked) -->
                    <div v-if="selectedRole" class="bg-white rounded-lg border border-gray-200">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-900">{{ selectedRole }} Accounts</h2>
                            <button 
                                @click="selectedRole = null" 
                                class="text-gray-500 hover:text-gray-700"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Number</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="account in paginatedAccounts" :key="account.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ account.student ? account.student.first_name : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ account.student ? account.student.last_name : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ account.username }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ account.student ? account.student.id_number : '-' }}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-700">
                                        Showing {{ startIndex + 1 }} to {{ endIndex }} of {{ filteredAccounts.length }} entries
                                    </span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button 
                                        @click="currentPage--" 
                                        :disabled="currentPage === 1"
                                        class="px-3 py-1 rounded-md border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        Previous
                                    </button>
                                    <span class="text-sm text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
                                    <button 
                                        @click="currentPage++" 
                                        :disabled="currentPage === totalPages"
                                        class="px-3 py-1 rounded-md border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-gray-500 text-center mt-4">Select a role to view accounts</p>
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
                            <div class="col-span-3 py-2.5 px-4 bg-gray-50 ring-1 ring-gray-300 rounded-lg text-sm w-full">
                                <span v-if="selectedStudentName" class="text-sm text-gray-700"><strong>{{ selectedStudentName }}</strong></span>
                                <span v-else class="text-sm text-gray-500">No student selected</span>
                            </div>
                            <button type="button" @click="openStudentModal" class="col-span-1 bg-blue-700 hover:bg-blue-700/90 text-white py-2.5 px-3 rounded-lg text-sm font-medium shadow  transition-colors">
                                <i class="fa-solid fa-file mr-1"></i>   
                                Student                          
                            </button>
                            <span v-if="form.errors.student_id" class="text-red-500">{{ form.errors.student_id }}</span>
                        </div>
                        <div class="p-3 mb-4 text-xs bg-blue-50 text-blue-700 rounded-lg">
                            <h1 class="font-bold mb-1">Note</h1>
                            <p>
                                In selecting a student, you need to click the <span class="font-semibold">Student</span> button at the right side of the form and select a student you want to put in this role.
                            </p>
                            <div class=" mt-2">
                                <h1 class="font-medium">Roles</h1>
                                <div class="px-2 mt-1">
                                    <div class="font-medium">- Sub Admin : <span class="font-normal">helps in scheduling the sports game</span></div>
                                    <div class="font-medium">- College Sport Head : <span class="font-normal">Assigns student as players in their specific sport</span></div>
                                    <div class="font-medium">- Facilitator : <span class="font-normal">Manages their assigned sport</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Input for Role -->
                        <div class="mb-4">
                            <label for="role" class="block font-medium mb-1.5 text-sm text-gray-800">Role</label>
                            <select
                                v-model="form.role"
                                id="role"
                                class="w-full border px-3 py-2.5 text-sm text-gray rounded-lg border-gray-300"
                            >
                                <option value="" disabled>Select role...</option>
                                <option value="Sub Admin">Sub Admin</option>
                                <option value="Facilitator">Facilitator</option>
                                <option value="College Sport Head">College Sport Head</option>
                            </select>
                            <span v-if="form.errors.role" class="text-red-500">{{ form.errors.role }}</span>
                        </div>

                        <!-- Modal Buttons -->
                        <div class="flex justify-end text-sm font-medium">
                            <button type="button" @click="closeModal" class="mr-2 px-4 py-2.5 bg-gray-50 hover:bg-gray-100 rounded-lg">Cancel</button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm transition relative"
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
                            {{ student.first_name }} {{ student.last_name }}
                        </li>
                    </ul>

                    <!-- Modal Buttons -->
                    <div class="flex absolute bottom-6 right-6 justify-end">
                        <button type="button" @click="closeStudentModal" class="px-4 py-2.5  font-medium text-sm bg-gray-50 hover:bg-gray-100 rounded-lg">Cancel</button>
                        <button
                            type="button"
                            @click="confirmStudentSelection"
                            class="ml-2 bg-blue-700 hover:bg-blue-700/90 text-white text-sm px-4 py-2.5  font-medium rounded-lg"
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
import { router,Head, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
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

// Create a ref for student accounts from props
const studentAccounts = ref(props.accounts);

const selectedRole = ref(null);

const uniqueRoles = computed(() => {
    if (!props.studentAccounts) return [];
    return [...new Set(props.studentAccounts.map(account => account.role))];
});

// Get count of accounts for each role
const getRoleCount = (role) => {
    if (!props.studentAccounts) return 0;
    return props.studentAccounts.filter(account => account.role === role).length;
};

// Filter accounts based on selected role and search query
const filteredAccounts = computed(() => {
    if (!props.studentAccounts) return [];
    
    let filtered = props.studentAccounts;
    
    // Filter by role if selected
    if (selectedRole.value) {
        filtered = filtered.filter(account => account.role === selectedRole.value);
    }
    
    // Filter by search query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(account => {
            // Search in student name if student exists
            const studentName = account.student 
                ? `${account.student.first_name} ${account.student.last_name}`.toLowerCase()
                : '';
            
            // Search in username
            const username = account.username.toLowerCase();
            
            // Return true if either student name or username matches the search query
            return studentName.includes(query) || username.includes(query);
        });
    }
    
    return filtered;
});

// Handle role selection
const selectRole = (role) => {
    selectedRole.value = selectedRole.value === role ? null : role;
};

// Pagination
const currentPage = ref(1);
const itemsPerPage = 16;
const searchQuery = ref('');

// Computed properties for pagination
const totalPages = computed(() => Math.ceil(filteredAccounts.value.length / itemsPerPage));
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage, filteredAccounts.value.length));
const paginatedAccounts = computed(() => {
    return filteredAccounts.value.slice(startIndex.value, Math.min(endIndex.value, startIndex.value + itemsPerPage));
});

// Reset pagination when selecting a new role
watch(selectedRole, () => {
    currentPage.value = 1;
});

// Reset pagination when search query changes
watch(searchQuery, () => {
    currentPage.value = 1;
});

const clearSearch = () => {
    searchQuery.value = '';
    currentPage.value = 1;
};
</script>

<style scoped>
/* Modal-specific styles */
</style>
