<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class RedirectIfAuthenticatedToDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Check user role
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard'); // your admin route
            } else {
                return redirect()->route('home'); // your user route
            }
        }

        // Not logged in, continue normally
        return $next($request);
    }
}
