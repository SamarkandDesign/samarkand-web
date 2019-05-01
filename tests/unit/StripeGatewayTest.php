<?php

use App\Billing\StripeGateway;
use App\Order;
use App\OrderItem;
use Mockery;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeGatewayTest extends TestCase
{
  /** @test **/
  public function it_creates_a_checkout_session_with_stripe()
  {
    $stripeMock = Mockery::mock(Stripe::class);

    $order = factory(Order::class)->create();
    $order_item = factory(OrderItem::class)->create([
      'order_id' => $order->id,
      'price_paid' => 2500,
      'quantity' => 4,
    ]);
    $order->fresh();

    $mockSession = new stdClass();
    $mockSession->id = '123';

    Mockery::mock('alias:' . Session::class)
      ->shouldReceive('create')
      ->with([
        'payment_method_types' => ['card'],
        'line_items' => [
          [
            'name' => $order_item->description,
            'images' => [],
            'amount' => 2500,
            'currency' => 'gbp',
            'quantity' => "4",
          ],
        ],
        'success_url' => "http://localhost/order-completed/$order->id",
        'cancel_url' => "http://localhost/cart",
        'client_reference_id' => $order->id,
        'customer_email' => $order->user->email,
        'customer' => null,
        'payment_intent_data' => [
          'description' => "Order #$order->id",
        ],
      ])
      ->andReturn($mockSession);

    $gateway = new StripeGateway();

    $sessionId = $gateway->createSession($order, $order->user);
    $this->assertEquals($sessionId, '123');
  }
}
