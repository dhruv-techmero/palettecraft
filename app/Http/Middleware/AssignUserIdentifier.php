<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class AssignUserIdentifier
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
        if (!$request->hasCookie('user_identifier')) {
            
            $cookieValue = Str::uuid();
            $cookie = cookie('user_identifier', $cookieValue, 60 * 24 * 30); // 30 days expiry

            // Attach the cookie to the response
            $response = $next($request);
            return $response->cookie($cookie);
        }
        return $next($request);
    }
}
