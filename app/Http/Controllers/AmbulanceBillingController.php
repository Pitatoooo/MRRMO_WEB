<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AmbulanceBilling;

class AmbulanceBillingController extends Controller
{
    public function create()
    {
        return view('public.billing.create'); // This is the correct view
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'service_type' => 'required|string|max:255',
        ]);

        $billing = AmbulanceBilling::create($validated);

        return view('public.billing.receipt', compact('billing')); // Also update to correct folder
    }
}
