<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Land;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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


    public function filter_lands(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'string',
            'min_space' => 'integer',
            'max_space' => 'integer',
            'min_price' => 'integer',
            'max_price' => 'integer',
            'operation_id' => 'integer',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $location = $request->input('location');
        $min_space = $request->input('min_space');
        $max_space = $request->input('max_space');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $operationId = $request->input('operation_id');

        $data = DB::table('users')
            ->join('post_user', 'users.id',  'post_user.user_id')
            ->join('posts', 'post_user.post_id', 'posts.id')
            ->join('lands', 'lands.id', 'posts.postsable_id')
            ->where('posts.postsable_type', 'App\Models\Land')
            ->join('operations', 'post_user.operation_id', 'operations.id')
            ->select('users.name AS TheOwner','lands.space','lands.description',)
            ->where('post_user.operation_id', $operationId)
            ->where('lands.location', 'like', '%'.$location.'%')
            ->whereBetween('lands.space', [$min_space, $max_space])
            ->whereBetween('post_user.price', [$min_price, $max_price])
            ->get();

            if(empty($data)){
                return response()->json($data);
            }
            return response()->json(['message' => 'Nothing matched the giving information'], 404);
    }
}
