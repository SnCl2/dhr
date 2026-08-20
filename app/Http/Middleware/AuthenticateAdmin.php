<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check()) {
            Auth::shouldUse('admin');
            return $next($request);
        }

        if (Auth::guard('staff')->check()) {
            Auth::shouldUse('staff');
            return $next($request);
        }

        return redirect()->route('admin.login')->with('error', 'Please log in to access this area.');
    }
}
