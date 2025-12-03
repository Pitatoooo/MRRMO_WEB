<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmergencyCase;
use App\Models\Ambulance;
use App\Models\CaseAmbulance;
use App\Models\CaseRejection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaseController extends Controller
{
    /**
     * Store a newly created case.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0|max:120',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'required|string',
            'destination' => 'required|string',
            'type' => 'nullable|string|max:255',
            'ambulance_ids' => 'required|array|min:1',
            'ambulance_ids.*' => 'exists:ambulances,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'destination_latitude' => 'required|numeric',
            'destination_longitude' => 'required|numeric',
            'timestamp' => 'required|date',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // Determine priority based on incident type
                $priority = 'Medium'; // Default for Pick up and Delivery
                if ($request->type === 'Accident') {
                    $priority = 'High'; // Emergency cases are high priority
                }
                
                // Create the case
                $case = EmergencyCase::create([
                    'status' => 'Pending',
                    'name' => $request->name,
                    'contact' => $request->contact,
                    'age' => $request->age,
                    'date_of_birth' => $request->date_of_birth,
                    'type' => $request->type,
                    'priority' => $priority,
                    'address' => $request->address,
                    'destination' => $request->destination,
                    'to_go_to_address' => $request->destination,
                    'to_go_to_latitude' => $request->destination_latitude,
                    'to_go_to_longitude' => $request->destination_longitude,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'timestamp' => $request->timestamp,
                    'contact_number' => $request->contact,
                    'driver_accepted' => false,
                    'notification_sent' => false,
                ]);

                // Assign multiple ambulances to the case
                foreach ($request->ambulance_ids as $ambulanceId) {
                    $ambulance = Ambulance::findOrFail($ambulanceId);
                    $driver = $ambulance->driver;

                    // Create case-ambulance assignment
                    CaseAmbulance::create([
                        'case_num' => $case->case_num,
                        'ambulance_id' => $ambulanceId,
                        'driver_accepted' => false,
                        'notification_sent' => true,
                        'assigned_at' => now(),
                    ]);

                    // Update ambulance status to "Out" if not already on a mission
                    if ($ambulance->status !== 'Out') {
                        $ambulance->update([
                            'status' => 'Out',
                            'destination_latitude' => $request->destination_latitude,
                            'destination_longitude' => $request->destination_longitude,
                        ]);
                    }
                }

                // Load the case with its assigned ambulances for response
                $case->load('assignedAmbulances');

                return response()->json([
                    'success' => true,
                    'message' => 'Case created successfully and assigned to ' . count($request->ambulance_ids) . ' ambulance(s)',
                    'case_num' => $case->case_num,
                    'case' => $case->fresh()->load('assignedAmbulances')
                ]);
            });

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating case: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of cases.
     */
    public function index()
    {
        $cases = EmergencyCase::with('ambulance')
            ->where('status', '!=', 'Completed')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // If it's an AJAX request, return JSON
        if (request()->ajax()) {
            return response()->json($cases);
        }
        
        return view('admin.cases.index', compact('cases'));
    }

    /**
     * Get case count for each ambulance
     */
    public function getAmbulanceCaseCounts()
    {
        $caseCounts = EmergencyCase::selectRaw('ambulance_id, COUNT(*) as case_count')
            ->whereNotNull('ambulance_id')
            ->groupBy('ambulance_id')
            ->pluck('case_count', 'ambulance_id');
            
        return response()->json($caseCounts);
    }

    /**
     * Get all ambulances with their details and case counts for re-deployment
     */
    public function getAmbulancesForRedeployment()
    {
        $ambulances = Ambulance::all();
        
        // Get case counts for each ambulance
        $caseCounts = EmergencyCase::selectRaw('ambulance_id, COUNT(*) as case_count')
            ->whereNotNull('ambulance_id')
            ->whereIn('status', ['Pending', 'Accepted']) // Only count active cases
            ->groupBy('ambulance_id')
            ->pluck('case_count', 'ambulance_id');
        
        // Add case counts to ambulance data
        $ambulancesWithCounts = $ambulances->map(function ($ambulance) use ($caseCounts) {
            return [
                'id' => $ambulance->id,
                'driver' => $ambulance->driver,
                'status' => $ambulance->status ?? 'Available',
                'case_count' => $caseCounts->get($ambulance->id, 0)
            ];
        });
        
        return response()->json($ambulancesWithCounts);
    }

    /**
     * Display the specified case.
     */
    public function show(EmergencyCase $case)
    {
        $case->load('ambulance');
        
        // If it's an AJAX request, return JSON
        if (request()->ajax()) {
            return response()->json($case);
        }
        
        return view('admin.cases.show', compact('case'));
    }

    /**
     * Remove the specified case from storage.
     */
    public function destroy(EmergencyCase $case)
    {
        try {
            $caseNum = $case->case_num;
            $case->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Case deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting case: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update case status (for driver acceptance)
     */
    public function updateStatus(Request $request, EmergencyCase $case)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,Accepted,In Progress,Completed,Cancelled',
            'driver_accepted' => 'boolean',
            'notification_sent' => 'boolean'
        ]);

        try {
            $updateData = [
                'status' => $request->status,
                'driver_accepted' => $request->driver_accepted ?? false,
            ];
            
            if ($request->has('notification_sent')) {
                $updateData['notification_sent'] = $request->notification_sent;
            }
            
            $case->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Case status updated successfully',
                'case' => $case->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating case: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get driver notifications for cases
     */
    public function getDriverNotifications(Request $request)
    {
        $driver = auth('driver')->user(); // Get authenticated driver using driver guard
        
        // Get cases assigned to this driver's ambulance through the pivot table
        $caseNums = CaseAmbulance::where('ambulance_id', $driver->ambulance_id)
            ->where('driver_accepted', false)
            ->where('notification_sent', true)
            ->pluck('case_num');
            
        $cases = EmergencyCase::with(['assignedAmbulances'])
            ->whereIn('case_num', $caseNums)
            ->where('status', '!=', 'Completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($cases);
    }

    /**
     * Get all cases for driver (including accepted ones, excluding rejected and completed)
     */
    public function getAllDriverCases(Request $request)
    {
        $driver = auth('driver')->user(); // Get authenticated driver using driver guard
        
        // Get cases assigned to this driver's ambulance through the pivot table
        $caseNums = CaseAmbulance::where('ambulance_id', $driver->ambulance_id)
            ->where('notification_sent', true)
            ->pluck('case_num');
            
        $cases = EmergencyCase::with(['assignedAmbulances'])
            ->whereIn('case_num', $caseNums)
            ->where('status', '!=', 'Rejected')
            ->where('status', '!=', 'Completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($cases);
    }

    /**
     * Driver accepts a case
     */
    public function acceptCase(Request $request, EmergencyCase $case)
    {
        try {
            $driver = auth('driver')->user();
            
            // Check if this case is assigned to the driver's ambulance
            $caseAmbulance = CaseAmbulance::where('case_num', $case->case_num)
                ->where('ambulance_id', $driver->ambulance_id)
                ->first();
                
            if (!$caseAmbulance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Case not assigned to your ambulance'
                ], 403);
            }

            return DB::transaction(function () use ($case, $caseAmbulance, $driver) {
                // Update the specific case-ambulance assignment as accepted
                $caseAmbulance->update([
                    'driver_accepted' => true,
                    'accepted_at' => now(),
                ]);

                // Update the main case status
                $case->update([
                    'status' => 'Accepted',
                    'driver_accepted' => true,
                    'ambulance_id' => $driver->ambulance_id, // Set the accepting ambulance as primary
                    'driver' => $driver->name,
                ]);

                // Remove assignments from other ambulances (cancel other notifications)
                CaseAmbulance::where('case_num', $case->case_num)
                    ->where('ambulance_id', '!=', $driver->ambulance_id)
                    ->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Case accepted successfully. Other ambulance assignments have been cancelled.',
                    'case' => $case->fresh()->load('assignedAmbulances')
                ]);
            });

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error accepting case: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Driver rejects a case
     */
    public function rejectCase(Request $request, EmergencyCase $case)
    {
        try {
            $driver = auth('driver')->user();
            
            // Check if this case is assigned to the driver's ambulance through the pivot table
            $caseAmbulance = CaseAmbulance::where('case_num', $case->case_num)
                ->where('ambulance_id', $driver->ambulance_id)
                ->where('driver_accepted', false)
                ->first();

            if (!$caseAmbulance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Case not found or already processed'
                ], 404);
            }

            DB::beginTransaction();

            // Record the rejection
            CaseRejection::create([
                'case_num' => $case->case_num,
                'ambulance_id' => $driver->ambulance_id,
                'driver_name' => $driver->name,
                'rejected_at' => now()
            ]);

            // Remove the case assignment for this driver
            $caseAmbulance->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Case rejected',
                'case_num' => $case->case_num
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting case: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cases that need re-deployment (no remaining assigned drivers)
     */
    public function getCasesNeedingRedeployment()
    {
        // Get all pending cases
        $pendingCases = EmergencyCase::where('status', 'Pending')
            ->where('driver_accepted', false)
            ->get();

        $casesNeedingRedeployment = [];

        foreach ($pendingCases as $case) {
            // Check if this case has any remaining assigned drivers
            $remainingDrivers = CaseAmbulance::where('case_num', $case->case_num)
                ->where('driver_accepted', false)
                ->count();

            if ($remainingDrivers === 0) {
                // Get list of drivers who rejected this case
                $rejections = CaseRejection::with('ambulance')
                    ->where('case_num', $case->case_num)
                    ->orderBy('rejected_at', 'desc')
                    ->get();

                $casesNeedingRedeployment[] = [
                    'case' => $case,
                    'remaining_drivers' => 0,
                    'needs_redeployment' => true,
                    'rejections' => $rejections->map(function ($rejection) {
                        return [
                            'driver_name' => $rejection->driver_name,
                            'ambulance_id' => $rejection->ambulance_id,
                            'rejected_at' => $rejection->rejected_at->format('Y-m-d H:i:s'),
                        ];
                    })
                ];
            }
        }

        return response()->json($casesNeedingRedeployment);
    }

    /**
     * Get recent driver actions (accepts/rejects) for admin notifications
     */
    public function getRecentDriverActions()
    {
        // Get recent accepts (last 10 minutes to be more inclusive)
        $recentAccepts = CaseAmbulance::with(['emergencyCase', 'ambulance'])
            ->where('driver_accepted', true)
            ->where('accepted_at', '>=', now()->subMinutes(10))
            ->orderBy('accepted_at', 'desc')
            ->get()
            ->map(function ($caseAmbulance) {
                return [
                    'action' => 'accepted',
                    'case_num' => $caseAmbulance->case_num,
                    'case_name' => $caseAmbulance->emergencyCase->name ?? 'Unknown',
                    'driver_name' => is_string($caseAmbulance->ambulance->driver) ? $caseAmbulance->ambulance->driver : ($caseAmbulance->ambulance->driver->name ?? 'Unknown'),
                    'ambulance_id' => $caseAmbulance->ambulance_id,
                    'timestamp' => $caseAmbulance->accepted_at->timestamp * 1000, // Convert to milliseconds for JS
                ];
            });

        // Get recent rejections (last 10 minutes to be more inclusive)
        $recentRejects = CaseRejection::with(['emergencyCase', 'ambulance'])
            ->where('rejected_at', '>=', now()->subMinutes(10))
            ->orderBy('rejected_at', 'desc')
            ->get()
            ->map(function ($rejection) {
                return [
                    'action' => 'rejected',
                    'case_num' => $rejection->case_num,
                    'case_name' => $rejection->emergencyCase->name ?? 'Unknown',
                    'driver_name' => $rejection->driver_name,
                    'ambulance_id' => $rejection->ambulance_id,
                    'timestamp' => $rejection->rejected_at->timestamp * 1000, // Convert to milliseconds for JS
                ];
            });

        // Combine and sort by timestamp
        $allActions = $recentAccepts->concat($recentRejects)->sortByDesc('timestamp')->values();

        return response()->json($allActions);
    }

    /**
     * Re-deploy a case to selected ambulances
     */
    public function redeployCase(Request $request, EmergencyCase $case)
    {
        $request->validate([
            'ambulance_ids' => 'required|array|min:1',
            'ambulance_ids.*' => 'exists:ambulances,id'
        ]);

        try {
            DB::beginTransaction();

            // Clear any existing assignments for this case
            CaseAmbulance::where('case_num', $case->case_num)->delete();

            // Create new assignments
            foreach ($request->ambulance_ids as $ambulanceId) {
                CaseAmbulance::create([
                    'case_num' => $case->case_num,
                    'ambulance_id' => $ambulanceId,
                    'driver_accepted' => false,
                    'notification_sent' => true,
                    'assigned_at' => now()
                ]);

                // Update ambulance status
                Ambulance::where('id', $ambulanceId)->update([
                    'status' => 'Out',
                    'destination_latitude' => $case->to_go_to_latitude,
                    'destination_longitude' => $case->to_go_to_longitude,
                ]);
            }

            // Reset case status
            $case->update([
                'status' => 'Pending',
                'driver_accepted' => false,
                'ambulance_id' => null,
                'driver' => null
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Case re-deployed successfully',
                'case' => $case->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error re-deploying case: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin completes a case
     */
    public function completeCaseAsAdmin(Request $request, EmergencyCase $case)
    {
        try {
            // Store old status for logging
            $oldStatus = $case->status;
            
            // Update case status to completed
            $case->update([
                'status' => 'Completed',
                'completed_at' => now(),
            ]);

            // Update ambulance status back to Available if assigned
            if ($case->ambulance_id) {
                $ambulance = $case->ambulance;
                if ($ambulance) {
                    $ambulance->update([
                        'status' => 'Available',
                        'destination_latitude' => null,
                        'destination_longitude' => null,
                    ]);
                }
            }

            // Log that admin completed the case (for driver notification purposes)
            \Log::info("Admin completed case #{$case->case_num}", [
                'completed_by' => 'admin',
                'ambulance_id' => $case->ambulance_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Case completed successfully by admin',
                'case' => $case->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error completing case: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Driver completes a case
     */
    public function completeCase(Request $request, EmergencyCase $case)
    {
        try {
            // Verify the case is assigned to the authenticated driver's ambulance
            $driver = auth('driver')->user();
            if ($case->ambulance_id !== $driver->ambulance_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Case not assigned to your ambulance'
                ], 403);
            }

            // Update case status to completed
            $case->update([
                'status' => 'Completed',
                'driver_accepted' => true,
                'completed_at' => now(),
            ]);

            // Update ambulance status back to Available
            $ambulance = $case->ambulance;
            if ($ambulance) {
                $ambulance->update([
                    'status' => 'Available',
                    'destination_latitude' => null,
                    'destination_longitude' => null,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Case completed successfully',
                'case' => $case->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error completing case: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get completed cases for logs
     */
    public function getCompletedCases()
    {
        try {
            $cases = EmergencyCase::with('ambulance')
                ->where('status', 'Completed')
                ->orderBy('completed_at', 'desc')
                ->get();

            return response()->json($cases);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load completed cases',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}