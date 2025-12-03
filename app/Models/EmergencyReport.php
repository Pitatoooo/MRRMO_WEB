<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyReport extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'location'];


    public function index()
{
    $reports = EmergencyReport::latest()->get(); // newest first
    return view('dashboard', compact('reports'));
}


}


