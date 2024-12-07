<template>
    <div class="pb-4">
        <div class="max-w-7xl mx-auto">

            <!-- Tabs -->
            <div class="mb-4 justify-between flex">
                <nav class="flex gap-2 rounded-lg bg-white">
                    <a @click="activeTab = 'all'"
                        class="px-4 py-2 font-medium rounded-lg text-sm cursor-pointer transition-all duration-200"
                        :class="[
                            activeTab === 'all' 
                                ? 'bg-gray-100 text-gray-900' 
                                : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'
                        ]"
                    >
                        All ( {{ matchCounts.all }} )
                    </a>
                    <a @click="activeTab = 'pending'"
                        class="px-4 py-2 font-medium rounded-lg text-sm cursor-pointer transition-all duration-200"
                        :class="[
                            activeTab === 'pending' 
                                ? 'bg-gray-100 text-gray-900' 
                                : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'
                        ]"
                    >
                        Pending ( {{ matchCounts.pending }} )
                    </a>
                    <a @click="activeTab = 'completed'"
                        class="px-4 py-2 font-medium rounded-lg text-sm cursor-pointer transition-all duration-200"
                        :class="[
                            activeTab === 'completed' 
                                ? 'bg-gray-100 text-gray-900' 
                                : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'
                        ]"
                    >
                        Completed ( {{ matchCounts.completed }} )

                    </a>
                </nav>
                <div class="flex">
                    <button
                        @click="openPreviewModal"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-blue-600 focus:z-10 focus:ring-2 focus:ring-blue-200"
                        >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Schedule
                    </button>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="mb-6">
                <div class="flex gap-4">
                    <!-- Sport Filter -->
                    <div class="relative">
                        <select v-model="selectedSport" class="appearance-none w-full py-2 pl-3 pr-10 text-sm bg-white rounded-lg ring-1 ring-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Sports</option>
                            <option v-for="sport in activeSports" :key="sport.id" :value="sport.sport.name">
                                {{ sport.sport.name }}
                            </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <!-- Category Filter -->
                    <div class="relative">
                        <select v-model="selectedCategory" class="appearance-none w-full py-2 pl-3 pr-10 text-sm bg-white rounded-lg ring-1 ring-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            <option value="Men">Men</option>
                            <option value="Women">Women</option>
                            <option value="Mixed">Mixed</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <!-- Date Filter -->
                    <div class="relative">
                        <input
                            type="date"
                            v-model="selectedDate"
                            class="appearance-none w-full py-2 pl-3 pr-10 text-sm bg-white rounded-lg ring-1 ring-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        />
                        <button 
                            v-if="selectedDate"
                            @click="selectedDate = ''"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Grid Layout -->
            <div class="grid grid-cols-4 gap-6">
                <!-- Left Side: Schedule Cards -->
                <div class="col-span-3 space-y-8">
                    <template v-for="(sportGroup, sportName) in groupedBySport" :key="sportName">
                        <!-- Sport and Category Header -->
                        <div class="mb-4">
                            <template v-for="(categoryGroup, category) in sportGroup" :key="category">
                                <div 
                                    :id="'section-' + sportName.toLowerCase().replace(/\s+/g, '-') + '-' + category.toLowerCase()"
                                    class="scroll-mt-32"
                                >
                                    <h3 class="text-lg font-semibold text-gray-700 mt-4 mb-2">
                                        {{ sportName }} {{ category }} Schedules
                                    </h3>
                                    <!-- Regular Matches -->
                                    <div v-if="categoryGroup.matches?.length" class="grid grid-cols-1 gap-4 mb-4">
                                        <div v-for="(match, index) in categoryGroup.matches" :key="match.id" 
                                            class="bg-white p-4 rounded-lg ring-1 ring-gray-200 hover:ring-2 hover:ring-blue-300 transition-all duration-200">
                                            <div class="flex flex-col space-y-2">
                                                <div class="flex justify-between items-center">
                                                    <span class="font-medium text-gray-900">Game {{ index + 1 }}</span>
                                                    <div class="text-sm text-gray-600">
                                                        {{ formatDate(match.date) }} at {{ formatTime(match.time) }}
                                                    </div>
                                                </div>
                                                <div class="flex justify-between items-center text-sm">
                                                    <span class="text-gray-800">{{ match.matchVenue?.name || 'TBA' }}</span>
                                                    <span v-if="match.status === 'completed'" class="text-green-600 font-medium">
                                                        Completed
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Variations -->
                                    <div v-if="categoryGroup.variations?.length" class="grid grid-cols-1 gap-4 mb-4">
                                        <div v-for="(variation, index) in categoryGroup.variations" :key="variation.id" 
                                            class="bg-white p-4 rounded-lg ring-1 ring-gray-200 hover:ring-2 hover:ring-blue-300 transition-all duration-200">
                                            <div class="flex flex-col space-y-2">
                                                <div class="flex justify-between items-center">
                                                    <span class="font-medium text-gray-900">{{ variation.sport_variation_name }}</span>
                                                    <div class="text-sm text-gray-600">
                                                        {{ formatDate(variation.date) }} at {{ formatTime(variation.time) }}
                                                    </div>
                                                </div>
                                                <div class="flex justify-between items-center text-sm">
                                                    <span class="text-gray-800">{{ variation.venue?.name || 'TBA' }}</span>
                                                    <span v-if="variation.status === 'completed'" class="text-green-600 font-medium">
                                                        Completed
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- No matches message -->
                    <div v-if="!Object.keys(groupedBySport).length" class="text-center py-12">
                        <div class="text-gray-400">
                            <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No Matches Found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Try adjusting your filters or check back later
                        </p>
                    </div>
                </div>

                <!-- Right Side: Quick Navigation -->
                <div class="col-span-1">
                    <div class="sticky top-24 px-4 bg-white">
                        <h3 class="text-md font-semibold text-gray-700 mt-4 mb-4">Quick Navigation</h3>
                        <nav class="">
                            <template v-for="(sportGroup, sportName) in groupedBySport" :key="sportName">
                                <template v-for="(categoryGroup, category) in sportGroup" :key="category">
                                    <a 
                                        :href="'#section-' + sportName.toLowerCase().replace(/\s+/g, '-') + '-' + category.toLowerCase()"
                                        class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-md transition-colors duration-200"
                                        @click.prevent="scrollToSection(sportName, category)"
                                    >
                                        {{ sportName }} {{ category }}
                                    </a>
                                </template>
                            </template>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <TransitionRoot appear :show="showPreviewModal" as="template">
        <Dialog as="div" @close="closePreviewModal" class="relative z-50">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" />
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
                        <DialogPanel class="w-full max-w-4xl transform overflow-hidden rounded-lg bg-white shadow-xl transition-all">
                            <div class="p-6">
                                <!-- Fixed Header -->
                                <div class="flex justify-between items-center pb-4 border-b">
                                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                                        Schedule Preview
                                    </DialogTitle>
                                    <div class="flex gap-2">
                                        <button
                                            @click="downloadScheduleAsPDF"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-200"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download PDF
                                        </button>
                                        <button
                                            @click="closePreviewModal"
                                            class="text-gray-500 bg-white font-medium px-4 py-2 text-sm rounded-lg border hover:bg-gray-50"
                                        >
                                            Close
                                        </button>
                                    </div>
                                </div>

                                <!-- Scrollable Content -->
                                <div class="mt-4 max-h-[70vh] overflow-y-auto">
                                    <div class="preview-content space-y-6">
                                        <h2 class="text-xl font-bold text-center mb-6">Palakasan Sports Schedule</h2>
                                        
                                        <template v-for="(sportGroup, sportName) in groupedBySport" :key="sportName">
                                            <div v-for="(categoryGroup, category) in sportGroup" :key="category" class="mb-8">
                                                <h3 class="text-lg font-semibold mb-3">{{ sportName }} {{ category }} Schedules</h3>
                                                <div class="space-y-2 pl-4">
                                                    <!-- Regular Matches -->
                                                    <template v-for="(match, index) in categoryGroup.matches" :key="match.id">
                                                        <div class="text-gray-700">
                                                            - Game {{ index + 1 }}: {{ formatDate(match.date) }} / {{ formatTime(match.time) }} / {{ match.matchVenue?.name || 'TBA' }}
                                                        </div>
                                                    </template>
                                                    
                                                    <!-- Sport Variations -->
                                                    <template v-for="variation in categoryGroup.variations" :key="variation.id">
                                                        <div class="text-gray-700">
                                                            - {{ variation.sport_variation_name }}: {{ formatDate(variation.date) }} / {{ formatTime(variation.time) }} / {{ variation.venue?.name || 'TBA' }}
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue'
import { jsPDF } from 'jspdf'

