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
            <div class="mt-3 flex gap-6">
                <!-- Sidebar -->
                <div class="w-[20rem] sticky top-0 overflow-y-auto bg-white px-2 py-4 h-[calc(100vh-4rem)]">
                    <!-- Create Account Button -->
                    <div class="sticky top-0 bg-white pb-4">
                        <button 
                            @click="openModal(false)" 
                            type="button" 
                            class="w-full flex items-center justify-center gap-2 text-white text-sm bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-4 py-2.5 shadow-sm transition-all duration-200"
                        >
                            <i class="fas fa-user-plus"></i>
                            Create Account
                        </button>

                        <!-- Search -->
                        <div class="relative mt-4">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search accounts..."
                                class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm transition-all duration-300"
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
                    <div class="space-y-2">
                        <div 
                            v-for="role in uniqueRoles" 
                            :key="role"
                            @click="selectRole(role)"
                            class="cursor-pointer"
                        >
                            <div class="p-3 rounded-lg transition-all duration-200"
                                :class="{ 'bg-blue-50 ring-1 ring-blue-500/20': selectedRole === role }">
                                <div class="flex items-center gap-3">
                                    <div class="text-blue-600">
                                        <i class="fas fa-folder" :class="{ 'fa-folder-open': selectedRole === role }"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-900">{{ role }}</h3>
                                        <p class="text-xs text-gray-500">{{ getRoleCount(role) }} accounts</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1 py-4">
                    <!-- Table View -->
                    <div v-if="selectedRole" class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                                    <i class="fas fa-users text-blue-600 text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">{{ selectedRole }} Accounts</h2>
                                    <p class="text-sm text-gray-500">Manage {{ selectedRole.toLowerCase() }} accounts</p>
                                </div>
                            </div>
                            <button 
                                @click="selectedRole = null" 
                                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all"
                            >
                                <i class="fas fa-times"></i>
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
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button 
                                                @click="openDeleteModal(account)"
                                                class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1 rounded-lg text-sm font-medium transition-all"
                                            >
                                                Delete
                                            </button>
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
                                <div class="flex items-center gap-2">
                                    <button 
                                        @click="currentPage--" 
                                        :disabled="currentPage === 1"
                                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                                    >
                                        Previous
                                    </button>
                                    <span class="text-sm text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
                                    <button 
                                        @click="currentPage++" 
                                        :disabled="currentPage === totalPages"
                                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                                    >
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="h-[calc(100vh-12rem)] flex flex-col items-center justify-center text-center">
                        <div class="w-20 h-20 rounded-full bg-blue-50 flex items-center justify-center mb-4">
                            <i class="fas fa-folder-open text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No folder selected</h3>
                        <p class="text-sm text-gray-500">Select a role folder to view accounts</p>
                    </div>
                </div>
            </div>

            <!-- Modal for adding new account -->
            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="isModalOpen" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50">
                    <transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center p-4">
                            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg transform">
                                <h3 class="text-lg font-semibold mb-4">Add New Account</h3>

                                <form @submit.prevent="submitAccount">
                                    <!-- Display selected student -->
                                    <label for="student" class=" text-gray-800 text-sm font-medium">Select a student</label>
                                    <div class="mb-2 mt-1.5 grid grid-cols-4 items-center gap-3">
                                        <div class="col-span-3 py-2.5 px-4 bg-gray-50 ring-1 ring-gray-300 rounded-lg text-sm w-full">
                                            <span v-if="selectedStudentName" class="text-sm text-gray-700"><strong>{{ selectedStudentName }} </strong></span>
                                            <span v-else class="text-sm text-gray-500">No student selected</span>
                                        </div>
                                        <button type="button" @click="openStudentModal" class="col-span-1 bg-blue-600 hover:bg-blue-700/90 text-white py-2.5 px-3 rounded-lg text-sm font-medium shadow  transition-colors">
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
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm transition relative"
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
                    </transition>
                </div>
            </transition>

            <!-- Second modal for listing students -->
            <TransitionRoot appear :show="isStudentModalOpen" as="template">
                <Dialog as="div" @close="closeStudentModal" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black bg-opacity-25" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-lg bg-white p-6 text-left align-middle shadow-xl transition-all">
                                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                                        Select a Student
                                    </DialogTitle>

                                    <div class="grid grid-cols-5 items-center gap-3 mb-3">
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
                                            <span class="text-sm w-full py-2.5 rounded-lg text-blue-700 px-4 bg-blue-50">
                                                Total Students: <span class="font-bold">{{ students.length }}</span>
                                            </span>
                                        </div>
                                    </div>
                                            <!-- Display the message if the student has an active account -->
                                    <p v-if="studentAccountStatusMessage" class="text-sm p-2 bg-red-50 rounded-lg my-2 text-red-500">
                                        {{ studentAccountStatusMessage }}
                                    </p>
                                    <div class="h-[22rem] overflow-y-auto mb-4">
                                        <ul>
                                            <li
                                                v-for="student in filteredStudents"
                                                :key="student.id"
                                                @click="selectStudentForPreview(student)"
                                                class="py-2 px-4 cursor-pointer transition-colors"
                                                :class="{ 'bg-blue-100': student.id === selectedStudentId }"
                                            >
                                                <div class="flex items-center">
                                                    
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ student.first_name }} {{ student.last_name }}
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            ID: {{ student.id_number }}
                                                        </p>

                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Modal Buttons -->
                                    <div class="mt-4 flex justify-end space-x-3">
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="closeStudentModal"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            :disabled="!selectedStudentId"
                                            @click="confirmStudentSelection"
                                        >
                                            Select
                                        </button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

            <!-- Delete Confirmation Modal -->
            <TransitionRoot appear :show="isDeleteModalOpen" as="template">
                <Dialog as="div" @close="closeDeleteModal" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black bg-opacity-25" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-lg bg-white p-6 text-left align-middle shadow-xl transition-all">
                                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                        Delete Account
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Are you sure you want to delete this account? This action cannot be undone.
                                        </p>
                                    </div>

                                    <div class="mt-4 flex justify-end space-x-3">
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                            @click="closeDeleteModal"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-red-100 px-4 py-2 text-sm font-medium text-red-900 hover:bg-red-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                                            @click="deleteAccount"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
        </template>
    </AppLayout>
    <SuccessModal
        :show="showSuccessModal"
        :message="successMessage"
        @close="showSuccessModal = false"
     />
