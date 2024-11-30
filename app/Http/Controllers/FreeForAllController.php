<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\College;
use App\Models\FfoFacilitatorSubmits;
use App\Models\Palakasan;
use App\Models\Points;
use App\Models\SportMatch;
use App\Models\SportsVariations;
use App\Models\SportsVariationsMatches;
use App\Models\StudentPlayer;
use App\Models\StudentAccount;
use App\Models\UsedVenueRecord;
use App\Models\Venue;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FreeForAllController extends Controller
{
    public function index(AssignedSports $assignedSports)
    {

            $points = Points::all();
            $colleges = College::all();
            $venues = Venue::all();
            $assignedSports->load('sport');
            $tournaments = Palakasan::all();
            $teams = AssignedTeams::where('palakasan_id', $assignedSports->palakasan_sport_id)->get();
            $allMatches = SportMatch::all();
            $sportVariations = SportsVariations::where('assigned_sport_id', $assignedSports->id)
                ->get();
            $sportVariationMatches = SportsVariationsMatches::all();
            $latestPalakasan = Palakasan::latest()->first();
            $venueRecords = UsedVenueRecord::where('palakasan_id', $latestPalakasan->id)->get();
            $players = StudentPlayer::with(['student', 'assignedTeam'])
            ->where('student_assigned_sport_id', $assignedSports->id)
            ->get();

    
            return Inertia::render('SSCAdmin/MatchSetup/FreeForAll', [
                'sport' => $assignedSports,
                'tournaments' => $tournaments,
                'teams' => $teams,
                'venues' => $venues,
                'allMatches' => $allMatches,
                'sportVariations' => $sportVariations,
                'colleges' => $colleges,
                'sportVariationMatches' => $sportVariationMatches,
                'points' => $points,
                'venueRecords' => $venueRecords,
                'players' => $players,
                'latestPalakasan' => $latestPalakasan,

            ]);

    }

    public function facilitatorIndex(AssignedSports $assignedSports, $encryptedFacilitatorId)
    {
        try {
            $facilitatorId = Crypt::decryptString($encryptedFacilitatorId);
            $facilitator = StudentAccount::with('student')->findOrFail($facilitatorId);

            $points = Points::all();
            $colleges = College::all();
            $venues = Venue::all();
            $assignedSports->load('sport');
            $tournaments = Palakasan::all();
            $teams = AssignedTeams::where('palakasan_id', $assignedSports->palakasan_sport_id)->get();
            $allMatches = SportMatch::all();
            $sportVariations = SportsVariations::where('assigned_sport_id', $assignedSports->id)
                ->get();
            $sportVariationMatches = SportsVariationsMatches::all();
            $latestPalakasan = Palakasan::latest()->first();
            $venueRecords = UsedVenueRecord::where('palakasan_id', $latestPalakasan->id)->get();
            $majorPoints = Points::where('type', 'Major')->get();
            $minorPoints = Points::where('type', 'Minor')->get();
            $latestPalakasan = Palakasan::latest()->first();
            $players = StudentPlayer::with(['student', 'assignedTeam'])
            ->where('student_assigned_sport_id', $assignedSports->id)
            ->get();

    
            return Inertia::render('Facilitator/SportSetup/FacilitatorFreeForAll', [
                'sport' => $assignedSports,
                'tournaments' => $tournaments,
                'teams' => $teams,
                'venues' => $venues,
                'allMatches' => $allMatches,
                'sportVariations' => $sportVariations,
                'colleges' => $colleges,
                'sportVariationMatches' => $sportVariationMatches,
                'points' => $points,
                'venueRecords' => $venueRecords,
                'facilitator' => $facilitator,
                'majorPoints' => $majorPoints,
                'minorPoints' => $minorPoints,
                'players' => $players,
                'latestPalakasan' => $latestPalakasan,

            ]);

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred while decrypting the facilitator ID.');
        }
    }

    public function homeIndex(AssignedSports $assignedSports)
    {

            $points = Points::all();
            $colleges = College::all();
            $venues = Venue::all();
            $assignedSports->load('sport');
            $tournaments = Palakasan::all();
            $teams = AssignedTeams::where('palakasan_id', $assignedSports->palakasan_sport_id)->get();
            $allMatches = SportMatch::all();
            $sportVariations = SportsVariations::where('assigned_sport_id', $assignedSports->id)
                ->get();
            $sportVariationMatches = SportsVariationsMatches::all();
            $latestPalakasan = Palakasan::latest()->first();
            $venueRecords = UsedVenueRecord::where('palakasan_id', $latestPalakasan->id)->get();
            $players = StudentPlayer::with(['student', 'assignedTeam'])
            ->where('student_assigned_sport_id', $assignedSports->id)
            ->get();

    
            return Inertia::render('SportsLandingPage/SportSetup/SportFreeForAll', [
                'sport' => $assignedSports,
                'tournaments' => $tournaments,
                'teams' => $teams,
                'venues' => $venues,
                'allMatches' => $allMatches,
                'sportVariations' => $sportVariations,
                'colleges' => $colleges,
                'sportVariationMatches' => $sportVariationMatches,
                'points' => $points,
                'venueRecords' => $venueRecords,
                'players' => $players,
                'latestPalakasan' => $latestPalakasan,

            ]);

    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'assigned_sport_id' => 'required|exists:assigned_sports,id',
                'sport_variation_name' => 'required|string|max:255',
                'sport_variation_venue_id' => 'required|exists:venues,id',
                'date' => 'nullable|date',
                'time' => 'nullable|string',
                'status' => 'required|in:pending,completed',
            ]);
    
            DB::beginTransaction();

            // Get the assigned sport to get the palakasan_sport_id
            $assignedSport = AssignedSports::findOrFail($validated['assigned_sport_id']);
    
            $sportVariation = SportsVariations::create([
                'assigned_sport_id' => $validated['assigned_sport_id'],
                'sport_variation_name' => $validated['sport_variation_name'],
                'sport_variation_venue_id' => $validated['sport_variation_venue_id'],
                'date' => $validated['date'],
                'time' => $validated['time'],
                'status' => $validated['status'],
            ]);
    
            // Only get teams for the current Palakasan event
            $teams = AssignedTeams::where('palakasan_id', $assignedSport->palakasan_sport_id)
                ->pluck('id')
                ->toArray();

            foreach ($teams as $teamId) {
                SportsVariationsMatches::create([
                    'sport_variation_id' => $sportVariation->id,
                    'sport_variation_team_id' => $teamId,
                    'rank' => null,
                    'points' => null,
                ]);
            }
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Sport variation and teams generated successfully');

        } catch (QueryException $e) {
            DB::rollBack();
            $errorMessage = 'Database error occurred while creating sport variation.';
            if ($e->getCode() === '23000') {
                $errorMessage = 'A sport variation with this name already exists.';
            }
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An unexpected error occurred while creating the sport variation.')
                ->withInput();
        }
    }

    public function generateTeams(Request $request)
    {
        try {
            $validated = $request->validate([
                'sport_variation_id' => 'required|exists:sports_variations,id',
                'teams' => 'required|array',
                'teams.*' => 'exists:assigned_teams,id',
            ]);

            DB::beginTransaction();

            $sportVariation = SportsVariations::findOrFail($validated['sport_variation_id']);
            $sportVariationId = $validated['sport_variation_id'];

            // Delete existing teams for this sport variation
            SportsVariationsMatches::where('sport_variation_id', $sportVariationId)->delete();

            // Only create entries for the teams that were specifically selected
            foreach ($validated['teams'] as $teamId) {
                // Verify that this team belongs to the correct Palakasan event
                $team = AssignedTeams::where('id', $teamId)
                    ->where('palakasan_id', $sportVariation->assignedSport->palakasan_sport_id)
                    ->first();

                if ($team) {
                    SportsVariationsMatches::create([
                        'sport_variation_id' => $sportVariationId,
                        'sport_variation_team_id' => $teamId,
                        'rank' => null,
                        'points' => null,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Teams generated and saved successfully');

        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Database error occurred while generating teams.')->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An unexpected error occurred while generating teams.')->withInput();
        }
    }

    public function updateTime(Request $request, SportsVariations $sportVariation)
    {
        try {
            $validated = $request->validate([
                'sport_variation_venue_id' => 'required|exists:venues,id',
                'date' => 'required|date|after_or_equal:today',
                'time' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) use ($request, $sportVariation) {
                        // Check for scheduling conflicts
                        $conflictingMatches = SportsVariations::where('id', '!=', $sportVariation->id)
                            ->where('sport_variation_venue_id', $request->sport_variation_venue_id)
                            ->where('date', $request->date)
                            ->where('time', $value)
                            ->exists();

                        if ($conflictingMatches) {
                            $fail('This time slot is already booked for the selected venue and date.');
                        }
                    },
                ],
            ]);

            DB::beginTransaction();

            $latestPalakasan = Palakasan::latest()->first();

            // Update the sport variation
            $sportVariation->update([
                'sport_variation_venue_id' => $validated['sport_variation_venue_id'],
                'date' => $validated['date'],
                'time' => $validated['time'],
            ]);

            // Create a new record in the usedvenuerecords table
            UsedVenueRecord::create([
                'palakasan_id' => $latestPalakasan->id,
                'venue_id' => $validated['sport_variation_venue_id'],
                'date' => $validated['date'],
                'time' => $validated['time'],
 
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Schedule updated successfully and venue record created');

        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Database error occurred while updating the schedule.')
                ->withInput();

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An unexpected error occurred while updating the schedule.')
                ->withInput();
        }
    }

    
    public function updateRanks(Request $request, SportsVariations $sportVariation)
    {
        try {
            Log::info('Rank update request received', ['request' => $request->all()]);
            
            $validated = $request->validate([
                'matches' => 'required|array',
                'matches.*.id' => 'required|exists:sports_variations_matches,id',
                'matches.*.rank' => 'required|integer|min:1',
                'matches.*.points' => 'required|integer|min:0',
                'signature' => 'required|string',
                'official_name' => 'required|string',
                'facilitator_id' => 'required|exists:student_accounts,id'
            ]);

            DB::beginTransaction();

            try {
                // Create facilitator submit record
                $submitRecord = FfoFacilitatorSubmits::create([
                    'facilitator_id' => $validated['facilitator_id'],
                    'match_id' => $sportVariation->id,
                    'official_name' => $validated['official_name'],
                    'signature' => $validated['signature']
                ]);

                // Update match ranks and points
                foreach ($validated['matches'] as $match) {
                    $updateResult = SportsVariationsMatches::where('id', $match['id'])
                        ->update([
                            'rank' => $match['rank'],
                            'points' => $match['points']
                        ]);
                    
                    Log::info('Updated match ranking', [
                        'match_id' => $match['id'],
                        'rank' => $match['rank'],
                        'points' => $match['points'],
                        'update_result' => $updateResult
                    ]);
                }

                // Update sport variation status to completed
                $sportVariation->update(['status' => 'completed']);

                DB::commit();
                return redirect()->back()->with('message', 'Rank submitted successfully');

            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Error in database transaction', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (ValidationException $e) {
            Log::warning('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()->with('message', 'Error');
        } catch (Exception $e) {
            Log::error('Unexpected error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('message', 'An error occurred while updating ranks');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $assignedSport = AssignedSports::findOrFail($id);
        $assignedSport->update(['status' => $request->input('status')]);
        
        return redirect()->back()->with('message', 'Status updated successfully');
    }

}