const props = defineProps({
    latestPalakasan: {
        type: Object,
        default: () => ({
            sportMatches: []
        })
    },
    activeSports: {
        type: Array,
        default: () => []
    },
    activeTeams: {
        type: Array,
        default: () => []
    },
    palakasanYear: {
        type: [String, Number],
        default: new Date().getFullYear()
    },
    admin: {
        type: Array,
        default: () => []
    }
});

const selectedSport = ref('')
const selectedCategory = ref('')
const selectedTeam = ref('')
const activeTab = ref('all')
const showPreviewModal = ref(false)
const selectedDate = ref('');


// Computed property for match counts
const matchCounts = computed(() => {
    const matches = props.latestPalakasan?.sportMatches || []
    const variations = props.latestPalakasan?.sportVariations || []
    
    const pendingMatches = matches.filter(m => !m.match_result).length + 
                          variations.filter(v => v.status !== 'completed').length || 0
    const completedMatches = matches.filter(m => m.match_result).length + 
                           variations.filter(v => v.status === 'completed').length || 0
    
    return {
        all: matches.length + variations.length || 0,
        pending: pendingMatches,
        completed: completedMatches
    }
})

const scrollToSection = (sportName, category) => {
    const sectionId = 'section-' + sportName.toLowerCase().replace(/\s+/g, '-') + '-' + category.toLowerCase();
    console.log('Scrolling to section:', sectionId); // Debug log
    const element = document.getElementById(sectionId);
    
    if (element) {
        console.log('Element found:', element); // Debug log
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        console.log('Element not found for ID:', sectionId); // Debug log
    }
};

