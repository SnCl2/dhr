<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.admin' => \App\Http\Middleware\AuthenticateAdmin::class,
            'auth.employee' => \App\Http\Middleware\AuthenticateEmployee::class,
            'password.change' => \App\Http\Middleware\EnforcePasswordChange::class,
            'password.change.staff' => \App\Http\Middleware\EnforceStaffPasswordChange::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle Laravel's can: ability check failures
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'This action is unauthorized.'], 403);
            }

            $previousUrl = url()->previous();
            $currentUrl = $request->url();

            if ($previousUrl === $currentUrl || empty($previousUrl)) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Access Denied: You do not have permission to perform this action.');
            }

            return redirect()->back()
                ->with('error', 'Access Denied: You do not have permission to perform this action.');
        });

        // Handle generic abort(403) or AccessDeniedHttpException
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 403) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'This action is unauthorized.'], 403);
                }

                $previousUrl = url()->previous();
                $currentUrl = $request->url();

                if ($previousUrl === $currentUrl || empty($previousUrl)) {
                    return redirect()->route('admin.dashboard')
                        ->with('error', 'Access Denied: You do not have permission to perform this action.');
                }

                return redirect()->back()
                    ->with('error', 'Access Denied: You do not have permission to perform this action.');
            }
        });
    })->create();
