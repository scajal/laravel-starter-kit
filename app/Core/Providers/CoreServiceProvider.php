<?php

declare(strict_types=1);

namespace App\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Illuminate\Contracts\Auth\Authenticatable::class,
            \App\Core\Models\User::class
        );
    }
}
