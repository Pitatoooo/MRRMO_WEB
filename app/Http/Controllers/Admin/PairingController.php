<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Driver;
use App\Models\Medic;
use App\Models\Ambulance;
use App\Models\DriverMedicPairing;
use App\Models\DriverAmbulancePairing;
use App\Models\PairingLog;

class PairingController extends Controller
{
    public function index(Request $request)
    {
        $query = DriverMedicPairing::with(['driver', 'medic'])
            ->where('status', 'active');
            
        $ambulanceQuery = DriverAmbulancePairing::with(['driver', 'ambulance'])
            ->where('status', 'active');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('driver', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('medic', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
            
            $ambulanceQuery->whereHas('driver', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('ambulance', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        
        // Filter by driver
        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
            $ambulanceQuery->where('driver_id', $request->driver_id);
        }
        
        $driverMedicPairings = $query->orderBy('pairing_date', 'desc')->get()
            ->filter(function($pairing) {
                return $pairing->driver && $pairing->medic; // Only include pairings where both driver and medic exist
            });
        $driverAmbulancePairings = $ambulanceQuery->orderBy('pairing_date', 'desc')->get()
            ->filter(function($pairing) {
                return $pairing->driver && $pairing->ambulance; // Only include pairings where both driver and ambulance exist
            });
        
        // Group driver-medic pairings by driver and date for better display
        $groupedDriverMedicPairings = $driverMedicPairings->groupBy(function($pairing) {
            return $pairing->driver_id . '_' . $pairing->pairing_date->format('Y-m-d');
        });
        
        // Group driver-ambulance pairings by ambulance and date so multiple operators appear together
        $groupedDriverAmbulancePairings = $driverAmbulancePairings->groupBy(function($pairing) {
            return $pairing->ambulance_id . '_' . $pairing->pairing_date->format('Y-m-d');
        });

        // Build full operator lists per ambulance+date group (ignoring driver filter)
        $allAmbulancePairsQuery = DriverAmbulancePairing::with(['driver', 'ambulance'])
            ->where('status', 'active');
        if ($request->filled('search')) {
            $search = $request->search;
            $allAmbulancePairsQuery->whereHas('driver', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('ambulance', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        $driverAmbulancePairsAll = $allAmbulancePairsQuery->get();
        $allAmbulancePairs = $driverAmbulancePairsAll->groupBy(function($pairing) {
            return $pairing->ambulance_id . '_' . $pairing->pairing_date->format('Y-m-d');
        });
        $groupOperators = [];
        foreach ($allAmbulancePairs as $key => $pairs) {
            $groupOperators[$key] = $pairs->pluck('driver')->unique('id')->values();
        }
        
        // Get drivers, medics, and ambulances for filter dropdowns and side panel
        $drivers = Driver::where('status', 'active')->get();
        $medics = Medic::where('status', 'active')->get();
        $ambulances = Ambulance::all();

        // Compute currently paired resources for the selected date (used to disable options in side panel)
        // Default the working date to the most recent pairing date so UI constraints are visible without user input
        $selectedDate = $request->get('pairing_date');
        if (!$selectedDate) {
            $latestAmbDate = DriverAmbulancePairing::max('pairing_date');
            $latestMedDate = DriverMedicPairing::max('pairing_date');
            $latest = max($latestAmbDate ?? '1970-01-01', $latestMedDate ?? '1970-01-01');
            $selectedDate = $latest && $latest !== '1970-01-01' ? $latest : date('Y-m-d');
        }
        $pairedMedics = DriverMedicPairing::where('pairing_date', $selectedDate)
            ->where('status', 'active')
            ->pluck('medic_id')
            ->unique()
            ->toArray();
        // Drivers already paired in Driver-Medic for the selected date (active)
        $driversPairedWithMedics = DriverMedicPairing::where('pairing_date', $selectedDate)
            ->where('status', 'active')
            ->pluck('driver_id')
            ->unique()
            ->toArray();
        $pairedAmbulances = DriverAmbulancePairing::where('pairing_date', $selectedDate)
            ->where('status', 'active')
            ->pluck('ambulance_id')
            ->unique()
            ->toArray();
        $pairedDriversAmbulance = DriverAmbulancePairing::where('pairing_date', $selectedDate)
            ->where('status', 'active')
            ->pluck('driver_id')
            ->unique()
            ->toArray();
        
        return view('admin.pairing.index', compact('driverMedicPairings', 'driverAmbulancePairings', 'groupedDriverMedicPairings', 'groupedDriverAmbulancePairings', 'drivers', 'medics', 'ambulances', 'selectedDate', 'pairedMedics', 'pairedAmbulances', 'pairedDriversAmbulance', 'groupOperators', 'driverAmbulancePairsAll', 'driversPairedWithMedics'));
    }

    public function createDriverMedicPairing(Request $request)
    {
        $drivers = Driver::where('status', 'active')->get();
        $medics = Medic::where('status', 'active')->get();
        
        // Get already paired drivers and medics for the selected date
        $selectedDate = $request->get('pairing_date', date('Y-m-d'));
        $pairedDrivers = DriverMedicPairing::where('pairing_date', $selectedDate)
            ->where('status', 'active')
            ->pluck('driver_id')
            ->unique()
            ->toArray();
        $pairedMedics = DriverMedicPairing::where('pairing_date', $selectedDate)
            ->where('status', 'active')
            ->pluck('medic_id')
            ->unique()
            ->toArray();
        
        return view('admin.pairing.create-driver-medic', compact('drivers', 'medics', 'pairedDrivers', 'pairedMedics', 'selectedDate'));
    }

    public function storeDriverMedicPairing(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'medic_id' => 'required|exists:medics,id',
            'pairing_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for existing pairings
        // Check if medic is already paired with a different driver
        $existingMedicPairing = DriverMedicPairing::where('medic_id', $request->medic_id)
            ->where('pairing_date', $request->pairing_date)
            ->where('status', 'active')
            ->where('driver_id', '!=', $request->driver_id)
            ->first();

        if ($existingMedicPairing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['medic_id' => 'This medic is already paired with another driver on this date.']);
        }

        // Check if driver is already paired with a different team (different medics)
        $existingDriverPairing = DriverMedicPairing::where('driver_id', $request->driver_id)
            ->where('pairing_date', $request->pairing_date)
            ->where('status', 'active')
            ->where('medic_id', '!=', $request->medic_id)
            ->first();

        if ($existingDriverPairing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['driver_id' => 'This driver is already paired with a different team on this date.']);
        }

        $pairing = DriverMedicPairing::create($request->all());

        // Log the creation
        $this->logPairingAction('driver_medic', $pairing->id, 'created', null, $pairing->toArray());

        return redirect()->route('admin.pairing.index')
            ->with('success', 'Driver-Medic pairing created successfully!');
    }

    public function createDriverAmbulancePairing(Request $request)
    {
        $drivers = Driver::where('status', 'active')->get();
        $ambulances = Ambulance::all();
        
        // Get already paired drivers and ambulances for the selected date
        $selectedDate = $request->get('pairing_date', date('Y-m-d'));
        $pairedDrivers = DriverAmbulancePairing::where('pairing_date', $selectedDate)
            ->where('status', 'active')
            ->pluck('driver_id')
            ->unique()
            ->toArray();
        // Also block drivers that are already paired with any medic on this date (active)
        $driversPairedWithMedics = DriverMedicPairing::where('pairing_date', $selectedDate)
            ->where('status', 'active')
            ->pluck('driver_id')
            ->unique()
            ->toArray();
        // Combined list of drivers not selectable for driver-ambulance on this date
        $blockedDrivers = array_values(array_unique(array_merge($pairedDrivers, $driversPairedWithMedics)));
        $pairedAmbulances = DriverAmbulancePairing::where('pairing_date', $selectedDate)
            ->where('status', 'active')
            ->pluck('ambulance_id')
            ->unique()
            ->toArray();
        
        return view('admin.pairing.create-driver-ambulance', compact('drivers', 'ambulances', 'pairedDrivers', 'pairedAmbulances', 'selectedDate', 'blockedDrivers'));
    }

    public function storeDriverAmbulancePairing(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'ambulance_id' => 'required|exists:ambulances,id',
            'pairing_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for existing pairings
        // Check if ambulance is already paired with a different driver
        $existingAmbulancePairing = DriverAmbulancePairing::where('ambulance_id', $request->ambulance_id)
            ->where('pairing_date', $request->pairing_date)
            ->where('status', 'active')
            ->where('driver_id', '!=', $request->driver_id)
            ->first();

        if ($existingAmbulancePairing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['ambulance_id' => 'This ambulance is already paired with another driver on this date.']);
        }

        // Check if driver is already paired with a different ambulance
        $existingDriverPairing = DriverAmbulancePairing::where('driver_id', $request->driver_id)
            ->where('pairing_date', $request->pairing_date)
            ->where('status', 'active')
            ->where('ambulance_id', '!=', $request->ambulance_id)
            ->first();

        if ($existingDriverPairing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['driver_id' => 'This driver is already paired with another ambulance on this date.']);
        }

        $pairing = DriverAmbulancePairing::create($request->all());

        // Sync driver's ambulance_id with the pairing
        $this->syncDriverAmbulanceAssignment($pairing->driver_id, $pairing->ambulance_id);

        // Log the creation
        $this->logPairingAction('driver_ambulance', $pairing->id, 'created', null, $pairing->toArray());

        return redirect()->route('admin.pairing.index')
            ->with('success', 'Driver-Ambulance pairing created successfully!');
    }

    public function editDriverMedicPairing($id)
    {
        $pairing = DriverMedicPairing::with(['driver', 'medic'])->findOrFail($id);
        $drivers = Driver::where('status', 'active')->get();
        $medics = Medic::where('status', 'active')->get();
        
        return view('admin.pairing.edit-driver-medic', compact('pairing', 'drivers', 'medics'));
    }

    public function updateDriverMedicPairing(Request $request, $id)
    {
        $pairing = DriverMedicPairing::findOrFail($id);

        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'medic_id' => 'required|exists:medics,id',
            'pairing_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'status' => 'required|in:active,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldData = $pairing->toArray();
        $pairing->update($request->all());

        // Log the update
        $this->logPairingAction('driver_medic', $pairing->id, 'updated', $oldData, $pairing->fresh()->toArray());

        return redirect()->route('admin.pairing.index')
            ->with('success', 'Driver-Medic pairing updated successfully!');
    }

    public function editDriverAmbulancePairing($id)
    {
        $pairing = DriverAmbulancePairing::with(['driver', 'ambulance'])->findOrFail($id);
        $drivers = Driver::where('status', 'active')->get();
        $ambulances = Ambulance::all();
        
        return view('admin.pairing.edit-driver-ambulance', compact('pairing', 'drivers', 'ambulances'));
    }

    public function updateDriverAmbulancePairing(Request $request, $id)
    {
        $pairing = DriverAmbulancePairing::findOrFail($id);

        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'ambulance_id' => 'required|exists:ambulances,id',
            'pairing_date' => 'required|date',
            'status' => 'required|in:active,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldData = $pairing->toArray();
        $pairing->update($request->all());

        // Sync driver's ambulance_id with the updated pairing
        $this->syncDriverAmbulanceAssignment($pairing->driver_id, $pairing->ambulance_id);

        // Log the update
        $this->logPairingAction('driver_ambulance', $pairing->id, 'updated', $oldData, $pairing->fresh()->toArray());

        return redirect()->route('admin.pairing.index')
            ->with('success', 'Driver-Ambulance pairing updated successfully!');
    }

    public function destroyDriverMedicPairing($id)
    {
        $pairing = DriverMedicPairing::findOrFail($id);
        $oldData = $pairing->toArray();
        $pairing->delete();

        // Log the deletion
        $this->logPairingAction('driver_medic', $id, 'deleted', $oldData, null);

        return redirect()->route('admin.pairing.index')
            ->with('success', 'Driver-Medic pairing deleted successfully!');
    }

    public function destroyDriverAmbulancePairing($id)
    {
        $pairing = DriverAmbulancePairing::findOrFail($id);
        $oldData = $pairing->toArray();
        
        // Clear driver's ambulance assignment when pairing is deleted
        $this->clearDriverAmbulanceAssignment($pairing->driver_id);
        
        $pairing->delete();

        // Log the deletion
        $this->logPairingAction('driver_ambulance', $id, 'deleted', $oldData, null);

        return redirect()->route('admin.pairing.index')
            ->with('success', 'Driver-Ambulance pairing deleted successfully!');
    }

    public function completeDriverMedicPairing($id)
    {
        $pairing = DriverMedicPairing::findOrFail($id);
        $oldData = $pairing->toArray();
        
        $pairing->update(['status' => 'completed']);
        
        // Log the completion
        $this->logPairingAction('driver_medic', $pairing->id, 'completed', $oldData, $pairing->fresh()->toArray());
        
        return redirect()->route('admin.pairing.index')
            ->with('success', 'Driver-Medic pairing marked as completed!');
    }

    public function cancelDriverMedicPairing($id)
    {
        $pairing = DriverMedicPairing::findOrFail($id);
        $oldData = $pairing->toArray();
        
        $pairing->update(['status' => 'cancelled']);
        
        // Log the cancellation
        $this->logPairingAction('driver_medic', $pairing->id, 'cancelled', $oldData, $pairing->fresh()->toArray());
        
        return redirect()->route('admin.pairing.index')
            ->with('success', 'Driver-Medic pairing cancelled!');
    }

    public function completeDriverAmbulancePairing($id)
    {
        $pairing = DriverAmbulancePairing::findOrFail($id);
        $oldData = $pairing->toArray();
        
        // Check if there's already a completed pairing for this ambulance and date
        $existingPairing = DriverAmbulancePairing::where('ambulance_id', $pairing->ambulance_id)
            ->where('pairing_date', $pairing->pairing_date)
            ->where('status', 'completed')
            ->where('id', '!=', $pairing->id)
            ->first();
        
        if ($existingPairing) {
            // If there's already a completed pairing, delete the old one first
            // This handles the unique constraint issue
            $existingPairing->delete();
        }
        
        $pairing->update(['status' => 'completed']);
        
        // Clear driver's ambulance assignment when pairing is completed
        $this->clearDriverAmbulanceAssignment($pairing->driver_id);
        
        // Log the completion
        $this->logPairingAction('driver_ambulance', $pairing->id, 'completed', $oldData, $pairing->fresh()->toArray());
        
        return redirect()->route('admin.pairing.index')
            ->with('success', 'Driver-Ambulance pairing marked as completed!');
    }

    public function cancelDriverAmbulancePairing($id)
    {
        $pairing = DriverAmbulancePairing::findOrFail($id);
        $oldData = $pairing->toArray();
        
        // Check if there's already a cancelled pairing for this ambulance and date
        $existingPairing = DriverAmbulancePairing::where('ambulance_id', $pairing->ambulance_id)
            ->where('pairing_date', $pairing->pairing_date)
            ->where('status', 'cancelled')
            ->where('id', '!=', $pairing->id)
            ->first();
        
        if ($existingPairing) {
            // If there's already a cancelled pairing, delete the old one first
            // This handles the unique constraint issue
            $existingPairing->delete();
        }
        
        $pairing->update(['status' => 'cancelled']);
        
        // Clear driver's ambulance assignment when pairing is cancelled
        $this->clearDriverAmbulanceAssignment($pairing->driver_id);
        
        // Log the cancellation
        $this->logPairingAction('driver_ambulance', $pairing->id, 'cancelled', $oldData, $pairing->fresh()->toArray());
        
        return redirect()->route('admin.pairing.index')
            ->with('success', 'Driver-Ambulance pairing cancelled!');
    }

    public function pairingLog(Request $request)
    {
        $query = DriverMedicPairing::with(['driver', 'medic'])
            ->whereIn('status', ['completed', 'cancelled']);
            
        $ambulanceQuery = DriverAmbulancePairing::with(['driver', 'ambulance'])
            ->whereIn('status', ['completed', 'cancelled']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('driver', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('medic', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
            
            $ambulanceQuery->whereHas('driver', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('ambulance', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        // Filter by driver
        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
            $ambulanceQuery->where('driver_id', $request->driver_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $ambulanceQuery->where('status', $request->status);
        }
        
        $driverMedicPairings = $query->orderBy('updated_at', 'desc')->get()
            ->filter(function($pairing) {
                return $pairing->driver && $pairing->medic; // Only include pairings where both driver and medic exist
            });
        $driverAmbulancePairings = $ambulanceQuery->orderBy('updated_at', 'desc')->get()
            ->filter(function($pairing) {
                return $pairing->driver && $pairing->ambulance; // Only include pairings where both driver and ambulance exist
            });
        // We intentionally do not group here; the log should show one row per completed/cancelled pairing instance
        
        // Get drivers for filter dropdown
        $drivers = Driver::where('status', 'active')->get();
        
        return view('admin.pairing.log', compact('driverMedicPairings', 'driverAmbulancePairings', 'drivers'));
    }

    public function bulkPairing(Request $request)
    {
        $drivers = Driver::where('status', 'active')->get();
        $medics = Medic::where('status', 'active')->get();
        $ambulances = Ambulance::all();
        
        // Get already paired medics and ambulances for the selected date
        // Note: We don't mark drivers as "paired" since they can have multiple medics (team concept)
        $selectedDate = $request->get('pairing_date', date('Y-m-d'));
        $pairedMedics = DriverMedicPairing::where('pairing_date', $selectedDate)
            ->where('status', 'active')
            ->pluck('medic_id')
            ->unique()
            ->toArray();
        $pairedAmbulances = DriverAmbulancePairing::where('pairing_date', $selectedDate)
            ->where('status', 'active')
            ->pluck('ambulance_id')
            ->unique()
            ->toArray();
        
        // For drivers, we need to check if they're already paired with medics from a different team
        // This is more complex and will be handled in the validation logic
        $pairedDrivers = []; // We'll handle this in the validation, not in the UI
        
        return view('admin.pairing.bulk', compact('drivers', 'medics', 'ambulances', 'pairedDrivers', 'pairedMedics', 'pairedAmbulances', 'selectedDate'));
    }

    public function storeBulkPairing(Request $request)
    {
        $request->validate([
            'pairing_type' => 'required|in:driver_medic,driver_ambulance',
            'pairing_date' => 'required|date',
            'start_time' => 'required_if:pairing_type,driver_medic|nullable|date_format:H:i',
            'end_time' => 'required_if:pairing_type,driver_medic|nullable|date_format:H:i|after:start_time',
            'drivers' => 'required|array|min:1|max:2',
            'drivers.*' => 'exists:drivers,id',
            'medics' => 'required_if:pairing_type,driver_medic|array',
            'medics.*' => 'exists:medics,id',
            'ambulances' => 'required_if:pairing_type,driver_ambulance|array',
            'ambulances.*' => 'exists:ambulances,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $created = 0;
        $errors = [];

        if ($request->pairing_type === 'driver_medic') {
            // Additional validation for driver-medic pairings
            if (empty($request->start_time) || empty($request->end_time)) {
                return redirect()->route('admin.pairing.index')
                    ->with('error', 'Start time and end time are required for driver-medic pairings.');
            }
            
            foreach ($request->drivers as $driverId) {
                foreach ($request->medics as $medicId) {
                    try {
                        // Check for existing pairings before creating
                        // Only check if medic is already paired with a different driver
                        $existingMedicPairing = DriverMedicPairing::where('medic_id', $medicId)
                            ->where('pairing_date', $request->pairing_date)
                            ->where('status', 'active')
                            ->where('driver_id', '!=', $driverId)
                            ->first();

                        if ($existingMedicPairing) {
                            $errors[] = "Medic {$medicId} is already paired with another driver on this date";
                            continue;
                        }

                        // Check if this exact pairing already exists
                        $existingPairing = DriverMedicPairing::where('driver_id', $driverId)
                            ->where('medic_id', $medicId)
                            ->where('pairing_date', $request->pairing_date)
                            ->where('status', 'active')
                            ->first();

                        if ($existingPairing) {
                            $errors[] = "Driver {$driverId} and Medic {$medicId} are already paired on this date";
                            continue;
                        }

                        DriverMedicPairing::create([
                            'driver_id' => $driverId,
                            'medic_id' => $medicId,
                            'pairing_date' => $request->pairing_date,
                            'start_time' => $request->start_time,
                            'end_time' => $request->end_time,
                            'status' => 'active',
                            'notes' => $request->notes,
                        ]);
                        $created++;
                    } catch (\Exception $e) {
                        $errors[] = "Failed to create pairing for driver {$driverId} and medic {$medicId}: " . $e->getMessage();
                    }
                }
            }
        } else {
            foreach ($request->drivers as $driverId) {
                foreach ($request->ambulances as $ambulanceId) {
                    try {
                        // Check for existing pairings before creating
                        // Only check if ambulance is already paired with a different driver
                        $existingAmbulancePairing = DriverAmbulancePairing::where('ambulance_id', $ambulanceId)
                            ->where('pairing_date', $request->pairing_date)
                            ->where('status', 'active')
                            ->where('driver_id', '!=', $driverId)
                            ->first();

                        if ($existingAmbulancePairing) {
                            $errors[] = "Ambulance {$ambulanceId} is already paired with another driver on this date";
                            continue;
                        }

                        // Check if this exact pairing already exists
                        $existingPairing = DriverAmbulancePairing::where('driver_id', $driverId)
                            ->where('ambulance_id', $ambulanceId)
                            ->where('pairing_date', $request->pairing_date)
                            ->where('status', 'active')
                            ->first();

                        if ($existingPairing) {
                            $errors[] = "Driver {$driverId} and Ambulance {$ambulanceId} are already paired on this date";
                            continue;
                        }

                        $pairing = DriverAmbulancePairing::create([
                            'driver_id' => $driverId,
                            'ambulance_id' => $ambulanceId,
                            'pairing_date' => $request->pairing_date,
                            'status' => 'active',
                            'notes' => $request->notes,
                        ]);
                        
                        // Sync driver's ambulance_id with the pairing
                        $this->syncDriverAmbulanceAssignment($driverId, $ambulanceId);
                        
                        $created++;
                    } catch (\Exception $e) {
                        $errors[] = "Failed to create pairing for driver {$driverId} and ambulance {$ambulanceId}: " . $e->getMessage();
                    }
                }
            }
        }

        // Only show success if pairings were actually created
        if ($created > 0 && empty($errors)) {
            $message = "Successfully created {$created} pairings.";
            return redirect()->route('admin.pairing.index')
                ->with('success', $message);
        } elseif ($created > 0 && !empty($errors)) {
            $message = "Successfully created {$created} pairings. Errors: " . implode(', ', $errors);
            return redirect()->route('admin.pairing.index')
                ->with('success', $message);
        } else {
            // No pairings created, show error
            $message = "Failed to create any pairings. Errors: " . implode(', ', $errors);
            return redirect()->route('admin.pairing.index')
                ->with('error', $message);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:complete,cancel',
            'pairing_type' => 'required|in:driver_medic,driver_ambulance',
            'pairing_ids' => 'required|array|min:1',
        ]);

        $updated = 0;
        $status = $request->action === 'complete' ? 'completed' : 'cancelled';

        if ($request->pairing_type === 'driver_medic') {
            // Handle grouped pairings - pairing_ids now contains group keys like "1_2024-01-15"
            foreach ($request->pairing_ids as $groupKey) {
                // Parse the group key to get driver_id and date
                $parts = explode('_', $groupKey);
                if (count($parts) >= 2) {
                    $driverId = $parts[0];
                    $date = implode('-', array_slice($parts, 1)); // Handle dates with dashes
                    
                    $pairings = DriverMedicPairing::where('driver_id', $driverId)
                        ->where('pairing_date', $date)
                        ->where('status', 'active')
                        ->get();
                        
                    foreach ($pairings as $pairing) {
                        $oldData = $pairing->toArray();
                        $pairing->update(['status' => $status]);
                        
                        $this->logPairingAction('driver_medic', $pairing->id, $status, $oldData, $pairing->fresh()->toArray());
                        $updated++;
                    }
                }
            }
        } else {
            // Handle grouped pairings for driver-ambulance
            foreach ($request->pairing_ids as $groupKey) {
                // Parse the group key - for driver-ambulance, group key is {ambulance_id}_{date}
                $parts = explode('_', $groupKey);
                if (count($parts) >= 2) {
                    $ambulanceId = $parts[0];
                    $date = implode('-', array_slice($parts, 1)); // Handle dates with dashes
                    
                    $pairings = DriverAmbulancePairing::where('ambulance_id', $ambulanceId)
                        ->where('pairing_date', $date)
                        ->where('status', 'active')
                        ->get();
                        
                    foreach ($pairings as $pairing) {
                        $oldData = $pairing->toArray();
                        
                        // Check if there's already a pairing with the target status for this ambulance and date
                        $existingPairing = DriverAmbulancePairing::where('ambulance_id', $ambulanceId)
                            ->where('pairing_date', $date)
                            ->where('status', $status)
                            ->where('id', '!=', $pairing->id)
                            ->first();
                        
                        if ($existingPairing) {
                            // If there's already a completed/cancelled pairing, delete the old one first
                            // This handles the unique constraint issue
                            $existingPairing->delete();
                        }
                        
                        // Now update the pairing status
                        $pairing->update(['status' => $status]);
                        
                        // Clear driver's ambulance assignment when pairing is completed
                        if ($status === 'completed') {
                            $this->clearDriverAmbulanceAssignment($pairing->driver_id);
                        }
                        
                        $this->logPairingAction('driver_ambulance', $pairing->id, $status, $oldData, $pairing->fresh()->toArray());
                        $updated++;
                    }
                }
            }
        }

        return redirect()->route('admin.pairing.index')
            ->with('success', "Successfully {$request->action}d {$updated} pairings.");
    }

    public function groupAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:complete,cancel',
            'pairing_type' => 'required|in:driver_medic,driver_ambulance',
            'group_key' => 'required|string',
        ]);

        $status = $request->action === 'complete' ? 'completed' : 'cancelled';
        $updated = 0;

        // Parse the group key
        $parts = explode('_', $request->group_key);
        if (count($parts) >= 2) {
            $date = implode('-', array_slice($parts, 1)); // Handle dates with dashes
            
            if ($request->pairing_type === 'driver_medic') {
                // For driver-medic: group key is {driver_id}_{date}
                $driverId = $parts[0];
                $pairings = DriverMedicPairing::where('driver_id', $driverId)
                    ->where('pairing_date', $date)
                    ->where('status', 'active')
                    ->get();
                    
                foreach ($pairings as $pairing) {
                    $oldData = $pairing->toArray();
                    $pairing->update(['status' => $status]);
                    
                    $this->logPairingAction('driver_medic', $pairing->id, $status, $oldData, $pairing->fresh()->toArray());
                    $updated++;
                }
            } else {
                // For driver-ambulance: group key is {ambulance_id}_{date}
                $ambulanceId = $parts[0];
                
                try {
                    $pairings = DriverAmbulancePairing::where('ambulance_id', $ambulanceId)
                        ->where('pairing_date', $date)
                        ->where('status', 'active')
                        ->get();
                        
                    if ($pairings->isEmpty()) {
                        return redirect()->route('admin.pairing.index')
                            ->with('error', 'No active pairings found for this group. They may have already been completed or cancelled.');
                    }
                        
                    foreach ($pairings as $pairing) {
                        $oldData = $pairing->toArray();
                        
                        // Check if there's already a pairing with the target status for this ambulance and date
                        $existingPairing = DriverAmbulancePairing::where('ambulance_id', $ambulanceId)
                            ->where('pairing_date', $date)
                            ->where('status', $status)
                            ->where('id', '!=', $pairing->id)
                            ->first();
                        
                        if ($existingPairing) {
                            // If there's already a completed/cancelled pairing, delete the old one first
                            // This handles the unique constraint issue
                            $existingPairing->delete();
                        }
                        
                        // Now update the pairing status
                        $pairing->update(['status' => $status]);
                        
                        // Clear driver's ambulance assignment when pairing is completed
                        if ($status === 'completed') {
                            $this->clearDriverAmbulanceAssignment($pairing->driver_id);
                        }
                        
                        $this->logPairingAction('driver_ambulance', $pairing->id, $status, $oldData, $pairing->fresh()->toArray());
                        $updated++;
                    }
                } catch (\Exception $e) {
                    Log::error('Error completing/cancelling driver-ambulance pairing: ' . $e->getMessage());
                    return redirect()->route('admin.pairing.index')
                        ->with('error', 'An error occurred while processing the request: ' . $e->getMessage());
                }
            }
            
            if ($updated === 0) {
                return redirect()->route('admin.pairing.index')
                    ->with('error', 'No active pairings found for this group. They may have already been completed or cancelled.');
            }
        } else {
            return redirect()->route('admin.pairing.index')
                ->with('error', 'Invalid group key format.');
        }

        return redirect()->route('admin.pairing.index')
            ->with('success', "Successfully {$request->action}d {$updated} pairing" . ($updated > 1 ? 's' : '') . " in this group.");
    }

    private function logPairingAction($pairingType, $pairingId, $action, $oldData, $newData)
    {
        PairingLog::create([
            'pairing_type' => $pairingType,
            'pairing_id' => $pairingId,
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
            'admin_id' => auth()->id(),
            'notes' => "Pairing {$action} by admin",
        ]);
    }

    /**
     * Sync driver's ambulance_id with the latest active pairing
     */
    private function syncDriverAmbulanceAssignment($driverId, $ambulanceId)
    {
        $driver = Driver::find($driverId);
        if ($driver) {
            $driver->ambulance_id = $ambulanceId;
            $driver->save();
        }
    }

    /**
     * Clear driver's ambulance assignment when pairing is deleted
     */
    private function clearDriverAmbulanceAssignment($driverId)
    {
        $driver = Driver::find($driverId);
        if ($driver) {
            $driver->ambulance_id = null;
            $driver->save();
        }
    }

    /**
     * Sync all active driver-ambulance pairings with driver assignments
     * This method can be called to fix any existing mismatches
     */
    public function syncAllPairings()
    {
        $activePairings = DriverAmbulancePairing::where('status', 'active')
            ->orderBy('pairing_date', 'desc')
            ->get()
            ->groupBy('driver_id');

        $synced = 0;
        foreach ($activePairings as $driverId => $pairings) {
            // Get the most recent pairing for this driver
            $latestPairing = $pairings->first();
            $this->syncDriverAmbulanceAssignment($driverId, $latestPairing->ambulance_id);
            $synced++;
        }

        return $synced;
    }

    /**
     * Clean up orphaned pairings (pairings where driver, medic, or ambulance no longer exist)
     * This method can be called to remove invalid pairings
     */
    public function cleanupOrphanedPairings()
    {
        $cleaned = 0;

        // Clean up driver-medic pairings with deleted drivers or medics
        $orphanedDriverMedicPairings = DriverMedicPairing::whereDoesntHave('driver')
            ->orWhereDoesntHave('medic')
            ->get();

        foreach ($orphanedDriverMedicPairings as $pairing) {
            $pairing->delete();
            $cleaned++;
        }

        // Clean up driver-ambulance pairings with deleted drivers or ambulances
        $orphanedDriverAmbulancePairings = DriverAmbulancePairing::whereDoesntHave('driver')
            ->orWhereDoesntHave('ambulance')
            ->get();

        foreach ($orphanedDriverAmbulancePairings as $pairing) {
            $pairing->delete();
            $cleaned++;
        }

        return $cleaned;
    }
}
