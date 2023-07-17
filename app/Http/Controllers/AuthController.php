<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'img' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth_token',['user'])->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Token revoked successfully',
        ]);
    }
    
    
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}


