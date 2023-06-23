<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminAbility
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->tokenCan('admin')) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}