// Computed property for available teams
const availableTeams = computed(() => {
    const teams = new Set()
    const matches = props.latestPalakasan?.sportMatches || []
    matches.forEach(match => {
        if (match.teamA?.college?.name) teams.add(match.teamA.college.name)
        if (match.teamB?.college?.name) teams.add(match.teamB.college.name)
    })
    return Array.from(teams)
})


const filteredMatches = computed(() => {
    let matches = props.latestPalakasan?.sportMatches || [];

    // Existing filters
    if (activeTab.value !== 'all') {
        matches = matches.filter(match => {
            if (activeTab.value === 'pending') return !match.match_result;
            if (activeTab.value === 'completed') return match.match_result;
            return true;
        });
    }

    if (selectedSport.value) {
        matches = matches.filter(match => match.assignedSport?.sport?.name === selectedSport.value);
    }

    if (selectedCategory.value) {
        matches = matches.filter(match => match.category === selectedCategory.value);
    }

    // New date filter
    if (selectedDate.value) {
        const selectedDateStr = new Date(selectedDate.value).toISOString().split('T')[0];
        matches = matches.filter(match => {
            if (!match.date) return false;
            const matchDate = new Date(match.date).toISOString().split('T')[0];
            return matchDate === selectedDateStr;
        });
    }

    return matches;
});

