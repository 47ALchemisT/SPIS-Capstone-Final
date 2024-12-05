<?php

namespace App\Http\Controllers;

use App\Models\StudentAccount;
use App\Models\Student;
use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\Palakasan;
use App\Models\ComHeadColleges;
use App\Models\StudentPlayer;
use App\Models\SportMatch;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CHSDashboardController extends Controller
{
    public function index($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
            $comHead = StudentAccount::with(['student', 'com_head'])->findOrFail($id);
            $latestPalakasan = Palakasan::latest()->first();
            
            if (!$latestPalakasan) {
                return Inertia::render('CSHCommittee/Dashboard', [
                    'comHead' => $comHead,
                    'message' => 'No Palakasan event found.'
                ]);
            }

            // Get assigned sports for the current Palakasan
            $assignedSports = AssignedSports::where('palakasan_sport_id', $latestPalakasan->id)
                ->with(['sport', 'palakasan'])
                ->get()
                ->map(function ($sport) use ($id) {
                    // Get players assigned to this sport for the committee head's team
                    $players = StudentPlayer::where('student_assigned_sport_id', $sport->id)
                        ->whereHas('assignedTeam', function ($query) use ($id) {
                            $query->whereHas('comHeadColleges', function ($q) use ($id) {
                                $q->where('com_head_id', $id);
                            });
                        })
                        ->with(['student' => function ($query) {
                            $query->select('id', 'first_name', 'last_name');
                        }])
                        ->get();
                    
                    return array_merge($sport->toArray(), [
                        'assigned_players' => $players,
                        'player_count' => $players->count()
                    ]);
                });

            // Calculate total assigned students
            $totalAssignedStudents = $assignedSports->sum('player_count');

            $assignedTeams = AssignedTeams::where('palakasan_id', $latestPalakasan->id)
                ->with('college')
                ->get()
                ->map(function ($team) use ($latestPalakasan) {
                    // Check if this team is already assigned to a committee head
                    $isAssigned = ComHeadColleges::where('assigned_team_id', $team->id)
                        ->where('palakasan_sport_id', $latestPalakasan->id)
                        ->exists();
                    
                    return array_merge($team->toArray(), ['is_assigned' => $isAssigned]);
                });

            // Get the committee head's assigned college if any
            $assignedCollege = ComHeadColleges::where('com_head_id', $id)
                ->where('palakasan_sport_id', $latestPalakasan->id)
                ->with('assigned_team.college')
                ->first();

            // Initialize empty collections
            $upcomingSchedules = collect([]);
            $students = collect([]);

            if ($assignedCollege && $assignedCollege->assigned_team) {
                // Get upcoming schedules for the committee head's college
                $upcomingSchedules = SportMatch::with([
                    'teamA.college',
                    'teamB.college',
                    'matchVenue',
                    'assignedSport.sport',
                    'match_result.winning_team.college',
                    'match_result.losing_team.college'
                ])
                    ->where(function($query) use ($assignedCollege) {
                        $query->where('teamA_id', $assignedCollege->assigned_team_id)
                              ->orWhere('teamB_id', $assignedCollege->assigned_team_id);
                    })
                    ->where('date', '>=', now()->toDateString())
                    ->orderBy('date', 'asc')
                    ->orderBy('time', 'asc')
                    ->get();

                // Process the matches only if there are any
                if ($upcomingSchedules->isNotEmpty()) {
                    $upcomingSchedules = $upcomingSchedules->map(function($match) use ($assignedCollege) {
                        $teamA = AssignedTeams::with('college')->find($match->teamA_id);
                        $teamB = AssignedTeams::with('college')->find($match->teamB_id);

                        // If teamB is the committee head's team, swap positions
                        if ($match->teamB_id === $assignedCollege->assigned_team_id) {
                            $temp = $teamA;
                            $teamA = $teamB;
                            $teamB = $temp;
                        }

                        $match->teamA = $teamA;
                        $match->teamB = $teamB;

                        // Add status based on match result
                        $match->status = $match->match_result ? 'Completed' : 'Pending';

                        return $match;
                    });
                }

                // Get students for the assigned college
                if ($assignedCollege->assigned_team->college) {
                    $students = Student::where('status', 'active')
                        ->where('college', $assignedCollege->assigned_team->college->name)
                        ->get();
                }
            }

            // Get all assigned players for the committee head's team
            $assignedPlayers = StudentPlayer::whereHas('assignedTeam', function ($query) use ($id) {
                    $query->whereHas('comHeadColleges', function ($q) use ($id) {
                        $q->where('com_head_id', $id);
                    });
                })
                ->with(['student' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                }])
                ->get();

            // Log for debugging
            \Log::info('Match Data:', [
                'matches' => $upcomingSchedules->map(function($match) {
                    return [
                        'id' => $match->id,
                        'teamA' => [
                            'name' => $match->teamA->assigned_team_name ?? 'TBD',
                            'college' => $match->teamA->college->name ?? 'TBD'
                        ],
                        'teamB' => [
                            'name' => $match->teamB->assigned_team_name ?? 'TBD',
                            'college' => $match->teamB->college->name ?? 'TBD'
                        ]
                    ];
                })->toArray()
            ]);

            return Inertia::render('CSHCommittee/Dashboard', [
                'comHead' => $comHead,
                'assignedTeams' => $assignedTeams,
                'assignedSports' => $assignedSports,
                'palakasan' => $latestPalakasan,
                'assignedCollege' => $assignedCollege,
                'students' => $students,
                'assignedPlayers' => $assignedPlayers,
                'upcomingSchedules' => $upcomingSchedules,
                'totalAssignedStudents' => $totalAssignedStudents
            ]);

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            Log::error('Decryption failed: ' . $e->getMessage());
            abort(403, 'Invalid or tampered CHS ID');
        } catch (\Exception $e) {
            Log::error('Error in CHSDashboardController: ' . $e->getMessage());
            throw $e;
        }
    }

    public function assignCollege(Request $request)
    {
        try {
            $validated = $request->validate([
                'assigned_team_id' => 'required|exists:assigned_teams,id',
                'com_head_id' => 'required|exists:student_accounts,id',
                'palakasan_sport_id' => 'required|exists:palakasans,id',
            ]);

            // Check if the committee head already has a college assigned for this palakasan
            $existingAssignment = ComHeadColleges::where('com_head_id', $validated['com_head_id'])
                ->where('palakasan_sport_id', $validated['palakasan_sport_id'])
                ->first();

            if ($existingAssignment) {
                $existingAssignment->update([
                    'assigned_team_id' => $validated['assigned_team_id']
                ]);
            } else {
                ComHeadColleges::create($validated);
            }

            return redirect()->back()->with('success', 'College assigned successfully');
        } catch (\Exception $e) {
            Log::error('Error assigning college to committee head: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to assign college');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',  // Ensure student IDs exist in the students table
            'sport_id' => 'required|exists:assigned_sports,id', // Validate assigned sport ID
            'assigned_team_id' => 'required|exists:assigned_teams,id', // Validate assigned team ID
        ]);
    
        try {
            DB::beginTransaction();
    
            // Get the assigned sport instance for the provided ID
            $assignedSport = AssignedSports::findOrFail($request->sport_id);
    
            // Loop through each student ID and create or update the player assignment
            foreach ($request->student_ids as $studentId) {
                // Using firstOrCreate to avoid duplicates in the StudentPlayer table
                StudentPlayer::firstOrCreate([
                    'student_id' => $studentId,
                    'student_assigned_sport_id' => $assignedSport->id,
                    'assigned_team_id' => $request->assigned_team_id,
                ]);
            }
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Students added as players successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for troubleshooting
            Log::error('Failed to create student players:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to add students as players.');
        }
    }

    public function destroy(AssignedSports $sport, $playerId)
    {
        try {
            $player = StudentPlayer::where('id', $playerId)
                ->where('student_assigned_sport_id', $sport->id)
                ->firstOrFail();

            $player->delete();

            return response()->json([
                'message' => 'Player removed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing player: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error removing player'
            ], 500);
        }
    }
}
