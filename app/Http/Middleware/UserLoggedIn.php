<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json("Unauthorized", 401);
        }

        return $next($request);
    }
}