</template>

<script setup>
import { router,Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';
import AppLayout from '@/Layout/DashboardLayout.vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import SuccessModal from '@/Components/SuccessModal.vue';

// Props for students and errors
const props = defineProps({
    students: Array,
    studentAccounts: Array,
    errors: Object,
});

const showSuccessModal = ref(false);
const successMessage = ref('');

// Available students and modal states
const availableStudents = ref(props.students || []);
const form = useForm({
    student_id: '',
    role: '',
    status: true
});

onMounted(() => {
    if (props.studentAccounts) {
        studentAccounts.value = props.studentAccounts;
    } else {
        console.error('Failed to load student accounts');
    }
});

const isModalOpen = ref(false);
const isStudentModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const accountToDelete = ref(null);
const studentAccounts = ref([]);
// State for selected student
const selectedStudentId = ref(null);
const selectedStudentName = ref('');
const studentAccountStatusMessage = ref('');

// Search query for students
const studentSearchQuery = ref('');

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
    const existingAccount = studentAccounts.value.find(account => account.student_id === student.id);

    if (existingAccount && existingAccount.status) {
    studentAccountStatusMessage.value = `${student.first_name} ${student.last_name} already has an active account.`;
    console.log('Message set:', studentAccountStatusMessage.value); // Log the message
    return;
}

    selectedStudentId.value = student.id;
    selectedStudentName.value = student.first_name + ' ' + student.last_name;
    studentAccountStatusMessage.value = '';
};

// Function to confirm student selection
const confirmStudentSelection = () => {
    const selectedStudent = availableStudents.value.find(student => student.id === selectedStudentId.value);
    if (selectedStudent) {
        form.student_id = selectedStudent.id;
        selectedStudentName.value = selectedStudent.first_name + ' ' + selectedStudent.last_name;
    }
    closeStudentModal();
};

// Submit account function
const submitAccount = () => {
    // Store the current scroll position
    localStorage.setItem('scrollPosition', window.scrollY);

    form.post(route('account.store'), {
        onSuccess: () => {
            closeModal();
            showSuccessModal.value = true;
            successMessage.value = 'Account created successfully!';
        }
    });
};

// Restore scroll position on page load
window.addEventListener('load', () => {
    const scrollPosition = localStorage.getItem('scrollPosition');
    if (scrollPosition) {
        window.scrollTo(0, parseInt(scrollPosition, 10));
        localStorage.removeItem('scrollPosition'); // Clean up
    }
});

// Create a ref for student accounts from props
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

// Auto-select first role on mount
onMounted(() => {
    if (props.studentAccounts) {
        studentAccounts.value = props.studentAccounts;
        // Get the first available role and select it
        const roles = uniqueRoles.value;
        if (roles.length > 0) {
            selectedRole.value = roles[0];
        }
    } else {
        console.error('Failed to load student accounts');
    }
});

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

const openDeleteModal = (account) => {
    isDeleteModalOpen.value = true;
    accountToDelete.value = account;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    accountToDelete.value = null;
};

const deleteAccount = () => {
    router.delete(`/accounts/${accountToDelete.value.id}`, {
        onSuccess: () => {
            closeDeleteModal();
        },
    });
};
</script>

<style scoped>
/* Modal-specific styles */
</style>
