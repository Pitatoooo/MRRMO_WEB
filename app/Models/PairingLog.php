<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'pairing_type',
        'pairing_id',
        'action',
        'old_data',
        'new_data',
        'admin_id',
        'notes',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
