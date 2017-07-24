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
        event(new OrderStatusChanged(22, Order::PAID, Order::CANCELLED));

        $this->assertDatabaseHas('order_notes', [
            'order_id' => 22,
            'key'      => 'status_changed',
            ]);
    }
}
