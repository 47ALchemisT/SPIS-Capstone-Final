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
            <div class="mt-3 space-y-3">
                <!--Utility-->
                <div class="utility">
                    <div class="flex justify-between items-center">
                        <div class="search flex space-x-2 items-center">
      

                        </div>
                        <!--Buttons-->
                        <div class="flex items-center space-x-2.5">
                            <button @click="openModal(false)" class=" bg-blue-800 text-white py-2 px-3 rounded-lg text-sm font-medium shadow hover:bg-blue-800/90 transition-colors" >
                                <i class="fa-solid fa-square-plus mr-1"></i>   
                                Sport                             
                            </button>
                        </div>
                    </div>
                </div>
                <Toast ref="toastRef" />

                <!--Main Content, List of Cards-->
                <div>
                    <div  class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-2 gap-4">
                        <div v-for="sport in sports" :key="sport.id" class="bg-white rounded-lg shadow hover:shadow-lg transition-all ring-1 ring-gray-300">
                            <div class="p-4 relative">
                                <div class="absolute right-3 top-3">
                                    <Menu as="div" class="relative inline-block text-left">
                                        <MenuButton class="inline-flex items-center justify-center w-full rounded-lg  px-2.5 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50" >
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
                                <div class="flex items-center justify-between mb-3">
                                    <h2 class="text-gray-800 text-2xl font-bold">{{ sport.name }}</h2>
                                    <i class="fa-solid fa-cube rounded-full text-blue-900 text-xl"></i>
                                </div>

                                <p class="text-gray-600 text-sm mb-4">{{ sport.description }}</p>
                                <div class="flex flex-wrap bottom-0 gap-2">
                                    <span v-for="type in sport.types" :key="type" class="bg-blue-100 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ type }}
                                    </span>
                                </div>
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
                            <button type="button" @click="closeModal" class="mr-2 bg-gray-100 hover:bg-gray-200 px-4 py-2 text-sm rounded-lg transition">Cancel</button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition relative"
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
 