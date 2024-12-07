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
            <div class="mt-5 space-y-4">
                <!--Utility Bar-->
                <div class="bg-white ">
                    <div class="flex flex-wrap gap-4 justify-between items-center">
                        <div class="flex items-center gap-2">
                            <!-- Enhanced Search Input -->
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search venues..."
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
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-map-location-dot text-blue-600"></i>
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ venues.length }} {{ venues.length === 1 ? 'venue' : 'venues' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!--Add Venue Button-->
                        <button 
                            @click="openModal(false)" 
                            type="button" 
                            class="inline-flex items-center gap-2 text-white text-sm bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 font-medium rounded-lg px-4 py-2.5 transition-all duration-200 shadow-sm"
                        >
                            Add Venue
                        </button>
                    </div>
                </div>

                <!--Venue Grid-->
                <div v-if="filteredVenues.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Venue Card -->
                    <div 
                        v-for="venue in filteredVenues" 
                        :key="venue.id" 
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
                                                    @click="openModal(true, venue)" 
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
                                                    @click="confirmDelete(venue.id)" 
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
                            <!-- Venue Icon with Background -->
                            <div class="mb-4 inline-flex items-center justify-center w-12 h-12 rounded-lg bg-blue-50 text-blue-600">
                                <i class="fa-solid fa-location-dot text-xl"></i>
                            </div>

                            <!-- Venue Information -->
                            <div class="space-y-1">
                                <h3 class="text-xl font-semibold text-gray-900">{{ venue.name }}</h3>
                                <p class="text-sm font-medium text-gray-600">{{ venue.location }}</p>
                            </div>

                            <!-- Description with fade effect -->
                            <div class="mt-4 relative">
                                <p class="text-sm text-gray-600 line-clamp-2">{{ venue.description || 'No description available' }}</p>
                                <div class="absolute bottom-0 left-0 right-0 h-4 bg-gradient-to-t from-white to-transparent"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="flex flex-col items-center justify-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fa-solid fa-map-location-dot text-5xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No venues found</h3>
                    <p class="text-gray-500 text-sm">Try adjusting your search or add a new venue</p>
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
                                    <DialogTitle class="text-xl font-bold text-gray-900 mb-4">{{ isEditing ? 'Edit Venue' : 'Add Venue' }}</DialogTitle>
                                    <form @submit.prevent="submitVenue" class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Venue Name</label>
                                            <input 
                                                required 
                                                placeholder="Enter the name of the venue..." 
                                                v-model="form.name" 
                                                type="text" 
                                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </div>

                                        <div>            
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                            <textarea 
                                                required 
                                                rows="3" 
                                                v-model="form.description" 
                                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" 
                                                placeholder="Enter a brief description about the venue..."
                                            ></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                            <input 
                                                required 
                                                placeholder="e.g., MSU at Naawan..." 
                                                v-model="form.location" 
                                                type="text" 
                                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
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
                                                    {{ isEditing ? 'Save Changes' : 'Add Venue' }}
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
                                            <DialogTitle class="text-xl font-bold text-gray-900">Delete Venue</DialogTitle>
                                            <p class="text-sm text-gray-600">This action cannot be undone</p>
                                        </div>
                                    </div>

                                    <p class="text-gray-600 mb-6">Are you sure you want to remove this venue from the system?</p>

                                    <div class="flex justify-end gap-3">
                                        <button 
                                            type="button" 
                                            @click="closeDeleteModal" 
                                            class="px-4 py-2.5 text-sm font-medium text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50 rounded-lg transition"
                                        >
                                            Cancel
                                        </button>
                                        <button 
                                            @click="deleteVenue" 
                                            class="px-4 py-2.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition"
                                        >
                                            Delete Venue
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
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { Head,useForm, router } from '@inertiajs/vue3';
    import { ref, onMounted, computed } from 'vue';
    import { route } from 'ziggy-js';
    import AppLayout from '@/Layout/DashboardLayout.vue';
    import SuccessModal from '@/Components/SuccessModal.vue';
    import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';

    const props = defineProps({
        venues: Array,
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
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                    showSuccessModal.value = true;
                    successMessage.value = 'Venue updated successfully!';
                },
            });
        } else {
            form.post(route('venue.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                    showSuccessModal.value = true;
                    successMessage.value = 'Venue created successfully!';
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
            preserveScroll: true,
            onSuccess: () => {
                closeDeleteModal();
                showSuccessModal.value = true;
                successMessage.value = 'Venue deleted successfully!';
            },
        });
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
</script>
 
<style scoped>
    /* Home page specific styles */
</style>
 