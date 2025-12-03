<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ambulance;

class AmbulanceController extends Controller
{
    public function index()
    {
        $ambulances = Ambulance::all();
        return view('admin.ambulances.index', compact('ambulances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:ambulances,name',
            'status' => 'required|in:Available,Out,Unavailable'
        ]);

        Ambulance::create([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return back()->with('success', 'Ambulance added successfully!');
    }


    public function setDestination(Request $request, $id)
{
    $ambulance = Ambulance::findOrFail($id);
    $ambulance->destination_latitude = $request->latitude;
    $ambulance->destination_longitude = $request->longitude;
    $ambulance->destination_updated_at = now();
    $ambulance->status = 'Out';
    $ambulance->save();

    return response()->json(['success' => true]);
}

    public function clearDestination($id)
{
    $ambulance = Ambulance::findOrFail($id);
    $ambulance->destination_latitude = null;
    $ambulance->destination_longitude = null;
    $ambulance->destination_updated_at = null;
    $ambulance->status = 'Available';
    $ambulance->save();

    return response()->json(['success' => true]);
}

}
