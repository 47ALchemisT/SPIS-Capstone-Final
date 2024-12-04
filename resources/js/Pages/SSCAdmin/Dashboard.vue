<template>
    <Head title="Dashboard"/>
    <AppLayout>
        <template v-slot:default>
            <div class="space-y-2 px-2">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 flex items-center justify-center bg-yellow-100/80 rounded-lg">
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <defs>
                                <linearGradient id="handGradient" gradientTransform="rotate(45)">
                                    <stop offset="0%" stop-color="#FCD34D"/>
                                    <stop offset="100%" stop-color="#F59E0B"/>
                                </linearGradient>
                            </defs>
                            <path fill="url(#handGradient)" d="M23 17c0 3.31-2.69 6-6 6v-1.5c2.5 0 4.5-2 4.5-4.5zM1 7c0-3.31 2.69-6 6-6v1.5c-2.5 0-4.5 2-4.5 4.5zm7-2.68l-4.59 4.6c-3.22 3.22-3.22 8.45 0 11.67s8.45 3.22 11.67 0l7.07-7.09c.49-.47.49-1.26 0-1.75a1.25 1.25 0 0 0-1.77 0l-4.42 4.42l-.71-.71l6.54-6.54c.49-.49.49-1.28 0-1.77s-1.29-.49-1.79 0L14.19 13l-.69-.73l6.87-6.89c.49-.49.49-1.28 0-1.77s-1.28-.49-1.77 0l-6.89 6.89l-.71-.7l5.5-5.48c.5-.49.5-1.28 0-1.77s-1.28-.49-1.77 0l-7.62 7.62a4 4 0 0 1-.33 5.28l-.71-.71a3 3 0 0 0 0-4.24l-.35-.35l4.07-4.07c.49-.49.49-1.28 0-1.77c-.5-.48-1.29-.48-1.79.01"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            Welcome Back, Admin!
                        </h2>
                        <p class="text-md text-gray-600">
                            {{ getGreeting() }} Here's what's happening with your sports system today.
                        </p>
                    </div>
                </div>
            </div>
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 px-2 gap-5 mt-6 sm:grid-cols-2 lg:grid-cols-4">
                        <!-- Statistics Cards -->
                        <div class="bg-gradient-to-br from-white to-blue-50 overflow-hidden ring-1 ring-blue-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-5">
                                <div class="">
                                    <div class="flex items-center justify-between">
                                        <dt class="text-sm font-medium text-blue-900">Colleges</dt>
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div class="mt-6">
                                        <dl>
                                            <dd class="text-2xl font-semibold text-blue-900">{{ colleges.length }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-white to-blue-50 overflow-hidden ring-1 ring-blue-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-5">
                                <div class="">
                                    <div class="flex items-center justify-between">
                                        <dt class="text-sm font-medium text-blue-900">Sports</dt>
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                        </svg>
                                    </div>
                                    <div class="mt-6">
                                        <dl>
                                            <dd class="text-2xl font-semibold text-blue-900">{{ sports.length }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-white to-blue-50 overflow-hidden ring-1 ring-blue-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-5">
                                <div class="">
                                    <div class="flex items-center justify-between">
                                        <dt class="text-sm font-medium text-blue-900">Venues</dt>
                                        <svg  class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18.5L9 17l-6 3V7l6-3l6 3l6-3v7.5M9 4v13m6-10v5.5m6.121 7.621a3 3 0 1 0-4.242 0Q17.506 20.749 19 22q1.577-1.335 2.121-1.879M19 18v.01"/></svg>
                                    </div>
                                    <div class="mt-6">
                                        <dl>
                                            <dd class="text-2xl font-semibold text-blue-900">{{ venues.length }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-white to-blue-50 overflow-hidden ring-1 ring-blue-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-5">
                                <div class="">
                                    <div class="flex items-center justify-between">
                                        <dt class="text-sm font-medium text-blue-900">Students</dt>
                                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5.5 8a2.5 2.5 0 1 1 5 0a2.5 2.5 0 0 1-5 0M8 4a4 4 0 1 0 0 8a4 4 0 0 0 0-8m7.5 5a1.5 1.5 0 1 1 3 0a1.5 1.5 0 0 1-3 0M17 6a3 3 0 1 0 0 6a3 3 0 0 0 0-6m-2.752 13.038c.703.285 1.604.462 2.753.462c2.282 0 3.586-.697 4.297-1.558c.345-.418.52-.84.61-1.163a2.7 2.7 0 0 0 .093-.573v-.027A2.18 2.18 0 0 0 19.822 14H14.18q-.042 0-.082.002c.394.41.68.925.816 1.498h4.908c.372 0 .674.299.679.669l-.003.032q-.006.058-.037.18a1.6 1.6 0 0 1-.32.605c-.35.426-1.172 1.014-3.14 1.014c-.98 0-1.676-.146-2.17-.345c-.108.4-.286.883-.583 1.383M4.25 14A2.25 2.25 0 0 0 2 16.25v.278a2 2 0 0 0 .014.208a4.5 4.5 0 0 0 .778 2.07C3.61 19.974 5.172 21 8 21s4.39-1.025 5.208-2.195a4.5 4.5 0 0 0 .778-2.07a3 3 0 0 0 .014-.207v-.278A2.25 2.25 0 0 0 11.75 14zm-.75 2.507v-.257a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 .75.75v.257l-.007.08a3 3 0 0 1-.514 1.358C11.486 18.65 10.422 19.5 8 19.5s-3.486-.85-3.98-1.555a3 3 0 0 1-.513-1.358z"/></svg>
                                    </div>
                                    <div class="mt-6">
                                        <dl>
                                            <dd class="text-2xl font-semibold text-blue-900">{{ students.length }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>

            <!-- quick links with icons -->
            <div class="px-2 mt-6">
                <div class="">
                    <div class="">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-link h-10 w-10 flex items-center justify-center text-blue-600 bg-4 bg-blue-50 rounded-lg"></i>
                            <h1 class="text-md font-semibold text-gray-700">Quick Links</h1>
                        </div>
                        <div class="flex gap-4 mt-4">
                            <!-- Create New Palakasan -->
                            <Link href="/sport"  class="flex items-center bg-blue-50 py-2.5 px-4 rounded-full hover:bg-blue-700  group transition-colors">
                                <div class="flex items-center gap-2 text-blue-600 group-hover:text-white transition-colors text-sm">
                                    <h3 class="font-medium">Add Sport</h3>
                                    <svg class="w-5 h-5" mlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g class="plus-circle-outline"><g fill="currentColor" fill-rule="evenodd" class="Vector" clip-rule="evenodd"><path d="M12 17a1 1 0 0 1-1-1V8a1 1 0 1 1 2 0v8a1 1 0 0 1-1 1"/><path d="M17 12a1 1 0 0 1-1 1H8a1 1 0 1 1 0-2h8a1 1 0 0 1 1 1"/><path d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2s10 4.477 10 10m-10 8a8 8 0 1 0 0-16a8 8 0 0 0 0 16"/></g></g></svg>
                                </div>
                            </Link>
                            <Link href="/college"  class="flex items-center bg-blue-50 py-2.5 px-4 rounded-full hover:bg-blue-700  group transition-colors">
                                <div class="flex items-center gap-2 text-blue-600 group-hover:text-white transition-colors text-sm">
                                    <h3 class="font-medium">Add College</h3>
                                    <svg class="w-5 h-5" mlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g class="plus-circle-outline"><g fill="currentColor" fill-rule="evenodd" class="Vector" clip-rule="evenodd"><path d="M12 17a1 1 0 0 1-1-1V8a1 1 0 1 1 2 0v8a1 1 0 0 1-1 1"/><path d="M17 12a1 1 0 0 1-1 1H8a1 1 0 1 1 0-2h8a1 1 0 0 1 1 1"/><path d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2s10 4.477 10 10m-10 8a8 8 0 1 0 0-16a8 8 0 0 0 0 16"/></g></g></svg>
                                </div>
                            </Link>
                            <Link href="/points"  class="flex items-center bg-blue-50 py-2.5 px-4 rounded-full hover:bg-blue-700  group transition-colors">
                                <div class="flex items-center gap-2 text-blue-600 group-hover:text-white transition-colors text-sm">
                                    <h3 class="font-medium">Points Setup</h3>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m9 4l2.5 5.5L17 12l-5.5 2.5L9 20l-2.5-5.5L1 12l5.5-2.5zm0 4.83L8 11l-2.17 1L8 13l1 2.17L10 13l2.17-1L10 11zM19 9l-1.26-2.74L15 5l2.74-1.25L19 1l1.25 2.75L23 5l-2.75 1.26zm0 14l-1.26-2.74L15 19l2.74-1.25L19 15l1.25 2.75L23 19l-2.75 1.26z"/></svg>
                                </div>
                            </Link>
                            <Link href="/venue"  class="flex items-center bg-blue-50 py-2.5 px-4 rounded-full hover:bg-blue-700  group transition-colors">
                                <div class="flex items-center gap-2 text-blue-600 group-hover:text-white transition-colors text-sm">
                                    <h3 class="font-medium">New Venue</h3>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M16.219 1.943c.653.512 1.103 1.339 1.287 2.205l.065.026l2.045.946a.66.66 0 0 1 .384.597v12.367a.665.665 0 0 1-.85.634l-5.669-1.6l-6.74 1.858a.67.67 0 0 1-.371-.004L.474 17.217a.66.66 0 0 1-.474-.63V3.998c0-.44.428-.756.855-.632l5.702 1.661l2.898-.887q.06-.018.122-.025c.112-.656.425-1.286.95-1.9c.623-.73 1.716-1.158 2.781-1.209c1.105-.053 1.949.183 2.91.936M1.333 4.881v11.215l4.87 1.449V6.298zm8.209.614l-2.006.613v11.279l5.065-1.394v-3.295c0-.364.299-.659.667-.659s.666.295.666.66v3.177l4.733 1.335V6.136l-1.12-.52q-.028.165-.073.323A6.1 6.1 0 0 1 16.4 8.05l-2.477 3.093a.67.67 0 0 1-1.073-.037l-2.315-3.353q-.574-.799-.801-1.436a3.7 3.7 0 0 1-.192-.822m3.83-3.171c-.726.035-1.472.327-1.827.742c-.427.5-.637.968-.679 1.442c-.05.571-.016.974.126 1.373c.105.295.314.669.637 1.12l1.811 2.622l1.91-2.385a4.8 4.8 0 0 0 .841-1.657c.24-.84-.122-2.074-.8-2.604c-.695-.545-1.22-.692-2.018-.653m.138.697c1.104 0 2 .885 2 1.977a1.99 1.99 0 0 1-2 1.977c-1.104 0-2-.885-2-1.977s.896-1.977 2-1.977m0 1.318a.663.663 0 0 0-.667.659c0 .364.299.659.667.659a.663.663 0 0 0 .666-.66a.663.663 0 0 0-.666-.658"/></svg>                                
                                </div>
                            </Link>
                            <Link href="/student"  class="flex items-center bg-blue-50 py-2.5 px-4 rounded-full hover:bg-blue-700  group transition-colors">
                                <div class="flex items-center gap-2 text-blue-600 group-hover:text-white transition-colors text-sm">
                                    <h3 class="font-medium">Student Info</h3>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="256" height="256" viewBox="0 0 256 256"><path fill="currentColor" d="m226.53 56.41l-96-32a8 8 0 0 0-5.06 0l-96 32A8 8 0 0 0 24 64v80a8 8 0 0 0 16 0V75.1l33.59 11.19a64 64 0 0 0 20.65 88.05c-18 7.06-33.56 19.83-44.94 37.29a8 8 0 1 0 13.4 8.74C77.77 197.25 101.57 184 128 184s50.23 13.25 65.3 36.37a8 8 0 0 0 13.4-8.74c-11.38-17.46-27-30.23-44.94-37.29a64 64 0 0 0 20.65-88l44.12-14.7a8 8 0 0 0 0-15.18ZM176 120a48 48 0 1 1-86.65-28.45l36.12 12a8 8 0 0 0 5.06 0l36.12-12A47.9 47.9 0 0 1 176 120m-48-32.43L57.3 64L128 40.43L198.7 64Z"/></svg>
                                </div>
                            </Link>
                            <Link href="/account"  class="flex items-center bg-blue-50 py-2.5 px-4 rounded-full hover:bg-blue-700  group transition-colors">
                                <div class="flex items-center gap-2 text-blue-600 group-hover:text-white transition-colors text-sm">
                                    <h3 class="font-medium">Create Account</h3>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M10 12c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4m0-6a2 2 0 1 1 0 4c-1.11 0-2-.89-2-2s.9-2 2-2m2 14H2v-3c0-2.67 5.33-4 8-4c1 0 2.38.19 3.71.56c-.3.56-.48 1.18-.5 1.83c-.98-.29-2.1-.49-3.21-.49c-2.97 0-6.1 1.46-6.1 2.1v1.1H12zm8.8-3v-1.5c0-1.4-1.4-2.5-2.8-2.5s-2.8 1.1-2.8 2.5V17c-.6 0-1.2.6-1.2 1.2v3.5c0 .7.6 1.3 1.2 1.3h5.5c.7 0 1.3-.6 1.3-1.2v-3.5c0-.7-.6-1.3-1.2-1.3m-1.3 0h-3v-1.5c0-.8.7-1.3 1.5-1.3s1.5.5 1.5 1.3z"/></svg>                                
                                </div>
                            </Link>
                            <Link href="/details"  class="flex items-center bg-blue-50 py-2.5 px-4 rounded-full hover:bg-blue-700  group transition-colors">
                                <div class="flex items-center gap-2 text-blue-600 group-hover:text-white transition-colors text-sm">
                                    <h3 class="font-medium">Palakasan</h3>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M8.6 9.6c.4.6.9 1.1 1.6 1.4h4c.3-.1.5-.3.7-.5c1-1 1.4-2.5.9-3.8l-.1-.2c-.1-.3-.3-.5-.5-.7c-.1-.2-.3-.3-.4-.5c-.4-.3-.8-.6-1.2-1c-.9-.9-1-2.3-.5-3.3c-.5.1-1 .4-1.4.8C10.2 3 9.6 5.1 10.3 7v.2c0 .1-.1.2-.2.3s-.3 0-.4-.1l-.1-.1c-.6-.8-.7-2-.3-3c-.9.8-1.4 2.1-1.3 3.4c0 .3.1.6.2.9c0 .3.2.7.4 1m3.7-1.5c.1-.5-.1-.9-.2-1.3s-.1-.8.1-1.2l.3.6c.4.6 1.1.8 1.3 1.6v.3c0 .5-.2 1-.5 1.3c-.2.1-.4.3-.6.3c-.6.2-1.3-.1-1.7-.5c.8 0 1.2-.6 1.3-1.1M15 12v2h-1l-1 8h-2l-1-8H9v-2z"/></svg>                                
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <section class="bg-white mt-5 overflow-hidden rounded-lg p-2">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 h-full">
                    <div class="space-y-6 h-full">
                        <div class="bg-white overflow-hidden h-full ring-1 ring-gray-300 rounded-lg">
                            <div class="p-6 h-full">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-medium text-gray-900">Recent Activities</h3>
                                    <Link :href="route('activity-logs.index')" class="text-xs text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                                        View All
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </Link>
                                </div>

                                <div class="mt-6 flow-root">
                                    <ul class="-mb-8">
                                        <li v-for="activity in recentActivities" :key="activity.id">
                                            <div class="relative pb-8">
                                                <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div :class="[
                                                            'relative px-1',
                                                            {
                                                                'bg-green-100': activity.type === 'create',
                                                                'bg-blue-100': activity.type === 'update',
                                                                'bg-red-100': activity.type === 'delete'
                                                            }
                                                        ]" class="flex h-8 w-8 items-center justify-center rounded-full ring-8 ring-white">
                                                            <svg v-if="activity.type === 'create'" class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                            </svg>
                                                            <svg v-else-if="activity.type === 'update'" class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                                                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                                            </svg>
                                                            <svg v-else class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div>
                                                            <div class="text-sm text-gray-500">
                                                                <span class="font-medium text-gray-900">{{ activity.causer_name }}</span>
                                                                {{ activity.description }}
                                                            </div>
                                                            <p class="mt-0.5 text-xs text-gray-500">{{ activity.created_at }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-2 h-full">
                        <div v-if="latestPalakasan" class="bg-white overflow-hidden ring-1 ring-gray-300 rounded-lg h-full">
                            <div class="p-6 h-full">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Palakasan Event {{ latestPalakasan.year }}</h3>
                                    <span :class="{
                                        'bg-green-100 text-green-800': latestPalakasan.status === 'completed',
                                        'bg-blue-100 text-blue-800': latestPalakasan.status === 'live',
                                        'bg-gray-100 text-gray-800': latestPalakasan.status === 'pending'
                                    }" class="px-3 py-1 rounded-full text-sm font-medium">
                                        {{ latestPalakasan.status }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-1 gap-6">

                                    <!-- Event Details -->
                                    <div class="space-y-4">
                                        <div>                                            
                                            <h1 class="text-xl  font-medium text-gray-800">{{ latestPalakasan.theme }}</h1>

                                            <h1 class="text-md mb-2 font-medium text-gray-600">{{ latestPalakasan.tagline }}</h1>
                                            <p class="text-sm text-gray-500 line-clamp-4" v-if="!showFullDescription">
                                                {{ latestPalakasan.description }}
                                                <button @click="showFullDescription = true" class="text-blue-500 hover:text-blue-600 font-medium ml-1">
                                                    See more
                                                </button>
                                            </p>
                                            <p v-else class="text-sm text-gray-500">
                                                {{ latestPalakasan.description }}
                                                <button @click="showFullDescription = false" class="text-blue-500 hover:text-blue-600 font-medium ml-1">
                                                    See less
                                                </button>
                                            </p>
                                        </div>
                                        
                                        <div class="flex items-center space-x-6 text-sm">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                </svg>
                                                <div>
                                                    <span class="text-gray-500">From</span>
                                                    <span class="ml-1 font-medium text-gray-900">{{ new Date(latestPalakasan.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }}</span>
                                                </div>
                                            </div>
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                                            </svg>
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                </svg>
                                                <div>
                                                    <span class="text-gray-500">To</span>
                                                    <span class="ml-1 font-medium text-gray-900">{{ new Date(latestPalakasan.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--progress bar-->
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-sm font-medium text-gray-600">Overall Progress : <span class="font-normal">{{ completedSports }} of {{ assignedSports.length }} sports completed</span></h4>
                                            <span class="text-sm font-medium text-gray-900">{{ progressPercentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" :style="{ width: progressPercentage + '%' }"></div>
                                        </div>
                                    </div>

                                    <!-- Statistics -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Assigned Sports Card -->
                                        <div class="overflow-hidden bg-blue-50 rounded-lg p-4">
                                            <div class="flex flex-col">
                                                <dt class="text-sm font-medium text-blue-500">Assigned Sports</dt>
                                                <dd class="mt-4 text-3xl font-semibold text-blue-800">{{ assignedSports?.length }}</dd>
                                            </div>
                                            <div class="mt-4">
                                                <div class="text-sm text-blue-500">
                                                    <ul class="list-disc list-inside">
                                                        <li v-for="sport in assignedSports.slice(0, 3)" :key="sport.id">
                                                            {{ sport.sport.name }} {{ sport.categories }}
                                                        </li>
                                                    </ul>
                                                    <p v-if="assignedSports.length > 3" class="mt-1 text-blue-400">
                                                        +{{ assignedSports.length - 3 }} more
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Assigned Teams Card -->
                                        <div class="bg-blue-50 overflow-hidden  rounded-lg p-4">
                                            <div class="flex flex-col">
                                                <dt class="text-sm font-medium text-blue-500">Participating Teams</dt>
                                                <dd class="mt-4 text-3xl font-semibold text-blue-800">{{ assignedTeams.length }}</dd>
                                            </div>
                                            <div class="mt-4">
                                                <div class="text-sm text-blue-500">
                                                    <ul class="list-disc list-inside">
                                                        <li v-for="team in assignedTeams.slice(0, 3)" :key="team.id">
                                                            {{ team.assigned_team_name }} ( {{ team.college.shortName }} )
                                                        </li>
                                                    </ul>
                                                    <p v-if="assignedTeams.length > 3" class="mt-1 text-blue-400">
                                                        +{{ assignedTeams.length - 3 }} more
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- No Palakasan Message -->
                        <div v-else class="bg-white overflow-hidden ring-1 ring-gray-300 rounded-lg p-6">
                            <div class="text-center">
                                <h3 class="text-lg font-medium text-gray-900">No Palakasan Events</h3>
                                <p class="mt-2 text-sm text-gray-500">There are no Palakasan events created yet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </AppLayout>
</template>

<script setup>
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayout.vue';

const props = defineProps({
    sports: Array,
    colleges: Array,
    venues: Array,
    account: Array,
    students: Array,
    palakasans: Array,
    latestPalakasan: Object,
    recentActivities: Array,
    assignedTeams: Array,
    assignedSports: Array
});

const showFullDescription = ref(false);

const getGreeting = () => {
    const hour = new Date().getHours();
    if (hour < 12) return "Good morning!";
    if (hour < 17) return "Good afternoon!";
    return "Good evening!";
};

const completedSports = computed(() => {
    if (!props.assignedSports) return 0;
    return props.assignedSports.filter(sport => sport.status === 'completed').length;
});

const progressPercentage = computed(() => {
    if (!props.assignedSports || !props.assignedSports.length) return 0;
    return Math.round((completedSports.value / props.assignedSports.length) * 100);
});
</script>

<style scoped>
/* Dashboard specific styles */
</style>