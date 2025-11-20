<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // I check if user is logged in and is an admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            // I redirect to home if not admin
            return redirect()->route('home')->with('error', 'You must be an admin to access this page.');
        }

        return $next($request);
    }
}
