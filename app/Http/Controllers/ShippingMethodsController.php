<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SetShippingMethodRequest;
use App\Repositories\ShippingMethod\ShippingMethodRepository;

class ShippingMethodsController extends Controller
{
    public function __construct()
    {
        $this->middleware('order.session');
    }

    /**
     * Show the page for choosing a shipping method for an order.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ShippingMethodRepository $shipping_methods)
    {
        $order = $request->session()->get('order')->fresh();
        $shipping_methods = $shipping_methods->forCountry($order->shipping_address->country);
        // dd($shipping_methods);
        if ($shipping_methods->isEmpty()) {
            return redirect()->route('checkout.show')->with([
                'alert'       => 'It looks as though we can\'t deliver to your chosen country. Please choose a different shipping address.',
                'alert-class' => 'warning',
                ]);
        }

        // If there's only one shipping method available, just skip the shipping page and set it directly
        if (count($shipping_methods) === 1) {
            $order = $order->setShipping($shipping_methods->first()->id)->fresh();

            $request->session()->put('order', $order);

            return redirect()->route('checkout.pay');
        }

        return view('shop.shipping', [
            'order'            => $order,
            'shipping_methods' => $shipping_methods,
            ]);
    }

    /**
     * Add a shipping to an order.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SetShippingMethodRequest $request)
    {
        $order = $request->session()->get('order');

        $order = $order->setShipping($request->get('shipping_method_id'));

        $request->session()->put('order', $order->fresh());

        return redirect()->route('checkout.pay');
    }
}
