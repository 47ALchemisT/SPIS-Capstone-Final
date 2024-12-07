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
use App\Models\SportsVariations;
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

        // Load facilitators with student relationship
        $facilitator = StudentAccount::with('student')
            ->where('role', 'Facilitator')
            ->where('status', true)
            ->get();

        // Fetch the latest Palakasan
        $latestPalakasan = Palakasan::latest()->first();        
        $thisPalakasan = Palakasan::latest()->first();        

        // Initialize empty collections for assigned sports and teams
        $assignedSports = collect();
        $assignedTeams = collect();
        $activeSports = collect();
        $activeTeams = collect();

        // If a Palakasan exists, fetch the assigned sports and teams
        if ($latestPalakasan) {
            $assignedSports = AssignedSports::with('sport')
                ->where('palakasan_sport_id', $latestPalakasan->id)
                ->get();

            $activeSports = $assignedSports->filter(function($sport) {
                return $sport->status === 'completed';
            });

            // Get active teams with college info
            $activeTeams = AssignedTeams::with(['college'])
                ->where('palakasan_id', $latestPalakasan->id)
                ->get()
                ->map(function($team) {
                    return [
                        'id' => $team->id,
                        'name' => $team->college->name,
                        'acronym' => $team->college->acronym,
                        'logo' => $team->college->logo,
                        'assigned_team_name' => $team->assigned_team_name
                    ];
                });

            // Get sports variations
            $sportsVariations = SportsVariations::with([
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
                            'categories' => $variation->sport_id?->categories,
                            'setup' => $variation->sport_id?->setup

                        ],
                        'assigned_sport_id' => $variation->assigned_sport_id,
                        'venue' => [
                            'id' => $variation->venue_id?->id,
                            'name' => $variation->venue_id?->name ?? 'TBA'
                        ],
                        'sport_variation_name' => $variation->sport_variation_name,
                        'date' => $variation->date ? date('M d, Y', strtotime($variation->date)) : 'TBA',
                        'time' => $variation->time ? date('g:i A', strtotime($variation->time)) : 'TBA',
                        'status' => $variation->status ?? 'pending',
                        'sport_variation_i_d' => $variation->sportVariationID->map(function($match) {
                            return [
                                'id' => $match->id,
                                'rank' => $match->rank,
                                'assigned_team_variation_i_d' => [
                                    'id' => $match->assignedTeamVariationID?->id,
                                    'college' => [
                                        'id' => $match->assignedTeamVariationID?->college?->id,
                                        'name' => $match->assignedTeamVariationID?->college?->name,
                                        'acronym' => $match->assignedTeamVariationID?->college?->acronym,
                                        'logo' => $match->assignedTeamVariationID?->college?->logo
                                    ]
                                ]
                            ];
                        })
                    ];
                });

            // Get sport matches
            $sportMatches = SportMatch::with([
                'assignedSport.sport',
                'teamA.college',
                'teamB.college',
                'matchVenue',
                'match_result'
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
                                'name' => $match->teamA?->college?->name,
                                'acronym' => $match->teamA?->college?->acronym,
                                'logo' => $match->teamA?->college?->logo
                            ]
                        ],
                        'teamB' => [
                            'id' => $match->teamB?->id,
                            'assigned_team_name' => $match->teamB?->assigned_team_name,
                            'college' => [
                                'id' => $match->teamB?->college?->id,
                                'name' => $match->teamB?->college?->name,
                                'acronym' => $match->teamB?->college?->acronym,
                                'logo' => $match->teamB?->college?->logo
                            ]
                        ],
                        'matchVenue' => [
                            'id' => $match->matchVenue?->id,
                            'name' => $match->matchVenue?->name
                        ],
                        'round' => $match->round,
                        'game' => $match->game,
                        'bracket_type' => $match->bracket_type,
                        'elimination_type' => $match->elimination_type,
                        'date' => $match->date ? date('M d, Y', strtotime($match->date)) : 'TBA',
                        'time' => $match->time ? date('g:i A', strtotime($match->time)) : '',
                        'status' => $match->status ?? 'pending',
                        'match_result' => $match->match_result
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
                        'venue' => [
                            'id' => $variation->venue_id?->id,
                            'name' => $variation->venue_id?->name ?? 'TBA'
                        ],
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
        $admin = StudentAccount::with('student')->where('role', 'Admin')->get();
        $palakasans = Palakasan::all();

        // Fetch sport matches with relationships
        $sportMatches = SportMatch::with([
            'assignedSport.sport',
            'teamA.college',
            'teamB.college',
            'matchVenue',
            'match_result.winning_team.college'
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
                    'category' => $match->assignedSport?->categories,
                    'teamA' => [
                        'id' => $match->teamA?->id,
                        'assigned_team_name' => $match->teamA?->assigned_team_name,
                        'college' => [
                            'id' => $match->teamA?->college?->id,
                            'name' => $match->teamA?->college?->name,
                            'logo' => $match->teamA?->college?->logo
                        ]
                    ],
                    'teamB' => [
                        'id' => $match->teamB?->id,
                        'assigned_team_name' => $match->teamB?->assigned_team_name,
                        'college' => [
                            'id' => $match->teamB?->college?->id,
                            'name' => $match->teamB?->college?->name,
                            'logo' => $match->teamB?->college?->logo
                        ]
                    ],
                    'matchVenue' => [
                        'name' => $match->matchVenue?->name ?? 'TBA'
                    ],
                    'date' => $match->date ? date('Y-m-d', strtotime($match->date)) : null,
                    'time' => $match->time ? date('H:i', strtotime($match->time)) : null,
                    'status' => $match->status ?? 'pending',
                    'game' => $match->game,
                    'match_result' => $match->match_result ? [
                        'id' => $match->match_result->id,
                        'teamA_score' => $match->match_result->teamA_score,
                        'teamB_score' => $match->match_result->teamB_score,
                        'winning_team_id' => $match->match_result->winning_team_id,
                        'losing_team_id' => $match->match_result->losing_team_id,
                        'winning_team' => [
                            'college' => [
                                'name' => $match->match_result->winning_team?->college?->name
                            ]
                        ]
                    ] : null
                ];
            });

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
            'sports' => $sports,
            'colleges' => $colleges,
            'latestPalakasan' => [
                'id' => $latestPalakasan?->id,
                'theme' => $latestPalakasan?->theme,
                'year' => $latestPalakasan?->year,
                'start_date' => $latestPalakasan?->start_date,
                'end_date' => $latestPalakasan?->end_date,
                'status' => $latestPalakasan->status,
                'tagline' => $latestPalakasan->tagline,
                'description' => $latestPalakasan->description,
                'sportMatches' => $sportMatches,
                'sportVariations' => $sportsVariations ?? collect(),
            ],
            'palakasans' => $palakasans,
            'assignedSports' => $assignedSports,
            'assignedTeams' => $assignedTeams,
            'overallResult' => $overallResult,
            'variationResult' => $variationResult,
            'facilitator' => $facilitator,
            'matchResults' => $matchResults,
            'matchRankings' => $matchRankings,
            'facilitatorSubmits' => $facilitatorSubmits,
            'facilitatorRankSubmits' => $facilitatorRankSubmits,
            'ffofacilitatorSubmits' => $ffofacilitatorSubmits,
            'thisPalakasan' => $thisPalakasan,
            'activeSports' => $activeSports,
            'activeTeams' => $activeTeams,
            'overallRankings' => $overallRankings,
            'sportsVariations' => $sportsVariations,
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
                    ->update(['status' => 'Ongoing']);
                
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

    public function emergencyCancel(Request $request, $id)
    {
        try {
            // Validate admin credentials
            $credentials = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            // Find admin account
            $admin = StudentAccount::where('username', $credentials['username'])
                ->where('role', 'Admin')
                ->first();

            if (!$admin || !password_verify($credentials['password'], $admin->password)) {
                return back()->withErrors(['message' => 'Invalid admin credentials']);
            }

            // Find and update Palakasan status
            $palakasan = Palakasan::findOrFail($id);
            $palakasan->status = 'cancelled';
            $palakasan->save();

            return back()->with('success', 'Palakasan has been cancelled successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Failed to cancel Palakasan: ' . $e->getMessage()]);
        }
    }

    public function concludePalakasan(Request $request)
    {
        try {
            DB::beginTransaction();

            // Get the latest Palakasan
            $palakasan = Palakasan::latest()->first();
            
            if (!$palakasan) {
                throw new \Exception('No active Palakasan found');
            }

            // Check if all sports are completed
            $incompleteSports = AssignedSports::where('palakasan_sport_id', $palakasan->id)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->exists();

            if ($incompleteSports) {
                throw new \Exception('Cannot conclude Palakasan: Some sports are still ongoing or pending');
            }

            // Update Palakasan status to completed
            $palakasan->status = 'completed';
            $palakasan->save();

            // Update all student accounts status to false
            DB::table('student_accounts')
                ->where('status', true)
                ->update(['status' => false]);

            DB::commit();

            return redirect()->back()->with('success', 'Palakasan concluded successfully. All student accounts have been deactivated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function continuePalakasan(Request $request, $palakasan)
    {
        try {
            // Validate admin credentials
            $credentials = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            // Find admin account
            $admin = StudentAccount::where('username', $credentials['username'])
                ->where('role', 'Admin')
                ->first();

            if (!$admin || !password_verify($credentials['password'], $admin->password)) {
                return back()->withErrors(['message' => 'Invalid admin credentials']);
            }

            DB::beginTransaction();

            // Find and update Palakasan status
            $palakasan = Palakasan::findOrFail($palakasan);
            
            if ($palakasan->status !== 'cancelled') {
                throw new \Exception('Only cancelled Palakasan events can be continued');
            }

            // Update Palakasan status back to live
            $palakasan->status = 'live';
            $palakasan->save();

            // Update all non-completed sports back to Ongoing
            AssignedSports::where('palakasan_sport_id', $palakasan->id)
                ->where('status', '!=', 'completed')
                ->update(['status' => 'Ongoing']);

            DB::commit();

            return back()->with('success', 'Palakasan has been successfully continued');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => 'Failed to continue Palakasan: ' . $e->getMessage()]);
        }
    }
}
