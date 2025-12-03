<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambulance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'status',
        'destination_latitude',
        'destination_longitude',
        'destination_updated_at',
    ];

    public function driver()
{
    return $this->hasOne(Driver::class);
}

    public function driverAmbulancePairings()
    {
        return $this->hasMany(DriverAmbulancePairing::class);
    }

    public function emergencyCases()
    {
        return $this->hasMany(EmergencyCase::class, 'ambulance_id');
    }

    public function caseAmbulances()
    {
        return $this->hasMany(CaseAmbulance::class);
    }

    public function assignedCases()
    {
        return $this->belongsToMany(EmergencyCase::class, 'case_ambulances', 'ambulance_id', 'case_num')
                    ->withPivot(['driver_accepted', 'notification_sent', 'assigned_at', 'accepted_at'])
                    ->withTimestamps();
    }

}
