<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Ambulance;
use App\Models\EmergencyCase;
use App\Models\Medic;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $selectedDate = Carbon::today();
        $metrics = $this->compileMetrics($selectedDate);

        return view('dashboard', [
            'caseMetrics' => $metrics['caseMetrics'],
            'staffMetrics' => $metrics['staffMetrics'],
            'systemMetrics' => $metrics['systemMetrics'],
            'selectedDate' => $metrics['caseMetrics']['date']['raw'],
            'selectedDateHuman' => $metrics['caseMetrics']['date']['human'],
            'lastUpdated' => now()->format('g:i A')
        ]);
    }

    /**
     * Get dashboard metrics for a specific date
     */
    public function getMetricsByDate(Request $request)
    {
        $date = $request->input('date');
        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        try {
            $selectedDate = Carbon::parse($date);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date provided.'], 422);
        }

        $metrics = $this->compileMetrics($selectedDate);

        return response()->json([
            'caseMetrics' => $metrics['caseMetrics'],
            'staffMetrics' => $metrics['staffMetrics'],
            'systemMetrics' => $metrics['systemMetrics'],
            'requested_at' => now()->toIso8601String()
        ]);
    }

    private function compileMetrics(Carbon $focusDate): array
    {
        $activeStatuses = ['Accepted', 'In Progress'];

        $pendingCases = EmergencyCase::whereDate('timestamp', $focusDate)
            ->where('status', 'Pending')
            ->count();

        $activeCases = EmergencyCase::whereDate('timestamp', $focusDate)
            ->where(function ($query) use ($activeStatuses) {
                $query->whereIn('status', $activeStatuses)
                    ->orWhere('driver_accepted', true);
            })
            ->count();

        $casesToday = EmergencyCase::whereDate('timestamp', $focusDate)->count();

        $completedToday = EmergencyCase::whereDate('completed_at', $focusDate)->count();

        $totalCases = EmergencyCase::count();

        $totalDrivers = Driver::count();
        $onlineDrivers = Driver::where('availability_status', 'online')->count();

        $totalMedics = Medic::count();
        $activeMedics = Medic::where('status', 'active')->count();

        $totalUsers = User::count();
        $onlineUsers = $this->getActiveSessionCount();

        $totalAmbulances = Ambulance::count();
        $availableAmbulances = Ambulance::where('status', 'Available')->count();

        return [
            'caseMetrics' => [
                'pending' => $pendingCases,
                'active' => $activeCases,
                'daily' => $casesToday,
                'completed' => $completedToday,
                'total' => $totalCases,
                'date' => [
                    'raw' => $focusDate->toDateString(),
                    'human' => $focusDate->format('F j, Y')
                ]
            ],
            'staffMetrics' => [
                'drivers' => [
                    'online' => $onlineDrivers,
                    'total' => $totalDrivers
                ],
                'medics' => [
                    'active' => $activeMedics,
                    'total' => $totalMedics
                ],
            ],
            'systemMetrics' => [
                'users' => [
                    'online' => $onlineUsers,
                    'total' => $totalUsers
                ],
                'ambulances' => [
                    'available' => $availableAmbulances,
                    'total' => $totalAmbulances
                ],
            ]
        ];
    }

    private function getActiveSessionCount(): int
    {
        try {
            return (int) DB::table('sessions')
                ->whereNotNull('user_id')
                ->distinct()
                ->count('user_id');
        } catch (\Throwable $e) {
            return 0;
        }
    }
}