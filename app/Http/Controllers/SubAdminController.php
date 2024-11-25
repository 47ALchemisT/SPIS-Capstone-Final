<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\College;
use App\Models\FacilitatorRankSubmitions;
use App\Models\FacilitatorSubmits;
use App\Models\MatchResult;
use App\Models\OverallResult;
use App\Models\Palakasan;
use App\Models\Sport;
use App\Models\SportMatch; 
use App\Models\SportsVariationsMatches;
use App\Models\StudentAccount;
use Dotenv\Exception\ValidationException;
use Inertia\Inertia;

class SubAdminController extends Controller
{
    public function index()
    {   
        $sports = Sport::all();
        $colleges = College::all();
        $overallResult = OverallResult::all();
        $variationResult = SportsVariationsMatches::all();

        // Fetch the latest Palakasan
        $latestPalakasan = Palakasan::latest()->first();        

        // Initialize empty collections for assigned sports and teams
        $assignedSports = collect();
        $assignedTeams = collect();

        // If a Palakasan exists, fetch the assigned sports and teams
        if ($latestPalakasan) {
            $assignedSports = AssignedSports::with('sport')
                ->where('palakasan_sport_id', $latestPalakasan->id)
                ->get();
            $assignedTeams = AssignedTeams::with('college')
                ->where('palakasan_id', $latestPalakasan->id)
                ->get();
        }

        $facilitator = StudentAccount::with('student')->where('role', 'Facilitator')->get();  
        $palakasans = Palakasan::all();
        $matchResults = MatchResult::with(['sportMatch.assignedSport', 'winning_team', 'losing_team'])
            ->whereHas('sportMatch.assignedSport', function($query) use ($latestPalakasan) {
                $query->where('palakasan_sport_id', $latestPalakasan->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $matchRankings = OverallResult::with(['assignedSportID', 'assignedTeamID'])
            ->whereHas('assignedSportID', function($query) use ($latestPalakasan) {
                $query->where('palakasan_sport_id', $latestPalakasan->id);
            })
            ->orderBy('updated_at', 'desc')
            ->get();
        
        $facilitatorRankSubmits = FacilitatorRankSubmitions::with([
                'overallResult.assignedSportID',
                'overallResult.assignedSportID.sport:id,name',
                'overallResult.assignedTeamID',
                'overallResult.assignedTeamID.college:id,name',
                'facilitator.student:id,first_name,last_name'
            ])
            ->whereHas('overallResult.assignedSportID', function($query) use ($latestPalakasan) {
                $query->where('palakasan_sport_id', $latestPalakasan->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        $facilitatorSubmits = FacilitatorSubmits::with([
                'matchResult.sportMatch.assignedSport.sport', 
                'matchResult.winning_team.college', 
                'matchResult.losing_team.college',
                'facilitator.student'
            ])
            ->whereHas('matchResult.sportMatch.assignedSport', function($query) use ($latestPalakasan) {
                $query->where('palakasan_sport_id', $latestPalakasan->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('SubAdmin/SubAdminDash', [
            'sports' => $assignedSports,
            'facilitator' => $facilitator,
            'matchResults' => $matchResults,
        ]);
    }

    public function details()
    {   
        $sports = Sport::all();
        $colleges = College::all();
        $overallResult = OverallResult::all();
        $variationResult = SportsVariationsMatches::all();

        // Fetch the latest Palakasan
        $latestPalakasan = Palakasan::latest()->first();        

        // Initialize empty collections for assigned sports and teams
        $assignedSports = collect();
        $assignedTeams = collect();

        // If a Palakasan exists, fetch the assigned sports and teams
        if ($latestPalakasan) {
            $assignedSports = AssignedSports::with('sport')->where('palakasan_sport_id', $latestPalakasan->id)->get();
            $assignedTeams = AssignedTeams::with('college')->where('palakasan_id', $latestPalakasan->id)->get();
        }

        $facilitator = StudentAccount::with('student')->where('role', 'Facilitator')->get();  
        $palakasans = Palakasan::all();
        $matchResults = MatchResult::with(['sportMatch.assignedSport', 'winning_team', 'losing_team'])
            ->whereHas('sportMatch.assignedSport', function($query) use ($latestPalakasan) {
                $query->where('palakasan_sport_id', $latestPalakasan->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $matchRankings = OverallResult::with(['assignedSportID', 'assignedTeamID'])
            ->whereHas('assignedSportID', function($query) use ($latestPalakasan) {
                $query->where('palakasan_sport_id', $latestPalakasan->id);
            })
            ->orderBy('updated_at', 'desc')
            ->get();
        
        $facilitatorRankSubmits = FacilitatorRankSubmitions::with([
                'overallResult.assignedSportID',
                'overallResult.assignedSportID.sport:id,name',
                'overallResult.assignedTeamID',
                'overallResult.assignedTeamID.college:id,name',
                'facilitator.student:id,first_name,last_name'
            ])
                ->whereHas('overallResult.assignedSportID', function($query) use ($latestPalakasan) {
                    $query->where('palakasan_sport_id', $latestPalakasan->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

        \Log::info('Debugging Sport Data:', [
            'first_submit_id' => $facilitatorRankSubmits->first()?->id,
            'overall_result' => $facilitatorRankSubmits->first()?->overallResult,
            'assigned_sport' => $facilitatorRankSubmits->first()?->overallResult?->assignedSportID,
            'sport_details' => $facilitatorRankSubmits->first()?->overallResult?->assignedSportID?->sport,
        ]);
            
        $facilitatorSubmits = FacilitatorSubmits::with([
                'matchResult.sportMatch.assignedSport.sport', 
                'matchResult.winning_team.college', 
                'matchResult.losing_team.college',
                'facilitator.student'
            ])
                ->whereHas('matchResult.sportMatch.assignedSport', function($query) use ($latestPalakasan) {
                    $query->where('palakasan_sport_id', $latestPalakasan->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

        return Inertia::render('SubAdmin/SubAdminDash', [
            'sports' => $assignedSports,
            'facilitator' => $facilitator,
            'matchResults' => $matchResults,
        ]);
    }

}