// filteredVariations computed property
const filteredVariations = computed(() => {
    let variations = props.latestPalakasan?.sportVariations || [];

    // Existing filters
    if (activeTab.value !== 'all') {
        variations = variations.filter(variation => {
            if (activeTab.value === 'pending') return variation.status !== 'completed';
            if (activeTab.value === 'completed') return variation.status === 'completed';
            return true;
        });
    }

    if (selectedSport.value) {
        variations = variations.filter(variation => variation.sport_id?.sport?.name === selectedSport.value);
    }

    if (selectedCategory.value) {
        variations = variations.filter(variation => variation.sport_id?.categories === selectedCategory.value);
    }

    // New date filter
    if (selectedDate.value) {
        const selectedDateStr = new Date(selectedDate.value).toISOString().split('T')[0];
        variations = variations.filter(variation => {
            if (!variation.date) return false;
            const variationDate = new Date(variation.date).toISOString().split('T')[0];
            return variationDate === selectedDateStr;
        });
    }

    return variations;
});

// Computed property for grouped matches by sport and category
const groupedBySport = computed(() => {
    const grouped = {}
    
    // Group regular matches
    filteredMatches.value.forEach(match => {
        const sportName = match.assignedSport?.sport?.name || 'Uncategorized'
        const category = match.category || 'Uncategorized'
        
        if (!grouped[sportName]) {
            grouped[sportName] = {}
        }
        if (!grouped[sportName][category]) {
            grouped[sportName][category] = {
                matches: [],
                variations: []
            }
        }
        
        grouped[sportName][category].matches.push(match)
    })

    // Group variations
    filteredVariations.value.forEach(variation => {
        const sportName = variation.sport_id?.sport?.name || 'Uncategorized'
        const category = variation.sport_id?.categories || 'Uncategorized'
        
        if (!grouped[sportName]) {
            grouped[sportName] = {}
        }
        if (!grouped[sportName][category]) {
            grouped[sportName][category] = {
                matches: [],
                variations: []
            }
        }
        
        grouped[sportName][category].variations.push(variation)
    })
    
    // Sort matches and variations within each category by date
    Object.keys(grouped).forEach(sport => {
        Object.keys(grouped[sport]).forEach(category => {
            grouped[sport][category].matches.sort((a, b) => {
                if (!a.date) return 1
                if (!b.date) return -1
                return new Date(a.date) - new Date(b.date)
            })
            grouped[sport][category].variations.sort((a, b) => {
                if (!a.date) return 1
                if (!b.date) return -1
                return new Date(a.date) - new Date(b.date)
            })
        })
    })
    
    return grouped
})

// Helper function for tab classes
const getTabClass = (tab) => {
    const isActive = activeTab.value === tab
    return {
        'cursor-pointer px-1 py-4 text-sm font-medium': true,
        'text-blue-600 border-b-2 border-blue-600': isActive,
        'text-gray-500 hover:text-gray-700 hover:border-gray-300': !isActive
    }
}

