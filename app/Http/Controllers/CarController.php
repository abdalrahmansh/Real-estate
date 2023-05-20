<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
{
    public function index()
    {
        $cars = car::with('images')->get();
        return response()->json($cars);
    }

    public function show(car $car)
    {
        $car->load('images');
        return response()->json($car);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        
        $car = car::create($request->all());

        return response()->json($car, 201);
    }

    public function update(Request $request, car $car)
    {
        $car->update($request->all());

        return response()->json($car);
    }

    public function destroy(car $car)
    {
        $car->delete();

        return response()->json(['message' => 'car deleted successfully'], 200);
    }
}
