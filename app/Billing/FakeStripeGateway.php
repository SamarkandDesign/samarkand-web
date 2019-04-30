<?php

namespace App\Billing;

use Mockery;
use Stripe\Charge;
use Stripe\Stripe;

class FakeStripeGateway implements GatewayInterface
{
  public function createSession(\App\Order $order, \App\User $user)
  {
    return 'payment_session_123';
  }

  public function getOrderInfoFromEvent(string $payload, string $sig_header)
  {
    if ($sig_header === 'invalid_sig') {
      throw new \Stripe\Error\SignatureVerification('Invalid signature');
    }

    $event = json_decode($payload, true);

    if (!$event['client_reference_id']) {
      throw new \UnexpectedValueException('Payload is not parseable to a session');
    }

    return [
      'order_id' => $event['client_reference_id'],
      'payment_id' => "pi_1EUmyo2x6R10KRrhUuJXu9m0",
    ];
  }
}
