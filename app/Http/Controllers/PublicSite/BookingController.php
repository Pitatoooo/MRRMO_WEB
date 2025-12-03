<?php

namespace App\Http\Controllers\PublicSite;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'preferred_date' => 'required|date',
            'preferred_time' => 'required',
        ]);

        Booking::create($request->all());

        return redirect()->route('services.show', $request->service_id)
    ->with('success', 'Your request was submitted!');

    }
}
