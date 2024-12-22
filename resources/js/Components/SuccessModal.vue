<template>
    <Transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show"
            class="fixed mx-4 sm:mx-0 top-4 sm:right-4 z-50 w-auto min-w-full sm:min-w-0 sm:w-full sm:max-w-sm"
        >
            <div class="bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 p-3 sm:p-4">
                <div class="flex items-start space-x-2 sm:space-x-3">
                    <!-- Success Icon -->
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-check text-green-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 pt-0.5">
                        <p class="text-sm sm:text-base font-medium text-gray-900">{{ title }}</p>
                        <p class="mt-1 text-xs sm:text-sm text-gray-500">{{ message }}</p>
                    </div>
                    
                    <!-- Close Button -->
                    <div class="flex-shrink-0">
                        <button
                            @click="close"
                            class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times h-4 w-4 sm:h-5 sm:w-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { defineProps, defineEmits, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    title: {
        type: String,
        default: 'Success'
    },
    message: {
        type: String,
        default: 'Operation completed successfully.'
    },
    duration: {
        type: Number,
        default: 3000 // Auto-dismiss after 3 seconds
    }
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

// Auto-dismiss timer
watch(() => props.show, (newValue) => {
    if (newValue && props.duration > 0) {
        setTimeout(() => {
            close();
        }, props.duration);
    }
});
</script>