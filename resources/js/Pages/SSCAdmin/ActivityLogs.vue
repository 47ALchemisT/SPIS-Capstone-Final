<template>
    <AppLayout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto ">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Activity Logs</h1>
                    <div class="flex gap-4">
                        <!-- Search -->
                        <div class="flex items-center">
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search logs..."
                                class="rounded-lg border-gray-300 p-2 text-sm ring-1 ring-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                @input="debouncedSearch"
                            >
                        </div>
                        <!-- Type Filter -->
                        <select
                            v-model="selectedType"
                            class="rounded-lg border-gray-300 p-2 text-sm ring-1 ring-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            @change="filterByType"
                        >
                            <option value="">All Types</option>
                            <option v-for="type in types" :key="type" :value="type">
                                {{ type }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Activity Log List -->
                <div class="mt-8 flex flex-col">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">User</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="log in logs.data" :key="log.id" class="hover:bg-gray-50">
                                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                <div class="text-gray-900">{{ log.causer_name }}</div>
                                                <div class="text-gray-500 text-xs" v-if="log.causer_role">{{ log.causer_role }}</div>
                                            </td>
                                            <td class="px-3 py-4 text-sm text-gray-900">{{ log.description }}</td>

                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ log.date }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex items-center justify-between">
                    <div class="flex justify-between items-center w-full">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link
                                v-if="logs.prev_page_url"
                                :href="logs.prev_page_url"
                                class="relative inline-flex items-center px-4 py-2  text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Previous
                            </Link>
                            <Link
                                v-if="logs.next_page_url"
                                :href="logs.next_page_url"
                                class="ml-3 relative inline-flex items-center px-4 py-2  text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Next
                            </Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ logs.from }}</span>
                                    to
                                    <span class="font-medium">{{ logs.to }}</span>
                                    of
                                    <span class="font-medium">{{ logs.total }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md -space-x-px">
                                    <Link
                                        v-for="(link, index) in logs.links"
                                        :key="index"
                                        :href="link.url"
                                        :class="[
                                            link.active ? 'bg-blue-50  text-blue-600' : 'bg-white  text-gray-500 hover:bg-gray-50',
                                            'relative inline-flex items-center px-4 py-2 text-sm font-medium'
                                        ]"
                                        v-html="link.label"
                                    />
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layout/DashboardLayout.vue';

const props = defineProps({
    logs: Object,
    types: Array,
    filters: Object
});

const search = ref(props.filters.search || '');
const selectedType = ref(props.filters.type || '');
let searchTimeout;

const debouncedSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('activity-logs.index'), { 
            search: search.value,
            type: selectedType.value
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        });
    }, 300);
};

const filterByType = () => {
    router.get(route('activity-logs.index'), {
        search: search.value,
        type: selectedType.value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};
</script>
