<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Exceptions\Handler;

use App\Exceptions\ApiHandler;
use App\Models\CareHome;
use Laravel\Cashier\Cashier;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(ExceptionHandlerContract::class, function ($app) {
            if ($app['request']->is('api/*')) {
                return new ApiHandler($app);
            } else {
                return new Handler($app);
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::useCustomerModel(CareHome::class);
    }
}
