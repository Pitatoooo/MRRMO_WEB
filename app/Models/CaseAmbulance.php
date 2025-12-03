<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseAmbulance extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_num',
        'ambulance_id',
        'driver_accepted',
        'notification_sent',
        'assigned_at',
        'accepted_at',
    ];

    protected $casts = [
        'driver_accepted' => 'boolean',
        'notification_sent' => 'boolean',
        'assigned_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function emergencyCase()
    {
        return $this->belongsTo(EmergencyCase::class, 'case_num', 'case_num');
    }

    public function ambulance()
    {
        return $this->belongsTo(Ambulance::class);
    }
}
