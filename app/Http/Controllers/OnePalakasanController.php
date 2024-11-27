<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\College;
use App\Models\FacilitatorRankSubmitions;
use App\Models\FacilitatorSubmits;
use App\Models\FfoFacilitatorSubmits;
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
use Illuminate\Support\Facades\DB;

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

        $ffofacilitatorSubmits = FfoFacilitatorSubmits::join('sports_variations', 'ffo_facilitator_submits.match_id', '=', 'sports_variations.id')
                ->join('assigned_sports', 'sports_variations.assigned_sport_id', '=', 'assigned_sports.id')
                ->with([
                    'facilitator.student:id,first_name,last_name',
                    'match:id,sport_variation_name,assigned_sport_id',
                    'match.sport_id:id,sport_id,categories',
                    'match.sport_id.sport:id,name'
                ])
                ->where('assigned_sports.palakasan_sport_id', $latestPalakasan->id)
                ->select('ffo_facilitator_submits.*')
                ->orderBy('ffo_facilitator_submits.created_at', 'desc')
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
            'facilitatorRankSubmits' => $facilitatorRankSubmits,
            'ffofacilitatorSubmits' => $ffofacilitatorSubmits
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
            \Log::info('Updating Palakasan status', [
                'palakasan_id' => $id,
                'status' => $request->input('status')
            ]);

            DB::beginTransaction();

            // Update Palakasan status
            $palakasan = Palakasan::findOrFail($id);
            $palakasan->update(['status' => $request->input('status')]);

            // If Palakasan is being set to live, update all its sports to live as well
            if ($request->input('status') === 'live') {
                $updated = AssignedSports::where('palakasan_sport_id', $id)
                    ->update(['status' => 'live']);
                
                \Log::info('Updated all sports statuses', [
                    'palakasan_id' => $id,
                    'sports_updated' => $updated
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Palakasan and sports updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update Palakasan status', [
                'palakasan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
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

    public function updateSportStatus(Request $request, $id)
    {
        try {
            \Log::info('Updating sport status', [
                'sport_id' => $id,
                'status' => $request->input('status')
            ]);

            $assignedSport = AssignedSports::findOrFail($id);
            $oldStatus = $assignedSport->status;
            
            $updated = $assignedSport->update([
                'status' => $request->input('status')
            ]);

            \Log::info('Sport status update result', [
                'sport_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $assignedSport->fresh()->status,
                'update_success' => $updated
            ]);

            if (!$updated) {
                throw new \Exception('Failed to update sport status');
            }

            return redirect()->back()->with('success', 'Sport status updated successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to update sport status', [
                'sport_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to update sport status: ' . $e->getMessage());
        }
    }
}
