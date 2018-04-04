<?php

namespace Integration;

use App\User;
use TestCase;
use App\Order;
use App\Notifications\OrderCreated;

class NotificationTest extends TestCase
{
    /** @test **/
    public function it_sends_a_telegram_notification()
    {
        $this->markTestSkipped('Telegram test here');
        $user = factory(User::class)->create();
        //set telegram ID

        $order = factory(Order::class)->create();
        $order_item = factory('App\OrderItem')->create(['order_id' => $order->id]);

        $order->amount = $order_item->price_paid->value();
        $order->save();

        \Notification::send($user, new OrderCreated($order));
    }
}
