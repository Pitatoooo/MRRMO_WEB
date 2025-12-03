<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();
        return view('public.services.index', compact('services'));
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('public.services.show', compact('service'));
    }
}
