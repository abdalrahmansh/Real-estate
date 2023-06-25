<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserOwnership
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (is_string($user)) {
            $user = \App\Models\User::find($user);
        }
        
        if (! $user || ! $request->user() || $request->user()->id !== $user->id) {
            return response()->json(['message' => 'Forbidden, you have no rights no manage this user'], 403);
        }

        return $next($request);
    }
}