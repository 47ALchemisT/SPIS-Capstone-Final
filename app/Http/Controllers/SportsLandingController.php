<?php

namespace App\Http\Controllers;

use App\Models\Palakasan;
use App\Models\SportMatch;
use App\Models\AssignedTeams;
use App\Models\AssignedSports;
use App\Models\MatchResult;
use App\Models\OverallResult;
use App\Models\SportsVariationsMatches;
use App\Models\SportsVariations;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SportsLandingController extends Controller
{
    public function index()
    {
        $latestPalakasan = Palakasan::latest()->first();
        $overallResult = OverallResult::all();
        $variationResult = SportsVariationsMatches::all();
        $sportVariations = collect();
        $assignedSports = collect();
        $assignedTeams = collect();

        // If a Palakasan exists, fetch the assigned sports and teams
        if ($latestPalakasan) {
            $assignedSports = AssignedSports::with('sport')
                ->where('palakasan_sport_id', $latestPalakasan->id)
                ->get();

            // Get sports variations only for the assigned sports in current Palakasan
            $sportVariations = SportsVariations::with([
                'sport_id.sport',
                'venue_id',
                'sportVariationID' => function($query) {
                    $query->with(['assignedTeamVariationID.college']);
                }
            ])
                ->whereIn('assigned_sport_id', $assignedSports->pluck('id'))
                ->where('date', '>=', $latestPalakasan->start_date)
                ->where('date', '<=', $latestPalakasan->end_date)
                ->where(function($query) {
                    $query->where('status', '!=', 'cancelled')
                          ->orWhereNull('status');
                })
                ->orderBy('date')
                ->orderBy('time')
                ->get()
                ->map(function($variation) {
                    return [
                        'id' => $variation->id,
                        'sport_id' => [
                            'id' => $variation->sport_id?->id,
                            'sport' => [
                                'id' => $variation->sport_id?->sport?->id,
                                'name' => $variation->sport_id?->sport?->name
                            ],
                            'categories' => $variation->sport_id?->categories
                        ],
                        'assigned_sport_id' => $variation->assigned_sport_id,
                        'venue_id' => $variation->venue_id,
                        'sport_variation_name' => $variation->sport_variation_name,
                        'date' => $variation->date ? date('M d, Y', strtotime($variation->date)) : 'TBA',
                        'time' => $variation->time ? date('g:i A', strtotime($variation->time)) : '',
                        'status' => $variation->status ?? 'pending',
                        'sport_variation_i_d' => $variation->sportVariationID
                    ];
                });

            $assignedTeams = AssignedTeams::with('college')
                ->where('palakasan_id', $latestPalakasan->id)
                ->get();
        }
        $year = $latestPalakasan ? $latestPalakasan->year : date('Y');
        $sportMatches = SportMatch::with([
            'assignedSport.sport',
            'teamA.college',
            'teamB.college',
            'matchVenue',
            'match_result'
        ])
            ->whereHas('assignedSport', function($query) use ($latestPalakasan) {
                $query->where('palakasan_sport_id', $latestPalakasan->id);
            })
            ->get()
            ->map(function ($match) {
                return [
                    'id' => $match->id,
                    'assignedSport' => [
                        'id' => $match->assignedSport?->id,
                        'sport' => [
                            'id' => $match->assignedSport?->sport?->id,
                            'name' => $match->assignedSport?->sport?->name
                        ],
                        'categories' => $match->assignedSport?->categories
                    ],
                    'teamA' => [
                        'id' => $match->teamA?->id,
                        'assigned_team_name' => $match->teamA?->assigned_team_name,
                        'college' => [
                            'id' => $match->teamA?->college?->id,
                            'name' => $match->teamA?->college?->name
                        ]
                    ],
                    'teamB' => [
                        'id' => $match->teamB?->id,
                        'assigned_team_name' => $match->teamB?->assigned_team_name,
                        'college' => [
                            'id' => $match->teamB?->college?->id,
                            'name' => $match->teamB?->college?->name
                        ]
                    ],
                    'matchVenue' => [
                        'name' => $match->matchVenue?->name ?? 'TBA'
                    ],
                    'date' => $match->date ? date('M d, Y', strtotime($match->date)) : 'TBA',
                    'time' => $match->time ? date('g:i A', strtotime($match->time)) : '',
                    'status' => $match->status ?? 'pending',
                    'game' => $match->game,
                    'match_result' => $match->match_result ? [
                        'id' => $match->match_result->id,
                        'teamA_score' => $match->match_result->teamA_score,
                        'teamB_score' => $match->match_result->teamB_score,
                        'winning_team_id' => $match->match_result->winning_team_id,
                        'losing_team_id' => $match->match_result->losing_team_id
                    ] : null
                ];
            });

        // Get all active sports from assigned_sports
        $activeSports = AssignedSports::with(['sport'])
            ->where('palakasan_sport_id', $latestPalakasan->id)
            ->get()
            ->map(function ($assignedSport) {
                return [
                    'id' => $assignedSport->id,
                    'sport' => [
                        'name' => $assignedSport->sport?->name ?? 'Unknown Sport',
                        'icon' => $assignedSport->sport?->icon ?? 'fas fa-trophy'
                    ],
                    'categories' => $assignedSport->categories ?? 'Unknown Category',
                    'setup' => $assignedSport->setup ?? 'Unknown setup',
                    'type' => $assignedSport->type ?? 'Unknown type',
                    'status' => $assignedSport->status ?? 'pending'
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
                    'assigned_team_name' => $team->assigned_team_name,
                    'wins' => $totalWins,
                    'losses' => $totalLosses,
                    'total_games' => $totalGames,
                    'win_percentage' => $winPercentage,
                    'points' => $totalWins * 2 // 2 points per win
                ];
            })
            ->sortByDesc('points')
            ->values();

        return Inertia::render('SportsLandingPage/LandingPage', [
            'latestPalakasan' => $latestPalakasan,
            'sportMatches' => $sportMatches,
            'palakasanYear' => $year,
            'activeSports' => $activeSports,
            'activeTeams' => $activeTeams,
            'overallRankings' => $overallRankings,
            'overallResult' => $overallResult,
            'sportsVariationMatches' => $sportVariations,
            'assignedSports' => $assignedSports,
            'assignedTeams' => $assignedTeams
        ]);
    }

    public function sportsIndex()
    {
        $latestPalakasan = Palakasan::latest()->first();
        $overallResult = OverallResult::all();
        $variationResult = SportsVariationsMatches::all();
        $sportVariations = collect();
        $assignedSports = collect();
        $assignedTeams = collect();

        // If a Palakasan exists, fetch the assigned sports and teams
        if ($latestPalakasan) {
            $assignedSports = AssignedSports::with('sport')
                ->where('palakasan_sport_id', $latestPalakasan->id)
                ->get();

            // Get sports variations only for the assigned sports in current Palakasan
            $sportVariations = SportsVariations::with([
                'sport_id.sport',
                'venue_id',
                'sportVariationID' => function($query) {
                    $query->with(['assignedTeamVariationID.college']);
                }
            ])
                ->whereIn('assigned_sport_id', $assignedSports->pluck('id'))
                ->where('date', '>=', $latestPalakasan->start_date)
                ->where('date', '<=', $latestPalakasan->end_date)
                ->where(function($query) {
                    $query->where('status', '!=', 'cancelled')
                          ->orWhereNull('status');
                })
                ->orderBy('date')
                ->orderBy('time')
                ->get()
                ->map(function($variation) {
                    return [
                        'id' => $variation->id,
                        'sport_id' => [
                            'id' => $variation->sport_id?->id,
                            'sport' => [
                                'id' => $variation->sport_id?->sport?->id,
                                'name' => $variation->sport_id?->sport?->name
                            ],
                            'categories' => $variation->sport_id?->categories
                        ],
                        'assigned_sport_id' => $variation->assigned_sport_id,
                        'venue_id' => $variation->venue_id,
                        'sport_variation_name' => $variation->sport_variation_name,
                        'date' => $variation->date ? date('M d, Y', strtotime($variation->date)) : 'TBA',
                        'time' => $variation->time ? date('g:i A', strtotime($variation->time)) : '',
                        'status' => $variation->status ?? 'pending',
                        'sport_variation_i_d' => $variation->sportVariationID
                    ];
                });

            $assignedTeams = AssignedTeams::with('college')
                ->where('palakasan_id', $latestPalakasan->id)
                ->get();
        }
        $year = $latestPalakasan ? $latestPalakasan->year : date('Y');
        $sportMatches = SportMatch::with([
            'assignedSport.sport',
            'teamA.college',
            'teamB.college',
            'matchVenue',
            'match_result'
        ])
            ->whereHas('assignedSport', function($query) use ($latestPalakasan) {
                $query->where('palakasan_sport_id', $latestPalakasan->id);
            })
            ->get()
            ->map(function ($match) {
                return [
                    'id' => $match->id,
                    'assignedSport' => [
                        'id' => $match->assignedSport?->id,
                        'sport' => [
                            'id' => $match->assignedSport?->sport?->id,
                            'name' => $match->assignedSport?->sport?->name
                        ],
                        'categories' => $match->assignedSport?->categories
                    ],
                    'teamA' => [
                        'id' => $match->teamA?->id,
                        'assigned_team_name' => $match->teamA?->assigned_team_name,
                        'college' => [
                            'id' => $match->teamA?->college?->id,
                            'name' => $match->teamA?->college?->name
                        ]
                    ],
                    'teamB' => [
                        'id' => $match->teamB?->id,
                        'assigned_team_name' => $match->teamB?->assigned_team_name,
                        'college' => [
                            'id' => $match->teamB?->college?->id,
                            'name' => $match->teamB?->college?->name
                        ]
                    ],
                    'matchVenue' => [
                        'name' => $match->matchVenue?->name ?? 'TBA'
                    ],
                    'date' => $match->date ? date('M d, Y', strtotime($match->date)) : 'TBA',
                    'time' => $match->time ? date('g:i A', strtotime($match->time)) : '',
                    'status' => $match->status ?? 'pending',
                    'game' => $match->game,
                    'match_result' => $match->match_result ? [
                        'id' => $match->match_result->id,
                        'teamA_score' => $match->match_result->teamA_score,
                        'teamB_score' => $match->match_result->teamB_score,
                        'winning_team_id' => $match->match_result->winning_team_id,
                        'losing_team_id' => $match->match_result->losing_team_id
                    ] : null
                ];
            });

        // Get all active sports from assigned_sports
        $activeSports = AssignedSports::with(['sport'])
            ->where('palakasan_sport_id', $latestPalakasan->id)
            ->get()
            ->map(function ($assignedSport) {
                return [
                    'id' => $assignedSport->id,
                    'sport' => [
                        'name' => $assignedSport->sport?->name ?? 'Unknown Sport',
                        'icon' => $assignedSport->sport?->icon ?? 'fas fa-trophy'
                    ],
                    'categories' => $assignedSport->categories ?? 'Unknown Category',
                    'setup' => $assignedSport->setup ?? 'Unknown setup',
                    'type' => $assignedSport->type ?? 'Unknown type',
                    'status' => $assignedSport->status ?? 'pending'
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
                    'assigned_team_name' => $team->assigned_team_name,
                    'wins' => $totalWins,
                    'losses' => $totalLosses,
                    'total_games' => $totalGames,
                    'win_percentage' => $winPercentage,
                    'points' => $totalWins * 2 // 2 points per win
                ];
            })
            ->sortByDesc('points')
            ->values();

        return Inertia::render('SportsLandingPage/Sports', [
            'latestPalakasan' => $latestPalakasan,
            'sportMatches' => $sportMatches,
            'palakasanYear' => $year,
            'activeSports' => $activeSports,
            'activeTeams' => $activeTeams,
            'overallRankings' => $overallRankings,
            'overallResult' => $overallResult,
            'sportsVariationMatches' => $sportVariations,
            'assignedSports' => $assignedSports,
            'assignedTeams' => $assignedTeams
        ]);
    }

    public function scheduleIndex()
    {
        $latestPalakasan = Palakasan::latest()->first();
        $overallResult = OverallResult::all();
        $variationResult = SportsVariationsMatches::all();
        $sportVariations = collect();
        $assignedSports = collect();
        $assignedTeams = collect();

        // If a Palakasan exists, fetch the assigned sports and teams
        if ($latestPalakasan) {
            $assignedSports = AssignedSports::with('sport')
                ->where('palakasan_sport_id', $latestPalakasan->id)
                ->get();

            // Get sports variations only for the assigned sports in current Palakasan
            $sportVariations = SportsVariations::with([
                'sport_id.sport',
                'venue_id',
                'sportVariationID' => function($query) {
                    $query->with(['assignedTeamVariationID.college']);
                }
            ])
                ->whereIn('assigned_sport_id', $assignedSports->pluck('id'))
                ->where('date', '>=', $latestPalakasan->start_date)
                ->where('date', '<=', $latestPalakasan->end_date)
                ->where(function($query) {
                    $query->where('status', '!=', 'cancelled')
                          ->orWhereNull('status');
                })
                ->orderBy('date')
                ->orderBy('time')
                ->get()
                ->map(function($variation) {
                    return [
                        'id' => $variation->id,
                        'sport_id' => [
                            'id' => $variation->sport_id?->id,
                            'sport' => [
                                'id' => $variation->sport_id?->sport?->id,
                                'name' => $variation->sport_id?->sport?->name
                            ],
                            'categories' => $variation->sport_id?->categories
                        ],
                        'assigned_sport_id' => $variation->assigned_sport_id,
                        'venue_id' => $variation->venue_id,
                        'sport_variation_name' => $variation->sport_variation_name,
                        'date' => $variation->date ? date('M d, Y', strtotime($variation->date)) : 'TBA',
                        'time' => $variation->time ? date('g:i A', strtotime($variation->time)) : '',
                        'status' => $variation->status ?? 'pending',
                        'sport_variation_i_d' => $variation->sportVariationID
                    ];
                });

            $assignedTeams = AssignedTeams::with('college')
                ->where('palakasan_id', $latestPalakasan->id)
                ->get();
        }
        $year = $latestPalakasan ? $latestPalakasan->year : date('Y');
        $sportMatches = SportMatch::with([
            'assignedSport.sport',
            'teamA.college',
            'teamB.college',
            'matchVenue',
            'match_result'
        ])
            ->whereHas('assignedSport', function($query) use ($latestPalakasan) {
                $query->where('palakasan_sport_id', $latestPalakasan->id);
            })
            ->get()
            ->map(function ($match) {
                return [
                    'id' => $match->id,
                    'assignedSport' => [
                        'id' => $match->assignedSport?->id,
                        'sport' => [
                            'id' => $match->assignedSport?->sport?->id,
                            'name' => $match->assignedSport?->sport?->name
                        ],
                        'categories' => $match->assignedSport?->categories
                    ],
                    'teamA' => [
                        'id' => $match->teamA?->id,
                        'assigned_team_name' => $match->teamA?->assigned_team_name,
                        'college' => [
                            'id' => $match->teamA?->college?->id,
                            'name' => $match->teamA?->college?->name
                        ]
                    ],
                    'teamB' => [
                        'id' => $match->teamB?->id,
                        'assigned_team_name' => $match->teamB?->assigned_team_name,
                        'college' => [
                            'id' => $match->teamB?->college?->id,
                            'name' => $match->teamB?->college?->name
                        ]
                    ],
                    'matchVenue' => [
                        'name' => $match->matchVenue?->name ?? 'TBA'
                    ],
                    'date' => $match->date ? date('M d, Y', strtotime($match->date)) : 'TBA',
                    'time' => $match->time ? date('g:i A', strtotime($match->time)) : '',
                    'status' => $match->status ?? 'pending',
                    'game' => $match->game,
                    'match_result' => $match->match_result ? [
                        'id' => $match->match_result->id,
                        'teamA_score' => $match->match_result->teamA_score,
                        'teamB_score' => $match->match_result->teamB_score,
                        'winning_team_id' => $match->match_result->winning_team_id,
                        'losing_team_id' => $match->match_result->losing_team_id
                    ] : null
                ];
            });

        // Get all active sports from assigned_sports
        $activeSports = AssignedSports::with(['sport'])
            ->where('palakasan_sport_id', $latestPalakasan->id)
            ->get()
            ->map(function ($assignedSport) {
                return [
                    'id' => $assignedSport->id,
                    'sport' => [
                        'name' => $assignedSport->sport?->name ?? 'Unknown Sport',
                        'icon' => $assignedSport->sport?->icon ?? 'fas fa-trophy'
                    ],
                    'categories' => $assignedSport->categories ?? 'Unknown Category',
                    'setup' => $assignedSport->setup ?? 'Unknown setup',
                    'type' => $assignedSport->type ?? 'Unknown type',
                    'status' => $assignedSport->status ?? 'pending'
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
                    'assigned_team_name' => $team->assigned_team_name,
                    'wins' => $totalWins,
                    'losses' => $totalLosses,
                    'total_games' => $totalGames,
                    'win_percentage' => $winPercentage,
                    'points' => $totalWins * 2 // 2 points per win
                ];
            })
            ->sortByDesc('points')
            ->values();

        return Inertia::render('SportsLandingPage/SportsSchedule', [
            'latestPalakasan' => $latestPalakasan,
            'sportMatches' => $sportMatches,
            'palakasanYear' => $year,
            'activeSports' => $activeSports,
            'activeTeams' => $activeTeams,
            'overallRankings' => $overallRankings,
            'overallResult' => $overallResult,
            'sportsVariationMatches' => $sportVariations,
            'assignedSports' => $assignedSports,
            'assignedTeams' => $assignedTeams,
            'sportVariations' => $sportVariations
        ]);
    }

    public function rankingIndex()
    {
        $latestPalakasan = Palakasan::latest()->first();
        $overallResult = OverallResult::all();
        $variationResult = SportsVariationsMatches::all();
        $sportVariations = collect();
        $assignedSports = collect();
        $assignedTeams = collect();

        // If a Palakasan exists, fetch the assigned sports and teams
        if ($latestPalakasan) {
            $assignedSports = AssignedSports::with('sport')
                ->where('palakasan_sport_id', $latestPalakasan->id)
                ->get();

            // Get sports variations only for the assigned sports in current Palakasan
            $sportVariations = SportsVariations::with([
                'sport_id.sport',
                'venue_id',
                'sportVariationID' => function($query) {
                    $query->with(['assignedTeamVariationID.college']);
                }
            ])
                ->whereIn('assigned_sport_id', $assignedSports->pluck('id'))
                ->where('date', '>=', $latestPalakasan->start_date)
                ->where('date', '<=', $latestPalakasan->end_date)
                ->where(function($query) {
                    $query->where('status', '!=', 'cancelled')
                          ->orWhereNull('status');
                })
                ->orderBy('date')
                ->orderBy('time')
                ->get()
                ->map(function($variation) {
                    return [
                        'id' => $variation->id,
                        'sport_id' => [
                            'id' => $variation->sport_id?->id,
                            'sport' => [
                                'id' => $variation->sport_id?->sport?->id,
                                'name' => $variation->sport_id?->sport?->name
                            ],
                            'categories' => $variation->sport_id?->categories
                        ],
                        'assigned_sport_id' => $variation->assigned_sport_id,
                        'venue_id' => $variation->venue_id,
                        'sport_variation_name' => $variation->sport_variation_name,
                        'date' => $variation->date ? date('M d, Y', strtotime($variation->date)) : 'TBA',
                        'time' => $variation->time ? date('g:i A', strtotime($variation->time)) : '',
                        'status' => $variation->status ?? 'pending',
                        'sport_variation_i_d' => $variation->sportVariationID
                    ];
                });

            $assignedTeams = AssignedTeams::with('college')
                ->where('palakasan_id', $latestPalakasan->id)
                ->get();
        }
        $year = $latestPalakasan ? $latestPalakasan->year : date('Y');
        $sportMatches = SportMatch::with([
            'assignedSport.sport',
            'teamA.college',
            'teamB.college',
            'matchVenue',
            'match_result'
        ])
            ->whereHas('assignedSport', function($query) use ($latestPalakasan) {
                $query->where('palakasan_sport_id', $latestPalakasan->id);
            })
            ->get()
            ->map(function ($match) {
                return [
                    'id' => $match->id,
                    'assignedSport' => [
                        'id' => $match->assignedSport?->id,
                        'sport' => [
                            'id' => $match->assignedSport?->sport?->id,
                            'name' => $match->assignedSport?->sport?->name
                        ],
                        'categories' => $match->assignedSport?->categories
                    ],
                    'teamA' => [
                        'id' => $match->teamA?->id,
                        'assigned_team_name' => $match->teamA?->assigned_team_name,
                        'college' => [
                            'id' => $match->teamA?->college?->id,
                            'name' => $match->teamA?->college?->name
                        ]
                    ],
                    'teamB' => [
                        'id' => $match->teamB?->id,
                        'assigned_team_name' => $match->teamB?->assigned_team_name,
                        'college' => [
                            'id' => $match->teamB?->college?->id,
                            'name' => $match->teamB?->college?->name
                        ]
                    ],
                    'matchVenue' => [
                        'name' => $match->matchVenue?->name ?? 'TBA'
                    ],
                    'date' => $match->date ? date('M d, Y', strtotime($match->date)) : 'TBA',
                    'time' => $match->time ? date('g:i A', strtotime($match->time)) : '',
                    'status' => $match->status ?? 'pending',
                    'game' => $match->game,
                    'match_result' => $match->match_result ? [
                        'id' => $match->match_result->id,
                        'teamA_score' => $match->match_result->teamA_score,
                        'teamB_score' => $match->match_result->teamB_score,
                        'winning_team_id' => $match->match_result->winning_team_id,
                        'losing_team_id' => $match->match_result->losing_team_id
                    ] : null
                ];
            });

        // Get all active sports from assigned_sports
        $activeSports = AssignedSports::with(['sport'])
            ->where('palakasan_sport_id', $latestPalakasan->id)
            ->get()
            ->map(function ($assignedSport) {
                return [
                    'id' => $assignedSport->id,
                    'sport' => [
                        'name' => $assignedSport->sport?->name ?? 'Unknown Sport',
                        'icon' => $assignedSport->sport?->icon ?? 'fas fa-trophy'
                    ],
                    'categories' => $assignedSport->categories ?? 'Unknown Category',
                    'setup' => $assignedSport->setup ?? 'Unknown setup',
                    'type' => $assignedSport->type ?? 'Unknown type',
                    'status' => $assignedSport->status ?? 'pending'
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
                // Regular matches
                $totalWins = MatchResult::where('winning_team_id', $team->id)->count();
                $totalLosses = MatchResult::where('losing_team_id', $team->id)->count();
                $totalGames = $totalWins + $totalLosses;
                $winPercentage = $totalGames > 0 ? round(($totalWins / $totalGames) * 100, 1) : 0;
                
                // Sports variation matches
                $variationPoints = 0;
                $variationResults = SportsVariationsMatches::where('sport_variation_team_id', $team->id)
                    ->get();

                foreach ($variationResults as $result) {
                    if ($result->rank) {
                        if ($result->rank === 1) {
                            $variationPoints += 3;
                            $totalWins++; // Count first place as a win
                        } elseif ($result->rank === 2) {
                            $variationPoints += 2;
                        } elseif ($result->rank === 3) {
                            $variationPoints += 1;
                        }
                        $totalGames++;
                    }
                }
                
                return [
                    'team_id' => $team->id,
                    'name' => $team->college->name,
                    'acronym' => $team->college->acronym,
                    'assigned_team_name' => $team->assigned_team_name,
                    'wins' => $totalWins,
                    'losses' => $totalLosses,
                    'total_games' => $totalGames,
                    'win_percentage' => $winPercentage,
                    'regular_points' => $totalWins * 2, // 2 points per regular win
                    'variation_points' => $variationPoints,
                    'total_points' => ($totalWins * 2) + $variationPoints // Total points from both types
                ];
            })
            ->sortByDesc('total_points')
            ->values();

        return Inertia::render('SportsLandingPage/Rankings', [
            'latestPalakasan' => $latestPalakasan,
            'sportMatches' => $sportMatches,
            'palakasanYear' => $year,
            'activeSports' => $activeSports,
            'activeTeams' => $activeTeams,
            'overallRankings' => $overallRankings,
            'overallResult' => $overallResult,
            'sportsVariationMatches' => $sportVariations,
            'assignedSports' => $assignedSports,
            'assignedTeams' => $assignedTeams
        ]);
    }


    public function getSportMatches(Request $request, $sportId)
    {
        $matches = SportMatch::with([
            'assignedSport.sport',
            'teamA.college',
            'teamB.college',
            'matchVenue',
            'match_result'
        ])
            ->where('assigned_sport_id', $sportId)
            ->get()
            ->map(function($match) {
                // Get the match result if it exists
                $matchResult = $match->match_result;
                
                // Get team scores from the facilitator's input
                $team1Score = $matchResult ? $matchResult->teamA_score : '--';
                $team2Score = $matchResult ? $matchResult->teamB_score : '--';

                // Determine winners based on scores
                $team1IsWinner = $matchResult && $matchResult->teamA_score > $matchResult->teamB_score;
                $team2IsWinner = $matchResult && $matchResult->teamB_score > $matchResult->teamA_score;

                return [
                    'id' => $match->id,
                    'game' => $match->game,
                    'round' => $match->round,
                    'date' => $match->date,
                    'time' => $match->time,
                    'team1' => [
                        'name' => $match->teamA?->college?->name ?? 'TBD',
                        'color' => $team1IsWinner ? 'text-lg font-bold text-green-600' : 'text-lg font-bold text-blue-600',
                        'score' => $team1Score,
                        'isWinner' => $team1IsWinner
                    ],
                    'team2' => [
                        'name' => $match->teamB?->college?->name ?? 'TBD',
                        'color' => $team2IsWinner ? 'text-lg font-bold text-green-600' : 'text-lg font-bold text-red-600',
                        'score' => $team2Score,
                        'isWinner' => $team2IsWinner
                    ],
                    'venue' => $match->matchVenue?->name ?? 'TBA',
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
            $matches = SportMatch::with([
                'assignedSport.sport',
                'teamA.college',
                'teamB.college',
                'matchVenue',
                'match_result'
            ])
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
                        'sport' => $match->assignedSport?->sport?->name ?? 'Unknown Sport',
                        'team1' => [
                            'assigned_team_name' => $match->teamA?->assigned_team_name ?? 'Team A',
                            'college' => [
                                'name' => $match->teamA?->college?->name ?? ''
                            ],
                            'color' => $isTeamA ? 'text-lg font-bold text-blue-600' : 'text-lg',
                            'score' => '--'
                        ],
                        'team2' => [
                            'assigned_team_name' => $match->teamB?->assigned_team_name ?? 'Team B',
                            'college' => [
                                'name' => $match->teamB?->college?->name ?? ''
                            ],
                            'color' => !$isTeamA ? 'text-lg font-bold text-blue-600' : 'text-lg',
                            'score' => '--'
                        ],
                        'venue' => $match->matchVenue?->name ?? 'TBA',
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
