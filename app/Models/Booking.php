<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Booking.php
class Booking extends Model
{
    protected $fillable = [
    'service_id',
    'name',
    'contact_info',
    'preferred_date',
    'preferred_time',
    'status', // Add this
];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
