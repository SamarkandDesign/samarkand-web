<?php

namespace App\Http\Controllers;

class CheckoutControllerTest extends \TestCase
{
  use \UsesCart, \FlushesProductEvents;

  /** @test **/
  public function it_shows_the_checkout_page()
  {
    \Event::fake();
    $this->loginWithUser();

    $product = $this->putProductInCart();

    $response = $this->get('/checkout');

    $response->assertStatus(200);
    $response->assertSee($product->name);
  }

  /** @test **/
  public function it_redirects_if_no_order_is_in_session_or_cart_empty()
  {
    $this->loginWithUser();

    $this->get('cart')->assertRedirect('/shop'); // 'nothing in your cart'

    $this->get('checkout')->assertRedirect('/shop'); // 'nothing in your cart'

    $this->get('checkout/shipping')->assertRedirect('/shop'); // 'No order exists'

    $this->get('checkout/pay')->assertRedirect('/shop'); // 'No order exists'
  }
}
