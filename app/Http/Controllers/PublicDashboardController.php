<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;
use App\Models\MissionVision;
use App\Models\AboutMdrrmo;
use App\Models\Official;
use App\Models\Training;
use App\Models\Ambulance;


class PublicDashboardController extends Controller
{
public function index()
{
    $carousels = Carousel::latest()->get();
    $missionVision = MissionVision::latest()->first();
    $about = AboutMdrrmo::all();
    $officials = Official::all();
    $trainings = Training::all();
    // $ambulance = Ambulance::latest()->first(); // assuming only one record

    return view('public.dashboard.index', compact(
        'carousels',
        'missionVision',
        'about',
        'officials',
        'trainings'
        // 'ambulance'
    ));
}

}