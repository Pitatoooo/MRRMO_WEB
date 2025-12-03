<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyCase extends Model
{
    use HasFactory;

    protected $table = 'cases';
    protected $primaryKey = 'case_num';
    public $incrementing = true;
    public $keyType = 'int';

    protected $fillable = [
        'status',
        'name',
        'contact',
        'age',
        'date_of_birth',
        'type',
        'priority',
        'address',
        'landmark',
        'destination',
        'to_go_to_address',
        'to_go_to_landmark',
        'to_go_to_latitude',
        'to_go_to_longitude',
        'latitude',
        'longitude',
        'timestamp',
        'driver',
        'contact_number',
        'ambulance_id',
        'driver_accepted',
        'notification_sent',
        'completed_at'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'date_of_birth' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'to_go_to_latitude' => 'decimal:8',
        'to_go_to_longitude' => 'decimal:8',
        'driver_accepted' => 'boolean',
        'notification_sent' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function ambulance()
    {
        return $this->belongsTo(Ambulance::class, 'ambulance_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver', 'name');
    }

    public function caseAmbulances()
    {
        return $this->hasMany(CaseAmbulance::class, 'case_num', 'case_num');
    }

    public function assignedAmbulances()
    {
        return $this->belongsToMany(Ambulance::class, 'case_ambulances', 'case_num', 'ambulance_id')
                    ->withPivot(['driver_accepted', 'notification_sent', 'assigned_at', 'accepted_at'])
                    ->withTimestamps();
    }
}