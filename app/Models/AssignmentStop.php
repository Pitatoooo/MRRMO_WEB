<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'latitude',
        'longitude',
        'priority',
        'status',
        'sequence',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}


