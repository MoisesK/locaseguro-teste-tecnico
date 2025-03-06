<?php

declare(strict_types=1);

namespace App\Providers;

use App\Property\Domain\Contracts\PropertyRepository;
use App\Property\Infra\Repository\PropertyEloquentRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repository
        $this->app->singleton(PropertyRepository::class, PropertyEloquentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
