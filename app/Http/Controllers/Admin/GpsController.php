<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ambulance;
use Illuminate\Support\Facades\Cache;

class GpsController extends Controller
{
    public function index()
    {
        $ambulances = Ambulance::all();
        return view('admin.gps.index', compact('ambulances'));
    }

    public function setDestination(Request $request)
    {
        $request->validate([
            'ambulance_id' => 'required|exists:ambulances,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $ambulance = Ambulance::find($request->ambulance_id);
        $ambulance->destination_latitude = $request->latitude;
        $ambulance->destination_longitude = $request->longitude;
        $ambulance->destination_updated_at = now();
        $ambulance->status = 'Out'; // 🧠 Set to Out automatically
        $ambulance->save();

        return response()->json(['message' => 'Destination set successfully.']);
    }

    /**
     * Check for pending geofence notifications
     */
    public function checkGeofenceNotifications()
    {
        // Get all pending geofence notifications from cache
        $notifications = [];
        
        // Check for active cases and see if they have notifications (both pickup and destination)
        $cases = \App\Models\EmergencyCase::where('status', '!=', 'Completed')->get();
        
        foreach ($cases as $case) {
            // Check pickup notification
            if ($case->latitude && $case->longitude) {
                $pickupNotificationKey = "geofence_notification:case_{$case->case_num}:pickup";
                $pickupNotification = Cache::get($pickupNotificationKey);
                if ($pickupNotification) {
                    $notifications[] = $pickupNotification;
                }
            }
            
            // Check destination notification
            if ($case->to_go_to_latitude && $case->to_go_to_longitude) {
                $destNotificationKey = "geofence_notification:case_{$case->case_num}:destination";
                $destNotification = Cache::get($destNotificationKey);
                if ($destNotification) {
                    $notifications[] = $destNotification;
                }
            }
        }
        
        return response()->json([
            'notifications' => $notifications,
            'count' => count($notifications)
        ]);
    }

    /**
     * Acknowledge and dismiss a geofence notification
     */
    public function acknowledgeGeofenceNotification(Request $request)
    {
        $request->validate([
            'case_num' => 'required|integer|exists:cases,case_num',
            'location_type' => 'required|in:pickup,destination'
        ]);
        
        $notificationKey = "geofence_notification:case_{$request->case_num}:{$request->location_type}";
        Cache::forget($notificationKey);
        
        return response()->json(['success' => true, 'message' => 'Notification acknowledged']);
    }
}
