<?php

namespace App\Http\Controllers;

use App\Models\Palakasan;
use App\Models\SportMatch;
use App\Models\AssignedTeams;
use App\Models\AssignedSports;
use App\Models\MatchResult;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SportsLandingController extends Controller
{
    public function index()
    {
        $latestPalakasan = Palakasan::latest()->first();
        $year = $latestPalakasan ? $latestPalakasan->year : date('Y');
        
        // Get all active sports from assigned_sports
        $activeSports = AssignedSports::with(['sport'])
            ->where('palakasan_sport_id', $latestPalakasan->id)
            ->get()
            ->map(function($assignedSport) {
                return [
                    'id' => $assignedSport->id,
                    'name' => $assignedSport->sport->name,
                    'category' => $assignedSport->categories,
                    'setup' => $assignedSport->setup,
                    'status' => $assignedSport->status,
                    'type' => $assignedSport->type,
                    'statusClass' => $this->getStatusClass($assignedSport->status)
                ];
            });

        // Get active teams for the current Palakasan
        $activeTeams = AssignedTeams::with(['college'])
            ->where('palakasan_id', $latestPalakasan->id)
            ->get()
            ->map(function($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->college->name,
                    'acronym' => $team->college->acronym,
                    'logo' => $team->college->logo
                ];
            });

        // Get overall rankings
        $overallRankings = AssignedTeams::with(['college'])
            ->where('palakasan_id', $latestPalakasan->id)
            ->get()
            ->map(function($team) {
                $totalWins = MatchResult::where('winning_team_id', $team->id)->count();
                $totalLosses = MatchResult::where('losing_team_id', $team->id)->count();
                $totalGames = $totalWins + $totalLosses;
                $winPercentage = $totalGames > 0 ? round(($totalWins / $totalGames) * 100, 1) : 0;
                
                return [
                    'team_id' => $team->id,
                    'name' => $team->college->name,
                    'acronym' => $team->college->acronym,
                    'wins' => $totalWins,
                    'losses' => $totalLosses,
                    'total_games' => $totalGames,
                    'win_percentage' => $winPercentage,
                    'points' => $totalWins * 2 // 2 points per win
                ];
            })
            ->sortByDesc('points')
            ->values();

        return Inertia::render('SportsLandingPage/SportsLanding', [
            'palakasanYear' => $year,
            'activeSports' => $activeSports,
            'activeTeams' => $activeTeams,
            'overallRankings' => $overallRankings
        ]);
    }

    public function getSportMatches(Request $request, $sportId)
    {
        $matches = SportMatch::with(['assignedSport.sport', 'teamA.college', 'teamB.college', 'matchVenue'])
            ->where('assigned_sport_id', $sportId)
            ->get()
            ->map(function($match) {
                return [
                    'id' => $match->id,
                    'game' => $match->game,
                    'round' => $match->round,
                    'date' => $match->date,
                    'time' => $match->time,
                    'team1' => [
                        'name' => $match->teamA ? $match->teamA->college->name : 'TBD',
                        'color' => 'text-lg font-bold text-blue-600',
                        'score' => '--'
                    ],
                    'team2' => [
                        'name' => $match->teamB ? $match->teamB->college->name : 'TBD',
                        'color' => 'text-lg font-bold text-red-600',
                        'score' => '--'
                    ],
                    'venue' => $match->matchVenue->name,
                    'status' => $match->status,
                    'statusClass' => $this->getStatusClass($match->status)
                ];
            });

        return Inertia::render('SportsLandingPage/SportsLanding', [
            'matches' => $matches,
            'selectedSportId' => $sportId
        ]);
    }

    public function getSportStandings($sportId)
    {
        $sport = AssignedSports::with(['sport'])->findOrFail($sportId);
        
        // Get all teams and their match results for this sport
        $standings = AssignedTeams::with(['college'])
            ->where('palakasan_id', $sport->palakasan_sport_id)
            ->get()
            ->map(function($team) use ($sportId) {
                $wins = MatchResult::where('winning_team_id', $team->id)
                    ->whereHas('sportMatch', function($q) use ($sportId) {
                        $q->where('assigned_sport_id', $sportId);
                    })
                    ->count();
                    
                $losses = MatchResult::where('losing_team_id', $team->id)
                    ->whereHas('sportMatch', function($q) use ($sportId) {
                        $q->where('assigned_sport_id', $sportId);
                    })
                    ->count();
                
                return [
                    'team_id' => $team->id,
                    'team_name' => $team->college->name,
                    'team_acronym' => $team->college->acronym,
                    'wins' => $wins,
                    'losses' => $losses,
                    'total_games' => $wins + $losses,
                    'win_percentage' => $wins + $losses > 0 ? round(($wins / ($wins + $losses)) * 100, 1) : 0
                ];
            })
            ->sortByDesc('win_percentage')
            ->values();

        // Add logging to debug the response
        \Log::info('Sport Data:', [
            'sport' => $sport->toArray(),
            'standings' => $standings->toArray()
        ]);

        return Inertia::render('SportsLandingPage/SportsLanding', [
            'selectedSport' => [
                'name' => $sport->sport->name,
                'category' => $sport->categories,
                'id' => $sport->id
            ],
            'standings' => $standings
        ]);
    }

    private function getStatusClass($status)
    {
        switch(strtolower($status)) {
            case 'ongoing':
                return 'inline-block px-3 py-1 text-sm font-medium rounded-full bg-orange-100 text-orange-800';
            case 'completed':
                return 'inline-block px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800';
            case 'pending':
            default:
                return 'inline-block px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800';
        }
    }
    public function getTeamMatches($teamId)
    {
        try {
            // Get the latest Palakasan
            $latestPalakasan = Palakasan::latest()->first();
            if (!$latestPalakasan) {
                throw new \Exception('No active Palakasan found');
            }

            // Get the team with college info
            $team = AssignedTeams::with(['college'])
                ->where('palakasan_id', $latestPalakasan->id)
                ->find($teamId);
            
            if (!$team) {
                return response()->json(['error' => 'Team not found'], 404);
            }

            // Get all matches for this team
            $matches = SportMatch::with(['assignedSport.sport', 'teamA.college', 'teamB.college', 'matchVenue'])
                ->whereHas('assignedSport', function($query) use ($latestPalakasan) {
                    $query->where('palakasan_sport_id', $latestPalakasan->id);
                })
                ->where(function($query) use ($teamId) {
                    $query->where('team_a_id', $teamId)
                          ->orWhere('team_b_id', $teamId);
                })
                ->get()
                ->map(function($match) use ($teamId) {
                    $isTeamA = $match->team_a_id == $teamId;
                    
                    return [
                        'id' => $match->id,
                        'game' => $match->game,
                        'round' => $match->round,
                        'date' => $match->date,
                        'time' => $match->time,
                        'sport' => $match->assignedSport->sport->name,
                        'team1' => [
                            'name' => $match->teamA ? $match->teamA->college->name : 'TBD',
                            'color' => $isTeamA ? 'text-lg font-bold text-blue-600' : 'text-lg',
                            'score' => '--'
                        ],
                        'team2' => [
                            'name' => $match->teamB ? $match->teamB->college->name : 'TBD',
                            'color' => !$isTeamA ? 'text-lg font-bold text-blue-600' : 'text-lg',
                            'score' => '--'
                        ],
                        'venue' => $match->matchVenue->name,
                        'status' => $match->status,
                        'statusClass' => $this->getStatusClass($match->status)
                    ];
                });

            // Get all active sports
            $activeSports = AssignedSports::with(['sport'])
                ->where('palakasan_sport_id', $latestPalakasan->id)
                ->get()
                ->map(function($assignedSport) {
                    return [
                        'id' => $assignedSport->id,
                        'name' => $assignedSport->sport->name,
                        'category' => $assignedSport->categories,
                        'setup' => $assignedSport->setup,
                        'status' => $assignedSport->status,
                        'type' => $assignedSport->type,
                        'statusClass' => $this->getStatusClass($assignedSport->status)
                    ];
                });

            // Get all active teams
            $activeTeams = AssignedTeams::with(['college'])
                ->where('palakasan_id', $latestPalakasan->id)
                ->get()
                ->map(function($team) {
                    return [
                        'id' => $team->id,
                        'name' => $team->college->name,
                        'acronym' => $team->college->acronym,
                        'logo' => $team->college->logo
                    ];
                });

            return Inertia::render('SportsLandingPage/SportsLanding', [
                'palakasanYear' => $latestPalakasan->year,
                'activeSports' => $activeSports,
                'activeTeams' => $activeTeams,
                'selectedTeam' => [
                    'id' => $team->id,
                    'name' => $team->college->name,
                    'matches' => $matches
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getTeamMatches: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while fetching team matches');
        }
    }
}
