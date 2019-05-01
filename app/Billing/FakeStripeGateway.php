<?php

namespace App\Billing;

use Mockery;
use Stripe\Stripe;

class FakeStripeGateway implements GatewayInterface
{
  public function createSession(\App\Order $order)
  {
    return 'payment_session_123';
  }

  /**
   * @return \Stripe\Event the Event instance
   */
  public function getSessionFromEvent(string $payload, string $sig_header)
  {
    if ($sig_header === 'invalid_sig') {
      throw new \Stripe\Error\SignatureVerification('Invalid signature');
    }

    $event = json_decode($payload, true);

    if (!$event['client_reference_id']) {
      throw new \UnexpectedValueException('Payload is not parseable to a session');
    }

    $fakeSession = Mockery::mock();
    $fakeSession->client_reference_id = $event['client_reference_id'];
    $fakeSession->payment_intent = 'pi_1EUmyo2x6R10KRrhUuJXu9m0';
    $fakeSession->customer = "cus_Eyyxi4JhhB6wQF";

    return $fakeSession;
  }
}
