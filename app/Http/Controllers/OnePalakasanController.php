<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Inertia\Inertia;

class OnePalakasanController extends Controller
{
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

        return Inertia::render('SSCAdmin/Onepalakasan', [
            'colleges' => $colleges,
            'sports' => $sports,
            'palakasans' => $palakasans,
            'assignedSports' => $assignedSports,
            'assignedTeams' => $assignedTeams,
            'latestPalakasan' => $latestPalakasan, // Pass the latest Palakasan (could be null) to the view
            'overallResult' => $overallResult,
            'variationResult' => $variationResult,
            'facilitator' => $facilitator,
            'matchResults' => $matchResults,
            'matchRankings' => $matchRankings,
            'facilitatorSubmits' => $facilitatorSubmits,
            'facilitatorRankSubmits' => $facilitatorRankSubmits
        ]);
    }

    public function store_palakasan(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'theme' => 'required|string|max:255',
            'tagline' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required',
        ]);
        
        // Create a new Palakasan record
        Palakasan::create([
            'year' => date('Y'), // Set the year to the current year
            'theme' => $validated['theme'],
            'tagline' => $validated['tagline'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
        ]);
        
        // Redirect or return a response
        return redirect('/details')->with('success', 'Palakasan created successfully.');
    }

    public function store_sports(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'palakasan_sport_id' => 'required|exists:palakasans,id',
            'sport_id' => 'required|exists:sports,id',
            'categories' => 'required|string|in:Men,Women,Mixed',
            'setup' => 'required',
            'type' => 'required',
            'facilitator_id' => 'required|exists:student_accounts,id',
            'status' => 'required',

        ]);
    
        // Store the validated data in the database
        $assignedSport = AssignedSports::create([
            'palakasan_sport_id' => $validated['palakasan_sport_id'],
            'sport_id' => $validated['sport_id'],
            'categories' => $validated['categories'],
            'setup' => $validated['setup'],
            'type' => $validated['type'],
            'facilitator_id' => $validated['facilitator_id'],
            'status' => $validated['status'],

        ]);
        
        // Fetch the teams assigned to this Palakasan
        $assignedTeams = AssignedTeams::where('palakasan_id', $validated['palakasan_sport_id'])->get();

        // Check if there are at least two teams
        if ($assignedTeams->count() < 4) {
            return redirect()->back()->with('error', 'Not enough teams to create matchups.');
        }
        return redirect()->back()->with('success', 'Sport created and teams matched successfully.');
    }

    public function store_teams(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'assigned_team_name' => 'required|string|max:255',
            'college_id' => 'required|exists:colleges,id',
            'palakasan_id' => 'required|exists:palakasans,id',
        ], [
            'assigned_team_name.required' => 'The team name is required.',
            'college_id.exists' => 'The selected college does not exist.',
            'palakasan_id.exists' => 'The selected Palakasan does not exist.',
        ]);

        // Create a new assigned team
        AssignedTeams::create($validated);

        // Return a success response or redirect
        return redirect()->back()->with('success', 'Sport created and teams matched successfully.');
    }

    public function updatePalakasanStatus(Request $request, $id)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'status' => 'required|in:live,completed',
            ]);
    
            // Find the Palakasan record
            $palakasan = Palakasan::findOrFail($id);
            
            // Update the status
            $palakasan->update([
                'status' => $validated['status']
            ]);

            // If palakasan is set to live, update all pending sports to ongoing
            if ($validated['status'] === 'live') {
                AssignedSports::where('palakasan_sport_id', $id)
                    ->where('status', 'pending')
                    ->update(['status' => 'ongoing']);
            }
    
            $statusMessage = $validated['status'] === 'live' ? 'live' : 'completed';
            return redirect()->back()->with('success', "Palakasan status updated to {$statusMessage} successfully.");
 
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Palakasan status: ' . $e->getMessage());
        }
    }

    public function deleteSports(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'sportIds' => 'required|array',
                'sportIds.*' => 'exists:assigned_sports,id'
            ]);

            // Delete the selected sports
            AssignedSports::whereIn('id', $request->sportIds)->delete();

            return redirect()->back()->with('success', 'Sports deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting sports: ' . $e->getMessage());
        }
    }
}
