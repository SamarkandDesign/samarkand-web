<?php

namespace App\Billing;

use Stripe\Charge;
use Stripe\Stripe;

class StripeGateway implements GatewayInterface
{
  public function __construct()
  {
    Stripe::setApiKey(config('services.stripe.secret'));
  }

  public function createSession(\App\Order $order, \App\User $user)
  {
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

    // create a payment session and pass it to the view
    $session = \Stripe\Checkout\Session::create([
      'payment_method_types' => ['card'],
      'line_items' => $product_items->concat($shipping_item)->toArray(),
      'success_url' => "$site_url/order-completed/$order->id",
      'cancel_url' => "$site_url/cart",
      'client_reference_id' => $order->id,
      'customer_email' => $user->email,
    ]);

    return $session->id;
  }

  public function getOrderInfoFromEvent(string $payload, string $sig_header)
  {
    $endpoint_secret = config('services.stripe.webhook_secret');

    $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);

    if ($event->type == 'checkout.session.completed') {
      $session = $event->data->object;

      return [
        'order_id' => $session->client_reference_id,
        'payment_id' => $session->payment_intent,
      ];
    }

    return null;
  }
}
