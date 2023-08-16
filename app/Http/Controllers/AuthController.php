<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'img' => 'image',
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

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if ($user->role !== 'admin') {
                return response()->json([
                    'message' => 'You are not an admin in this website',
                ], 403);
            }

            $token = $user->createToken('auth_token', ['admin'])->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 404);
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

        return response()->json([
            'message' => 'Invalid credentials',
        ], 404);
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

    public function sendPasswordResetLinkEmail(Request $request) {
		$request->validate(['email' => 'required|email']);

		$status = Password::sendResetLink(
			$request->only('email')
		);

		if($status === Password::RESET_LINK_SENT) {
			return response()->json(['message' => __($status)], 200);
		} else {
			throw ValidationException::withMessages([
				'email' => __($status)
			]);
		}
	}

	public function resetPassword(Request $request) {
		$request->validate([
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:8|confirmed',
		]);

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) use ($request) {
				$user->forceFill([
					'password' => Hash::make($password)
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('new.password.success');
        } else {
            return redirect()->back()->withInput($request->only('email'))->withErrors(['email' => 'Password reset failed. Please try again later.']);
        }
    }

    public function resetPasswordView()
    {
        return view('password');
    }

    public function newPasswordView(Request $request)
    {
        return view('rest')->with('request', $request);;
    }

    public function newPasswordViewSuccess()
    {
        $success = session('success');
        return view('password.new', compact('success'));
    }

    public function newPasswordSuccess()
    {
        return view('password.success');
    }

}
