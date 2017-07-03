<?php

namespace Integration;

use TestCase;

class CheckoutTest extends TestCase
{
    /** @test **/
    public function it_redirects_if_no_order_is_in_session_or_cart_empty()
    {
         $this->get('cart')->assertRedirect('/shop'); // 'nothing in your cart'

         $this->get('checkout')->assertRedirect('/shop'); // 'nothing in your cart'

         $this->get('checkout/shipping')->assertRedirect('/shop'); // 'No order exists'

         $this->get('checkout/pay')->assertRedirect('/shop'); // 'No order exists'
    }
}
