<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use App\Billing\GatewayInterface;

class PaymentsController extends Controller
{
  public function __construct()
  {
    $this->middleware('order.session');
    $this->middleware('auth');
  }

  /**
   * Show the page for paying for an order.
   *
   * @param Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request, GatewayInterface $gateway)
  {
    $order = Order::find($request->session()->get('order_id'));

    if (!$order->hasShipping()) {
      return redirect()
        ->route('checkout.shipping')
        ->with([
          'alert' => 'Please select a shipping method',
          'alert-class' => 'warning',
        ]);
    }

    $user = $request->user();
    $session_id = $gateway->createSession($order, $user);
    return view('orders.pay', ['order' => $order, 'session_id' => $session_id]);
  }
}
