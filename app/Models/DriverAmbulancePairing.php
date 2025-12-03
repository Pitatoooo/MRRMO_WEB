<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class DriverAmbulancePairing extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'ambulance_id',
        'pairing_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'pairing_date' => 'date',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function ambulance()
    {
        return $this->belongsTo(Ambulance::class);
    }

    /**
     * Get validation rules for creating/updating driver-ambulance pairings
     */
    public static function getValidationRules($excludeId = null)
    {
        return [
            'driver_id' => 'required|exists:drivers,id',
            'ambulance_id' => [
                'required',
                'exists:ambulances,id',
                Rule::unique('driver_ambulance_pairings', 'ambulance_id')
                    ->where('pairing_date', request('pairing_date'))
                    ->where('status', 'active')
                    ->ignore($excludeId)
            ],
            'pairing_date' => 'required|date',
            'status' => 'in:active,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Check if a driver is already paired on a specific date
     */
    public static function isDriverPaired($driverId, $pairingDate, $excludeId = null)
    {
        $query = static::where('driver_id', $driverId)
            ->where('pairing_date', $pairingDate)
            ->where('status', 'active');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Check if an ambulance is already paired on a specific date
     */
    public static function isAmbulancePaired($ambulanceId, $pairingDate, $excludeId = null)
    {
        $query = static::where('ambulance_id', $ambulanceId)
            ->where('pairing_date', $pairingDate)
            ->where('status', 'active');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
