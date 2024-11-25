<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\Palakasan;
use App\Models\StudentAccount;
use App\Models\Venue;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FacilitatorController extends Controller
{   
    public function index($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
            $facilitator = StudentAccount::with('student')->findOrFail($id);
            $latestPalakasan = Palakasan::latest()->first();
            
            if (!$latestPalakasan) {
                return Inertia::render('Facilitator/FacilitatorDashboard', [
                    'facilitator' => $facilitator,
                    'message' => 'No Palakasan event found.'
                ]);
            }

            // Enable query logging
            DB::enableQueryLog();

            $assignedSports = AssignedSports::where('facilitator_id', $id)
                ->where('palakasan_sport_id', $latestPalakasan->id)
                ->with(['sport', 'palakasan'])
                ->get();

            if ($assignedSports->isEmpty()) {
                // Check raw data without relationships
                $rawAssignedSports = DB::table('assigned_sports')
                    ->where('facilitator_id', $id)
                    ->where('palakasan_sport_id', $latestPalakasan->id)
                    ->get();
            }

            return Inertia::render('Facilitator/FacilitatorDashboard', [
                'facilitator' => $facilitator,
                'assignedSports' => $assignedSports,
                'palakasan' => $latestPalakasan,
                'debug' => [
                    'facilitatorId' => $id,
                    'palakasanId' => $latestPalakasan->id,
                    'assignedSportsCount' => $assignedSports->count()
                ]
            ]);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            Log::error('Decryption failed: ' . $e->getMessage());
            abort(403, 'Invalid or tampered facilitator ID');
        } catch (\Exception $e) {
            Log::error('Error in FacilitatorController: ' . $e->getMessage());
            throw $e;
        }
    }
}