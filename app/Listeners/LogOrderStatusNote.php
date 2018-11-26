<?php

namespace App\Listeners;

use App\Order;
use App\OrderNote;
use App\Events\OrderStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogOrderStatusNote implements ShouldQueue
{
  public function handle(OrderStatusChanged $event)
  {
    $user = \Auth::user();

    OrderNote::create([
      'order_id' => $event->orderId,
      'key' => 'status_changed',
      'body' => sprintf('Order status changed from "%s" to "%s"', $event->from, $event->to),
      'user_id' => $user ? $user->id : null,
    ]);
  }
}
