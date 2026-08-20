<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceStaffPasswordChange
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $staff = Auth::guard('staff')->user();

        if ($staff && !$staff->is_password_changed) {
            if (!$request->routeIs('staff.password.change') && !$request->routeIs('staff.password.change.update') && !$request->routeIs('staff.logout')) {
                return redirect()->route('staff.password.change')
                    ->with('warning', 'You must change your password on your first login before you can proceed.');
            }
        }

        return $next($request);
    }
}
