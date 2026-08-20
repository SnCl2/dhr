<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Admin Super Access Bypass
        Gate::before(function ($user, $ability) {
            if ($user instanceof \App\Models\Admin) {
                return true;
            }
        });

        // 2. Resolve Dynamic Database-driven Gates via Fallback
        Gate::after(function ($user, $ability, $result) {
            if ($user instanceof \App\Models\Staff) {
                return $user->hasPermission($ability);
            }
            return false;
        });

        view()->composer('*', function ($view) {
            try {
                if (\Schema::hasTable('site_content')) {
                    $contactEmail = \DB::table('site_content')->where('key', 'contact_email')->value('value') ?? 'info@propszy.com';
                    $contactPhone = \DB::table('site_content')->where('key', 'contact_phone')->value('value') ?? '+91 94323 13430';
                    $contactAddress = \DB::table('site_content')->where('key', 'contact_address')->value('value') ?? 'Amtala, DH Road, South 24 Parganas, West Bengal, 743503';
                } else {
                    $contactEmail = 'info@propszy.com';
                    $contactPhone = '+91 94323 13430';
                    $contactAddress = 'Amtala, DH Road, South 24 Parganas, West Bengal, 743503';
                }
            } catch (\Throwable $e) {
                $contactEmail = 'info@propszy.com';
                $contactPhone = '+91 94323 13430';
                $contactAddress = 'Amtala, DH Road, South 24 Parganas, West Bengal, 743503';
            }
            $view->with(compact('contactEmail', 'contactPhone', 'contactAddress'));
        });
    }
}
