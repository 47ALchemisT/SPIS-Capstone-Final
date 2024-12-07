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
            class="fixed top-4 right-4 z-50 w-full max-w-sm"
        >
            <div class="bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 p-4">
                <div class="flex items-start">
                    <!-- Success Icon -->
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900">{{ title }}</p>
                        <p class="mt-1 text-sm text-gray-500">{{ message }}</p>
                    </div>

                    <!-- Close Button -->
                    <div class="ml-4 flex-shrink-0 flex">
                        <button
                            @click="close"
                            class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times h-5 w-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { defineProps, defineEmits, onMounted, watch } from 'vue';

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
