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

  /**
   * Create a new payment for an order.
   *
   * @param Request $request
   *
   * @return Illuminate\Http\Response
   */
  public function store(Request $request, GatewayInterface $gateway)
  {
    $this->validate($request, ['order_id' => 'required|numeric', 'stripe_token' => 'required']);
    $order = Order::find($request->order_id);

    try {
      $charge = $gateway->charge(
        [
          'amount' => $order->amount->value(),
          'card' => $request->stripe_token,
          'description' => sprintf('Order #%s', $order->id),
        ],
        [
          'email' => $order->email,
        ]
      );
    } catch (\App\Billing\CardException $e) {
      event(new \App\Events\PaymentFailed($order, $e->getMessage()));

      return redirect()
        ->back()
        ->with([
          'alert' => $this->paymentErrorMessage($e->getMessage()),
          'alert-class' => 'danger',
        ]);
    }

    event(new \App\Events\OrderWasPaid($order, $charge->id));

    \Cart::destroy();

    $request->session()->flash('order_id', $order->id);

    return redirect()->route('orders.completed');
  }

  /**
   * Derive the payment error message.
   *
   * @param string $message
   *
   * @return string|HtmlString
   */
  private function paymentErrorMessage($message)
  {
    if (strpos($message, 'zip code')) {
      return new \Illuminate\Support\HtmlString(
        'The postcode you supplied failed validation, please check your billing address on the <a href="/checkout" class="alert-link" title="return to the checkout page">checkout page</a>.'
      );
    }

    return $message;
  }
}
