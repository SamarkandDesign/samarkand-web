<?php

namespace App\Listeners;

use App\Events\OrderWasPaid;
use App\Jobs\CreateInvoiceForOrder;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateOrderInvoice implements ShouldQueue
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
                \Log::info('Creating invoice for order', [
          'order_id' => $event->order->id,
        ]);
    dispatch(new CreateInvoiceForOrder($event->order));
  }
}
