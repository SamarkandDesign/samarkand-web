<?php

namespace App\Listeners;

use App\Order;
use App\Events\OrderWasPaid;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkOrderPaid implements ShouldQueue
{
  /**
   * Handle the event.
   *
   * @param OrderWasPaid $event
   *
   * @return void
   */
  public function handle(OrderWasPaid $event)
  {
    \Log::info('Marking order paid', ['order_id' => $event->order->id]);
    $event->order->update([
      'payment_id' => $event->payment_id,
      'status' => Order::PAID,
    ]);
  }
}
