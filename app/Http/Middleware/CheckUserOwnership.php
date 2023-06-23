<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserOwnership
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->route('user');
        if (is_string($user)) {
            $user = \App\Models\User::find($user);
        }
        
        if (! $request->user() || $request->user()->id !== $user->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}