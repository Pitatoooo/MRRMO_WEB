<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carousel;

class CarouselController extends Controller
{
    public function store(Request $request)
    {
        $data = new Carousel;

        if ($request->hasFile('image')) {
            $filename = time().'_'.$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('image'), $filename);
            $data->image = $filename;
        }

        $data->caption = $request->caption;
        $data->save();

        return redirect()->back()->with('success', 'Carousel image uploaded successfully!');
    }
}
