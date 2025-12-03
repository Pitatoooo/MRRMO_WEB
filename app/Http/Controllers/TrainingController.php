<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;

class TrainingController extends Controller
{
   public function store(Request $request)
{
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $filename = time() . '.' . $request->image->extension();
        $request->image->move(public_path('image'), $filename);
        $data['image'] = $filename; // ✅ add image filename to the data array
    }

    Training::create($data); // ✅ This now includes the image filename

    return redirect()->back()->with('success', 'Training posted!');
}

}
