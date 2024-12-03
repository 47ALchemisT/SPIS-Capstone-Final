<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use App\Models\College;
use App\Models\Venue;
use App\Models\StudentAccount;
use App\Models\Student;
use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\Palakasan;
use App\Models\ActivityLog;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sports = Sport::all();
        $colleges = College::all();
        $venues = Venue::all();
        $account = StudentAccount::all();
        $students = Student::where('status', 'active')->get();
        $palakasans = Palakasan::all();
        $latestPalakasan = Palakasan::latest()->first();
        $assignedSports = AssignedSports::with('sport')->where('palakasan_sport_id', $latestPalakasan->id)->get();
        $assgnedTeams = AssignedTeams::with('college')->where('palakasan_id', $latestPalakasan->id)->get();

        // Get latest activity logs
        $recentActivities = ActivityLog::with(['causer', 'causer.student'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($log) {
                $causerName = 'System';
                $causerRole = null;

                if ($log->causer) {
                    if ($log->causer_type === 'App\\Models\\Admin') {
                        $causerName = $log->causer->name;
                        $causerRole = 'Administrator';
                    } else {
                        $causerName = $log->causer->student 
                            ? "{$log->causer->student->first_name} {$log->causer->student->last_name}"
                            : $log->causer->username;
                        $causerRole = $log->causer->role;
                    }
                }

                $date = Carbon::parse($log->created_at)->setTimezone('Asia/Manila');
                
                return [
                    'id' => $log->id,
                    'description' => $log->description,
                    'type' => $log->type,
                    'causer_name' => $causerName,
                    'causer_role' => $causerRole,
                    'created_at' => $date->diffForHumans(),
                    'properties' => $log->properties,
                ];
            });

        return Inertia::render('SSCAdmin/Dashboard', [
            'sports' => $sports,
            'colleges' => $colleges,
            'venues' => $venues,
            'account' => $account,
            'students' => $students,
            'palakasans' => $palakasans,
            'latestPalakasan' => $latestPalakasan,
            'recentActivities' => $recentActivities,
            'assignedSports' => $assignedSports,
            'assignedTeams' => $assgnedTeams
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The provided password does not match your current password.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function accountSettings()
    {
        $studentAccount = auth()->user();
        $student = $studentAccount->student;
        
        return Inertia::render('SSCAdmin/AccountSettings', [
            'user' => [
                'id' => $studentAccount->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'email' => $student->univ_email,
                'role' => $studentAccount->role,
                'created_at' => $studentAccount->created_at,
                'id_number' => $student->id_number,
                'college' => $student->college,
                'username' => $studentAccount->username
            ]
        ]);
    }
}
