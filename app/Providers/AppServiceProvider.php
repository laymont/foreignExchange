<?php

namespace App\Providers;

use App\Repositories\CurrencyRepository;
use App\Repositories\CurrencyValueRepository;
use App\Repositories\Interfaces\CurrencyInterface;
use App\Repositories\Interfaces\CurrencyValueInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CurrencyInterface::class, CurrencyRepository::class);
        $this->app->bind(CurrencyValueInterface::class, CurrencyValueRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
