<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MissionVision;

class MissionVisionController extends Controller
{
public function store(Request $request)
{
    $validated = $request->validate([
        'mission' => 'nullable|string',
        'vision' => 'nullable|string',
    ]);

    // Get the first (or only) record
    $mv = MissionVision::first();

    // If no record exists yet, create a new one
    if (!$mv) {
        $mv = new MissionVision();
    }

    // Only update if user entered something
    if ($request->filled('mission')) {
        $mv->mission = $request->mission;
    }

    if ($request->filled('vision')) {
        $mv->vision = $request->vision;
    }

    $mv->save();

    return redirect()->back()->with('success', 'Mission and/or Vision updated!');
}

}
