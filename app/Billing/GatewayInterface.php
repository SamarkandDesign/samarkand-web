<?php

namespace App\Billing;

interface GatewayInterface
{
  /**
   * Charge a card.
   *
   * @param array $data The attributes for the charge
   *
   * @return \Stripe\Charge
   */
  public function charge(array $data, array $meta = []);

  public function createSession(\App\Order $order, \App\User $user);

  public function getOrderInfoFromEvent(string $payload, string $sig_header);
}
