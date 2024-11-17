<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\MatchResult;
use App\Models\OverallResult;
use App\Models\Palakasan;
use App\Models\SportMatch;
use App\Models\UsedVenueRecord;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DoubleEliminationController extends Controller
{
    public function index(AssignedSports $assignedSports)
    {
        $venues = Venue::all();
        $assignedSports->load('sport');
        $tournaments = Palakasan::all();
        $teams = AssignedTeams::where('palakasan_id', $assignedSports->palakasan_sport_id)->get();
        $matches = SportMatch::where('assigned_sport_id', $assignedSports->id)->get();
        $results = MatchResult::whereIn('sport_match_id', $matches->pluck('id'))->get();
        $allMatches = SportMatch::all();
        $latestPalakasan = Palakasan::latest()->first();
        $venueRecords = UsedVenueRecord::where('palakasan_id', $latestPalakasan->id)->get();

        return Inertia::render('SSCAdmin/MatchSetup/DoubleElimination', [
            'sport' => $assignedSports,
            'tournaments' => $tournaments,
            'teams' => $teams,
            'matches' => $matches,
            'results' => $results,
            'venues' => $venues,
            'allMatches' => $allMatches,
            'venueRecords' => $venueRecords

        ]);
    }   

    public function store(Request $request)
    {
        try {
            $matches = [];
            $matchesData = $request->all();

            DB::beginTransaction();

            foreach ($matchesData as $match) {
                $validatedMatch = $this->validateMatchData($match);

                // Ensure match_venue_id is present
                if (!isset($match['match_venue_id'])) {
                    throw new \InvalidArgumentException('Venue ID is required for each match');
                }

                $existingMatch = SportMatch::where([
                    ['assigned_sport_id', $match['assigned_sport_id']],
                    ['round', $match['round']],
                    ['game', $match['game']],
                ])->first();

                if (!$existingMatch) {
                    $createdMatch = SportMatch::create($validatedMatch);
                    $matches[] = $createdMatch;
                }
            }

            DB::commit();
            return redirect()->back()->with(['message' => 'Matches created successfully', 'matches' => $matches]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create matches: ' . $e->getMessage()]);
        }
    }

    protected function validateMatchData($match)
    {
        $rules = [
            'assigned_sport_id' => 'required|exists:assigned_sports,id',
            'teamA_id' => 'nullable|exists:assigned_teams,id',
            'teamB_id' => 'nullable|exists:assigned_teams,id|different:teamA_id',
            'round' => 'required|integer|min:1',
            'game' => 'required|string',
            'bracket_type' => 'required|string',
            'elimination_type' => 'required|string',
            'status' => 'string|in:pending,completed',
            'match_venue_id' => 'required|exists:venues,id',
            'date' => 'nullable|date_format:Y-m-d',
            'time' => 'nullable'
        ];

        return validator($match, $rules)->validate();
    }

    public function storeResult(Request $request)
    {
        $request->validate([
            'sport_match_id' => 'required|exists:sport_matches,id',
            'teamA_score' => 'required|integer|min:0',
            'teamB_score' => 'required|integer|min:0',
            'winning_team_id' => 'required|exists:assigned_teams,id',
            'losing_team_id' => 'required|exists:assigned_teams,id',
            
        ]);
    
        try {
            DB::beginTransaction();
    
            $match = SportMatch::find($request->sport_match_id);
            
            MatchResult::create([
                'sport_match_id' => $request->sport_match_id,
                'teamA_score' => $request->teamA_score,
                'teamB_score' => $request->teamB_score,
                'winning_team_id' => $request->winning_team_id,
                'losing_team_id' => $request->losing_team_id
            ]);
            
            $match->update(['status' => 'completed']);
    
            if ($this->isFinalMatch($match)) {
                $this->handleFinalMatch($match, $request->winning_team_id, $request->losing_team_id);
            } else {
                if ($match->bracket_type === 'winners') {
                    $this->handleWinnersBracketProgression($match, $request->winning_team_id);
                    $this->handleLosersBracketProgression($match, $request->losing_team_id);
                } elseif ($match->bracket_type === 'losers') {
                    $this->handleLosersBracketProgression($match, $request->winning_team_id);
                }

                if ($this->isLastLosersBracketMatch($match)) {
                    $this->moveLosersBracketWinnerToFinal($match, $request->winning_team_id);
                }
            }

            DB::commit();
            return redirect()->back()->with('message', 'Score saved successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to save score: ' . $e->getMessage()]);
        }
    }

    protected function handleWinnersBracketProgression($match, $winningTeamId)
    {
        $nextWinnersMatch = SportMatch::where([
            ['assigned_sport_id', $match->assigned_sport_id],
            ['round', $match->round + 1],
            ['bracket_type', 'winners'],
            ['status', 'pending']
        ])
        ->where(function($query) {
            $query->whereNull('teamA_id')
                  ->orWhereNull('teamB_id');
        })
        ->orderBy('game')
        ->first();
    
        if ($nextWinnersMatch) {
            if (is_null($nextWinnersMatch->teamA_id)) {
                $nextWinnersMatch->update(['teamA_id' => $winningTeamId]);
            } else {
                $nextWinnersMatch->update(['teamB_id' => $winningTeamId]);
            }
        }
    }

    protected function handleLosersBracketProgression($match, $losingTeamId)
    {
        $nextLosersMatch = SportMatch::where([
            ['assigned_sport_id', $match->assigned_sport_id],
            ['bracket_type', 'losers'],
            ['status', 'pending']
        ])
        ->where(function($query) {
            $query->whereNull('teamA_id')
                  ->orWhereNull('teamB_id');
        })
        ->orderBy('round')
        ->orderBy('game')
        ->first();
    
        if ($nextLosersMatch) {
            if (is_null($nextLosersMatch->teamA_id)) {
                $nextLosersMatch->update(['teamA_id' => $losingTeamId]);
            } else {
                $nextLosersMatch->update(['teamB_id' => $losingTeamId]);
            }
        }
    }

    protected function isLastLosersBracketMatch($match)
    {
        if ($match->bracket_type !== 'losers') {
            return false;
        }

        $nextLoserMatch = SportMatch::where('assigned_sport_id', $match->assigned_sport_id)
            ->where('bracket_type', 'losers')
            ->where('round', '>', $match->round)
            ->first();

        return $nextLoserMatch === null;
    }

    protected function moveLosersBracketWinnerToFinal($match, $winningTeamId)
    {
        $finalMatch = SportMatch::where('assigned_sport_id', $match->assigned_sport_id)
            ->where('bracket_type', 'winners')
            ->where('status', 'pending')
            ->orderBy('round', 'desc')
            ->first();

        if ($finalMatch) {
            if (is_null($finalMatch->teamA_id)) {
                $finalMatch->update(['teamA_id' => $winningTeamId]);
            } else {
                $finalMatch->update(['teamB_id' => $winningTeamId]);
            }
        } else {
            SportMatch::create([
                'assigned_sport_id' => $match->assigned_sport_id,
                'round' => $match->round + 1,
                'game' => 'Final',
                'bracket_type' => 'winners',
                'elimination_type' => $match->elimination_type,
                'status' => 'pending',
                'teamA_id' => $winningTeamId,
                'match_venue_id' => $match->match_venue_id,
                'date' => 'nullable|date_format:Y-m-d',
                'time' => 'nullable'
                
            ]);
        }
    }
    protected function handleFinalMatch($match, $winningTeamId, $losingTeamId)
    {
        // Only create deciding match if winner is from losers bracket
        $isWinnerFromLosersBracket = $this->isFromLosersBracket($match, $winningTeamId);
        
        if ($isWinnerFromLosersBracket) {
            // If winner came from losers bracket, create deciding match
            $this->createDecidingMatch($match, $winningTeamId, $losingTeamId);
        } else {
            // If winner is from winners bracket, end tournament
            $this->endTournament($match->assigned_sport_id, $winningTeamId);
        }
    }
    
    protected function isFromLosersBracket($match, $teamId)
    {
        // Find the last match this team played in the losers bracket
        $lastLosersBracketMatch = SportMatch::where('assigned_sport_id', $match->assigned_sport_id)
            ->where('bracket_type', 'losers')
            ->where(function($query) use ($teamId) {
                $query->where('teamA_id', $teamId)
                      ->orWhere('teamB_id', $teamId);
            })
            ->orderBy('round', 'desc')
            ->first();
    
        // Check if the team's most recent match before the final was in the losers bracket
        return $lastLosersBracketMatch !== null;
    }


    protected function createDecidingMatch($previousMatch, $teamFromLosersBracket, $teamFromWinnersBracket)
    {
        SportMatch::create([
            'assigned_sport_id' => $previousMatch->assigned_sport_id,
            'round' => $previousMatch->round + 1,
            'game' => 'Deciding Match',
            'bracket_type' => 'deciding match',
            'elimination_type' => $previousMatch->elimination_type,
            'status' => 'pending',
            'teamA_id' => $teamFromLosersBracket,
            'teamB_id' => $teamFromWinnersBracket,
            'match_venue_id' => $previousMatch->match_venue_id,
            'date' => null,
            'time' => null
        ]);
    }

    protected function endTournament($assignedSportId, $winningTeamId)
    {
        SportMatch::where('assigned_sport_id', $assignedSportId)
            ->update(['status' => 'completed']);

    }

    protected function isFinalMatch($match)
    {
        $finalMatch = SportMatch::where('assigned_sport_id', $match->assigned_sport_id)
            ->where('bracket_type', '!=', 'deciding match')
            ->orderBy('round', 'desc')
            ->first();

        return $match->id === $finalMatch->id;
    }


    public function storeOverallResults(Request $request)
    {
        $request->validate([
            'rankings' => 'required|array',
            'rankings.*.assigned_sport_id' => 'required|exists:assigned_sports,id',
            'rankings.*.assigned_team_id' => 'required|exists:assigned_teams,id',
            'rankings.*.rank' => 'required|integer|min:1',
            'rankings.*.points' => 'required|integer|min:0',
        ]);
    
        try {
            DB::beginTransaction();
    
            foreach ($request->rankings as $ranking) {
                // Update or create the overall result
                OverallResult::updateOrCreate(
                    [
                        'assigned_sport_id' => $ranking['assigned_sport_id'],
                        'assigned_team_id' => $ranking['assigned_team_id'],
                    ],
                    [
                        'rank' => $ranking['rank'],
                        'points' => $ranking['points'],
                    ]
                );
    
                // Update the status of the assigned sport to 'completed'
                AssignedSports::where('id', $ranking['assigned_sport_id'])
                    ->update(['status' => 'completed']);
            }
    
            DB::commit();
            return redirect()->back()->with('message', 'Ranking successfully submitted');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'There was an error saving the scores.');
        }
    }
}