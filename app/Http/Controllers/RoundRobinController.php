<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\MatchResult;
use App\Models\Palakasan;
use App\Models\Points;
use App\Models\SportMatch;
use App\Models\StudentAccount;
use App\Models\StudentPlayer;
use App\Models\Venue;
use App\Models\TeamStanding;
use App\Models\UsedVenueRecord;
use App\Models\FacilitatorSubmits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;


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
        $players = StudentPlayer::with(['student', 'assignedTeam'])
        ->where('student_assigned_sport_id', $assignedSports->id)
        ->get();


        return Inertia::render('SSCAdmin/MatchSetup/RoundRobin', [
            'sport' => $assignedSports,
            'tournaments' => $tournaments,
            'teams' => $teams,
            'matches' => $matches,
            'results' => $results,
            'venues' => $venues,
            'standings' => $standings,
            'allMatches' => $allMatches,
            'venueRecords' => $venueRecords,
            'players' => $players

        ]);
    }   

    public function subIndex(AssignedSports $assignedSports)
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
        $players = StudentPlayer::with(['student', 'assignedTeam'])
        ->where('student_assigned_sport_id', $assignedSports->id)
        ->get();


        return Inertia::render('SubAdmin/SportSetup/SARoundRobin', [
            'sport' => $assignedSports,
            'tournaments' => $tournaments,
            'teams' => $teams,
            'matches' => $matches,
            'results' => $results,
            'venues' => $venues,
            'standings' => $standings,
            'allMatches' => $allMatches,
            'venueRecords' => $venueRecords,
            'players' => $players,
            'user' => auth()->user()

        ]);
    }   

    public function facilitatorIndex(AssignedSports $assignedSports, $encryptedFacilitatorId)
    {
        $facilitatorId = Crypt::decryptString($encryptedFacilitatorId);
        $facilitator = StudentAccount::with('student')->findOrFail($facilitatorId);

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
        $players = StudentPlayer::with(['student', 'assignedTeam'])
        ->where('student_assigned_sport_id', $assignedSports->id)
        ->get();


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
            'minorPoints' => $minorPoints,
            'players' => $players

        ]);
    }  

    public function homeIndex(AssignedSports $assignedSports)
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
        $players = StudentPlayer::with(['student', 'assignedTeam'])
        ->where('student_assigned_sport_id', $assignedSports->id)
        ->get();


        return Inertia::render('SportsLandingPage/SportSetup/SportRoundRobin', [
            'sport' => $assignedSports,
            'tournaments' => $tournaments,
            'teams' => $teams,
            'matches' => $matches,
            'results' => $results,
            'venues' => $venues,
            'standings' => $standings,
            'allMatches' => $allMatches,
            'venueRecords' => $venueRecords,
            'players' => $players,
            'latestPalakasan' => $latestPalakasan

        ]);
    }   

    public function historyIndex(AssignedSports $assignedSports)
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
        $players = StudentPlayer::with(['student', 'assignedTeam'])
        ->where('student_assigned_sport_id', $assignedSports->id)
        ->get();


        return Inertia::render('SSCAdmin/HistoryMatches/RoundRobin', [
            'sport' => $assignedSports,
            'tournaments' => $tournaments,
            'teams' => $teams,
            'matches' => $matches,
            'results' => $results,
            'venues' => $venues,
            'standings' => $standings,
            'allMatches' => $allMatches,
            'venueRecords' => $venueRecords,
            'players' => $players

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
    
            return redirect()->back()->with('message', 'Round-robin matches created successfully');

    
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
            'venue_id' => 'required|exists:venues,id', // Add venue validation
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
    
            // 1. UPDATE the match schedule and venue
            $match->update([
                'date' => $request->date,
                'time' => $request->time,
                'match_venue_id' => $request->venue_id // Add venue update
            ]);
    
            // 2. CREATE a new venue usage record
            $venueRecord = new UsedVenueRecord([
                'palakasan_id' => $match->assignedSport->palakasan_sport_id,
                'venue_id' => $request->venue_id, // Use the new venue ID
                'date' => $request->date,
                'time' => $request->time
            ]);
            $venueRecord->save();
    
            DB::commit();
    
            return redirect()->back()->with('message', 'Match schedule, venue, and venue record updated successfully');
    
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
        $baseRules = [
            'sport_match_id' => 'required|exists:sport_matches,id',
            'teamA_score' => 'required|integer|min:0',
            'teamB_score' => 'required|integer|min:0',
            'is_draw' => 'required|boolean',
            'official_name' => 'required|string',
            'signature' => 'required|string'
        ];

        // Add winning/losing team validation only if it's not a draw
        if (!$request->is_draw) {
            $baseRules['winning_team_id'] = 'required|exists:assigned_teams,id';
            $baseRules['losing_team_id'] = 'required|exists:assigned_teams,id|different:winning_team_id';
        }

        $validator = Validator::make($request->all(), $baseRules);

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
            
            $resultData = [
                'teamA_score' => $request->teamA_score,
                'teamB_score' => $request->teamB_score,
                'is_draw' => $request->is_draw,
                'winning_team_id' => $request->is_draw ? null : $request->winning_team_id,
                'losing_team_id' => $request->is_draw ? null : $request->losing_team_id
            ];

            $result = MatchResult::updateOrCreate(
                ['sport_match_id' => $request->sport_match_id],
                $resultData
            );

            // Store facilitator submission
            FacilitatorSubmits::create([
                'facilitator_id' => auth()->user()->id,
                'match_id' => $result->id,
                'official_name' => $request->official_name,
                'signature' => $request->signature,
            ]);

            $match->update(['status' => 'completed']);

            // Update standings
            $this->updateStandings($match, $request->teamA_score, $request->teamB_score, $request->is_draw ? null : $request->winning_team_id);

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
        if ($winningTeamId === null) { // It's a tie
            $teamAStanding->drawn++;
            $teamBStanding->drawn++;
            $teamAStanding->points += 1; // Changed from 0.5 to 1 point for a draw
            $teamBStanding->points += 1;
        } elseif ($winningTeamId == $match->teamA_id) {
            $teamAStanding->won++;
            $teamBStanding->lost++;
            $teamAStanding->points += 2; // Changed from 1 to 2 points for a win
        } else {
            $teamBStanding->won++;
            $teamAStanding->lost++;
            $teamBStanding->points += 2;
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