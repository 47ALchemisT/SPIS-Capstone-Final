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
        $palakasans = Palakasan::where('status', 'completed')->latest()->get();
        $latestPalakasan = Palakasan::latest()->first();

        return Inertia::render('SportsLandingPage/History', [
            'palakasans' => $palakasans,
            'latestPalakasan' => $latestPalakasan
        ]);
    }

    public function show(Palakasan $palakasan)
    {
        $latestPalakasan = Palakasan::latest()->first();
        $assignedSports = AssignedSports::with('sport')->where('palakasan_sport_id', $palakasan->id)->get();
        $assignedTeams = AssignedTeams::with('college')->where('palakasan_id', $palakasan->id)->get();
        
        // Get overall results for this palakasan
        $overallResult = OverallResult::with(['assignedSportID', 'assignedTeamID'])
            ->whereHas('assignedSportID', function($query) use ($palakasan) {
                $query->where('palakasan_sport_id', $palakasan->id);
            })->get();

        // Get variation results for this palakasan
        $variationResult = SportsVariationsMatches::with(['sportVariationID', 'assignedTeamVariationID'])
            ->whereHas('sportVariationID.assignedSport', function($query) use ($palakasan) {
                $query->where('palakasan_sport_id', $palakasan->id);
            })->get();

        // Calculate total points per team
        $teamPoints = [];
        
        // Initialize team points array with team data
        foreach ($assignedTeams as $team) {
            $teamPoints[$team->id] = [
                'assigned_team_name' => $team->name,
                'college' => $team->college,
                'totalPoints' => 0
            ];
        }

        // Add points from overall results
        foreach ($overallResult as $result) {
            if (isset($teamPoints[$result->assigned_team_id])) {
                $points = (int)$result->points;
                $teamPoints[$result->assigned_team_id]['totalPoints'] += $points;
            }
        }

        // Add points from variation results
        foreach ($variationResult as $result) {
            if (isset($teamPoints[$result->sport_variation_team_id])) {
                $points = (int)$result->points;
                $teamPoints[$result->sport_variation_team_id]['totalPoints'] += $points;
            }
        }

        // Find the team with highest points
        $overallWinner = null;
        $maxPoints = -1;

        foreach ($teamPoints as $teamId => $teamData) {
            if ($teamData['totalPoints'] > $maxPoints) {
                $maxPoints = $teamData['totalPoints'];
                $overallWinner = (object)[
                    'assigned_team_name' => $teamData['assigned_team_name'],
                    'college' => $teamData['college'],
                    'totalPoints' => $teamData['totalPoints']
                ];
            }
        }

        return Inertia::render('SSCAdmin/PalakasanHistoryView', [
            'palakasan' => $palakasan,
            'assignedSports' => $assignedSports,
            'assignedTeams' => $assignedTeams,
            'overallResult' => $overallResult,
            'variationResult' => $variationResult,
            'latestPalakasan' => $latestPalakasan,
            'overallWinner' => $overallWinner
        ]);
    }
    public function homeShow(Palakasan $palakasan)
    {
        $latestPalakasan = Palakasan::latest()->first();
        $assignedSports = AssignedSports::with('sport')->where('palakasan_sport_id', $palakasan->id)->get();
        $assignedTeams = AssignedTeams::with('college')->where('palakasan_id', $palakasan->id)->get();
        
        // Get overall results for this palakasan
        $overallResult = OverallResult::with(['assignedSportID', 'assignedTeamID'])
            ->whereHas('assignedSportID', function($query) use ($palakasan) {
                $query->where('palakasan_sport_id', $palakasan->id);
            })->get();

        // Get variation results for this palakasan
        $variationResult = SportsVariationsMatches::with(['sportVariationID', 'assignedTeamVariationID'])
            ->whereHas('sportVariationID.assignedSport', function($query) use ($palakasan) {
                $query->where('palakasan_sport_id', $palakasan->id);
            })->get();

        // Calculate total points per team
        $teamPoints = [];
        
        // Initialize team points array with team data
        foreach ($assignedTeams as $team) {
            $teamPoints[$team->id] = [
                'assigned_team_name' => $team->name,
                'college' => $team->college,
                'totalPoints' => 0
            ];
        }

        // Add points from overall results
        foreach ($overallResult as $result) {
            if (isset($teamPoints[$result->assigned_team_id])) {
                $points = (int)$result->points;
                $teamPoints[$result->assigned_team_id]['totalPoints'] += $points;
            }
        }

        // Add points from variation results
        foreach ($variationResult as $result) {
            if (isset($teamPoints[$result->sport_variation_team_id])) {
                $points = (int)$result->points;
                $teamPoints[$result->sport_variation_team_id]['totalPoints'] += $points;
            }
        }

        // Find the team with highest points
        $overallWinner = null;
        $maxPoints = -1;

        foreach ($teamPoints as $teamId => $teamData) {
            if ($teamData['totalPoints'] > $maxPoints) {
                $maxPoints = $teamData['totalPoints'];
                $overallWinner = (object)[
                    'assigned_team_name' => $teamData['assigned_team_name'],
                    'college' => $teamData['college'],
                    'totalPoints' => $teamData['totalPoints']
                ];
            }
        }

        return Inertia::render('SportsLandingPage/HistoryView', [
            'palakasan' => $palakasan,
            'assignedSports' => $assignedSports,
            'assignedTeams' => $assignedTeams,
            'overallResult' => $overallResult,
            'variationResult' => $variationResult,
            'latestPalakasan' => $latestPalakasan,
            'overallWinner' => $overallWinner
        ]);
    }
}