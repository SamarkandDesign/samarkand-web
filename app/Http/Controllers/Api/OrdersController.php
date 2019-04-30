<?php

namespace App\Http\Controllers\Api;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Billing\GatewayInterface;

class OrdersController extends Controller
{
  public function __construct()
  {
    $this->middleware('admin', ['except' => 'complete']);
  }

  public function update(Order $order, Request $request)
  {
    $order->update($request->all());

    return $order;
  }

  /**
   * Handle a webhook payload to complete an order
   */
  public function complete(Request $request, GatewayInterface $gateway)
  {
    $sig_header = $request->header('stripe-signature');
    $payload = $request->getContent();

    if (!$sig_header) {
      abort(401);
    }
    try {
      $session = $gateway->getSessionFromEvent($payload, $sig_header);
      $order = Order::findOrFail($session->client_reference_id);
      \Log::info(sprintf("Gateway webhook recieved for order %s", $session->client_reference_id), [
        'order_id' => $session->client_reference_id,
      ]);

      event(new \App\Events\OrderWasPaid($order, $session->payment_intent));
      if ($session->customer && !$order->user->billing_id) {
        $order->user->billing_id = $session->customer;
        $order->user->save();
      }
    } catch (\Exception $e) {
      \Log::warning('Order completion webhook failed', ['error' => $e]);

      return response()->json('Failed to handle webhook request', 400);
    }

    return response()->json('Received', 200);
  }
}
