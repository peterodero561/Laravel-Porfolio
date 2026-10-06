<?php

namespace App\Providers;

use App\Services\PortfolioService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PortfolioService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', fn ($user) => (bool) $user->is_admin);

        View::composer('*', function ($view) {
            $view->with('portfolio', app(PortfolioService::class));
        });
    }
}
