<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return view('admin.services.index', [
            'pendingBookings' => \App\Models\Booking::where('status', 'pending')->get(),
            'approvedBookings' => \App\Models\Booking::where('status', 'approved')->get(),
            'rejectedBookings' => \App\Models\Booking::where('status', 'rejected')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'status' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'_'.$request->image->getClientOriginalName();
            $request->image->move(public_path('image'), $imageName);
        }

        try {
            $service = Service::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'image' => $imageName,
                'category' => $request->category,
            ]);

            // Log successful creation
            \Log::info('Service created successfully', [
                'id' => $service->id,
                'title' => $service->title,
                'category' => $service->category
            ]);

            return redirect()->back()->with('success', 'Service added successfully!');
        } catch (\Exception $e) {
            // Log any errors
            \Log::error('Service creation failed', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return redirect()->back()->with('error', 'Failed to add service: ' . $e->getMessage());
        }
    }
}
