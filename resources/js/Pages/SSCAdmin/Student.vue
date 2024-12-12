<template>
    <Head title="Students"/>
    <AppLayout>
        <template v-slot:default>
            <!-- Head -->
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Students</h1>
                <h1 class="text-sm font-normal text-gray-700">List of Students</h1>
            </div>
            <!-- Content -->
            <div class="mt-3 flex gap-6">
                <!-- Sidebar -->
                <div class="w-[20rem] sticky top-2 overflow-y-auto bg-white px-2 py-4 ">
                    <!-- Import Button -->
                    <div class="sticky top-0 bg-white space-y-2">
                        <!-- Search -->
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search students..."
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
                        <button 
                            @click="showModal = true" 
                            type="button" 
                            class="w-full flex items-center justify-between gap-2 text-white text-sm bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-4 py-3 shadow-sm transition-all duration-200"
                        >
                            <p>
                                <i class="mr-2 fa-solid fa-file-import"></i>
                                Import Students
                            </p>
                            <p>
                                <i class="fa-solid fa-users"></i>
                            </p>

                        </button>
                        <button 
                            @click="showAddEmployeeModal = true" 
                            type="button" 
                            class="w-full flex items-center justify-between gap-2 text-white text-sm bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-4 py-3 shadow-sm transition-all duration-200"
                        >
                        <p>
                                <i class="mr-2 fa-solid fa-file-import"></i>
                                Add Employee
                            </p>
                            <p>
                                <i class=" fa-solid fa-user"></i>
                            </p>
                        </button>
                    </div>

                    <!-- College Folders -->
                    <div class="space-y-2 mt-4">
                        <div 
                            v-for="college in uniqueColleges" 
                            :key="college"
                            @click="selectCollege(college)"
                            class="cursor-pointer"
                        >
                            <div class="p-3 rounded-lg transition-all duration-200"
                                :class="{ 'bg-blue-50 ring-1 ring-blue-500/20': selectedCollege === college }">
                                <div class="flex gap-3">
                                    <div class="text-blue-600">
                                        <i class="fas fa-folder" :class="{ 'fa-folder-open': selectedCollege === college }"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-900">{{ college }}</h3>
                                        <p class="text-xs text-gray-500">{{ getCollegeCount(college) }} Participants</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="flex-1 py-4 overflow-x-hidden">
                    <!-- Table View -->
                    <div v-if="selectedCollege" class="bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-graduation-cap text-blue-600"></i>
                                <h2 class="text-lg font-semibold text-gray-900">{{ selectedCollege }} Participants</h2>
                            </div>
                            <button 
                                @click="selectedCollege = null" 
                                class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-all duration-200"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <!-- Table with better spacing -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">University Email</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="student in paginatedStudentsByCollege" :key="student.id" class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-600">{{ student.first_name[0] }}</span>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ student.first_name }} {{ student.last_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ student.id_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ student.univ_email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ student.contact }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Enhanced Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-700">
                                        Showing <span class="font-medium">{{ startIndex }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ filteredStudentsByCollege.length }}</span> entries
                                    </span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button 
                                        @click="currentPage--" 
                                        :disabled="currentPage === 1"
                                        class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                                    >
                                        <i class="fa-solid fa-chevron-left mr-2 text-xs"></i>
                                        Previous
                                    </button>
                                    <span class="text-sm font-medium text-gray-700">Page {{ currentPage }} of {{ totalPagesByCollege }}</span>
                                    <button 
                                        @click="currentPage++" 
                                        :disabled="currentPage === totalPagesByCollege"
                                        class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                                    >
                                        Next
                                        <i class="fa-solid fa-chevron-right ml-2 text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="flex flex-col items-center justify-center h-[calc(100vh-16rem)]">
                        <div class="text-gray-400 mb-4">
                            <i class="fa-solid fa-folder-open text-5xl"></i>
                        </div>
                        <p class="text-gray-500 text-lg">Select a college to view students</p>
                        <p class="text-gray-400 text-sm mt-2">Choose from the list on the left</p>
                    </div>
                </div>
            </div>
            <!-- Modal for File Import -->
            <TransitionRoot appear :show="showModal" as="template">
                <Dialog as="div" @close="showModal = false" class="relative z-50">
                    <!-- Backdrop -->
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" aria-hidden="true" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4">
                            <!-- Modal Panel -->
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="w-full max-w-xl transform overflow-hidden rounded-2xl bg-white p-6 shadow-xl transition-all">
                                    <div class="flex items-center justify-between mb-6">
                                        <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900">
                                            Import Students
                                        </DialogTitle>
                                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Close</span>
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>

                                    <form @submit.prevent="importFile">
                                        <!-- File Upload Area -->
                                        <div class="mb-6">
                                            <label for="dropzone-file" class="block w-full">
                                                <div class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-300">
                                                    <div v-if="!selectedFile" class="flex flex-col items-center justify-center pt-5 pb-6 px-4">
                                                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-3"></i>
                                                        <p class="mb-2 text-sm text-gray-500 text-center">
                                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                                        </p>
                                                        <p class="text-xs text-gray-500 text-center">
                                                            Excel files only (XLSX, XLS)
                                                        </p>
                                                    </div>
                                                    <div v-else class="flex items-center justify-between w-full max-w-md p-4 bg-white rounded-lg border border-gray-200">
                                                        <div class="flex items-center space-x-3">
                                                            <i class="fa-solid fa-file-excel text-lg text-blue-600"></i>
                                                            <span class="text-sm font-medium text-gray-700 truncate">{{ selectedFile }}</span>
                                                        </div>
                                                        <button type="button" @click.stop="clearFile" class="text-gray-400 hover:text-gray-500">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <input id="dropzone-file" @change="handleFileUpload" type="file" required accept=".xlsx,.xls" class="hidden" />
                                            </label>
                                        </div>

                                        <!-- Info Section -->
                                        <div class="bg-blue-50 rounded-xl p-4 mb-6">
                                            <div class="flex items-start space-x-3">
                                                <i class="fa-solid fa-circle-info text-blue-600 mt-0.5"></i>
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-semibold text-blue-900 mb-2">Important Information</h4>
                                                    <div class="text-sm text-blue-800 space-y-2">
                                                        <p>When importing an excel file, ensure it follows this format:</p>
                                                        <ul class="list-disc list-inside ml-2 text-blue-700">
                                                            <li>Name</li>
                                                            <li>E-mail</li>
                                                            <li>Contact number</li>
                                                        </ul>
                                                        <p class="text-blue-700 mt-2">
                                                            Note: Only enrolled students in the list will be displayed. Students not currently enrolled cannot participate in this year's Palakasan.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex justify-end space-x-3">
                                            <button
                                                type="button"
                                                @click="showModal = false"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            >
                                                Cancel
                                            </button>
                                            <button
                                                type="submit"
                                                :disabled="form.processing"
                                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                            >
                                                <i v-if="form.processing" class="fa-solid fa-circle-notch fa-spin mr-2"></i>
                                                {{ form.processing ? 'Importing...' : 'Import Students' }}
                                            </button>
                                        </div>
                                    </form>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
                        <!-- Modal for File Import -->
            <TransitionRoot appear :show="showAddEmployeeModal" as="template">
                <Dialog as="div" @close="showAddEmployeeModal = false" class="relative z-50">
                    <!-- Backdrop -->
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" aria-hidden="true" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4">
                            <!-- Modal Panel -->
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="w-full max-w-xl transform overflow-hidden rounded-2xl bg-white p-6 shadow-xl transition-all">
                                    <div class="flex items-center justify-between mb-6">
                                        <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900">
                                            Add Employee
                                        </DialogTitle>
                                        <button @click="showAddEmployeeModal = false" class="text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Close</span>
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>

                                    <form @submit.prevent="addEmployee">
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div class="">
                                                <label class="block text-gray-700 text-sm mb-1.5 font-medium">First Name</label>
                                                <input 
                                                    v-model="form1.first_name" 
                                                    required 
                                                    placeholder="Enter first name..." 
                                                    type="text" 
                                                    class="border transition border-gray-300 rounded-lg bg-gray-50 text-sm w-full p-2.5 "
                                                />
                                            </div>
                                            <div class="">
                                                <label class="block text-gray-700 text-sm mb-1.5 font-medium">Last Name</label>
                                                <input 
                                                    v-model="form1.last_name" 
                                                    required 
                                                    placeholder="Enter last name..." 
                                                    type="text" 
                                                    class="border transition border-gray-300 rounded-lg bg-gray-50 text-sm w-full p-2.5 "
                                                />
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm mb-1.5 font-medium">University Email</label>
                                            <input 
                                                v-model="form1.univ_email" 
                                                required 
                                                placeholder="Enter university email..." 
                                                type="email" 
                                                class="border transition border-gray-300 rounded-lg bg-gray-50 text-sm w-full p-2.5 "
                                            />
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-gray-700 text-sm mb-1.5 font-medium">Contact Number</label>
                                            <input 
                                                v-model="form1.contact" 
                                                required 
                                                placeholder="Enter contact number..." 
                                                type="text" 
                                                class="border transition border-gray-300 rounded-lg bg-gray-50 text-sm w-full p-2.5 "
                                            />
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex justify-end space-x-3">
                                            <button
                                                type="button"
                                                @click="showAddEmployeeModal = false"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            >
                                                Cancel
                                            </button>
                                            <button
                                                type="submit"
                                                :disabled="form1.processing"
                                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                            >
                                                <i v-if="form1.processing" class="fa-solid fa-circle-notch fa-spin mr-2"></i>
                                                {{ form.processing ? 'Adding...' : 'Add Employee' }}
                                            </button>
                                        </div>
                                    </form>
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

    import { ref, computed, watch, onMounted } from 'vue';
    import { TransitionRoot,TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
    import { Head, router, useForm } from '@inertiajs/vue3';
    import { route } from 'ziggy-js';
    import AppLayout from '@/Layout/DashboardLayout.vue';
    import SuccessModal from '@/Components/SuccessModal.vue';

    const props = defineProps({
        students: Array,
        errors: Object,
        flash: Object
    });

    const showSuccessModal = ref(false);
    const successMessage = ref('');

    const searchQuery = ref('');
    const showModal = ref(false);
    const showAddEmployeeModal = ref(false);
    const selectedFile = ref('');
    const currentPage = ref(1);
    const itemsPerPage = 16;

    const form1 = useForm({
        id: null,
        first_name: '',
        last_name: '',
        id_number: 'N/A',
        univ_email: '',
        college: 'Employee',
        contact: '',
        status: 'active'
    });

    const addEmployee = () => {
        form1.post(route('student.store'), {
            onSuccess: () => {
                showSuccessModal.value = true;
                successMessage.value = 'Employee added successfully!';
                showAddEmployeeModal.value = false; // Close the modal
            },
        });
    };

    // Define the form object using useForm
    const form = useForm({
        file: null,
    });

    const handleFileUpload = (event) => {
        const file = event.target.files[0];
        if (file) {
            selectedFile.value = file.name;
            form.file = file;
        }
    };

    const clearFile = () => {
        selectedFile.value = '';
        document.getElementById('dropzone-file').value = '';
        form.file = null;
    };

    const importFile = () => {
        if (form.file) {
            form.post(route('student.import'), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    showModal.value = false;
                    selectedFile.value = '';
                    router.reload({ only: ['students'] });
                    showSuccessModal.value = true;
                    successMessage.value = 'Students imported successfully!';
                },
                onError: (errors) => {
                    console.error(errors);
                    alert('There was an error importing the file. Please try again.');
                }
            });
        } else {
            alert('Please select a file to import.');
        }
    };

    // Check for flash message on component mount
    onMounted(() => {
        // Select the first college if there are any colleges available
        if (props.students && props.students.length > 0) {
            const firstCollege = uniqueColleges.value[0];
            if (firstCollege) {
                selectedCollege.value = firstCollege;
            }
        }
    });
    // Computed property to filter students based on the search query
    const filteredStudents = computed(() => {
        if (!searchQuery.value) return props.students || [];
        
        const query = searchQuery.value.toLowerCase();
        return (props.students || []).filter(student => {
            const fullname = student?.fullname || '';
            const email = student?.email || '';
            const contact = student?.contact || '';
            
            return fullname.toLowerCase().includes(query) ||
                   email.toLowerCase().includes(query) ||
                   contact.toLowerCase().includes(query);
        });
    });

    // Computed property to get paginated students
    const paginatedStudents = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return filteredStudents.value.slice(start, end);
    });

    // Computed property to get total number of pages
    const totalPages = computed(() => Math.ceil(filteredStudents.value.length / itemsPerPage));

    // Function to go to next page
    const nextPage = () => {
        if (currentPage.value < totalPages.value) {
            currentPage.value++;
        }
    };

    // Function to go to previous page
    const prevPage = () => {
        if (currentPage.value > 1) {
            currentPage.value--;
        }
    };

    const clearSearch = () => {
        searchQuery.value = '';
        currentPage.value = 1; // Reset to first page when clearing search
    };

    const selectedCollege = ref(null);
    // Compute unique colleges from students
    const uniqueColleges = computed(() => {
        if (!props.students) return [];
        return [...new Set(props.students.map(student => student.college))];
    });

    // Get count of students for each college
    const getCollegeCount = (college) => {
        if (!props.students) return 0;
        return props.students.filter(student => student.college === college).length;
    };

    // Filter students based on selected college and search query
    const filteredStudentsByCollege = computed(() => {
        if (!selectedCollege.value || !props.students) return [];
        
        let filtered = props.students.filter(student => student.college === selectedCollege.value);
        
        // Apply search filter if there's a search query
        if (searchQuery.value) {
            const query = searchQuery.value.toLowerCase();
            filtered = filtered.filter(student => {
                const fullName = `${student.first_name} ${student.last_name}`.toLowerCase();
                return fullName.includes(query) ||
                    student.id_number?.toLowerCase().includes(query) ||
                    student.univ_email?.toLowerCase().includes(query) ||
                    student.contact?.toLowerCase().includes(query);
            });
        }
        
        return filtered;
    });

    // Computed property to get paginated students by college
    const paginatedStudentsByCollege = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return filteredStudentsByCollege.value.slice(start, end);
    });

    // Computed property to get total number of pages by college
    const totalPagesByCollege = computed(() => Math.ceil(filteredStudentsByCollege.value.length / itemsPerPage));

    // Computed property to get start index
    const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage + 1);

    // Computed property to get end index
    const endIndex = computed(() => Math.min(currentPage.value * itemsPerPage, filteredStudentsByCollege.value.length));

    // Function to go to next page
    const nextPageByCollege = () => {
        if (currentPage.value < totalPagesByCollege.value) {
            currentPage.value++;
        }
    };

    // Function to go to previous page
    const prevPageByCollege = () => {
        if (currentPage.value > 1) {
            currentPage.value--;
        }
    };

    const clearSearchByCollege = () => {
        searchQuery.value = '';
        currentPage.value = 1; // Reset to first page when clearing search
    };

    // Handle college selection
    const selectCollege = (college) => {
        // Only toggle if clicking the currently selected college
        if (selectedCollege.value === college) {
            selectedCollege.value = null;
        } else {
            selectedCollege.value = college;
        }
    };

    // Reset pagination when selecting a new college
    watch(selectedCollege, () => {
        currentPage.value = 1;
    });

    // Reset pagination when search query changes
    watch(searchQuery, () => {
        currentPage.value = 1;
    });
</script>

<style scoped>
    /* Slide-down transition */
    .slide-down-enter-active, .slide-down-leave-active {
    transition: all 0.5s ease;
    }
    .slide-down-enter-from {
    transform: translateY(-50px);
    opacity: 0;
    }
    .slide-down-enter-to {
    transform: translateY(0);
    opacity: 1;
    }
    .slide-down-leave-from {
    transform: translateY(0);
    opacity: 1;
    }
    .slide-down-leave-to {
    transform: translateY(-50px);
    opacity: 0;
    }
</style>
