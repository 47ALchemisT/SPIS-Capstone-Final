<template>
    <Head title="Venue"/>
    <AppLayout>
        <template v-slot:default>
            <!--Head-->
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Venue</h1>
                <h1 class="text-sm font-normal text-gray-700">List of venue that can be used for palakasan sports</h1>
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
                                    placeholder="Search venue..."
                                    class="w-64 pl-10 pr-10 py-2 bg-white shadow-sm ring-1 ring-gray-300 focus:border-blue-500 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
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
                                <p>Number of venues: <span>{{ venues.length }}</span></p>
                            </div>

                        </div>
                        <!--Buttons-->
                        <div class="flex items-center space-x-2.5">
                            <button @click="openModal(false)" type="button" class="flex items-center gap-2 text-white text-sm bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M18 6h-6a2 2 0 0 0-2-2H6C4.346 4 3 5.346 3 7v10c0 1.654 1.346 3 3 3h12c1.654 0 3-1.346 3-3V9c0-1.654-1.346-3-3-3m0 12H6a1 1 0 0 1-1-1v-7h4c.275 0 .5-.225.5-.5S9.275 9 9 9H5V7a1 1 0 0 1 1-1h4a2 2 0 0 0 2 2h6a1 1 0 0 1 1 1h-4c-.275 0-.5.225-.5.5s.225.5.5.5h4v7a1 1 0 0 1-1 1m-3-6h-2v-2a1 1 0 1 0-2 0v2H9a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2"/></svg>
                                Venue
                            </button>
                        </div>
                    </div>
                </div>

                <!--Main Content, List of Venue Cards-->
                <div v-if="filteredVenues.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5">
                    <!-- Venue Card -->
                    <div v-for="venue in filteredVenues" :key="venue.id" class="bg-white rounded-lg ring-1 ring-gray-300 overflow-hidden transition-all duration-300 hover:ring-blue-400 hover:bg-blue-50 group">
                        <div class="relative p-6">
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
                                                <button @click="openModal(true, venue)" class="w-full text-sm text-left p-2 rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                                    <i class="fas fa-edit mr-2"></i> Update
                                                </button>
                                            </MenuItem>
                                            <MenuItem >
                                                <button @click="confirmDelete(venue.id)" class="w-full text-sm text-left p-2 rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                                    <i class="fa-regular fa-trash-can mr-2"></i> Remove
                                                </button>
                                            </MenuItem>

                                            </div>
                                        </MenuItems>
                                    </transition>
                                </Menu>
                            </div>
                            <svg class="h-8 w-8 mb-4 group-hover:text-blue-600" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="M22 10.5v-.783c0-1.94 0-2.909-.586-3.512c-.586-.602-1.528-.602-3.414-.602h-2.079c-.917 0-.925-.002-1.75-.415L10.84 3.521c-1.391-.696-2.087-1.044-2.828-1.02S6.6 2.918 5.253 3.704l-1.227.716c-.989.577-1.483.866-1.754 1.346C2 6.246 2 6.83 2 7.999v8.217c0 1.535 0 2.303.342 2.73c.228.285.547.476.9.54c.53.095 1.18-.284 2.478-1.042c.882-.515 1.73-1.05 2.785-.905c.884.122 1.705.68 2.495 1.075M8 2.5v15m7-12v4"/><path d="M17.5 12c2.435 0 4.5 2.017 4.5 4.463c0 2.485-2.098 4.23-4.036 5.415a.94.94 0 0 1-.927 0C15.102 20.681 13 18.957 13 16.463C13 14.016 15.065 12 17.5 12m0 4.5h.009"/></g></svg>
                            <h3 class="text-xl font-semibold text-gray-800 mb-1.5">{{ venue.name }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ venue.description }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs flex items-center font-medium text-blue-600 px-2 py-1 bg-blue-100 rounded-md group-hover:bg-blue-200">
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 21.325q-.35 0-.7-.125t-.625-.375Q9.05 19.325 7.8 17.9t-2.087-2.762t-1.275-2.575T4 10.2q0-3.75 2.413-5.975T12 2t5.588 2.225T20 10.2q0 1.125-.437 2.363t-1.275 2.575T16.2 17.9t-2.875 2.925q-.275.25-.625.375t-.7.125M12 12q.825 0 1.413-.587T14 10t-.587-1.412T12 8t-1.412.588T10 10t.588 1.413T12 12"/></svg>                                    
                                    {{ venue.location }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center text-gray-600 mt-4">
                    No venues found.
                </div>
            </div>

            <!-- Modal for Add/Edit -->
            <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModal"></div>
                <div class="bg-white p-6 rounded-xl shadow-lg z-50 max-w-md w-full">
                    <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit Venue' : 'Add Venue' }}</h2>
                    <form @submit.prevent="submitVenue">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm mb-1.5 font-medium">Venue Name</label>
                            <input required placeholder="Enter the name of the venue..." v-model="form.name" type="text" class="border transition border-gray-300 rounded-lg bg-gray-50 text-sm w-full p-2"/>
                        </div>

                        <div class="mb-4">            
                            <label for="address" class="block mb-1.5 text-sm font-medium text-gray-700">Description</label>
                            <textarea required id="address" rows="3" v-model="form.description" class="block p-2.5 transition w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter a brief description about the venue..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm mb-1.5 font-medium">Location</label>
                            <input required placeholder="e.g., MSU at Naawan..." v-model="form.location" type="text" class="border transition border-gray-300 rounded-lg bg-gray-50 text-sm w-full p-2"/>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="closeModal" class="mr-2 bg-gray-100 hover:bg-gray-200 px-4 py-2 text-sm rounded-lg transition">Cancel</button>
                           
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition relative"
                            >
                                <span v-if="!form.processing">
                                    {{ isEditing ? 'Save Changes' : 'Confirm' }}
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
                        <button @click="deleteVenue" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition">Yes, delete it</button>
                    </div>
                </div>
            </div>
            <!--Tooltip-->
            <div class="tooltip absolute z-10 invisible py-2 px-3 text-xs text-white bg-blue-800 rounded-lg opacity-0 transition-opacity duration-300">
                <div class="tooltip-content">Hello</div>
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
    import { Head,useForm, router } from '@inertiajs/vue3';
    import { ref, onMounted, computed } from 'vue';
    import { route } from 'ziggy-js';
    import AppLayout from '@/Layout/DashboardLayout.vue';

    const props = defineProps({
        venues: Array,
        errors: Object
    })

    const isModalOpen = ref(false);
    const isDeleteModalOpen = ref(false);
    const isEditing = ref(false);
    const form = useForm({
        id: null,
        name: '',
        description: '',
        location: '',
    });

    const venueIdToDelete = ref(null);

    const openModal = (editMode = false, venues = null) => {
        isModalOpen.value = true;
        isEditing.value = editMode;

        if (editMode && venues) {
            form.id = venues.id;
            form.name = venues.name;
            form.description = venues.description;
            form.location = venues.location;
        } else {
            form.reset();
        }
    };

    const closeModal = () => {
        isModalOpen.value = false;
        form.reset();
    };

    const submitVenue = () => {
        if (isEditing.value) {
            form.put(route('venue.update', form.id), {
                preserveState: false,
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                },
            });
        } else {
            form.post(route('venue.store'), {
                preserveState: false,
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                },
            });
        }
    };

    const confirmDelete = (venueId) => {
        venueIdToDelete.value = venueId;
        isDeleteModalOpen.value = true;
    };

    const closeDeleteModal = () => {
        isDeleteModalOpen.value = false;
        venueIdToDelete.value = null;
    };

    const deleteVenue = () => {
        router.delete(route('venue.destroy', venueIdToDelete.value), {
            preserveState: false,
            preserveScroll: true,
        });
        closeDeleteModal();
    };
    //---------------------------------------------------------------------------------

    const searchQuery = ref('');

    const filteredVenues = computed(() => {
        if (!searchQuery.value) {
            return props.venues;
        }
        const lowercaseQuery = searchQuery.value.toLowerCase();
        return props.venues.filter(venue => 
            venue.name.toLowerCase().includes(lowercaseQuery)
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
</script>
 
<style scoped>
    /* Home page specific styles */
</style>
 