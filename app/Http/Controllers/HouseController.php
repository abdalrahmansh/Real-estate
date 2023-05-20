<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;

class HouseController extends Controller
{
    public function index()
    {
        $houses = House::with('images')->get();
        return response()->json($houses);
    }

    public function show(House $house)
    {
        $house->load('images');
        return response()->json($house);
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required',
        ]);
        
        $house = House::create($request->all());

        return response()->json($house, 201);
    }

    public function update(Request $request, House $house)
    {
        $house->update($request->all());

        return response()->json($house);
    }

    public function destroy(House $house)
    {
        $house->delete();

        return response()->json(['message' => 'House deleted successfully'], 200);
    }

    
}
