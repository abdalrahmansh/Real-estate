<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show(User $user)
    {
        $user = User::find($user);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'img' => 'required|image',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $path = $request->img->store('public');
        $filename = basename($path);
        $url = asset('storage/' . $filename);

        $data = $request->except('password_confirmation');
        $data['password'] = bcrypt($data['password']);
        $data['img'] = $url;

        $user = User::create($data);

        $token = $user->createToken('authToken',['user'])->plainTextToken;
        $hashedToken = hash('sha256', $token);
        auth()->login($user);

        return response(['user' => $user, 'access_token' => $token]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required','email',Rule::unique('users')->ignore($user->id)],
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'img' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->img->store('public');
        $filename = basename($path);
        $url = asset('storage/' . $filename);

        $data = $request->except('password_confirmation');
        $data['password'] = bcrypt($data['password']);
        $data['img'] = $url;

        $user->update($data);

        return response()->json($user, 200);
    }

    public function destroy(User $user)
    {
        \Illuminate\Support\Facades\Storage::delete($user->img);
        $user->delete();

        return response()->json(['message' => 'user deleted successfully'], 200);
    }
}
