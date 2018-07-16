<?php

namespace App\Http\Controllers;

use App\Order;
use App\Address;
use App\OrderNote;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\Order\ViewOrderRequest;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('order.customer', ['only' => ['store']]);
        $this->middleware('order.session', ['only' => ['shipping']]);
        $this->middleware('auth', ['only' => ['show', 'pay']]);
    }

    /**
     * Create a new order.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrderRequest $request)
    {
        $customer = $request->get('customer');
        if (! $request->session()->has('order')) {
            abort(400, 'No order in session');
        }
        $order = $request->session()->get('order');

        $addresses = $this->processAddresses($request);

        // update the order with the correct info
        $order->billing_address_id = $addresses['billing_address_id'];
        $order->shipping_address_id = $addresses['shipping_address_id'];
        $order->user_id = $customer->id;
        $order->delivery_note = $request->get('delivery_note', '');
        $order->save();

        OrderNote::create([
            'order_id' => $order->id,
            'key'      => 'status_changed',
            'body'     => sprintf('Order created by IP %s', $request->getClientIp()),
            'user_id'  => $customer->id,
        ]);

        $order->syncWithCart();

        // replace the order in the session with the updated version
        $request->session()->put('order', $order->fresh());

        return redirect()->route('checkout.shipping');
    }

    /**
     * Show the page for a completed order.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function completed(Request $request)
    {
        if (! $request->session()->has('order_id')) {
            abort(Response::HTTP_BAD_REQUEST);
        }
        $order = Order::findOrFail($request->session()->get('order_id'));

        return view('shop.order_completed')->with([
            'order' => $order,
            ]);
    }

    public function show(ViewOrderRequest $request, Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function pay(ViewOrderRequest $request, Order $order)
    {
        $request->session()->put('order', $order);

        return redirect()->route('checkout.shipping');
    }

    /**
     * Get the order billing and shipping address depending on what was passed in the request.
     *
     * @param Request $request
     *
     * @return array
     */
    private function processAddresses(Request $request)
    {
        if (\Auth::check() and $request->has('billing_address_id')) {
            return [
               'billing_address_id'     => $request->get('billing_address_id'),
               'shipping_address_id'    => $request->get('shipping_address_id', $request->get('billing_address_id')),
            ];
        }

        $customer = $request->get('customer');

        $order = $request->session()->get('order');

        $billing_address = $order->getAddress('billing');
        $billing_address->fill($request->get('billing_address'));

        $customer->addresses()->save($billing_address);

        if ($customer->name === 'unknown') {
            $customer->update(['name' => $billing_address->name]);
        }

        $addresses = [
               'billing_address_id'     => $billing_address->id,
               'shipping_address_id'    => $billing_address->id,
        ];

        if ($request->has('different_shipping_address')) {

            // If the order previously had shipping same as billing but now a
            // different address is being used, we'll create a new one or
            // we'll end up updating the billing address too
            $shipping_address = $order->shippingSameAsBilling() ? new Address() : $order->getAddress('shipping');
            $shipping_address->fill($request->get('shipping_address'));

            $customer->addresses()->save($shipping_address);

            $addresses['shipping_address_id'] = $shipping_address->id;
        }

        return $addresses;
    }
}
