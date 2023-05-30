<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('authToken')->accessToken;
        auth()->login($user);

        return response(['user' => $user, 'access_token' => $token]);
    }

    /**
     * Login and create token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            session()->regenerate();
            $token = $user->createToken('authToken')->accessToken;
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
    public function logout(Request $request)
{
    if (!auth()->check()) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $accessToken = $request->user()->token();

    if (!$accessToken) {
        return response()->json(['message' => 'Access token not found'], 404);
    }

    $accessToken->revoke();
    auth()->logout();
    return response()->json(['message' => 'Successfully logged out']);
}
}
