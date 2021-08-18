<?php

namespace Medianova\LaravelPayment;

use Illuminate\Support\ServiceProvider;
use Medianova\LaravelPayment\Providers\ProviderManager;

class LaravelPaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/payment.php' => config_path('payment.php'),
        ]);

        $this->app->singleton('payment', function ($app) {
            return new ProviderManager;
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/payment.php',
            'payment'
        );
    }
}
