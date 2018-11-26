<?php

namespace App\Listeners;

use App\OrderNote;

class PaymentEventSubscriber
{
  /**
   * Handle user login events.
   */
  public function onPaymentFailed($event)
  {
    OrderNote::create([
      'order_id' => $event->order->id,
      'key' => 'payment_failed',
      'body' => sprintf('Payment failed with message "%s"', $event->message),
    ]);
  }

  public function onPaymentCompleted($event)
  {
    OrderNote::create([
      'order_id' => $event->order->id,
      'key' => 'payment_completed',
      'body' => sprintf('Payment completed with id "%s"', $event->payment_id),
    ]);
  }

  /**
   * Register the listeners for the subscriber.
   *
   * @param Illuminate\Events\Dispatcher $events
   */
  public function subscribe($events)
  {
    $events->listen(
      \App\Events\PaymentFailed::class,
      'App\Listeners\PaymentEventSubscriber@onPaymentFailed'
    );

    $events->listen(
      \App\Events\OrderWasPaid::class,
      'App\Listeners\PaymentEventSubscriber@onPaymentCompleted'
    );
  }
}
