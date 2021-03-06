<?php

namespace App\Billing;

use Illuminate\Support\ServiceProvider;

class BillingServiceProvider extends ServiceProvider
{
  public function register()
  {
    // bind a fake payment gateway if the environment is testing
    $this->app->singleton(GatewayInterface::class, function () {
      return $this->getDriver();
    });
  }

  public function getDriver()
  {
    if (config('shop.billing_driver') === 'fake') {
      return new FakeStripeGateway();
    }

    return new StripeGateway();
  }
}
