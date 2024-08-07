<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuthMiddelware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->header('PHP_AUTH_USER');
        $password = $request->header('PHP_AUTH_PW');

        $validUser = env('BASIC_AUTH_USER');
        $validPassword = env('BASIC_AUTH_PASSWORD');

        if ($user !== $validUser || $password !== $validPassword) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
