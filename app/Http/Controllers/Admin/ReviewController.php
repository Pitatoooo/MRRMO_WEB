<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create($request->all());

        return back()->with('success', 'Thank you for your review!');
    }
}