// Helper function to format dates
const formatDate = (dateString) => {
    if (!dateString) return 'TBA'
    try {
        const date = new Date(dateString)
        if (isNaN(date.getTime())) return 'TBA'
        return new Intl.DateTimeFormat('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        }).format(date)
    } catch (error) {
        return 'TBA'
    }
}

// Helper function to format times
const formatTime = (timeString) => {
    if (!timeString) return ''
    try {
        const [hours, minutes] = timeString.split(':')
        const date = new Date()
        date.setHours(parseInt(hours), parseInt(minutes))
        return new Intl.DateTimeFormat('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        }).format(date)
    } catch (error) {
        return ''
    }
}

// Download schedule as PDF
const downloadScheduleAsPDF = () => {
    const doc = new jsPDF();
    let yPos = 20;
    const lineHeight = 10;
    const margin = 20;
    
    // Add title
    doc.setFontSize(16);
    doc.text('Palakasan Sports Schedule', margin, yPos);
    yPos += lineHeight * 2;
    
    // Iterate through sports and categories
    Object.entries(groupedBySport.value).forEach(([sportName, categories]) => {
        Object.entries(categories).forEach(([category, data]) => {
            // Add sport and category header
            doc.setFontSize(14);
            doc.text(`${sportName} ${category} Schedules`, margin, yPos);
            yPos += lineHeight * 1.5;
            
            // Add matches
            doc.setFontSize(12);
            data.matches.forEach((match, index) => {
                const gameText = `Game ${index + 1}: ${formatDate(match.date)} / ${formatTime(match.time)} / ${match.matchVenue?.name || 'TBA'}`;
                
                // Check if we need a new page
                if (yPos > 270) {
                    doc.addPage();
                    yPos = 20;
                }
                
                doc.text(`- ${gameText}`, margin, yPos);
                yPos += lineHeight;
            });
            
            // Add variations if any
            data.variations.forEach((variation, index) => {
                const variationText = `${variation.sport_variation_name}: ${formatDate(variation.date)} / ${formatTime(variation.time)} / ${variation.venue?.name || 'TBA'}`;
                
                if (yPos > 270) {
                    doc.addPage();
                    yPos = 20;
                }
                
                doc.text(`- ${variationText}`, margin, yPos);
                yPos += lineHeight;
            });
            
            // Add spacing between categories
            yPos += lineHeight;
            
            // Add new page if near bottom
            if (yPos > 250) {
                doc.addPage();
                yPos = 20;
            }
        });
    });
    
    // Save the PDF
    doc.save('sports-schedule.pdf');
};

// Preview modal function
const openPreviewModal = () => {
    showPreviewModal.value = true;
}

const closePreviewModal = () => {
    showPreviewModal.value = false;
}

//

</script>

<style>
/* Print styles */
@media print {
    /* Hide non-printable elements */
    nav,
    button,
    select,
    .sticky,
    .hover\:ring-2,
    .hover\:ring-blue-300 {
        display: none !important;
    }

    /* Reset layout for printing */
    .grid {
        display: block !important;
    }
    .col-span-3 {
        width: 100% !important;
    }

    /* Ensure white background and black text */
    * {
        background-color: white !important;
        color: black !important;
        border-color: #ddd !important;
    }

    /* Add page breaks between sports */
    .mb-4 {
        page-break-inside: avoid;
        margin-bottom: 2rem !important;
    }

    /* Adjust spacing */
    .space-y-8 > * + * {
        margin-top: 2rem !important;
    }

    .gap-4 {
        gap: 1rem !important;
    }

    /* Format headers */
    h3 {
        font-size: 1.25rem !important;
        margin-top: 1.5rem !important;
        margin-bottom: 1rem !important;
        border-bottom: 1px solid #ddd !important;
        padding-bottom: 0.5rem !important;
    }

    /* Format cards */
    .rounded-lg {
        border: 1px solid #ddd !important;
        padding: 1rem !important;
        margin-bottom: 0.5rem !important;
    }

    /* Adjust text sizes */
    .text-sm {
        font-size: 0.875rem !important;
    }

    /* Add print header */
    .pb-4::before {
        content: 'Sports Schedule' !important;
        display: block !important;
        font-size: 1.5rem !important;
        font-weight: bold !important;
        text-align: center !important;
        margin-bottom: 2rem !important;
        padding-bottom: 1rem !important;
        border-bottom: 2px solid #ddd !important;
    }

    /* Format status badges */
    .text-green-600 {
        font-weight: bold !important;
    }

    /* Ensure proper margins */
    @page {
        margin: 2cm;
    }
}

/* Preview Modal Styles */
.preview-content {
    font-family: Arial, sans-serif;
}

.preview-content h2 {
    color: #1a1a1a;
}

.preview-content h3 {
    color: #2c5282;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 0.5rem;
}

.preview-content .text-gray-700 {
    line-height: 1.6;
}
</style>