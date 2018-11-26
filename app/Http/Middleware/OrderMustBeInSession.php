<?php

namespace App\Http\Middleware;

use Closure;
use App\Order;

class OrderMustBeInSession
{
  /**
   * Handle an incoming request.
   *
   * @param \Illuminate\Http\Request $request
   * @param \Closure                 $next
   *
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $order_id = $request->session()->get('order_id', null);
    $order = Order::find($order_id);

    if (!$order or !$this->orderNeedsPayment($order)) {
      return redirect()
        ->route('products.index')
        ->with([
          'alert' => 'No order exists or your order has expired. Please try again.',
          'alert-class' => 'warning',
        ]);
    }

    return $next($request);
  }

  /**
   * Does the order in the session need payment?
   * I.E. Is it pending?
   *
   * @return bool
   */
  private function orderNeedsPayment(Order $order)
  {
    return $order->status === \App\Order::PENDING;
  }
}
