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
        'student_ids.*' => 'exists:students,id',
       'student_assigned_sport_id' => 'required|exists:assigned_sports,id', 
    ]);

    try {
        DB::beginTransaction();

        foreach ($request->student_ids as $studentId) {
            StudentPlayer::firstOrCreate([
                'student_id' => $studentId,
                'student_assigned_sport_id' => $request->student_assigned_sport_id,
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

}