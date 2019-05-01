<?php

namespace App\Billing;

interface GatewayInterface
{
  public function createSession(\App\Order $order);

  public function getSessionFromEvent(string $payload, string $sig_header);
}
