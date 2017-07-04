<?php

namespace App\Billing;

use Illuminate\Support\ServiceProvider;
use App\Billing\FakeStripeGateway;
use App\Billing\GatewayInterface;
use App\Billing\StripeGateway;

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
      return new FakeStripeGateWay();
    }
    return new StripeGateway();
  }
}
