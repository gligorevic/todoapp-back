<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUserGuest
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
        $token = $request->bearerToken();
        if($token == null) return response()->json(["error"=> "Unauthorized"], 401);
        return $next($request);
    }
}
