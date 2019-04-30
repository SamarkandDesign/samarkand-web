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
    $this->middleware('auth');
  }

  /**
   * Show the checkout page.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
    return view('shop.checkout');
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
    $addresses = $this->processAddresses($request);
    $session = $request->session();

    // see if there's a previously incomplete order
    $currentOrder = Order::where([
      'id' => $session->get('order_id', -1),
      'status' => Order::PENDING,
    ])->first();

    $order = $currentOrder ? $currentOrder : new Order();

    // update the order with the correct info
    $order->billing_address_id = $addresses['billing_address_id'];
    $order->shipping_address_id = $addresses['shipping_address_id'];
    $order->user_id = $request->user()->id;
    $order->delivery_note = $request->get('delivery_note', '');
    $order->save();

    OrderNote::create([
      'order_id' => $order->id,
      'key' => 'status_changed',
      'body' => sprintf('Order created by IP %s', $request->getClientIp()),
      'user_id' => $request->user(),
    ]);

    $order->syncWithCart();
    // replace the order in the session with the updated version
    $session->put('order_id', $order->id);

    return redirect()->route('checkout.shipping');
  }

  /**
   * Show the page for a completed order.
   *
   * @param Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function completed(Request $request, Order $order)
  {
    $user = $request->user();

    if (!$user || !$user->owns($order)) {
      \Log::warning('Unauthorised attempt to access order completed page', ['order_id' => $order->id]);
      abort(Response::HTTP_FORBIDDEN);
    }

    return view('shop.order_completed')->with([
      'order' => $order,
    ]);
  }

  public function show(ViewOrderRequest $request, Order $order)
  {
    return view('orders.show', compact('order'));
  }

  /**
   * Show the page for paying for a pending order
   */
  public function pay(ViewOrderRequest $request, Order $order)
  {
    $request->session()->put('order_id', $order->id);
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
    // if the user already had addresses saved on their account, we can use the IDs
    if ($request->has('billing_address_id')) {
      return [
        'billing_address_id' => $request->get('billing_address_id'),
        'shipping_address_id' => $request->get(
          'shipping_address_id',
          $request->get('billing_address_id')
        ),
      ];
    }
    $user = $request->user();

    $billing_address = $user->addresses()->save(new Address($request->input('address.billing')));

    if ($request->has('different_shipping_address')) {
      $shipping_address = $user
        ->addresses()
        ->save(new Address($request->input('address.shipping')));
    } else {
      $shipping_address = $billing_address;
    }

    return [
      'billing_address_id' => $billing_address->id,
      'shipping_address_id' => $shipping_address->id,
    ];
  }
}
