<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Set timezone to Asia/Manila
        Carbon::setLocale('en');
        
        $query = ActivityLog::with(['causer'])->latest();

        // Search functionality
        if ($request->search) {
            $query->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('type', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        $logs = $query->paginate(15)->through(function ($log) {
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
                'date' => $date->format('M d, Y h:i A'),
            ];
        });

        $types = ActivityLog::distinct()->pluck('type');

        return Inertia::render('SSCAdmin/ActivityLogs', [
            'logs' => $logs,
            'types' => $types,
            'filters' => $request->only(['search', 'type']),
        ]);
    }
}
