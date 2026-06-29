<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if the user is logged in
        // 2. Check if the logged-in user is an admin
        if (Auth::check() && Auth::user()->is_admin) {
            // If yes, continue to the requested admin page
            return $next($request);
        }

        // If not an admin, redirect them to the regular user dashboard
        return redirect(route('dashboard'));
    }
}