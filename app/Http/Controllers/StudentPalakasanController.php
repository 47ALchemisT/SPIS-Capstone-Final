<?php
namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\Sport;
use App\Models\Student;
use App\Models\StudentPlayer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentPalakasanController extends Controller
{
    public function index()
    {
        $currentPalakasanSportsId = 1;

        $sports = Sport::all();
        $students = Student::all();
        $assignedSports = AssignedSports::with('sport')
        ->where('palakasan_sport_id', $currentPalakasanSportsId)
        ->get();

        $studentPlayers = StudentPlayer::with(['student', 'assignedSport.sport'])
        ->whereHas('assignedSport', function ($query) use ($currentPalakasanSportsId) {
            $query->where('palakasan_sport_id', $currentPalakasanSportsId);
        })
        ->get();

        return Inertia::render('CSHCommittee/StudentPlayer', [
            'assignedSports' => $assignedSports,
            'students' => $students,
            'sports' => $sports,
            'studentPlayers' => $studentPlayers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id', // Ensure student IDs exist in the students table
            'student_assigned_sport_id' => 'required|exists:assigned_sports,id', // Validate assigned sport ID
        ]);
    
        try {
            DB::beginTransaction();
    
            // Get the assigned sport instance for the provided ID
            $assignedSport = AssignedSports::findOrFail($request->student_assigned_sport_id);
    
            $errors = []; // To track any students who can't be assigned
    
            foreach ($request->student_ids as $studentId) {
                // Count the number of sports this student is already assigned to
                $assignedSportsCount = StudentPlayer::where('student_id', $studentId)->count();
    
                if ($assignedSportsCount >= 2) {
                    // If the student already has 2 sports assigned, add an error message
                    $errors[] = "Student ID $studentId cannot be assigned to more than 2 sports.";
                    continue;
                }
    
                // Create the assignment if the student has less than 2 sports
                StudentPlayer::firstOrCreate([
                    'student_id' => $studentId,
                    'student_assigned_sport_id' => $assignedSport->id,
                ]);
            }
    
            DB::commit();
    
            // Redirect with success and any warnings about unassignable students
            if (!empty($errors)) {
                return redirect()->back()->with('success', 'Some students were assigned successfully.')->with('warnings', $errors);
            }
    
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
    
    
public function show($sportId)
{
    // Fetch the sport details along with the assigned players
    $sport = AssignedSports::with('sport')
        ->where('id', $sportId)
        ->firstOrFail();

    $players = StudentPlayer::with('student')
        ->where('student_assigned_sport_id', $sportId)
        ->get();

    $students = Student::all();

    return Inertia::render('CSHCommittee/SportDetails', [
        'sport' => $sport,
        'players' => $players,
        'students' => $students
    ]);
}


}