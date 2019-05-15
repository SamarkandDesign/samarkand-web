<?php

namespace App\Billing;

use Stripe\Event;
use Stripe\Stripe;

class StripeGateway implements GatewayInterface
{
  public function __construct()
  {
    Stripe::setApiKey(config('services.stripe.secret'));
  }

  public function createSession(\App\Order $order)
  {
    $user = $order->user;

    if (!$user) {
      throw new \InvalidArgumentException('Order must have a customer attached');
    }

    $shipping_item = $order->shipping_items->map(function ($item) {
      return [
        'name' => $item->description,
        'amount' => $item->price_paid->value(),
        'currency' => 'gbp',
        'quantity' => 1,
      ];
    });

    $product_items = $order->product_items->map(function ($item) {
      $images =
        $item->orderable && $item->orderable->thumbnail ? [$item->orderable->thumbnail] : [];
      return [
        'name' => $item->description,
        'images' => $images,
        'amount' => $item->price_paid->value(),
        'currency' => 'gbp',
        'quantity' => $item->quantity,
      ];
    });
    $site_url = config('app.url');

    $lineItems =
      // we cannot charge for items priced at 0
      $product_items
        ->concat($shipping_item)
        ->filter(function ($item) {
          return $item['amount'] > 0;
        })
        ->toArray();

    // create a payment session and pass it to the view
    $session = \Stripe\Checkout\Session::create([
      'payment_method_types' => ['card'],
      'line_items' => $lineItems,
      'success_url' => "$site_url/order-completed/$order->id",
      'cancel_url' => "$site_url/cart",
      'client_reference_id' => $order->id,
      'customer_email' => $user->billing_id ? null : $user->email,
      'customer' => $user->billing_id ?: null,
      'payment_intent_data' => [
        'description' => "Order #$order->id",
      ],
    ]);

    return $session->id;
  }

  /**
   * @return \Stripe\Event the Event instance
   */
  public function getSessionFromEvent(string $payload, string $sig_header)
  {
    $endpoint_secret = config('services.stripe.webhook_secret');

    $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);

    if ($event->type == 'checkout.session.completed') {
      return $event->data->object;
    }

    return null;
  }
}
