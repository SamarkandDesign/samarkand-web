<?php

namespace App\Listeners;

use App\Events\OrderWasPaid;
use App\Order;
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
        $event->order->update([
            'payment_id' => $event->payment_id,
            'status'     => Order::PAID,
            ]);
    }
}
