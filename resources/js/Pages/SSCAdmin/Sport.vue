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
            <div class="mt-5 space-y-4">
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
                                    placeholder="Search sports..."
                                    class="w-72 pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
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
                            <div class="flex items-center gap-3">
                                <div class="h-6 w-px bg-gray-200"></div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-trophy text-blue-600"></i>
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ filteredSports.length }} {{ filteredSports.length === 1 ? 'sport' : 'sports' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--Buttons-->
                        <div class="flex items-center space-x-2.5">
                            <button 
                                @click="openModal(false)" 
                                type="button" 
                                class="inline-flex items-center gap-2 text-white text-sm bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 font-medium rounded-lg px-4 py-2.5 transition-all duration-200 shadow-sm"
                            >
                                Add Sport
                            </button>
                        </div>
                    </div>
                </div>

                <!--Main Content, List of Cards-->
                <div v-if="filteredSports.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-5">
                    <div 
                        v-for="sport in filteredSports" 
                        :key="sport.id" 
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
                                                    @click="openModal(true, sport)" 
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
                                                    @click="confirmDelete(sport.id)" 
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
                            <!-- Sport Icon with Background -->
                            <div class="mb-4 inline-flex items-center justify-center w-12 h-12 rounded-lg bg-blue-50 text-blue-600">
                                <i class="fa-solid fa-trophy text-xl"></i>
                            </div>

                            <!-- Sport Information -->
                            <div class="space-y-1">
                                <h3 class="text-xl font-semibold text-gray-900">{{ sport.name }}</h3>
                            </div>

                            <!-- Description with fade effect -->
                            <div class="mt-2 relative">
                                <p v-if="!isExpanded" class="text-sm text-gray-600 line-clamp-2">{{ sport.description || 'No description available' }}</p>
                                <p v-else class="text-sm text-gray-600">{{ sport.description || 'No description available' }}</p>
                                
                                <button @click="toggleDescription" class="text-blue-500 text-sm mt-2">
                                    {{ isExpanded ? 'See Less' : 'See More' }}
                                </button>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="flex flex-col items-center justify-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fa-solid fa-trophy text-5xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No sports found</h3>
                    <p class="text-gray-500 text-sm">Try adjusting your search or add a new sport</p>
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
                                    <DialogTitle class="text-xl font-bold text-gray-900 mb-4">{{ isEditing ? 'Edit Sport' : 'Add Sport' }}</DialogTitle>
                                    <form @submit.prevent="submitSport">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-gray-700 text-sm mb-1.5 font-medium">Sport Name</label>
                                                <input 
                                                    v-model="form.name" 
                                                    required 
                                                    placeholder="Enter the full name of the sport..." 
                                                    type="text" 
                                                    class="border transition border-gray-300 rounded-lg bg-gray-50 text-sm w-full p-2.5 "
                                                />
                                            </div>

                                            <div>            
                                                <label for="description" class="block mb-1.5 text-sm font-medium text-gray-700">Description</label>
                                                <textarea 
                                                    v-model="form.description" 
                                                    id="description" 
                                                    rows="3" 
                                                    class="block p-2.5 transition w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 " 
                                                    placeholder="Enter a brief description about the sport..."
                                                ></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-end gap-3 mt-6">
                                            <button 
                                                type="button" 
                                                @click="closeModal" 
                                                class="px-4 py-2.5 text-sm font-medium text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-gray-300"
                                                >
                                                Cancel
                                            </button>
                                            <button
                                                type="submit"
                                                :disabled="form.processing"
                                                class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-75 disabled:cursor-not-allowed"
                                            >
                                                <span v-if="!form.processing">
                                                    {{ isEditing ? 'Save Changes' : 'Add Sport' }}
                                                </span>
                                                <span v-else class="flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="p-3 bg-red-100 rounded-full">
                                            <i class="fa-regular fa-trash-can text-xl text-red-600"></i>
                                        </div>
                                        <DialogTitle class="text-xl font-bold text-gray-900">Delete Sport</DialogTitle>
                                    </div>
                                    <p class="text-gray-600">Are you sure you want to remove this sport? This action cannot be undone.</p>
                                    <div class="flex justify-end gap-3 mt-6">
                                        <button 
                                            type="button" 
                                            @click="closeDeleteModal"
                                            class="px-4 py-2.5 text-sm font-medium text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-gray-300"
                                        >
                                            Cancel
                                        </button>
                                        <button 
                                            :disabled="form.processing"
                                            @click="deleteSport" 
                                            class="px-4 py-2.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-75 disabled:cursor-not-allowed"
                                        >
                                            <span v-if="!form.processing">Yes, delete sport</span>
                                            <span v-else class="flex items-center">
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.969 7.969 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Deleting...
                                            </span>
                                        </button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

            <!-- Modal for Success -->
            <SuccessModal
                :show="showSuccessModal"
                :message="successMessage"
                @close="showSuccessModal = false"
             />
        </template>
    </AppLayout>
</template>
 
<script setup>
    import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { ChevronDownIcon } from '@heroicons/vue/20/solid'
    import { Head, useForm, router, usePage } from '@inertiajs/vue3';
    import { ref, onMounted, watch, computed } from 'vue';
    import AppLayout from '@/Layout/DashboardLayout.vue';
    import SuccessModal from '@/Components/SuccessModal.vue';
    import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue'

    const props = defineProps({
        sports: Array,
    });
    //modal
    const showSuccessModal = ref(false);
    const successMessage = ref('');

    const isModalOpen = ref(false);
    const isDeleteModalOpen = ref(false);
    const isEditing = ref(false);
    const form = useForm({
        id: null,
        name: '',
        description: '',
    });

    const sportIdToDelete = ref(null);

    const searchQuery = ref('');

    const isExpanded = ref(false);

    function toggleDescription() {
        isExpanded.value = !isExpanded.value;
    }
    const filteredSports = computed(() => {
        if (!searchQuery.value) return props.sports;
        
        const query = searchQuery.value.toLowerCase();
        return props.sports.filter(sport => 
            sport.name.toLowerCase().includes(query) ||
            (sport.description && sport.description.toLowerCase().includes(query))
        );
    });

    const clearSearch = () => {
        searchQuery.value = '';
    };

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
                    showSuccessModal.value = true;
                    successMessage.value = 'Sport updated successfully!';
                },
            });
        } else {
            form.post(route('sport.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    closeModal();
                    showSuccessModal.value = true;
                    successMessage.value = 'Sport created successfully!';
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
            preserveScroll: true,
            onSuccess: () => {
                closeDeleteModal();
                showSuccessModal.value = true;
                successMessage.value = 'Sport deleted successfully!';
            }
        });
    };
    
 </script>
 
 <style scoped>
    /* Home page specific styles */
 </style>