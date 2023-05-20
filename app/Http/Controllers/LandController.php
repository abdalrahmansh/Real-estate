<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Land;

class LandController extends Controller
{
    public function index()
    {
        $lands = land::with('images')->get();
        return response()->json($lands);
    }

    public function show(land $land)
    {
        $land->load('images');
        return response()->json($land);
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required',
        ]);
        
        $land = land::create($request->all());

        return response()->json($land, 201);
    }

    public function update(Request $request, land $land)
    {
        $land->update($request->all());

        return response()->json($land);
    }

    public function destroy(land $land)
    {
        $land->delete();

        return response()->json(['message' => 'land deleted successfully'], 200);
    }
}
