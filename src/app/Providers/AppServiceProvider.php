<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        Passport::tokensCan([
            'register-user' => 'Registrar Usuarios',
        ]);

        // Passport::setDefaultScope([
        //     'check-status',
        //     'place-orders',
        // ]);
    }
}
