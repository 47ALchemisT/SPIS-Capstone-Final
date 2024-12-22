<template>
    <div class="">
        <div v-if="groupedPlayers.length === 0" class="text-center py-4 text-gray-600">
            No players found.
        </div>
        <div v-else class="space-y-6">
            <div v-for="group in groupedPlayers" :key="group.teamId" class="border rounded-lg p-4">
                <h3 class="text-lg flex font-semibold mb-3 p-2 rounded">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512"><path fill="currentColor" d="m256 23.57l-16.1 48.86h32.2zM73 90.43v15.97h366V90.43zm48 33.97V479l135-105l135 105V124.4zm87 37h96l-32 80l80-32v96l-80-32l32 80h-96l32-80l-80 32v-96l80 32zm48 235.4l-23 17.9v73.7h46v-73.7z"/></svg>
                    {{ group.teamName || 'Unassigned Team' }}
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-gray-700 font-normal">
                                <th class="py-2 px-4 text-left whitespace-nowrap">Player Name</th>
                                <th class="py-2 px-4 text-left whitespace-nowrap">Student ID</th>
                                <th class="py-2 px-4 text-left whitespace-nowrap">College</th>
                                <th class="py-2 px-4 text-left whitespace-nowrap">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="player in group.players" :key="player.id" class="hover:bg-gray-50">
                                <td class="py-2 px-4 text-sm text-gray-800 font-semibold whitespace-nowrap">
                                    {{ player.student.first_name }} {{ player.student.last_name }}
                                </td>
                                <td class="py-2 px-4 text-sm text-gray-600 whitespace-nowrap">{{ player.student.id_number }}</td>
                                <td class="py-2 px-4 text-sm text-gray-600 whitespace-nowrap">{{ player.student.college }}</td>
                                <td class="py-2 px-4 text-sm text-gray-600 whitespace-nowrap">{{ player.student.univ_email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    players: {
        type: Array,
        required: true,
        default: () => []
    }
});

const groupedPlayers = computed(() => {
    const groups = props.players.reduce((acc, player) => {
        const teamId = player.assigned_team_id || 'unassigned';
        const teamName = player.assigned_team?.assigned_team_name;
        if (!acc[teamId]) {
            acc[teamId] = {
                players: [],
                teamName: teamName
            };
        }
        acc[teamId].players.push(player);
        return acc;
    }, {});

    return Object.entries(groups).map(([teamId, data]) => ({
        teamId: teamId === 'unassigned' ? null : Number(teamId),
        teamName: data.teamName,
        players: data.players
    })).sort((a, b) => {
        if (a.teamId === null) return 1;
        if (b.teamId === null) return -1;
        return (a.teamName || '').localeCompare(b.teamName || '');
    });
});
</script>
