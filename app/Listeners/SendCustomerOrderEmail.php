<?php

namespace App\Listeners;

use App\Events\OrderWasPaid;
use App\Mail\OrderConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCustomerOrderEmail implements ShouldQueue
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
    $order = $event->order;

    \Log::info('Emailing customer order notification', [
      'recipient' => $order->customer->email,
      'order_id' => $order->id,
    ]);
    \Mail::to($order->customer)->send(new OrderConfirmed($order));
  }
}
