<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\Palakasan;
use App\Models\OverallResult;
use App\Models\SportsVariationsMatches;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PalakasanHistoryController extends Controller
{
    public function index()
    {
        $palakasans = Palakasan::all();

        return Inertia::render('SSCAdmin/PalakasanHistoryView', [
            'palakasans' => $palakasans,
        ]);
    }

    public function show(Palakasan $palakasan)
    {
        $assignedSports = AssignedSports::with('sport')->where('palakasan_sport_id', $palakasan->id)->get();
        $assignedTeams = AssignedTeams::with('college')->where('palakasan_id', $palakasan->id)->get();
        
        // Get overall results for this palakasan
        $overallResult = OverallResult::whereHas('assignedSportID', function($query) use ($palakasan) {
            $query->where('palakasan_sport_id', $palakasan->id);
        })->get();

        // Get variation results for this palakasan
        $variationResult = SportsVariationsMatches::whereHas('sportVariationID.sport_id', function($query) use ($palakasan) {
            $query->where('palakasan_sport_id', $palakasan->id);
        })->get();

        return Inertia::render('SSCAdmin/PalakasanHistoryView', [
            'palakasan' => $palakasan,
            'assignedSports' => $assignedSports,
            'assignedTeams' => $assignedTeams,
            'overallResult' => $overallResult,
            'variationResult' => $variationResult
        ]);
    }
}