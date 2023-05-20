<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operation;

class OperationController extends Controller
{
    public function index()
    {
        $operations = operation::all();
        return response()->json($operations);
    }

    public function show(operation $operation)
    {
        $operation = operation::find($operation);
        return response()->json($operation);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        
        $operation = operation::create($request->all());

        return response()->json($operation, 201);
    }

    public function update(Request $request, operation $operation)
    {
        $operation->update($request->all());

        return response()->json($operation);
    }

    public function destroy(operation $operation)
    {
        $operation->delete();

        return response()->json(['message' => 'operation deleted successfully'], 200);
    }
}
