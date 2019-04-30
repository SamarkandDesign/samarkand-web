<?php

namespace App\Billing;

use Mockery;
use Stripe\Charge;
use Stripe\Stripe;

class FakeStripeGateway implements GatewayInterface
{
  /**
   * Simulate a call to the Stripe api to perform a charge.
   *
   * @param array $data The charge data
   * @param array $meta Meta info
   *
   * @throws \App\Billing\CardException
   *
   * @return \Stripe\Charge
   */
  public function charge(array $data, array $meta = [])
  {
    if ($data['card'] === 'tok_cardfailuretoken') {
      throw new CardException('Card declined');
    }

    $fakeCharge = Mockery::mock(Charge::class);
    $fakeCharge->id = 'ch_18bE2t4C5r3jEhospTyyfba5';

    return $fakeCharge;
  }

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
