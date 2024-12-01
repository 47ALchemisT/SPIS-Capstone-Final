<template>
    <Head title="Sports"/>
    <AppLayout>
        <template v-slot:default>
            <!--Head-->
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Sports</h1>
                <h1 class="text-sm font-normal text-gray-700">List of Sports</h1>
                <div v-if="$page.props.flash.message" class="alert">
                    {{ $page.props.flash.message }}
                </div>
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
                                    placeholder="Search college..."
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
                            <p class="text-2xl text-gray-400 mb-1">|</p>
                            <div class="text-sm font-medium text-gray-700">
                                <p>Number of sports: <span>{{ sports.length }}</span></p>
                            </div>                       
                         </div>
                        <!--Buttons-->
                        <div class="flex items-center space-x-2.5">
                            <button @click="openModal(false)" type="button" class="flex items-center gap-2 text-white text-sm bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M18 6h-6a2 2 0 0 0-2-2H6C4.346 4 3 5.346 3 7v10c0 1.654 1.346 3 3 3h12c1.654 0 3-1.346 3-3V9c0-1.654-1.346-3-3-3m0 12H6a1 1 0 0 1-1-1v-7h4c.275 0 .5-.225.5-.5S9.275 9 9 9H5V7a1 1 0 0 1 1-1h4a2 2 0 0 0 2 2h6a1 1 0 0 1 1 1h-4c-.275 0-.5.225-.5.5s.225.5.5.5h4v7a1 1 0 0 1-1 1m-3-6h-2v-2a1 1 0 1 0-2 0v2H9a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2"/></svg>
                                Sport
                            </button>
                        </div>
                    </div>
                </div>
                <Toast ref="toastRef" />

                <!--Main Content, List of Cards-->
                <div>
                    <div  class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-2 gap-4">
                        <div v-for="sport in sports" :key="sport.id" class="bg-white rounded-lg shadow  transition-all ring-1 ring-gray-300 hover:ring-blue-400 hover:bg-blue-50 transition group">
                            <div class="p-4 relative">
                                <div class="absolute right-3 top-3">
                                    <Menu as="div" class="relative inline-block text-left">
                                        <MenuButton class="inline-flex items-center justify-center w-full rounded-lg  px-2.5 py-2.5 text-sm font-medium text-gray-700 " >
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
                                            <MenuItems class="origin-top-right absolute right-0 z-10 mt-2 w-36 rounded-lg shadow bg-white ring-1 ring-gray-300 focus:outline-none">
                                                <div class="p-1">
                                                <MenuItem >
                                                    <button @click="openModal(true, sport)" class="w-full text-sm text-left p-2 rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                                        <i class="fas fa-edit mr-2"></i> Update
                                                    </button>
                                                </MenuItem>
                                                <MenuItem >
                                                    <button @click="confirmDelete(sport.id)" class="w-full text-sm text-left p-2 rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                                        <i class="fa-regular fa-trash-can mr-2"></i> Remove
                                                    </button>
                                                </MenuItem>

                                                </div>
                                            </MenuItems>
                                        </transition>
                                    </Menu>
                                </div>
                                <svg class="h-8 w-8 mb-2 group-hover:text-blue-600 transition-colors" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M18.035 15.096a6.5 6.5 0 0 1-2.154 1.128q.118.745.119 1.524a8.003 8.003 0 0 0-.12-15.526A8.003 8.003 0 0 0 6.252 8q.778 0 1.523.119a6.5 6.5 0 0 1 1.128-2.154zm1.06-1.06L15.062 10l.761-.761a4.23 4.23 0 0 0 2.428.76a4.23 4.23 0 0 0 2.22-.624q.03.308.03.625a6.47 6.47 0 0 1-1.404 4.035M16.225 3.89c.66.24 1.27.585 1.811 1.014l-2.187 2.187A2.74 2.74 0 0 1 15.5 5.75c0-.717.274-1.37.724-1.86M14.76 8.178l-.76.761l-4.035-4.035a6.47 6.47 0 0 1 4.66-1.374A4.23 4.23 0 0 0 14 5.75c0 .903.281 1.74.761 2.428m5.349-.402a2.74 2.74 0 0 1-1.86.724c-.487 0-.944-.127-1.34-.349l2.186-2.186a6.5 6.5 0 0 1 1.014 1.81M4.25 10.5a.75.75 0 0 0-.75.75v2a7.25 7.25 0 0 0 7.25 7.25h2a.75.75 0 0 0 .75-.75v-2a7.25 7.25 0 0 0-7.25-7.25zM2 11.25A2.25 2.25 0 0 1 4.25 9h2A8.75 8.75 0 0 1 15 17.75v2A2.25 2.25 0 0 1 12.75 22h-2A8.75 8.75 0 0 1 2 13.25zm5.78 2.47a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 1 0 1.06-1.06z"/></svg>
                                <div class="flex items-center justify-between mb-2">

                                    <h2 class="text-gray-800 text-xl font-semibold">{{ sport.name }}</h2>
                                </div>

                                <p class="text-gray-600 text-sm mb-4">{{ sport.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for Add/Edit -->
            <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal"></div>
                <div class="bg-white p-6 rounded-xl shadow-lg z-50 max-w-md w-full">
                    <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit Sport' : 'Add Sport' }}</h2>
                    <form @submit.prevent="submitSport">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm mb-1.5 font-medium">Sport Name</label>
                            <input v-model="form.name" required placeholder="Enter the full name of the sport..." type="text" class="border transition border-gray-300 rounded-lg bg-gray-50 text-sm w-full p-2"/>
                        </div>

                        <div class="mb-4">            
                            <label for="description" class="block mb-1.5 text-sm font-medium text-gray-700">Description</label>
                            <textarea v-model="form.description" id="description" rows="3" class="block p-2.5 transition w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter a brief description about the college..."></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="closeModal" class="mr-2 bg-gray-100 hover:bg-gray-200 px-4 py-2.5 font-medium text-sm rounded-lg transition">Cancel</button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2.5 font-medium rounded-lg text-sm transition relative"
                            >
                                <span v-if="!form.processing">
                                    {{ isEditing ? 'Save' : 'Confirm' }}
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

            <!-- Modal for Delete Confirmation -->
            <div v-if="isDeleteModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeDeleteModal"></div>
                <div class="bg-white p-6 rounded-xl shadow-lg z-50 max-w-md w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Confirm Delete</h2>
                        <i class="fa-regular fa-trash-can text-xl"></i>
                    </div>
                    <p>Are you sure you want to remove this Venue from the list?</p>
                    <div class="flex justify-end mt-4">
                        <button type="button" @click="closeDeleteModal" class="mr-2 bg-gray-100  hover:bg-gray-200 px-4 py-2 rounded-lg text-sm transition">No, keep it</button>
                        <button 
                            :disabled="form.processing"
                            @click="deleteSport" 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition">
                            <span v-if="form.processing">Deleting...</span>
                            <span v-else>Yes, delete it</span>
                        </button>
                    </div>
                </div>
            </div>

            <!--Tooltip-->
            <div class="tooltip absolute z-10 invisible py-2 px-3 text-xs text-white bg-blue-800 rounded-lg opacity-0 transition-opacity duration-300">
                <div class="tooltip-content">Hello</div>
            </div>

        </template>
    </AppLayout>
 </template>
 
 <script setup>
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { ChevronDownIcon } from '@heroicons/vue/20/solid'
    import { Head, useForm, router, usePage } from '@inertiajs/vue3';
    import { ref, onMounted, watch } from 'vue';
    import AppLayout from '@/Layout/DashboardLayout.vue';
    import Toast from '@/Components/Toast.vue';  // Ad/just the import path as needed


    const props = defineProps({
        sports: Array,
    });

    const isModalOpen = ref(false);
    const isDeleteModalOpen = ref(false);
    const isEditing = ref(false);
    const form = useForm({
        id: null,
        name: '',
        description: '',
    });

    const toastRef = ref(null);
    const page = usePage();

    // Watch for flash messages
    watch(
        () => page.props.flash,
        (flash) => {
            if (flash.message) {
            toastRef.value.addToast(flash.message, 'success');
            }
            if (flash.error) {
            toastRef.value.addToast(flash.error, 'error');
            }
        },
        { deep: true, immediate: true }
    );

    const sportIdToDelete = ref(null);

    const openModal = (editMode = false, sports = null) => {
        isModalOpen.value = true;
        isEditing.value = editMode;

        if (editMode && sports) {
            form.id = sports.id;
            form.name = sports.name;
            form.description = sports.description;
        } else {
            form.reset();
        }
    };

    const closeModal = () => {
        isModalOpen.value = false;
        form.reset();
    };

    const submitSport = () => {
        if (isEditing.value) {
            form.put(route('sport.update', form.id), {
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                },
            });
        } else {
            form.post(route('sport.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                },
            });
        }
    };

    const confirmDelete = (sportId) => {
        sportIdToDelete.value = sportId;
        isDeleteModalOpen.value = true;
    };

    const closeDeleteModal = () => {
        isDeleteModalOpen.value = false;
        sportIdToDelete.value = null;
    };

    const deleteSport = () => {
        router.delete(route('sport.destroy', sportIdToDelete.value), {
            preserveState: false,
            preserveScroll: true,
        });
        closeDeleteModal();
    };
    
 </script>
 
 <style scoped>
    /* Home page specific styles */
 </style>
 