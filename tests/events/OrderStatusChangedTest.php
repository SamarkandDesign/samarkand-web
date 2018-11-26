<?php

namespace Events;

use TestCase;
use App\Order;
use App\Events\OrderStatusChanged;

class OrderStatusChangedTest extends TestCase
{
  /** @test **/
  public function it_logs_an_order_note_when_an_order_status_changes()
  {
    $order = factory(Order::class)->create();
    event(new OrderStatusChanged($order->id, Order::PAID, Order::CANCELLED));

    $this->assertDatabaseHas('order_notes', [
      'order_id' => $order->id,
      'key' => 'status_changed',
    ]);
  }
}
