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
      $order_info = $gateway->getOrderInfoFromEvent($payload, $sig_header);
      $order = Order::findOrFail($order_info['order_id']);
      \Log::info(sprintf("Gateway webhook recieved for order %s", $order_info['order_id']), [
        'order_id' => $order_info['order_id'],
      ]);

      event(new \App\Events\OrderWasPaid($order, $order_info['payment_id']));
    } catch (\Exception $e) {
      \Log::warning('Order completion webhook failed', ['error' => $e]);

      return response()->json('Failed to handle webhook request', 400);
    }

    return response()->json('Received', 200);
  }
}
