<?php

namespace App\Http\Controllers;

use App\Models\Points;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PointsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $points = Points::all(); // Fetch from the database
        return Inertia::render('SSCAdmin/Points', [
            'points' => $points, // Pass to the view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input fields
        $validated = $request->validate([
            'type' => 'required|string|in:Major,Minor',
            'rankPoints' => 'required|array|min:1',
            'rankPoints.*.rank' => 'required|string|max:255',
            'rankPoints.*.points' => 'required|numeric',
        ]);
        
        // Create a new Points record for each rank-points pair
        foreach ($validated['rankPoints'] as $rankPoint) {
            Points::create([
                'type' => $validated['type'],
                'rank' => $rankPoint['rank'],
                'points' => $rankPoint['points'],
            ]);
        }
        
        // Redirect with a success message
        return redirect()->route('points.index')->with('success', 'Points successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $points = Points::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string|in:Major,Minor',
            'rankPoints' => 'required|array|min:1',
            'rankPoints.*.rank' => 'required|string|max:255',
            'rankPoints.*.points' => 'required|numeric',
        ]);

        // Update the existing record
        $points->update([
            'type' => $validated['type'],
            'rank' => $validated['rankPoints'][0]['rank'],
            'points' => $validated['rankPoints'][0]['points'],
        ]);

        // Create new records for any additional rank-points pairs
        for ($i = 1; $i < count($validated['rankPoints']); $i++) {
            Points::create([
                'type' => $validated['type'],
                'rank' => $validated['rankPoints'][$i]['rank'],
                'points' => $validated['rankPoints'][$i]['points'],
            ]);
        }

        return redirect()->route('points.index')->with('success', 'Points updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Points $points)
    {
        $points->delete();
    
        return redirect()->route('points.index')->with('success', 'Points deleted successfully.');
    }
}
