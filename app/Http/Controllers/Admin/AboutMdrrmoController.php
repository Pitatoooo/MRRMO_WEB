<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutMdrrmo;

class AboutMdrrmoController extends Controller
{
    public function store(Request $request)
    {
        $data = new AboutMdrrmo;
        $data->text = $request->text;

        if ($request->hasFile('image')) {
            $filename = time().'_'.$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('image'), $filename);
            $data->image = $filename;
        }

        $data->save();

        return redirect()->back()->with('success', 'About MDRRMO posted successfully!');
    }
}
