<?php

namespace App\Billing;

interface GatewayInterface
{
  public function createSession(\App\Order $order, \App\User $user);

  public function getOrderInfoFromEvent(string $payload, string $sig_header);
}
