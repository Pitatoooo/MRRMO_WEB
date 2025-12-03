<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Review.php
class Review extends Model
{
    protected $fillable = ['service_id', 'name', 'rating', 'comment'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
