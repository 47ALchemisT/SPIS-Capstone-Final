<template>
    <Head title="College"/>
    <AppLayout>
        <template v-slot:default>
            <div v-if="$page.props.flash.message" class="alert">
                {{ $page.props.flash.message }}
            </div>
            <!--Head-->
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">College</h1>
                <h1 class="text-sm font-normal text-gray-700">List of colleges in MSU at Naawan</h1>
            </div>
            <!--Content-->
            <div class="mt-5 space-y-4">
                <!--Utility-->
                <div class="utility">
                    <div class="flex justify-between items-center">
                        <div class="search flex space-x-2 items-center">
                            <!-- Search Input -->
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search college..."
                                    class="w-72 pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                                >
                                <button
                                    v-if="searchQuery"
                                    @click="clearSearch"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="h-6 w-px bg-gray-200"></div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-building text-blue-600"></i>
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ colleges.length }} Colleges
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--Buttons-->
                        <button 
                            @click="openModal(false)" 
                            type="button" 
                            class="inline-flex items-center gap-2 text-white text-sm bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 font-medium rounded-lg px-4 py-2.5 transition-all duration-200 shadow-sm"
                        >
                            Add College
                        </button>
                    </div>
                </div>

                <!--Main Content, List of College Cards-->
                <div v-if="filteredColleges.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-5">
                    <div
                        v-for="college in filteredColleges"
                        :key="college.id"  
                        class="bg-white rounded-xl ring-1 ring-gray-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md group relative"
                    >
                        <!-- Card Header with Menu -->
                        <div class="absolute right-3 top-3 z-10">
                            <Menu as="div" class="relative inline-block text-left">
                                <MenuButton class="py-2 px-3 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">
                                    <i class="fa-solid fa-ellipsis"></i>                                    
                                </MenuButton>
                                <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                >
                                    <MenuItems class="absolute right-0 z-10 mt-2 w-36 rounded-lg shadow-lg bg-white ring-1 ring-gray-200 focus:outline-none">
                                        <div class="py-1">
                                            <MenuItem v-slot="{ active }">
                                                <button 
                                                    @click="openModal(true, college)" 
                                                    :class="[
                                                        active ? 'bg-gray-50 text-gray-700' : 'text-gray-600',
                                                        'w-full text-left px-4 py-2 text-sm flex items-center gap-2 transition-colors'
                                                    ]"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                    Update
                                                </button>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <button 
                                                    @click="confirmDelete(college.id)" 
                                                    :class="[
                                                        active ? 'bg-gray-50 text-red-600' : 'text-gray-600',
                                                        'w-full text-left px-4 py-2 text-sm flex items-center gap-2 transition-colors'
                                                    ]"
                                                >
                                                    <i class="fa-regular fa-trash-can"></i>
                                                    Remove
                                                </button>
                                            </MenuItem>
                                        </div>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6">
                            <!-- College Icon with Background -->
                            <div class="mb-4 inline-flex items-center justify-center w-12 h-12 rounded-lg bg-blue-50 text-blue-600">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M4.75 22a.75.75 0 0 1-.75-.75v-17A2.25 2.25 0 0 1 6.25 2h8a2.25 2.25 0 0 1 2.25 2.25V9.5h1.25A2.25 2.25 0 0 1 20 11.75v1.3a2.5 2.5 0 0 0-1.5.95v-2.25a.75.75 0 0 0-.75-.75h-2a.75.75 0 0 1-.75-.75v-6a.75.75 0 0 0-.75-.75h-8a.75.75 0 0 0-.75.75V20.5h5.528a4 4 0 0 0 .478 1.5zM7.5 6.5a1 1 0 1 1 2 0a1 1 0 0 1-2 0m0 7a1 1 0 1 1 2 0a1 1 0 0 1-2 0M12 9a1 1 0 1 0 0 2a1 1 0 0 0 0-2m-1-2.5a1 1 0 1 1 2 0a1 1 0 0 1-2 0M8.5 9a1 1 0 1 0 0 2a1 1 0 0 0 0-2m7 8a2 2 0 1 0 0-4a2 2 0 0 0 0 4m0 6c2.567 0 3.5-1.52 3.5-3a2 2 0 0 0-2-2h-3a2 2 0 0 0-2 2c0 1.48.933 3 3.5 3m4.007-1.022q.234.021.493.022c2.2 0 3-1.216 3-2.4a1.6 1.6 0 0 0-1.6-1.6h-2.164c.475.53.764 1.232.764 2c0 .656-.144 1.35-.493 1.978M22 15.5a1.5 1.5 0 1 1-3 0a1.5 1.5 0 0 1 3 0"/>
                                </svg>
                            </div>

                            <!-- College Information -->
                            <div class="space-y-1">
                                <h3 class="text-xl font-semibold text-gray-900">{{ college.shortName }}</h3>
                                <p class="text-sm font-medium text-gray-600">{{ college.name }}</p>
                            </div>

                            <!-- Description with fade effect -->
                            <div class="mt-4 relative">
                                <p class="text-sm text-gray-600 line-clamp-2">{{ college.description || 'No description available' }}</p>
                                <div class="absolute bottom-0 left-0 right-0 h-4 bg-gradient-to-t from-white to-transparent"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Display message if no colleges are found -->
                <div v-else class="mt-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i class="fas fa-school text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 text-lg">No colleges found</p>
                    <p class="text-gray-500 text-sm mt-1">Try adjusting your search criteria</p>
                </div>

            </div>

            <!-- Modal for Add/Edit -->
            <TransitionRoot appear :show="isModalOpen" as="template">
                <Dialog as="div" @close="closeModal" class="relative z-50">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md transform transition-all">
                                    <DialogTitle class="text-xl font-bold text-gray-900 mb-4">{{ isEditing ? 'Edit College' : 'Add College' }}</DialogTitle>
                                    <form @submit.prevent="submitCollege" class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">College Name</label>
                                            <input 
                                                v-model="form.name" 
                                                required 
                                                placeholder="Enter the full name of the college..." 
                                                type="text" 
                                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">College Shorthand</label>
                                            <input 
                                                v-model="form.shortName" 
                                                required  
                                                placeholder="Enter the shorthand (e.g., CMAS)..." 
                                                type="text" 
                                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </div>

                                        <div>            
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                                            <textarea 
                                                v-model="form.description" 
                                                rows="3" 
                                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" 
                                                placeholder="Enter a brief description about the college..."
                                            ></textarea>
                                        </div>

                                        <div class="flex justify-end gap-3 pt-4">
                                            <button 
                                                type="button" 
                                                @click="closeModal" 
                                                class="px-4 py-2.5 text-sm font-medium text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50 rounded-lg transition"
                                            >
                                                Cancel
                                            </button>
                                            <button
                                                type="submit"
                                                :disabled="form.processing"
                                                class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition flex items-center gap-2"
                                            >
                                                <span v-if="!form.processing">
                                                    {{ isEditing ? 'Save Changes' : 'Add College' }}
                                                </span>
                                                <span v-else class="flex items-center gap-2">
                                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.969 7.969 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Processing...
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

            <!-- Modal for Delete Confirmation -->
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
                        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4">
                            <TransitionChild
                                as="template"
                                enter="duration-300 ease-out"
                                enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100"
                                leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100"
                                leave-to="opacity-0 scale-95"
                            >
                                <DialogPanel class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md transform transition-all">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-red-100">
                                            <i class="fa-regular fa-trash-can text-red-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <DialogTitle class="text-xl font-bold text-gray-900">Delete College</DialogTitle>
                                            <p class="text-sm text-gray-600">This action cannot be undone</p>
                                        </div>
                                    </div>

                                    <p class="text-gray-600 mb-6">Are you sure you want to remove this college from the system?</p>

                                    <div class="flex justify-end gap-3">
                                        <button 
                                            type="button" 
                                            @click="closeDeleteModal" 
                                            class="px-4 py-2.5 text-sm font-medium text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50 rounded-lg transition"
                                        >
                                            Cancel
                                        </button>
                                        <button 
                                            @click="deleteCollege" 
                                            class="px-4 py-2.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition"
                                        >
                                            Delete College
                                        </button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
            <!--Tooltip-->
            <div class="tooltip absolute z-10 invisible py-2 px-3 text-xs text-white bg-blue-800 rounded-lg opacity-0 transition-opacity duration-300">
                <div class="tooltip-content">Hello</div>
            </div>


        </template>
    </AppLayout>
    <SuccessModal
        :show="showSuccessModal"
        :message="successMessage"
        @close="showSuccessModal = false"
     />
 </template>
 
 <script setup>
    import { Menu, MenuButton, MenuItem, MenuItems, Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
    import { ChevronDownIcon } from '@heroicons/vue/20/solid'
    import { Head, useForm, router, usePage } from '@inertiajs/vue3';
    import { ref, onMounted, computed } from 'vue';
    import { route } from 'ziggy-js';
    import AppLayout from '@/Layout/DashboardLayout.vue';
    import SuccessModal from '@/Components/SuccessModal.vue';

    const props = defineProps({
        colleges: Array,
        errors: Object
    })
   
    const showSuccessModal = ref(false);
    const successMessage = ref('');

    const isModalOpen = ref(false);
    const isDeleteModalOpen = ref(false);
    const isEditing = ref(false);
    const form = useForm({
        id: null,
        name: '',
        shortName: '',
        description: '',
    });

    const collegeIdToDelete = ref(null);

    const openModal = (editMode = false, colleges = null) => {
        isModalOpen.value = true;
        isEditing.value = editMode;

        if (editMode && colleges) {
            form.id = colleges.id;
            form.name = colleges.name;
            form.shortName = colleges.shortName;
            form.description = colleges.description;

        } else {
            form.reset();
        }
    };

    const closeModal = () => {
        isModalOpen.value = false;
        form.reset();
    };

    const submitCollege = () => {
        if (isEditing.value) {
            form.put(route('college.update', form.id), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                    showSuccessModal.value = true;
                    successMessage.value = 'College updated successfully!';
                },
                onError: () => {
                    // Handle errors if needed
                    showSuccessModal.value = false;
                }
            });
        } else {
            form.post(route('college.store'), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                    showSuccessModal.value = true;
                    successMessage.value = 'New college added successfully!';
                },
                onError: () => {
                    // Handle errors if needed
                    showSuccessModal.value = false;
                }
            });
        }
    };

    const confirmDelete = (collegeId) => {
        collegeIdToDelete.value = collegeId;
        isDeleteModalOpen.value = true;
    };

    const closeDeleteModal = () => {
        isDeleteModalOpen.value = false;
        collegeIdToDelete.value = null;
    };

    const deleteCollege = () => {
        router.delete(route('college.destroy', collegeIdToDelete.value), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                closeDeleteModal();
                showSuccessModal.value = true;
                successMessage.value = 'College deleted successfully!';
            },
            onError: () => {
                // Handle errors if needed
                showSuccessModal.value = false;
                closeDeleteModal();
            }
        });
    };

    
    //--------------------------------------------------------------------------
    const searchQuery = ref('');

    const filteredColleges = computed(() => {
        if (!searchQuery.value) {
            return props.colleges;
        }
        const lowercaseQuery = searchQuery.value.toLowerCase();
        return props.colleges.filter(college => 
            college.name.toLowerCase().includes(lowercaseQuery) ||
            college.shortName.toLowerCase().includes(lowercaseQuery) ||
            college.teamName.toLowerCase().includes(lowercaseQuery) ||
            (college.description && college.description.toLowerCase().includes(lowercaseQuery))
        );
    });

    const clearSearch = () => {
        searchQuery.value = '';
    };
    //tooltips
    onMounted(() => {
        const tooltip = document.querySelector('.tooltip');
        const tooltipContent = tooltip.querySelector('.tooltip-content');

        // Select all buttons with the 'tooltip-btn' class
        const buttons = document.querySelectorAll('.tooltip-btn');

        buttons.forEach(btn => {
            btn.addEventListener('mouseenter', (e) => {
                const tooltipText = e.target.getAttribute('data-tooltip');
                const btnRect = e.target.getBoundingClientRect();

                // Set the tooltip content and position
                tooltipContent.textContent = tooltipText;
                tooltip.classList.remove('invisible', 'opacity-0');
                tooltip.style.top = `${btnRect.top - tooltip.offsetHeight - 6}px`;
                tooltip.style.left = `${btnRect.left + (btnRect.width / 2) - (tooltip.offsetWidth / 2)}px`;
            });

            btn.addEventListener('mouseleave', () => {
                tooltip.classList.add('invisible', 'opacity-0');
            });
        });
    });


    

    //--------------------------------------------------------------------------

 </script>
 
 <style scoped>
    /* Home page specific styles */
 </style>
 