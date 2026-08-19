<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforcePasswordChange
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $employee = Auth::guard('employee')->user();

        if ($employee && !$employee->is_password_changed && !session('admin_impersonating')) {
            if (!$request->routeIs('employee.password.change') && !$request->routeIs('employee.password.change.update') && !$request->routeIs('employee.logout')) {
                return redirect()->route('employee.password.change')->with('warning', 'You must change your password on your first login before you can proceed.');
            }
        }

        return $next($request);
    }
}
