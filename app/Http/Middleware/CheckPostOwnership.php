<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PostUser;

class CheckPostOwnership
{
    public function handle(Request $request, Closure $next)
    {
        $post = PostUser::findOrFail($request->route('post'))->first();

        if (! $request->user() || $post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden, you have no rights no manage this post'], 403);
        }

        return $next($request);
    }
}