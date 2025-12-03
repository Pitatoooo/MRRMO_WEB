<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmbulanceBilling extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'service_type',
    ];
}
