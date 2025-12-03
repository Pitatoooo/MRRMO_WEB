<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class DriverMedicPairing extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'medic_id',
        'pairing_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'pairing_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function medic()
    {
        return $this->belongsTo(Medic::class);
    }

    /**
     * Get validation rules for creating/updating driver-medic pairings
     */
    public static function getValidationRules($excludeId = null)
    {
        return [
            'driver_id' => 'required|exists:drivers,id',
            'medic_id' => [
                'required',
                'exists:medics,id',
                Rule::unique('driver_medic_pairings', 'medic_id')
                    ->where('pairing_date', request('pairing_date'))
                    ->where('status', 'active')
                    ->ignore($excludeId)
            ],
            'pairing_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
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
     * Check if a medic is already paired on a specific date
     */
    public static function isMedicPaired($medicId, $pairingDate, $excludeId = null)
    {
        $query = static::where('medic_id', $medicId)
            ->where('pairing_date', $pairingDate)
            ->where('status', 'active');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
