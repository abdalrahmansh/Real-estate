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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('authToken')->accessToken;
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
            $token = $user->createToken('auth_token')->plainTextToken;

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



/*


public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // $user = Auth::user();
            $user = $request->user();

            // session()->regenerate();
            $token = $user->createToken('authToken')->accessToken;
                    $user = $request->user();
                    // return response()->json([
                    //     'user' => $user,
                    //     'access_token' => $token,
                    // ]);
            return response(['user' => $user, 'access_token' => [$token,'token' => $token->token]]);
        }

        return response(['error' => 'Invalid credentials'], 401);
    }

    /**
     * Logout and revoke token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function logout(Request $request)
    // {
        // if (!auth()->check()) {
        //     return response()->json(['message' => 'User not authenticated'], 401);
        // }

        // $accessToken = $request->user()->token();

    //     if (!$accessToken) {
    //         return response()->json(['message' => 'Access token not found'], 404);
    //     }

    //     $accessToken->revoke();
    //     auth()->logout();
    //     return response()->json(['message' => 'Successfully logged out']);
    // }