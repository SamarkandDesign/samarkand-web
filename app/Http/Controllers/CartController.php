<?php

namespace App\Http\Controllers;

use Cart;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use App\Http\Requests\AddToCartRequest;

class CartController extends Controller
{
    /**
     * Show a list of the cart contents.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('shop.cart');
    }

    /**
     * Put an item in the cart.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AddToCartRequest $request)
    {
        $product = Product::findOrFail($request->product_id);
        $qty = (int) $request->quantity;

        Cart::add([
      'id'    => $product->id,
      'qty'   => $qty,
      'name'  => $product->name,
      'price' => $product->getPrice()->asDecimal(),
    ])->associate(Product::class);

        $request->session()->forget('order');

        return redirect()->back()->with([
      'alert'       => new HtmlString(sprintf('%d %s added to cart. %s',
      $qty,
      str_plural($product->name, $qty),
      '<a href="/checkout" class="btn btn-primary pull-right"><i class="fa fa-shopping-cart"></i> Checkout</a>'
    )),
    'alert-class' => 'success',
  ]);
    }

    /**
     * Remove an item from the cart.
     *
     * @param Request $request
     * @param string  $rowid   The row id to remove
     *
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request, $rowid)
    {
        $product = Cart::get($rowid)->model;

        Cart::remove($rowid);

        $route = Cart::count() > 0 ? 'cart' : 'products.index';

        $request->session()->forget('order');

        return redirect()->route($route)->with([
    'alert'       => "{$product->name} removed from cart",
    'alert-class' => 'success',
  ]);
    }
}
