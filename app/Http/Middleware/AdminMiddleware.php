<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // check if user is logged in and is admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            // redirect to home if not admin
            return redirect()->route('home')->with('error', 'You must be an admin to access this page.');
        }

        return $next($request);
    }
}
