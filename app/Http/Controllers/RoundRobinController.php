<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\MatchResult;
use App\Models\Palakasan;
use App\Models\Points;
use App\Models\SportMatch;
use App\Models\StudentAccount;
use App\Models\Venue;
use App\Models\TeamStanding;
use App\Models\UsedVenueRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RoundRobinController extends Controller
{
    public function index(AssignedSports $assignedSports)
    {
        $venues = Venue::all();
        $allMatches = SportMatch::all();
        $assignedSports->load('sport');
        $tournaments = Palakasan::all();
        $latestPalakasan = Palakasan::latest()->first();
        $teams = AssignedTeams::where('palakasan_id', $assignedSports->palakasan_sport_id)->get();
        $matches = SportMatch::where('assigned_sport_id', $assignedSports->id)->get();
        $results = MatchResult::whereIn('sport_match_id', $matches->pluck('id'))->get();
        $standings = TeamStanding::where('assigned_sport_id', $assignedSports->id)
                                 ->get();
        $venueRecords = UsedVenueRecord::where('palakasan_id', $latestPalakasan->id)->get();


        return Inertia::render('SSCAdmin/MatchSetup/RoundRobin', [
            'sport' => $assignedSports,
            'tournaments' => $tournaments,
            'teams' => $teams,
            'matches' => $matches,
            'results' => $results,
            'venues' => $venues,
            'standings' => $standings,
            'allMatches' => $allMatches,
            'venueRecords' => $venueRecords

        ]);
    }   

    public function facilitatorIndex(AssignedSports $assignedSports, StudentAccount $facilitator)
    {
        $venues = Venue::all();
        $allMatches = SportMatch::all();
        $assignedSports->load('sport');
        $tournaments = Palakasan::all();
        $latestPalakasan = Palakasan::latest()->first();
        $teams = AssignedTeams::where('palakasan_id', $assignedSports->palakasan_sport_id)->get();
        $matches = SportMatch::where('assigned_sport_id', $assignedSports->id)->get();
        $results = MatchResult::whereIn('sport_match_id', $matches->pluck('id'))->get();
        $standings = TeamStanding::where('assigned_sport_id', $assignedSports->id)
                                 ->get();
        $venueRecords = UsedVenueRecord::where('palakasan_id', $latestPalakasan->id)->get();

        $majorPoints = Points::where('type', 'Major')->get();
        $minorPoints = Points::where('type', 'Minor')->get();


        return Inertia::render('Facilitator/SportSetup/FacilitatorRoundRobin', [
            'sport' => $assignedSports,
            'tournaments' => $tournaments,
            'teams' => $teams,
            'matches' => $matches,
            'results' => $results,
            'venues' => $venues,
            'standings' => $standings,
            'allMatches' => $allMatches,
            'venueRecords' => $venueRecords,
            'facilitator' => $facilitator,
            'majorPoints' => $majorPoints,
            'minorPoints' => $minorPoints

        ]);
    }  

    public function store_round_robin(Request $request)
    {
        try {
            $matches = [];
            $matchesData = $request->all();
    
            DB::beginTransaction();
    
            foreach ($matchesData as $match) {
                $validatedMatch = $this->validateMatch($match);
    
                $existingMatch = SportMatch::where([
                    ['assigned_sport_id', $match['assigned_sport_id']],
                    ['teamA_id', $match['teamA_id']],
                    ['teamB_id', $match['teamB_id']],
                ])->first();
    
                if (!$existingMatch) {
                    $createdMatch = SportMatch::create([
                        'assigned_sport_id' => $match['assigned_sport_id'],
                        'teamA_id' => $match['teamA_id'],
                        'teamB_id' => $match['teamB_id'],
                        'round' => $match['round'],
                        'game' => $match['game'],
                        'bracket_type' => 'round_robin',
                        'elimination_type' => 'round_robin',
                        'status' => 'pending',
                        'match_venue_id' => $match['match_venue_id'],
                        'date' => $match['date'] ?? null,
                        'time' => $match['time'] ?? null
                    ]);
    
                    $matches[] = $createdMatch;
                }
            }
    
            // Initialize standings for all teams
            $teams = AssignedTeams::where('palakasan_id', $matchesData[0]['assigned_sport_id'])->get();
            foreach ($teams as $team) {
                TeamStanding::updateOrCreate(
                    ['assigned_sport_id' => $matchesData[0]['assigned_sport_id'], 'team_id' => $team->id],
                    [
                        'played' => 0,
                        'won' => 0,
                        'drawn' => 0,
                        'lost' => 0,
                    ]
                );
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Round-robin matches created successfully',
                'matches' => $matches
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'error' => 'Failed to create round-robin matches: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function validateMatch($match)
    {
        if (!is_array($match)) {
            throw new \InvalidArgumentException('Match data must be an array');
        }

        $rules = [
            'assigned_sport_id' => 'required|exists:assigned_sports,id',
            'teamA_id' => 'required|exists:assigned_teams,id',
            'teamB_id' => 'required|exists:assigned_teams,id|different:teamA_id',
            'round' => 'required|integer|min:1',
            'game' => 'required|string',
            'match_venue_id' => 'required|exists:venues,id',
            'date' => 'nullable|date_format:Y-m-d',
            'time' => 'nullable'
        ];

        return validator($match, $rules)->validate();
    }

    public function updateDateTime(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'matchId' => 'required|exists:sport_matches,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            DB::beginTransaction();
    
            // Find the match and eager load necessary relationships
            $match = SportMatch::with(['assignedSport'])->findOrFail($request->matchId);
            
            if (!$match->assignedSport) {
                throw new \Exception('Associated sport not found');
            }
    
            // 1. UPDATE the match schedule
            $match->update([
                'date' => $request->date,
                'time' => $request->time,
            ]);
    
            // 2. CREATE a new venue usage record
            // Always create a new record, not update
            $venueRecord = new UsedVenueRecord([
                'palakasan_id' => $match->assignedSport->palakasan_sport_id,
                'venue_id' => $match->match_venue_id,
                'date' => $request->date,
                'time' => $request->time
            ]);
            $venueRecord->save();
    
            DB::commit();
    
            return redirect()->back()->with('message', 'Match schedule and venue record updated successfully');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update match schedule: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function storeResult(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sport_match_id' => 'required|exists:sport_matches,id',
            'teamA_score' => 'required|integer|min:0',
            'teamB_score' => 'required|integer|min:0',
            'winning_team_id' => 'required|exists:assigned_teams,id',
            'losing_team_id' => 'required|exists:assigned_teams,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $match = SportMatch::findOrFail($request->sport_match_id);
            
            $result = MatchResult::updateOrCreate(
                ['sport_match_id' => $request->sport_match_id],
                [
                    'teamA_score' => $request->teamA_score,
                    'teamB_score' => $request->teamB_score,
                    'winning_team_id' => $request->winning_team_id,
                    'losing_team_id' => $request->losing_team_id,
                ]
            );

            $match->update(['status' => 'completed']);

            // Update standings
            $this->updateStandings($match, $request->teamA_score, $request->teamB_score, $request->winning_team_id);

            // Check for ties and create tie-breaker matches if necessary
            $this->checkForTiesAndCreateMatches($match->assigned_sport_id);

            DB::commit();

            return redirect()->back()->with('message', 'Match result saved successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save match result: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function updateStandings($match, $teamAScore, $teamBScore, $winningTeamId)
    {
        $teamAStanding = TeamStanding::firstOrCreate(
            ['assigned_sport_id' => $match->assigned_sport_id, 'team_id' => $match->teamA_id],
            ['played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'points' => 0]
        );
        $teamBStanding = TeamStanding::firstOrCreate(
            ['assigned_sport_id' => $match->assigned_sport_id, 'team_id' => $match->teamB_id],
            ['played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'points' => 0]
        );
    
        // Update played games
        $teamAStanding->played++;
        $teamBStanding->played++;
    
        // Update wins, draws, and losses
        if ($teamAScore == $teamBScore) {
            $teamAStanding->drawn++;
            $teamBStanding->drawn++;
            $teamAStanding->points += 0.5;
            $teamBStanding->points += 0.5;
        } elseif ($winningTeamId == $match->teamA_id) {
            $teamAStanding->won++;
            $teamBStanding->lost++;
            $teamAStanding->points += 1;
        } else {
            $teamBStanding->won++;
            $teamAStanding->lost++;
            $teamBStanding->points += 1;
        }
    
        $teamAStanding->save();
        $teamBStanding->save();
    }
    
    protected function checkForTiesAndCreateMatches($assignedSportId)
    {
        // Get all matches for this sport
        $matches = SportMatch::where('assigned_sport_id', $assignedSportId)->get();
    
        // Check if all matches have been played
        $allMatchesPlayed = $matches->every(function ($match) {
            return $match->status === 'completed';
        });
    
        if (!$allMatchesPlayed) {
            return; // Don't create tie-breakers if not all matches are played
        }
    
        // Get the standings
        $standings = TeamStanding::where('assigned_sport_id', $assignedSportId)
            ->orderBy('points', 'desc')
            ->orderBy('won', 'desc')
            ->take(2)
            ->get();
    
        // Check if the top two teams are tied
        if ($standings->count() == 2 && $standings[0]->points == $standings[1]->points) {
            // Get the most recently used venue or a default venue
            $lastMatch = SportMatch::where('assigned_sport_id', $assignedSportId)
                ->whereNotNull('match_venue_id')
                ->latest()
                ->first();
    
            $venueId = $lastMatch ? $lastMatch->match_venue_id : Venue::first()->id;
    
            // Create a tie-breaker match between the top two teams
            SportMatch::create([
                'assigned_sport_id' => $assignedSportId,
                'teamA_id' => $standings[0]->team_id,
                'teamB_id' => $standings[1]->team_id,
                'round' => 'Tie Breaker',
                'game' => 'Tie Breaker Final',
                'bracket_type' => 'tie_breaker',
                'elimination_type' => 'tie_breaker',
                'status' => 'pending',
                'match_venue_id' => $venueId,
                'date' => null,
                'time' => null
            ]);
    
            // You might want to notify the admin or update the UI to show that a tie-breaker match has been created
        }
    }
}