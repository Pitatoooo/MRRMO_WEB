<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DashboardCarousel;
use App\Models\MissionVision;
use App\Models\AboutMDRRMO;

class AdminContentController extends Controller
{
    public function showPostingPage()
    {
        return view('admin.posting.index');
    }

    // Show forms
    public function carouselForm()
    {
        return view('admin.posting.carousel');
    }

    public function missionVisionForm()
    {
        return view('admin.posting.mission_vision');
    }

    public function aboutForm()
    {
        return view('admin.posting.about');
    }

    // Handle submissions
    public function storeCarousel(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'caption' => 'nullable|string'
        ]);

        // Store uploaded image
        $imagePath = $request->file('image')->store('carousel', 'public');

        DashboardCarousel::create([
            'image' => $imagePath,
            'caption' => $request->caption
        ]);

        return back()->with('success', 'Carousel item posted!');
    }

    public function storeMissionVision(Request $request)
    {
        $request->validate([
            'mission' => 'required|string',
            'vision' => 'required|string'
        ]);

        MissionVision::create($request->only(['mission', 'vision']));

        return back()->with('success', 'Mission & Vision posted!');
    }

    public function storeAbout(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('about', 'public');
        }

        AboutMDRRMO::create([
            'text' => $request->text,
            'image' => $path
        ]);

        return back()->with('success', 'About MDRRMO posted!');
    }
}
