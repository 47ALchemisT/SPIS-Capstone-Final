<template>
    <Head title="Create Admin Account - Step 1" />
    <section class="min-h-screen flex items-center justify-center dark:bg-gray-900">
        <div class="w-full max-w-xl p-4">
            <div class="p-6 bg-white ">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-0.5">Create Admin Account</h1>
                <h3 class="text-sm text-gray-600 mb-6">Enter student information</h3>

                <form @submit.prevent="createStudent" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-900 dark:text-white mb-2">First Name</label>
                            <input 
                                v-model="studentForm.first_name" 
                                type="text" 
                                class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" 
                                required 
                            />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-900 dark:text-white mb-2">Last Name</label>
                            <input 
                                v-model="studentForm.last_name" 
                                type="text" 
                                class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" 
                                required 
                            />
                        </div>
                    </div>


                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm text-gray-900 dark:text-white mb-2">University Email</label>
                            <input 
                                v-model="studentForm.univ_email" 
                                type="email" 
                                class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" 
                                required 
                            />
                        </div>

                        <div>
                            <label class="block text-sm text-gray-900 dark:text-white mb-2">ID Number</label>
                            <input 
                                v-model="studentForm.id_number" 
                                type="text" 
                                class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" 
                                required 
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-900 dark:text-white mb-2">Contact Number</label>
                        <input 
                            v-model="studentForm.contact" 
                            type="text" 
                            class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" 
                            required 
                        />
                    </div>

                    <div>
                        <label class="block text-sm text-gray-900 dark:text-white mb-2">College</label>
                        <select 
                            v-model="studentForm.college" 
                            class="ring-1 ring-gray-200 w-full p-2.5 bg-gray-50 border-gray-300 text-sm rounded-lg dark:bg-gray-700 dark:text-white" 
                            required
                        >
                            <option value="" disabled>Select College</option>
                            <option value="College of Engineering">College of Engineering</option>
                            <option value="College of Industrial Technology">College of Industrial Technology</option>
                            <option value="College of Architecture and Fine Arts">College of Architecture and Fine Arts</option>
                            <option value="College of Liberal Arts">College of Liberal Arts</option>
                            <option value="College of Science">College of Science</option>
                        </select>
                    </div>


                    <div class="mt-2">
                        <span v-if="errorMessage" class="text-sm text-red-600">{{ errorMessage }}</span>
                    </div>

                    <div class="flex justify-end font-medium space-x-3 mt-8">
                        <Link 
                            href="/login" 
                            class="px-4 py-2.5 text-sm text-gray-600 hover:text-gray-800"
                        >
                            Cancel
                        </Link>
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Next
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { router, Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    first_name: {
        type: String,
        default: ''
    },
    last_name: {
        type: String,
        default: ''
    },
    id_number: {
        type: String,
        default: ''
    },
    univ_email: {
        type: String,
        default: ''
    },
    college: {
        type: String,
        default: 'College of Engineering'
    },
    contact: {
        type: String,
        default: ''
    }
});

const errorMessage = ref('');

const studentForm = reactive({
    first_name: props.first_name,
    last_name: props.last_name,
    id_number: props.id_number,
    univ_email: props.univ_email,
    college: props.college,
    contact: props.contact
});

const createStudent = () => {
    router.post('/register-admin/create-student', studentForm, {
        preserveScroll: true,
        onError: (errors) => {
            if (errors.id_number) {
                errorMessage.value = errors.id_number;
            } else if (errors.univ_email) {
                errorMessage.value = errors.univ_email;
            } else {
                errorMessage.value = 'Error creating student record';
            }
        }
    });
};
</script>
