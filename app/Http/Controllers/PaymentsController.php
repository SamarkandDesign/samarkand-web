<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use App\Billing\GatewayInterface;

class PaymentsController extends Controller
{
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

        if ($order->status !== Order::PENDING) {
            return redirect()->route('products.index')->with([
            'alert'       => 'Oops, This order has either already been paid for or has been cancelled. Please create a new order to proceed.',
            'alert-class' => 'danger',
            ]);
        }

        try {
            $charge = $gateway->charge([
          'amount'      => $order->amount->value(),
          'card'        => $request->stripe_token,
          'description' => sprintf('Order #%s', $order->id),
          ], [
          'email' => $order->email,
          ]);
        } catch (\App\Billing\CardException $e) {
            event(new \App\Events\PaymentFailed($order, $e->getMessage()));

            return redirect()->back()->with([
            'alert'       => $this->paymentErrorMessage($e->getMessage()),
            'alert-class' => 'danger',
            ]);
        }

        $request->session()->forget('order');
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
            return new \Illuminate\Support\HtmlString('The postcode you supplied failed validation, please check your billing address on the <a href="/checkout" class="alert-link" title="return to the checkout page">checkout page</a>.');
        }

        return $message;
    }
}
