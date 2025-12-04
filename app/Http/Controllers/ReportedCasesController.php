<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Report;
use Illuminate\Validation\Rule;

class ReportedCasesController extends Controller
{
    // ✅ 1. DISPLAY REPORTS (Reads from Supabase)
    public function index(Request $request)
    {
        $query = DB::connection('supabase')
            ->table('reports')
            ->leftJoin('users', 'reports.user_id', '=', 'users.id')
            ->select(
                'reports.*',                   // ✅ Selects latitude, longitude, and all other columns
                'users.name as reporter_name'  // ✅ Get user name
            );

        // Filter: Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reports.location', 'ilike', "%{$search}%")
                  ->orWhere('reports.incident_type', 'ilike', "%{$search}%")
                  ->orWhere('users.name', 'ilike', "%{$search}%");
            });
        }

        // Filter: Type
        if ($request->has('type') && $request->type != 'All Types') {
            $query->where('reports.incident_type', $request->type);
        }

        $cases = $query->orderBy('reports.incident_datetime', 'desc')->paginate(10);

        return view('admin.reports.reported-cases', [
            'cases' => $cases
        ]);
    }

    // ✅ 2. CREATE/SAVE REPORT (Handles both Mobile App & Admin Panel)
    // This aligns the Mobile App's single string with your new database columns
    public function store(Request $request)
{
    $rawLocation = $request->input('location');
    $reqLat = $request->input('latitude');
    $reqLng = $request->input('longitude');
    $address = $request->input('address');

    $finalLat = null;
    $finalLng = null;
    $finalAddress = null;

    if ($reqLat && $reqLng) {
        $finalLat = $reqLat;
        $finalLng = $reqLng;
        $finalAddress = $address;
    } elseif ($rawLocation) {
        if (preg_match('/^(-?\d+(\.\d+)?),\s*(-?\d+(\.\d+)?)$/', trim($rawLocation), $matches)) {
            $finalLat = $matches[1];
            $finalLng = $matches[3];
            $finalAddress = $rawLocation;
        } else {
            $finalAddress = $rawLocation;
        }
    }

    // Get reporter info if user is logged in
    $user = auth()->user();
    $reporterName = $user ? $user->name : $request->input('name', 'Guest');
    $reporterContact = $user ? $user->contact_number : $request->input('contact', '—');

    $id = DB::connection('supabase')->table('reports')->insertGetId([
        'user_id' => $user->id ?? null,
        'reporter_name' => $reporterName,
        'contact_number' => $reporterContact,
        'incident_type' => $request->input('type'),
        'description' => $request->input('description'),
        'latitude' => $finalLat,
        'longitude' => $finalLng,
        'location' => $finalAddress,
        'status' => 'PENDING',
        'created_at' => now(),
        'updated_at' => now(),
        'incident_datetime' => now(),
    ]);

    return response()->json(['success' => true, 'case_num' => $id]);
}

    public function updateStatus(Request $request, $id)
    {
        // Admins may only move a report forward in the workflow:
        // PENDING -> ACKNOWLEDGED -> ON_GOING
        // They cannot set RESOLVED or DECLINED directly.
        $request->validate([
            'status' => ['required', Rule::in(['ACKNOWLEDGED', 'ON_GOING'])],
        ]);

        // Fetch current report status from Supabase
        $report = DB::connection('supabase')
            ->table('reports')
            ->where('id', $id)
            ->first();

        if (!$report) {
            return back()->with('error', 'Report not found.');
        }

        $currentStatus = $report->status ?? 'PENDING';
        $targetStatus = $request->status;

        $isValid = false;

        // Enforce one-way transitions: PENDING -> ACKNOWLEDGED -> ON_GOING
        if ($currentStatus === 'PENDING' && $targetStatus === 'ACKNOWLEDGED') {
            $isValid = true;
        } elseif ($currentStatus === 'ACKNOWLEDGED' && $targetStatus === 'ON_GOING') {
            $isValid = true;
        }

        if (!$isValid) {
            return back()->with('error', 'Invalid status transition.');
        }

        $updated = DB::connection('supabase')
            ->table('reports')
            ->where('id', $id)
            ->update([
                'status'     => $targetStatus,
                'updated_at' => now(),
            ]);

        if (!$updated) {
            return back()->with('error', 'Failed to update report status.');
        }

        return back()->with('success', "Status updated to {$targetStatus}");
    }
}