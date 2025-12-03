<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentStop;
use App\Models\Driver;
use App\Models\Ambulance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    // ----------------------
    // Admin endpoints
    // ----------------------

    public function ensureForDriver($driverId)
    {
        $driver = Driver::findOrFail($driverId);

        $assignment = Assignment::where('driver_id', $driver->id)
            ->where('status', 'active')
            ->first();

        if (!$assignment) {
            $assignment = Assignment::create([
                'driver_id' => $driver->id,
                'ambulance_id' => $driver->ambulance_id,
                'status' => 'active',
                'started_at' => now(),
            ]);
        }

        return response()->json($assignment->load('stops'));
    }

    public function listStops($driverId)
    {
        $assignment = Assignment::where('driver_id', $driverId)
            ->where('status', 'active')
            ->with('stops')
            ->firstOrFail();

        return response()->json($assignment);
    }

    public function addStop(Request $request, $driverId)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'priority' => 'nullable|in:high,normal,low',
        ]);

        $assignment = Assignment::firstOrCreate(
            ['driver_id' => $driverId, 'status' => 'active'],
            ['ambulance_id' => optional(Driver::find($driverId))->ambulance_id, 'started_at' => now()]
        );

        // Prevent duplicate within 20m
        $existingStops = AssignmentStop::where('assignment_id', $assignment->id)
            ->where('status', '!=', 'canceled')
            ->get();

        foreach ($existingStops as $stop) {
            if ($this->distanceInMeters($stop->latitude, $stop->longitude, $request->latitude, $request->longitude) <= 20) {
                return response()->json(['message' => 'Duplicate stop within 20 meters'], 409);
            }
        }

        $nextSequence = (int) (AssignmentStop::where('assignment_id', $assignment->id)->max('sequence') ?? 0) + 1;

        $stop = AssignmentStop::create([
            'assignment_id' => $assignment->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'priority' => $request->priority ?? 'normal',
            'sequence' => $nextSequence,
        ]);

        // Mark ambulance as Out when a new stop is added
        if ($assignment->ambulance_id) {
            $ambulance = Ambulance::find($assignment->ambulance_id);
            if ($ambulance) {
                $ambulance->status = 'Out';
                $ambulance->save();
            }
        }

        // Touch assignment so polling clients notice change
        $assignment->touch();

        return response()->json($stop->fresh());
    }

    public function reorderStops(Request $request, $driverId)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:assignment_stops,id',
        ]);

        $assignment = Assignment::where('driver_id', $driverId)
            ->where('status', 'active')
            ->firstOrFail();

        DB::transaction(function () use ($request, $assignment) {
            $sequence = 1;
            foreach ($request->order as $stopId) {
                AssignmentStop::where('assignment_id', $assignment->id)
                    ->where('id', $stopId)
                    ->update(['sequence' => $sequence++]);
            }
        });

        $assignment->touch();

        return response()->json(['success' => true]);
    }

    public function removeStop($driverId, $stopId)
    {
        $assignment = Assignment::where('driver_id', $driverId)
            ->where('status', 'active')
            ->firstOrFail();

        $stop = AssignmentStop::where('assignment_id', $assignment->id)
            ->where('id', $stopId)
            ->firstOrFail();

        $stop->status = 'canceled';
        $stop->save();

        // If no remaining pending stops, set ambulance Available
        $remaining = AssignmentStop::where('assignment_id', $assignment->id)
            ->where('status', 'pending')
            ->count();
        if ($remaining === 0 && $assignment->ambulance_id) {
            $ambulance = Ambulance::find($assignment->ambulance_id);
            if ($ambulance) {
                $ambulance->status = 'Available';
                $ambulance->save();
            }
        }

        $assignment->touch();

        return response()->json(['success' => true]);
    }

    // ----------------------
    // Driver endpoints
    // ----------------------

    public function driverAssignment(Request $request)
    {
        $driver = auth('driver')->user();
        $assignment = Assignment::where('driver_id', $driver->id)
            ->where('status', 'active')
            ->with('stops')
            ->first();

        return response()->json($assignment);
    }

    public function driverReorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:assignment_stops,id',
        ]);

        $driver = auth('driver')->user();
        $assignment = Assignment::where('driver_id', $driver->id)
            ->where('status', 'active')
            ->firstOrFail();

        DB::transaction(function () use ($request, $assignment) {
            $sequence = 1;
            foreach ($request->order as $stopId) {
                AssignmentStop::where('assignment_id', $assignment->id)
                    ->where('id', $stopId)
                    ->update(['sequence' => $sequence++]);
            }
        });

        $assignment->touch();

        return response()->json(['success' => true]);
    }

    public function driverCompleteStop($stopId)
    {
        $driver = auth('driver')->user();
        $assignment = Assignment::where('driver_id', $driver->id)
            ->where('status', 'active')
            ->firstOrFail();

        $stop = AssignmentStop::where('assignment_id', $assignment->id)
            ->where('id', $stopId)
            ->firstOrFail();

        $stop->status = 'completed';
        $stop->completed_at = now();
        $stop->save();

        // If no remaining pending stops, set ambulance Available
        $remaining = AssignmentStop::where('assignment_id', $assignment->id)
            ->where('status', 'pending')
            ->count();
        if ($remaining === 0 && $assignment->ambulance_id) {
            $ambulance = Ambulance::find($assignment->ambulance_id);
            if ($ambulance) {
                $ambulance->status = 'Available';
                $ambulance->save();
            }
        }

        $assignment->touch();

        return response()->json(['success' => true]);
    }

    // ----------------------
    // Helpers
    // ----------------------

    private function distanceInMeters($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}


