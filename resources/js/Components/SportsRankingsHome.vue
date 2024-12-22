<template>
    <div class="">
        <div>
            <Graph
                :teams="assignedTeams"
                :overallResult="overallResult"
                :variationResult="variationResult"
            />
        </div>

        <!-- Sports Filter -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-4">
                <div class="relative">
                    <select v-model="selectedSport" class="appearance-none w-full py-2 pl-3 pr-10 text-sm bg-white rounded-lg ring-1 ring-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Sports</option>
                        <option v-for="sportName in availableSports" :key="sportName" :value="sportName">
                            {{ sportName }}
                        </option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
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
            </div>
        </div>

        <!-- Rankings Grid -->
        <div class="grid grid-cols-1 gap-6 mb-6">
            <template v-for="(sport) in filteredSports" :key="sport.id">
                <div v-for="category in getSportCategories(sport)" :key="category" class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-4 sm:p-6">
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-2">{{ sport.sport?.name }} {{ category }} <span class="font-normal text-gray-500">( {{ sport.setup }} )</span> </h3>

                    <!-- Regular Matches Rankings -->
                    <div v-if="getSportRankings(sport.id, category).length > 0" class="mb-6">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Rank</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Team</th>
                                    <th class="text-right py-3 px-4 text-sm font-medium text-gray-600">Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(team, index) in getSportRankings(sport.id, category)" 
                                    :key="team.teamId"
                                    class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-sm font-medium text-gray-600">{{ index + 1 }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-800">{{ getTeamName(team.teamId).name }}</span>
                                            <span class="text-xs text-gray-600 sm:block hidden">{{ getTeamName(team.teamId).college }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-sm font-medium text-gray-600 text-right">{{ team.points }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- No Rankings Message -->
                    <div v-if="!getSportRankings(sport.id, category).length && !getSportVariations(sport.id, category).length" 
                         class="text-center py-8 bg-gray-50 rounded-lg">
                        <p class="text-gray-500">No rankings available for this sport</p>
                    </div>
                </div>
            </template>
        </div>

        <div v-for="(variation, index) in filteredAndCompletedVariations" :key="index" class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-4 sm:p-6 mb-6">
            <div class="flex flex-col sm:flex-row mb-4  sm:gap-2">
                <h4 class="text-sm sm:text-lg font-semibold">{{ getSportName(variation.assigned_sport_id) || 'Unknown Sport' }} {{ variation.sport_id?.categories }} <span class="font-normal text-gray-500">({{ variation.sport_id?.setup }})</span></h4>
                <h4 class="text-sm sm:text-lg font-normal sm:font-semibold">{{ variation.sport_variation_name }} </h4>
            </div>
            <!-- Rankings Section -->
            <div>
                <div v-if="getVariationRankings(variation.id).length > 0">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Rank</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Team</th>
                                <th class="text-right py-3 px-4 text-sm font-medium text-gray-600">Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(team, rankIndex) in getVariationRankings(variation.id)" 
                                :key="rankIndex"
                                class="hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm font-medium text-gray-600">{{ rankIndex + 1 }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-800">{{ getTeamName(team.teamId).name }}</span>
                                        <span class="text-xs text-gray-600 hidden sm:block">{{ getTeamName(team.teamId).college }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm font-medium text-gray-600 text-right">{{ team.points }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p v-else class="text-gray-500">No rankings available</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue';
import Graph from '@/Components/BarGraphV2.vue';  // Adjust the path as necessary
import jsPDF from 'jspdf';

const props = defineProps({
    assignedSports: {
        type: Array,
        required: true
    },
    assignedTeams: {
        type: Array,
        required: true
    },
    sportMatches: {
        type: Array,
        required: true
    },
    sportsVariations: {
        type: Array,
        required: true
    },
    latestPalakasan: {
        type: Object,
        required: true
    },
    overallResult: {
        type: Array,
        required: true
    },
    variationResult: {  
        type: Array,
        required: true
    }
});


const selectedSport = ref('');
const selectedCategory = ref('');

// Modal state
const showPreviewModal = ref(false);

const openPreviewModal = () => {
    showPreviewModal.value = true;
};

const closePreviewModal = () => {
    showPreviewModal.value = false;
};

// Filter sports based on search and completion status
const filteredSports = computed(() => {
    let sports = props.assignedSports;

    // Filter by selected sport
    if (selectedSport.value) {
        sports = sports.filter(s => s.sport?.name === selectedSport.value);
    }

    // Filter by selected category
    if (selectedCategory.value) {
        sports = sports.filter(s => {
            const categories = getSportCategories(s);
            return categories.includes(selectedCategory.value);
        });
    }

    // Filter out free-for-all sports
    sports = sports.filter(sport => sport.setup !== 'Free for All' && sport.status === 'completed');

    return sports;
});

// Filter variations based on selected filters
const filteredVariations = computed(() => {
    let variations = props.sportsVariations;

    // Filter by selected sport
    if (selectedSport.value) {
        variations = variations.filter(v => {
            const sportName = getSportName(v.assigned_sport_id);
            return sportName === selectedSport.value;
        });
    }

    // Filter by selected category
    if (selectedCategory.value) {
        variations = variations.filter(v => v.sport_id?.categories === selectedCategory.value);
    }

    return variations;
});

const filteredAndCompletedVariations = computed(() => {
    console.log('assignedSports:', props.assignedSports);
    console.log('filteredVariations:', filteredVariations.value);
    
    if (!props.assignedSports || !filteredVariations.value) return [];
    
    return filteredVariations.value.filter(variation => {
        if (!variation.assigned_sport_id) {
            console.log('Variation missing assigned_sport_id:', variation);
            return false;
        }
        const sport = props.assignedSports.find(s => s.id === variation.assigned_sport_id);
        console.log('Found sport for variation:', sport);
        console.log('Sport status:', sport?.status);
        return sport?.status === 'completed';
    });
});

// Get all available sports for the filter dropdown
const availableSports = computed(() => {
    const sportNames = new Set();
    
    // Add regular sports
    props.assignedSports.forEach(sport => {
        if (sport.sport?.name) {
            sportNames.add(sport.sport.name);
        }
    });
    
    // Add sports from variations
    props.sportsVariations.forEach(variation => {
        const sportName = getSportName(variation.assigned_sport_id);
        if (sportName && sportName !== 'Unknown Sport') {
            sportNames.add(sportName);
        }
    });
    
    return Array.from(sportNames).sort();
});

// Get categories for a sport
const getSportCategories = (sport) => {
    return sport.categories ? [sport.categories] : ['Men', 'Women', 'Mixed'];
};

// Get matches for a specific sport and category
const getSportMatches = (sportId, category) => {
    return props.sportMatches.filter(match => 
        match.sport_id?.id === sportId && 
        match.sport_id?.categories === category
    );
};

// Get variations for a specific sport and category
const getSportVariations = (sportId, category) => {
    return props.sportsVariations.filter(variation => 
        variation.assigned_sport_id === sportId && 
        variation.categories === category
    );
};

// Get rankings from overall results
const getSportRankings = (sportId, category) => {
    return props.overallResult
        .filter(result => result.assigned_sport_id === sportId)
        .map(result => ({
            teamId: result.assigned_team_id,
            rank: result.rank,
            points: result.points
        }))
        .sort((a, b) => a.rank - b.rank);
};

// Get rankings for variations
const getVariationRankings = (variationId) => {
    const rankings = props.variationResult
        .filter(result => {
            // Check both possible property names and log the entire result object
            return (result.sport_variation_id === variationId) || 
                   (result.sports_variation_id === variationId) || 
                   (result.variation_id === variationId);
        })
        .map(result => ({
            teamId: result.assigned_team_id || result.team_id || result.sport_variation_team_id,
            rank: result.rank || 0,
            points: result.points || 0
        }))
        .sort((a, b) => a.rank - b.rank);
    return rankings;
};

// Get team name and college from ID
const getTeamName = (teamId) => {
    const team = props.assignedTeams.find(team => team.id === teamId);
    if (!team) return { name: 'Unknown Team', college: '' };
    
    // Parse the college object if it's a string
    let collegeObj = team.college;
    if (typeof team.college === 'string') {
        try {
            collegeObj = JSON.parse(team.college);
        } catch (e) {
            collegeObj = { name: team.college };
        }
    }
    
    return {
        name: team.assigned_team_name,
        college: collegeObj.name || collegeObj.shortName || 'Unknown College'
    };
};

// Get sport name from ID
const getSportName = (sportId) => {
    const sport = props.assignedSports.find(sport => sport.id === sportId);
    return sport.sport?.name || 'Unknown Sport';
};

// Download PDF function
const downloadRankingsAsPDF = () => {
    const doc = new jsPDF();
    let yPos = 20;
    const lineHeight = 10;
    const margin = 20;
    const pageWidth = doc.internal.pageSize.width;
    
    // Add title
    doc.setFontSize(16);
    doc.text('Sports Rankings', margin, yPos);
    yPos += lineHeight * 2;
    
    // Table configuration
    const columns = ['Rank', 'Team', 'Points'];
    const columnWidths = [20, 120, 30];  // Widths for each column
    
    // Function to draw table header
    const drawTableHeader = (startY) => {
        let xPos = margin;
        doc.setFillColor(245, 245, 245); // Light gray background
        doc.setTextColor(0); // Black text
        doc.setFontSize(12);
        
        // Draw header background
        doc.rect(xPos, startY - 7, columnWidths.reduce((a, b) => a + b, 0), 10, 'F');
        
        // Draw header text
        columns.forEach((col, index) => {
            doc.text(col, xPos + 2, startY);
            xPos += columnWidths[index];
        });
        return startY + lineHeight;
    };
    
    // Add regular rankings
    filteredSports.value.forEach(sport => {
        getSportCategories(sport).forEach(category => {
            const rankings = getSportRankings(sport.id, category);
            if (rankings.length > 0) {
                // Check if we need a new page
                if (yPos > 250) {
                    doc.addPage();
                    yPos = 20;
                }
                
                // Add sport and category header
                doc.setFontSize(14);
                doc.text(`${sport.sport?.name} ${category}`, margin, yPos);
                yPos += lineHeight * 1.5;
                
                // Draw table header
                yPos = drawTableHeader(yPos);
                
                // Add rankings in table format
                doc.setFontSize(11);
                rankings.forEach((team, index) => {
                    // Check if we need a new page
                    if (yPos > 270) {
                        doc.addPage();
                        yPos = 20;
                        yPos = drawTableHeader(yPos);
                    }
                    
                    const teamName = getTeamName(team.teamId);
                    let xPos = margin;
                    
                    // Draw row background (alternating colors)
                    if (index % 2 === 0) {
                        doc.setFillColor(252, 252, 252);
                        doc.rect(xPos, yPos - 7, columnWidths.reduce((a, b) => a + b, 0), 10, 'F');
                    }
                    
                    // Add row data
                    doc.setTextColor(0); // Ensure text is black
                    doc.text(`${index + 1}`, xPos + 2, yPos);
                    doc.text(`${teamName.name} (${teamName.college})`, xPos + columnWidths[0] + 2, yPos);
                    doc.text(`${team.points}`, xPos + columnWidths[0] + columnWidths[1] + 2, yPos);
                    
                    yPos += lineHeight;
                });
                
                yPos += lineHeight * 1.5;
            }
        });
    });
    
    // Add variations rankings with the same table format
    filteredAndCompletedVariations.value.forEach(variation => {
        const rankings = getVariationRankings(variation.id);
        if (rankings.length > 0) {
            // Check if we need a new page
            if (yPos > 250) {
                doc.addPage();
                yPos = 20;
            }
            
            // Add variation header
            doc.setFontSize(14);
            const header = `${getSportName(variation.assigned_sport_id)} ${variation.sport_id?.categories} - ${variation.sport_variation_name}`;
            doc.text(header, margin, yPos);
            yPos += lineHeight * 1.5;
            
            // Draw table header
            yPos = drawTableHeader(yPos);
            
            // Add rankings in table format
            doc.setFontSize(11);
            rankings.forEach((team, index) => {
                if (yPos > 270) {
                    doc.addPage();
                    yPos = 20;
                    yPos = drawTableHeader(yPos);
                }
                
                const teamName = getTeamName(team.teamId);
                let xPos = margin;
                
                // Draw row background (alternating colors)
                if (index % 2 === 0) {
                    doc.setFillColor(252, 252, 252);
                    doc.rect(xPos, yPos - 7, columnWidths.reduce((a, b) => a + b, 0), 10, 'F');
                }
                
                // Add row data
                doc.setTextColor(0); // Ensure text is black
                doc.text(`${index + 1}`, xPos + 2, yPos);
                doc.text(`${teamName.name} (${teamName.college})`, xPos + columnWidths[0] + 2, yPos);
                doc.text(`${team.points}`, xPos + columnWidths[0] + columnWidths[1] + 2, yPos);
                
                yPos += lineHeight;
            });
            
            yPos += lineHeight * 1.5;
        }
    });
    
    // Save the PDF
    doc.save('sports-rankings.pdf');
};

//
// Add this computed property after your existing computed properties
const topThreeTeams = computed(() => {
    if (!props.assignedTeams || !props.overallResult) return [];

    const pointsMap = new Map();

    // Sum up points from overall results
    props.overallResult.forEach(result => {
        if (result.assigned_team_id && result.total_points) {
            const currentPoints = pointsMap.get(result.assigned_team_id) || 0;
            pointsMap.set(result.assigned_team_id, currentPoints + result.total_points);
        }
    });

    // Add points from variation results if they exist
    if (props.variationResult) {
        props.variationResult.forEach(result => {
            if (result.assigned_team_id && result.total_points) {
                const currentPoints = pointsMap.get(result.assigned_team_id) || 0;
                pointsMap.set(result.assigned_team_id, currentPoints + result.total_points);
            }
        });
    }

    // Map and sort teams
    return props.assignedTeams
        .map(team => ({
            ...team,
            total_points: pointsMap.get(team.id) || 0
        }))
        .sort((a, b) => b.total_points - a.total_points)
        .slice(0, 3);
});
</script>

<style>
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

/* Print styles */
@media print {
    .print\:hidden {
        display: none !important;
    }
}
</style>
