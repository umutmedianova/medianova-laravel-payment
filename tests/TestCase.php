<?php
namespace Medianova\LaravelPayment\Test;

use Medianova\LaravelPayment\Facades\Payment;
use Medianova\LaravelPayment\LaravelPaymentServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return [LaravelPaymentServiceProvider::class];
    }
    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'payment' => Payment::class,
        ];
    }
}
