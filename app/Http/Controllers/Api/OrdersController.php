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

      if ($order->status === Order::PAID || $order->status === Order::COMPLETED) {
        \Log::warning("Cannot complete order that is in $order->status state", [
          'status' => $order->status,
          'order_id' => $order->id,
        ]);
        return response()->json(sprintf('Cannot complete order marked as %s', $order->status), 400);
      }

      \Log::info(sprintf("Gateway webhook received for order %s", $session->client_reference_id), [
        'order_id' => $session->client_reference_id,
      ]);

      event(new \App\Events\OrderWasPaid($order, $session->payment_intent));
      if ($session->customer && !$order->user->billing_id) {
        $order->user->billing_id = $session->customer;
        $order->user->save();
      }
    } catch (\UnexpectedValueException $e) {
      \Log::warning("Gateway webhook failed due to invalid payload", [
        'error' => $e,
      ]);
      return response()->json('Invalid payload', 400);
    } catch (\Stripe\Error\SignatureVerification $e) {
      \Log::warning("Gateway webhook failed due to invalid signature", [
        'error' => $e,
      ]);
      return response()->json('Invalid signature', 400);
    }

    return response()->json('Received', 200);
  }
}
