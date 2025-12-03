<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseRejection extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_num',
        'ambulance_id',
        'driver_name',
        'rejected_at',
    ];

    protected $casts = [
        'rejected_at' => 'datetime',
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
