<template>
    <Head title="Students"/>
    <AppLayout>
        <template v-slot:default>
            <!-- Head -->
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Students</h1>
                <h1 class="text-sm font-normal text-gray-700">List of Students</h1>
            </div>
            <!--Alert-->
            <transition name="slide-down">
                <div
                    v-if="showSuccessAlert"
                    class="fixed top-2 right-2 w-1/2 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg z-50 flex justify-between items-center shadow-lg"
                    role="alert"
                >
                    <div>
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline"> Students data has been successfully imported.</span>
                    </div>
                    <span @click="showSuccessAlert = false" class="cursor-pointer">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"
                            />
                        </svg>
                    </span>
                </div>
            </transition>

            <!-- Content -->
            <div class="mt-3 flex">
                <!-- Sidebar -->
                <div class="w-[20rem] sticky top-0 overflow-y-auto bg-white px-2">
                    <!-- Import Button -->
                    <div class="sticky top-0 bg-white pt-4 pb-2">
                        <button @click="showModal = true" type="button" class="w-full flex items-center justify-center gap-2 text-white text-sm bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-4 py-2.5 mb-4">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"><path d="M13 11.15V4a1 1 0 1 0-2 0v7.15L8.78 8.374a1 1 0 1 0-1.56 1.25l4 5a1 1 0 0 0 1.56 0l4-5a1 1 0 1 0-1.56-1.25z"/><path d="M9.657 15.874L7.358 13H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2.358l-2.3 2.874a3 3 0 0 1-4.685 0M17 16a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2z"/></g></svg>                                 
                            Import
                        </button>

                        <!-- Search -->
                        <div class="relative mb-4">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search student..."
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

                    <!-- College Folders -->
                    <div class="space-y-2 pb-4">
                        <div 
                            v-for="college in uniqueColleges" 
                            :key="college"
                            @click="selectCollege(college)"
                            class="cursor-pointer"
                        >
                            <div class="p-3 rounded-lg  transition-all duration-200"
                                :class="{ 'bg-gray-100': selectedCollege === college }">
                                <div class="flex gap-3">
                                    <div class="text-gray-400">
                                        <svg v-if="selectedCollege === college" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19 20H4c-1.11 0-2-.9-2-2V6c0-1.11.89-2 2-2h6l2 2h7c1.097 0 2 .903 2 2H4v10l2.14-8h17.07l-2.28 8.5c-.23.87-1.01 1.5-1.93 1.5z"/>
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h6l2 2h8q.825 0 1.413.588T22 8v10q0 .825-.587 1.413T20 20z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">{{ college }}</h3>
                                        <p class="text-xs text-gray-500">{{ getCollegeCount(college) }} students</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1 p-4">
                    <!-- Table View (shows when folder is clicked) -->
                    <div v-if="selectedCollege" class="bg-white rounded-lg border border-gray-200">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-900">{{ selectedCollege }} Students</h2>
                            <button 
                                @click="selectedCollege = null" 
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">University Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="student in paginatedStudentsByCollege" :key="student.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ student.first_name }} {{ student.last_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ student.id_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ student.univ_email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ student.contact }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-700">
                                        Showing {{ startIndex }} to {{ endIndex }} of {{ filteredStudentsByCollege.length }} entries
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
                                    <span class="text-sm text-gray-700">Page {{ currentPage }} of {{ totalPagesByCollege }}</span>
                                    <button 
                                        @click="currentPage++" 
                                        :disabled="currentPage === totalPagesByCollege"
                                        class="px-3 py-1 rounded-md border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-gray-500 text-center mt-4">Select a college to view students</p>
                </div>
            </div>

            <!-- Modal for File Import -->
            <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                    <h2 class="text-xl font-semibold mb-4">Import Students</h2>
                    <form @submit.prevent="importFile">

                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col w-full p-4 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <div class="flex flex-col ">

                                    <div v-if="!selectedFile" class="flex gap-2">
                                        <i class="fa-solid fa-file-arrow-up text-xl py-2 px-3 rounded-md text-gray-600 bg-gray-200"></i>
                                        <p class="text-sm text-gray-700 font-normal dark:text-gray-400">Excel files that are accepted are only in this format : <br> 
                                        <span class="font-semibold">xlsx or xls</span></p>
                                    </div>
                                    <!-- Display selected file with an icon and a remove button -->
                                    <div v-if="selectedFile" class="flex items-center justify-between w-full bg-white border border-gray-300 rounded-lg">
                                        <span class="flex items-center text-sm p-2 text-gray-800">
                                            <i class="fa-solid bg-blue-700 py-1.5 px-2.5 text-white rounded text-sm fa-file-excel mr-2"></i> 
                                            <span class="font-semibold">{{ selectedFile }}</span>
                                        </span>
                                        <button type="button" @click="clearFile" class="text-red-500 hover:text-red-700 px-4 py-1.5 focus:outline-none">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Accept only xlsx and xls file formats -->
                                <input id="dropzone-file" @change="handleFileUpload" type="file" required accept=".xlsx,.xls" class="hidden" />
                            </label>
                        </div> 
                        <div class="note mt-2 bg-blue-50 p-4 rounded-lg text-xs text-blue-700">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm font-semibold">Info</p>
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <h1 class="space-y-2">
                                <span>When importing an excel file make sure it follows a specific format so that there would be no problem that will happen in the later time, the format should be as followed: <span class="font-semibold">Name, E-mail, and contact number</span> </span>.

                                <div class="pt-1"> Keep in mind, only the enrolled student that are in the list will be displayed, if the student that was playing before but not enrolled then he/she cant play in this years Palakasan </div>

                            </h1>
                        </div>

                        <div class="flex justify-end font-medium mt-4 space-x-2">
                            <button type="button" @click="showModal = false" class="bg-gray-100 text-gray-800 py-2.5 px-4 rounded-lg text-sm hover:bg-gray-200 transition-colors">Close</button>
                            <button 
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-700 text-white py-2.5 px-4 rounded-lg hover:bg-blue-700 text-sm transition-colors">
                                <span v-if="form.processing">importing...</span>
                                <span v-else>Import</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </AppLayout>
</template>

<script setup>

    import { ref, computed, watch, onMounted } from 'vue';
    import { Head, router, useForm } from '@inertiajs/vue3';
    import { route } from 'ziggy-js';
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { ChevronDownIcon } from '@heroicons/vue/20/solid'
    import AppLayout from '@/Layout/DashboardLayout.vue';

    const props = defineProps({
        students: Array,
        errors: Object,
        flash: Object
    });

    const searchQuery = ref('');
    const showModal = ref(false);
    const selectedFile = ref('');
    const currentPage = ref(1);
    const itemsPerPage = 16;
    const showSuccessAlert = ref(false);

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
                    showSuccessAlert.value = true;
                    router.reload({ only: ['students'] });
                    setTimeout(() => {
                        showSuccessAlert.value = false;
                    }, 4000); // Hide the alert after 5 seconds
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
        if (props.flash && props.flash.message) {
            showSuccessAlert.value = true;
            setTimeout(() => {
                showSuccessAlert.value = false;
            }, 4000);
        }
        
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
