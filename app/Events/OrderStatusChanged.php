<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class OrderStatusChanged
{
  public $orderId;
  public $from;
  public $to;

  use InteractsWithSockets, SerializesModels;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($orderId, $from, $to)
  {
    $this->orderId = $orderId;
    $this->from = $from;
    $this->to = $to;
  }
}
