<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'user_id',
        'reporter_name',
        'contact_number',
        'incident_type',
        'description',
        'latitude',
        'longitude',
        'location',
        'status',
        'incident_datetime',
        'uploaded_media',
    ];
}
