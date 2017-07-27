<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Invoicing\InvoiceCreator;
use XeroPHP\Application\PrivateApplication;
use App\Services\Invoicing\XeroInvoiceCreator;

class XeroServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PrivateApplication::class, function () {
            $config = [
                'oauth' => [
                    'callback'         => 'http://localhost/',
                    'consumer_key'     => config('services.xero.key'),
                    'consumer_secret'  => config('services.xero.secret'),
                    'rsa_private_key'  => config('services.xero.rsa_key'),
                ],
            ];

            return new PrivateApplication($config);
        });

        $this->app->singleton(InvoiceCreator::class, XeroInvoiceCreator::class);
    }
}
