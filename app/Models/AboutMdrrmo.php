<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutMdrrmo extends Model
{
    use HasFactory;

    protected $table = 'about_mdrrmos'; // ← optional if table name is obvious

    protected $fillable = [
        'text',
        'image'
    ];
}
