<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\Palakasan;
use App\Models\StudentAccount;
use App\Models\Venue;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FacilitatorController extends Controller
{   
    public function index($id)
    {
        $facilitator = StudentAccount::with('student')->findOrFail($id);
        
        $latestPalakasan = Palakasan::latest()->first();
        
        if (!$latestPalakasan) {
            // Handle the case where no Palakasan event exists
            return Inertia::render('Facilitator/FacilitatorDashboard', [
                'facilitator' => $facilitator,
                'sports' => [],
                'message' => 'No Palakasan event found.'
            ]);
        }

        $sports = AssignedSports::with('sport')
            ->where('facilitator_id', $id)
            ->where('palakasan_sport_id', $latestPalakasan->id)
            ->get();

        return Inertia::render('Facilitator/FacilitatorDashboard', [
            'facilitator' => $facilitator,
            'sports' => $sports,
            'latestPalakasan' => $latestPalakasan
        ]);
    }
}